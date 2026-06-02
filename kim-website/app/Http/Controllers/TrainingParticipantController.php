<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingSubmission;
use App\Models\SeminarEnrollment;
use App\Models\DigitalOrder;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TrainingParticipantController extends Controller
{
    // ========================================
    // AKSES VIA TOKEN (dari email)
    // ========================================
    public function access($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training = $participant->training()->with('seminar.preTest.questions', 'seminar.postTest.questions')->first();

        // Tentukan view saat ini
        $currentView = $this->determineView($participant, $training);

        $submission = $participant->submission;

        return view('training.participant', compact('participant', 'training', 'currentView', 'submission'));
    }

    // ========================================
    // CHECK-IN (oleh peserta sendiri)
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
    // CHECK-OUT (oleh peserta sendiri)
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
    // SUBMIT TUGAS
    // ========================================
    public function submitTask(Request $request, $token)
    {
        $request->validate([
            'drive_link' => 'required|url',
            'notes' => 'nullable|string|max:500',
        ]);

        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();

        if ($participant->submission) {
            // Update jika sudah ada
            $participant->submission->update([
                'drive_link' => $request->drive_link,
                'notes' => $request->notes,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        } else {
            TrainingSubmission::create([
                'training_id' => $participant->training_id,
                'participant_id' => $participant->id,
                'drive_link' => $request->drive_link,
                'notes' => $request->notes,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('training.participant.access', $token)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // ========================================
    // START QUIZ (pre/post test)
    // ========================================
    public function startQuiz(Request $request, $token, $quizType)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();
        $training = $participant->training()->with('seminar')->first();
        $seminar = $training->seminar;

        if (!$seminar) {
            return back()->with('error', 'Tidak ada seminar yang terhubung.');
        }

        // Pastikan ada enrollment untuk seminar ini
        $enrollment = $this->getOrCreateEnrollment($participant, $seminar);

        // Redirect ke quiz via SeminarController (reuse existing)
        // Kita pass order_number enrollment
        $orderNumber = $enrollment->order ? $enrollment->order->order_number : null;
        if (!$orderNumber) {
            return back()->with('error', 'Terjadi kesalahan, hubungi admin.');
        }

        return redirect()->route('digital.seminar.quiz.start', [$orderNumber, $quizType]);
    }

    // ========================================
    // DOWNLOAD SERTIFIKAT
    // ========================================
    public function downloadCertificate($token)
    {
        $participant = TrainingParticipant::where('access_token', $token)->firstOrFail();

        if (!$participant->certificate_path) {
            return back()->with('error', 'Sertifikat belum tersedia. Pastikan Anda sudah menyelesaikan semua tahapan.');
        }

        $path = storage_path('app/public/' . $participant->certificate_path);
        if (!file_exists($path)) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->download($path, 'sertifikat_' . $participant->certificate_number . '.pdf');
    }

    // ========================================
    // PRIVATE: Tentukan view saat ini
    // ========================================
    private function determineView(TrainingParticipant $participant, Training $training)
    {
        $seminar = $training->seminar;

        // 1. Belum check-in
        if (!$participant->checked_in_at) {
            return 'checkin';
        }

        // 2. Ada seminar → pre-test dulu
        if ($seminar && $participant->enrollment) {
            $enrollment = $participant->enrollment;

            if (!$enrollment->pre_test_passed) {
                return 'pre_test';
            }
        }

        // 3. Materi
        if ($seminar && $participant->enrollment && !$participant->enrollment->material_viewed) {
            return 'material';
        }

        // 4. Belum check-out
        if (!$participant->checked_out_at) {
            return 'checkout';
        }

        // 5. Post-test
        if ($seminar && $participant->enrollment && !$participant->enrollment->post_test_passed) {
            return 'post_test';
        }

        // 6. Tugas
        if (!$participant->submission || $participant->submission->status === 'revision') {
            return 'task';
        }

        // 7. Sertifikat
        if ($participant->certificate_path) {
            return 'completed';
        }

        return 'waiting'; // Menunggu admin generate sertifikat
    }

    // ========================================
    // PRIVATE: Get or create seminar enrollment
    // ========================================
    private function getOrCreateEnrollment(TrainingParticipant $participant, $seminar)
    {
        // Cek jika sudah ada enrollment
        if ($participant->seminar_enrollment_id) {
            return $participant->enrollment;
        }

        // Buat dummy order untuk seminar gratis
        $order = \App\Models\DigitalOrder::create([
            'order_number' => 'TRN-' . strtoupper(Str::random(8)),
            'customer_name' => $participant->name,
            'customer_email' => $participant->email,
            'customer_phone' => $participant->phone ?? '-',
            'total_amount' => 0,
            'status' => 'paid',
            'payment_method' => 'free',
            'paid_at' => now(),
        ]);

        // Buat order item
        $seminar->digitalProduct && \App\Models\DigitalOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $seminar->digitalProduct->id,
            'product_name' => $seminar->title,
            'price' => 0,
            'quantity' => 1,
        ]);

        // Buat enrollment
        $enrollment = \App\Models\SeminarEnrollment::create([
            'seminar_id' => $seminar->id,
            'customer_email' => $participant->email,
            'participant_name' => $participant->name,
            'order_id' => $order->id,
        ]);

        // Simpan ke participant
        $participant->update(['seminar_enrollment_id' => $enrollment->id]);

        return $enrollment;
    }
}