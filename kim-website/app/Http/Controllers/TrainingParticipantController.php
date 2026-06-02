<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingSubmission;
use App\Models\SeminarEnrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TrainingParticipantController extends Controller
{
    // ========================================
    // AKSES VIA TOKEN
    // ========================================
    public function access($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training = $participant->training()
            ->with('seminar.preTest.questions', 'seminar.postTest.questions')
            ->first();

        // Auto-create enrollment
        if ($training->seminar && !$participant->seminar_enrollment_id) {
            $this->getOrCreateEnrollment($participant, $training->seminar);
            $participant->refresh();
        }
        $participant->load('enrollment');

        $currentView = $this->determineView($participant, $training);
        $submission   = $participant->submission;
        $ongoingAttempt = null;
        $quizResult   = null;

        // Cek ongoing attempt untuk quiz
        if (in_array($currentView, ['pre_test', 'post_test']) && $participant->enrollment) {
            $seminar = $training->seminar;
            $quizId  = $currentView === 'pre_test'
                ? optional($seminar->preTest)->id
                : optional($seminar->postTest)->id;

            if ($quizId) {
                $ongoingAttempt = QuizAttempt::where('user_email', $participant->email)
                    ->where('quiz_id', $quizId)
                    ->where('is_submitted', false)
                    ->latest()
                    ->first();
            }
        }

        return view('training.participant', compact(
            'participant', 'training', 'currentView',
            'submission', 'ongoingAttempt', 'quizResult'
        ));
    }

    // ========================================
    // CHECK-IN (peserta)
    // ========================================
    public function checkIn($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        if ($participant->checked_in_at) {
            return back()->with('info', 'Anda sudah melakukan check-in sebelumnya.');
        }
        $participant->update(['checked_in_at' => now()]);
        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Check-in berhasil! Selamat datang di pelatihan.');
    }

    // ========================================
    // CHECK-OUT (peserta)
    // ========================================
    public function checkOut($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        if (!$participant->checked_in_at) {
            return back()->with('error', 'Anda belum melakukan check-in.');
        }
        if ($participant->checked_out_at) {
            return back()->with('info', 'Anda sudah melakukan check-out sebelumnya.');
        }
        $participant->update(['checked_out_at' => now()]);
        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Check-out berhasil! Terima kasih atas partisipasi Anda.');
    }

    // ========================================
    // TANDAI MATERI SUDAH DIBACA
    // ========================================
    public function markMaterialViewed($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $enrollment  = $participant->enrollment;

        if (!$enrollment) {
            return back()->with('error', 'Enrollment tidak ditemukan.');
        }

        $enrollment->update([
            'material_viewed'    => true,
            'material_viewed_at' => now(),
        ]);

        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Materi sudah ditandai dibaca. Silakan lanjut ke check-out.');
    }

    // ========================================
    // START QUIZ
    // ========================================
public function startQuiz($token, $quizType)
{
    $participant = TrainingParticipant::where('access_token', $token)
        ->with('enrollment', 'training.seminar.preTest.questions', 'training.seminar.postTest.questions')
        ->firstOrFail();

    $seminar = $participant->training->seminar;
    $quiz    = $quizType === 'pre' ? $seminar->preTest : $seminar->postTest;

    if (!$quiz) return back()->with('error', 'Quiz tidak tersedia.');

    // Cek sudah ada attempt aktif
    $existing = QuizAttempt::where('user_email', $participant->email)
        ->where('quiz_id', $quiz->id)
        ->where('is_submitted', false)
        ->first();

    if (!$existing) {
        $allQuestions = $quiz->questions;

        // Pre-test: ambil 5 random, Post-test: semua
        if ($quizType === 'pre') {
            $selected = $allQuestions->shuffle()->take(5);
        } else {
            $selected = $allQuestions->shuffle();
        }

        // Buat shuffled_options per soal
        // Format: { question_id: ['c','a','e','b','d'] } = urutan opsi yang ditampilkan
        $shuffledOptions = [];
        foreach ($selected as $q) {
            $optsRaw = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            $opts = collect(array_keys($optsRaw ?? []))->shuffle()->values()->toArray(); // ['C','A','B',...]
            $shuffledOptions[$q->id] = $opts;
        }

        QuizAttempt::create([
            'quiz_id'          => $quiz->id,
            'user_id'          => null,
            'user_email'       => $participant->email,
            'started_at'       => now(),
            'answers'          => json_encode([]),
            'question_order'   => json_encode($selected->pluck('id')->toArray()),
            'shuffled_options' => json_encode($shuffledOptions),
            'score'            => 0,
            'is_passed'        => false,
            'is_submitted'     => false,
        ]);
    }

    return redirect()->route('training.participant.access', $token);
}

    // ========================================
    // SAVE ANSWER (AJAX)
    // ========================================
    public function saveAnswer(Request $request, $token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $attempt = QuizAttempt::where('id', $request->attempt_id)
            ->where('user_email', $participant->email)
            ->where('is_submitted', false)
            ->first();

        if (!$attempt) {
            return response()->json(['ok' => false]);
        }

        $answers = is_array($attempt->answers)
            ? $attempt->answers
            : json_decode($attempt->answers ?? '{}', true);
        if (!is_array($answers)) $answers = [];

        $answers[$request->question_id] = $request->answer;
        $attempt->update(['answers' => json_encode($answers)]);

        return response()->json(['ok' => true]);
    }

    // ========================================
    // SUBMIT QUIZ
    // ========================================
    public function submitQuiz(Request $request, $token, $quizType)
    {
        $participant = TrainingParticipant::where('access_token', $token)
            ->with('enrollment', 'training.seminar.preTest', 'training.seminar.postTest')
            ->firstOrFail();

        $seminar    = $participant->training->seminar;
        $enrollment = $participant->enrollment;
        $quiz       = $quizType === 'pre' ? $seminar->preTest : $seminar->postTest;

        $attempt = QuizAttempt::where('user_email', $participant->email)
            ->where('quiz_id', $quiz->id)
            ->where('is_submitted', false)
            ->latest()
            ->firstOrFail();

        // Hitung skor
        $answers       = $request->input('answers', []);
        $questionOrder = json_decode($attempt->question_order ?? '[]', true);
        $questions     = collect($questionOrder)
            ->map(fn($id) => $quiz->questions->firstWhere('id', $id))
            ->filter();
        if ($questions->isEmpty()) $questions = $quiz->questions;

        $correct = 0;
        foreach ($questions as $q) {
            $userAnswer    = strtoupper(trim($answers[$q->id] ?? $answers[(string)$q->id] ?? ''));
            $correctAnswer = strtoupper(trim($q->correct_answer ?? ''));
            if ($userAnswer !== '' && $userAnswer === $correctAnswer) {
                $correct++;
            }
        }

        $total      = $quiz->questions->count();
        $percentage = $total > 0 ? ($correct / $total) * 100 : 0;
        $isPassed   = $percentage >= $quiz->passing_score;

        $attempt->update([
            'answers'      => json_encode($answers),
            'score'        => $percentage,
            'is_passed'    => $isPassed,
            'is_submitted' => true,
            'submitted_at' => now(),
        ]);

        // Update enrollment
        if ($quizType === 'pre') {
            $enrollment->update([
                'pre_test_passed'       => $isPassed,
                'pre_test_completed_at' => now(),
                'pre_test_score'        => round($percentage),
            ]);
        } else {
            $enrollment->update([
                'post_test_passed'       => $isPassed,
                'post_test_completed_at' => now(),
                'post_test_score'        => round($percentage),
            ]);

            // Jika post-test lulus, tandai seminar selesai
            if ($isPassed) {
                $enrollment->update([
                    'is_completed' => true,
                    'completed_at' => now(),
                    'participant_name' => $participant->name,
                ]);
            }
        }

        $msg = $isPassed
            ? ($quizType === 'pre' ? 'Pre-Test lulus! Silakan baca materi.' : 'Post-Test lulus! Silakan kumpulkan tugas.')
            : 'Belum lulus (nilai ' . round($percentage) . '%). Silakan coba lagi.';

        return redirect()->route('training.participant.access', $token)
            ->with($isPassed ? 'success' : 'error', $msg);
    }

    // ========================================
    // SUBMIT TUGAS
    // ========================================
    public function submitTask(Request $request, $token)
    {
        $request->validate([
            'drive_link' => 'required|url',
            'notes'      => 'nullable|string|max:500',
        ]);

        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();

        if ($participant->submission) {
            $participant->submission->update([
                'drive_link'   => $request->drive_link,
                'notes'        => $request->notes,
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);
        } else {
            TrainingSubmission::create([
                'training_id'    => $participant->training_id,
                'participant_id' => $participant->id,
                'drive_link'     => $request->drive_link,
                'notes'          => $request->notes,
                'status'         => 'submitted',
                'submitted_at'   => now(),
            ]);
        }

        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // ========================================
    // DOWNLOAD SERTIFIKAT
    // ========================================
    public function downloadCertificate($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();

        if (!$participant->certificate_path) {
            return back()->with('error', 'Sertifikat belum tersedia.');
        }

        $path = storage_path('app/public/' . $participant->certificate_path);
        if (!file_exists($path)) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->download($path, 'sertifikat_' . $participant->certificate_number . '.pdf');
    }

    // ========================================
    // PRIVATE: determineView
    // ========================================
    private function determineView(TrainingParticipant $participant, Training $training)
    {
        $seminar    = $training->seminar;
        $enrollment = $participant->enrollment;

        if (!$participant->checked_in_at) return 'checkin';

        if ($seminar) {
            if (!$enrollment || !$enrollment->pre_test_passed) return 'pre_test';
            if (!$enrollment->material_viewed) return 'material';
        }

        if (!$participant->checked_out_at) return 'checkout';

        if ($seminar) {
            if (!$enrollment || !$enrollment->post_test_passed) return 'post_test';
        }

        if (!$participant->submission || $participant->submission->status === 'revision') return 'task';

        if ($participant->certificate_path) return 'completed';

        return 'waiting';
    }

    // ========================================
    // PRIVATE: getOrCreateEnrollment
    // ========================================
    private function getOrCreateEnrollment(TrainingParticipant $participant, $seminar)
    {
        if ($participant->seminar_enrollment_id) {
            return $participant->enrollment;
        }

        $order = \App\Models\DigitalOrder::create([
            'order_number'   => 'TRN-' . strtoupper(Str::random(8)),
            'customer_name'  => $participant->name,
            'customer_email' => $participant->email,
            'subtotal'       => 0,
            'tax'            => 0,
            'total'          => 0,
            'payment_method' => 'free',
            'payment_status' => 'paid',
            'status'         => 'completed',
            'paid_at'        => now(),
        ]);

        $enrollment = \App\Models\SeminarEnrollment::create([
            'seminar_id'       => $seminar->id,
            'customer_email'   => $participant->email,
            'participant_name' => $participant->name,
            'order_id'         => $order->id,
        ]);

        $participant->update(['seminar_enrollment_id' => $enrollment->id]);

        return $enrollment;
    }
}