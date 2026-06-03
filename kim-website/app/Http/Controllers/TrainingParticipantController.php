<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingQuestion;
use App\Models\TrainingQuizAttempt;
use App\Models\TrainingSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TrainingParticipantController extends Controller
{
    // ============================================================
    // AKSES VIA TOKEN
    // ============================================================
    public function access($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training    = $participant->training()->with('materials', 'questions')->first();

        $currentView    = $this->determineView($participant, $training);
        $submission     = $participant->submission;
        $ongoingAttempt = null;

        if (in_array($currentView, ['pre_test', 'post_test'])) {
            $ongoingAttempt = TrainingQuizAttempt::where('participant_id', $participant->id)
                ->where('training_id', $training->id)
                ->where('type', $currentView === 'pre_test' ? 'pre' : 'post')
                ->where('is_submitted', false)
                ->latest()->first();
        }

        return view('training.participant', compact(
            'participant',
            'training',
            'currentView',
            'submission',
            'ongoingAttempt'
        ));
    }

    // ============================================================
    // CHECK-IN / CHECK-OUT
    // ============================================================
    public function checkIn($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        if ($participant->checked_in_at) return back()->with('info', 'Sudah check-in sebelumnya.');
        $participant->update(['checked_in_at' => now()]);
        return redirect()->route('training.participant.access', $token)->with('success', 'Check-in berhasil!');
    }

    public function checkOut($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        if (!$participant->checked_in_at) return back()->with('error', 'Belum check-in.');
        if ($participant->checked_out_at) return back()->with('info', 'Sudah check-out sebelumnya.');
        $participant->update(['checked_out_at' => now()]);
        return redirect()->route('training.participant.access', $token)->with('success', 'Check-out berhasil!');
    }

    // ============================================================
    // MATERI — tandai sudah dibaca
    // ============================================================
    public function markMaterialViewed($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $participant->update(['material_viewed' => true]);
        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Materi sudah ditandai dibaca.');
    }

    // ============================================================
    // START QUIZ
    // ============================================================
    public function startQuiz($token, $quizType)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training    = $participant->training()->with('questions')->first();
        $type        = $quizType === 'pre' ? 'pre' : 'post';

        // Jika sudah ada attempt aktif, langsung tampilkan
        $existing = TrainingQuizAttempt::where('participant_id', $participant->id)
            ->where('training_id', $training->id)
            ->where('type', $type)
            ->where('is_submitted', false)
            ->first();

        if (!$existing) {
            $allQuestions = $training->questions;

            // Pre-test: 5 soal random | Post-test: semua soal diacak
            $selected = $type === 'pre'
                ? $allQuestions->shuffle()->take(5)->values()
                : $allQuestions->shuffle()->values();

            // Acak urutan opsi per soal
            $shuffledOptions = [];
            foreach ($selected as $q) {
                $opts = collect(['A', 'B', 'C', 'D'])
                    ->when($q->option_e, fn($c) => $c->push('E'))
                    ->shuffle()->values()->toArray();
                $shuffledOptions[$q->id] = $opts;
            }

            TrainingQuizAttempt::create([
                'training_id'      => $training->id,
                'participant_id'   => $participant->id,
                'type'             => $type,
                'question_order'   => $selected->pluck('id')->toArray(),
                'shuffled_options' => $shuffledOptions,
                'answers'          => [],
                'started_at'       => now(),
            ]);
        }

        return redirect()->route('training.participant.access', $token);
    }

    // ============================================================
    // SAVE ANSWER (AJAX)
    // ============================================================
    public function saveAnswer(Request $request, $token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $attempt     = TrainingQuizAttempt::where('id', $request->attempt_id)
            ->where('participant_id', $participant->id)
            ->where('is_submitted', false)
            ->first();

        if (!$attempt) return response()->json(['ok' => false]);

        $answers = $attempt->answers ?? [];
        $answers[$request->question_id] = strtoupper($request->answer);
        $attempt->update(['answers' => $answers]);

        return response()->json(['ok' => true]);
    }

    // ============================================================
    // SUBMIT QUIZ
    // ============================================================
    public function submitQuiz(Request $request, $token, $quizType)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training    = $participant->training()->with('questions')->first();
        $type        = $quizType === 'pre' ? 'pre' : 'post';

        $attempt = TrainingQuizAttempt::where('participant_id', $participant->id)
            ->where('training_id', $training->id)
            ->where('type', $type)
            ->where('is_submitted', false)
            ->latest()->firstOrFail();

        $answers = $request->input('answers', []);

        // Ambil soal sesuai question_order
        $questionOrder = $attempt->question_order ?? [];
        $questions     = collect($questionOrder)
            ->map(fn($id) => $training->questions->firstWhere('id', $id))
            ->filter();

        // Hitung skor
        $correct = 0;
        $total   = $questions->count();

        foreach ($questions as $q) {
            $userAnswer    = strtoupper(trim($answers[$q->id] ?? $answers[(string)$q->id] ?? ''));
            $correctAnswer = strtoupper(trim($q->correct_answer));
            if ($userAnswer !== '' && $userAnswer === $correctAnswer) $correct++;
        }

        $percentage = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        // Pre-test passing = 0 (semua lulus), post-test passing = 60%
        $passingScore = $type === 'pre' ? 0 : 60;
        $isPassed     = $percentage >= $passingScore;

        $attempt->update([
            'answers'      => $answers,
            'score'        => $percentage,
            'is_passed'    => $isPassed,
            'is_submitted' => true,
            'submitted_at' => now(),
        ]);

        // Update participant
        if ($type === 'pre') {
            $participant->update(['pre_test_passed' => true, 'pre_test_score' => $percentage]);
        } else {
            $participant->update(['post_test_passed' => $isPassed, 'post_test_score' => $percentage]);
        }

        $msg = $type === 'pre'
            ? "Pre-Test selesai! Nilai: {$percentage}%. Silakan baca materi."
            : ($isPassed
                ? "Post-Test lulus! Nilai: {$percentage}%. Silakan kumpulkan tugas."
                : "Belum lulus. Nilai: {$percentage}%. Minimum 60%. Silakan coba lagi.");

        return redirect()->route('training.participant.access', $token)
            ->with($isPassed || $type === 'pre' ? 'success' : 'error', $msg);
    }

    // ============================================================
    // SUBMIT TUGAS
    // ============================================================
    public function submitTask(Request $request, $token)
    {
        $request->validate(['drive_link' => 'required|url', 'notes' => 'nullable|string|max:500']);
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

        return redirect()->route('training.participant.access', $token)->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // ============================================================
    // DOWNLOAD SERTIFIKAT
    // ============================================================
    public function downloadCertificate($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        if (!$participant->certificate_path) return back()->with('error', 'Sertifikat belum tersedia.');

        $path = storage_path('app/public/' . $participant->certificate_path);
        if (!file_exists($path)) return back()->with('error', 'File tidak ditemukan.');

        $safeNumber = str_replace(['/', '\\'], '-', $participant->certificate_number);
        return response()->download($path, 'sertifikat_' . $safeNumber . '.pdf');
    }

    // ============================================================
    // PRIVATE: determineView
    // ============================================================
    private function determineView(TrainingParticipant $participant, Training $training)
    {
        $hasQuestions = $training->questions->count() > 0;

        if (!$participant->checked_in_at) return 'checkin';

        if ($hasQuestions && !$participant->pre_test_passed) return 'pre_test';

        if ($training->materials->count() > 0 && !$participant->material_viewed) return 'material';

        if (!$participant->checked_out_at) return 'checkout';

        if ($hasQuestions && !$participant->post_test_passed) return 'post_test';

        if (!$participant->submission || $participant->submission->status === 'revision') return 'task';

        if ($participant->certificate_path) return 'completed';

        return 'waiting';
    }

    public function viewMaterial($token)
{
    $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
    $training    = $participant->training()->with('materials', 'questions')->first();

    // Boleh akses materi kalau sudah check-in
    if (!$participant->checked_in_at) {
        return redirect()->route('training.participant.access', $token)
            ->with('error', 'Silakan check-in terlebih dahulu.');
    }

    $currentView    = 'material';
    $submission     = $participant->submission;
    $ongoingAttempt = null;

    return view('training.participant', compact(
        'participant', 'training', 'currentView', 'submission', 'ongoingAttempt'
    ));
}
}
