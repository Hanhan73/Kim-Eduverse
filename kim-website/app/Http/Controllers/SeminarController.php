<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use App\Models\SeminarEnrollment;
use App\Models\DigitalOrder;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SeminarController extends Controller
{
    /**
     * Display seminar learning page (after purchase)
     */
    public function learn($orderNumber)
    {
        // Get order with seminar enrollment
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->with('items')
            ->firstOrFail();

        // Get seminar from order items
        $seminarItem = $order->items()->where('product_type', 'seminar')->first();

        if (!$seminarItem) {
            return redirect()->route('digital.payment.success', $orderNumber)
                ->with('error', 'Seminar tidak ditemukan');
        }

        $seminar = Seminar::findOrFail($seminarItem->product_id);

        // Get or create enrollment
        $enrollment = SeminarEnrollment::firstOrCreate([
            'seminar_id' => $seminar->id,
            'customer_email' => $order->customer_email,
            'order_id' => $order->id,
        ]);

        // Load relations
        $seminar->load(['preTest.questions', 'postTest.questions']);

        // Check if there's ongoing quiz attempt
        $ongoingAttempt = null;
        $currentView = 'overview'; // overview, pre_test, material, post_test
        $currentQuiz = null;

        // Determine current view based on progress
        if (!$enrollment->pre_test_passed && $seminar->preTest) {
            $currentView = 'pre_test';
            $currentQuiz = $seminar->preTest;

            // Check ongoing attempt
            $ongoingAttempt = QuizAttempt::where('user_id', null)
                ->where('user_email', $order->customer_email)
                ->where('quiz_id', $seminar->pre_test_id)
                ->where('is_submitted', false)
                ->first();
        } elseif ($enrollment->pre_test_passed && !$enrollment->material_viewed) {
            $currentView = 'material';
        } elseif ($enrollment->material_viewed && !$enrollment->post_test_passed && $seminar->postTest) {
            $currentView = 'post_test';
            $currentQuiz = $seminar->postTest;

            // Check ongoing attempt
            $ongoingAttempt = QuizAttempt::where('user_id', null)
                ->where('user_email', $order->customer_email)
                ->where('quiz_id', $seminar->post_test_id)
                ->where('is_submitted', false)
                ->first();
        } elseif ($enrollment->is_completed) {
            $currentView = 'completed';
        }

        return view('digital.seminar.learn', compact(
            'order',
            'seminar',
            'enrollment',
            'currentView',
            'currentQuiz',
            'ongoingAttempt'
        ));
    }

    /**
     * Start quiz (pre-test or post-test)
     */
    public function startQuiz($orderNumber, $quizType)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $enrollment = SeminarEnrollment::where('order_id', $order->id)->firstOrFail();
        $seminar = $enrollment->seminar;

        // Validate quiz type
        if ($quizType === 'pre') {
            if ($enrollment->pre_test_passed) {
                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('info', 'Pre-test sudah selesai');
            }
            $quiz = $seminar->preTest;
        } else {
            if (!$enrollment->canAccessPostTest()) {
                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('error', 'Selesaikan pre-test dan lihat materi terlebih dahulu');
            }
            $quiz = $seminar->postTest;
        }

        // Create quiz attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_email' => $order->customer_email,
            'started_at' => now(),
        ]);

        return redirect()->route('digital.seminar.learn', $orderNumber);
    }

    /**
     * Submit quiz
     */
    public function submitQuiz(Request $request, $orderNumber, $quizType)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)->firstOrFail();
        $enrollment = SeminarEnrollment::where('order_id', $order->id)->firstOrFail();
        $seminar = $enrollment->seminar;

        $quiz = $quizType === 'pre' ? $seminar->preTest : $seminar->postTest;

        // Get or create attempt
        $attempt = QuizAttempt::where('user_email', $order->customer_email)
            ->where('quiz_id', $quiz->id)
            ->where('is_submitted', false)
            ->first();

        if (!$attempt) {
            return redirect()->route('digital.seminar.learn', $orderNumber)
                ->with('error', 'Quiz attempt tidak ditemukan');
        }

        // Calculate score
        $answers = $request->input('answers', []);
        $totalScore = 0;
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer == $question->correct_answer) {
                $correctCount++;
                $totalScore += $question->points;
            }
        }

        $percentage = ($correctCount / $quiz->questions->count()) * 100;
        $isPassed = $percentage >= $quiz->passing_score;

        // Update attempt
        $attempt->update([
            'answers' => $answers,
            'score' => $totalScore,
            'percentage' => $percentage,
            'is_passed' => $isPassed,
            'is_submitted' => true,
            'submitted_at' => now(),
        ]);

        // Update enrollment based on quiz type
        if ($quizType === 'pre') {
            $enrollment->update([
                'pre_test_passed' => $isPassed,
                'pre_test_completed_at' => now(),
                'pre_test_score' => $percentage,
            ]);

            if ($isPassed) {
                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('success', 'Pre-test berhasil! Silakan lihat materi seminar.');
            } else {
                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('error', 'Pre-test belum lulus. Nilai: ' . round($percentage) . '%. Minimum: ' . $quiz->passing_score . '%');
            }
        } else {
            $enrollment->update([
                'post_test_passed' => $isPassed,
                'post_test_completed_at' => now(),
                'post_test_score' => $percentage,
            ]);

            if ($isPassed) {
                // Generate certificate
                $this->generateCertificate($enrollment);

                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('success', 'Selamat! Anda telah menyelesaikan seminar. Sertifikat akan dikirim ke email.');
            } else {
                return redirect()->route('digital.seminar.learn', $orderNumber)
                    ->with('error', 'Post-test belum lulus. Nilai: ' . round($percentage) . '%. Minimum: ' . $quiz->passing_score . '%');
            }
        }
    }

    /**
     * Mark material as viewed
     */
    public function markMaterialViewed($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)->firstOrFail();
        $enrollment = SeminarEnrollment::where('order_id', $order->id)->firstOrFail();

        if (!$enrollment->pre_test_passed) {
            return response()->json(['error' => 'Selesaikan pre-test terlebih dahulu'], 403);
        }

        $enrollment->update([
            'material_viewed' => true,
            'material_viewed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Generate and send certificate
     */
    private function generateCertificate($enrollment)
    {
        try {
            // Generate certificate number
            $enrollment->generateCertificate();

            // Generate PDF certificate
            $pdf = Pdf::loadView('pdf.seminar-certificate', [
                'enrollment' => $enrollment,
                'seminar' => $enrollment->seminar,
            ]);

            $fileName = 'sertifikat_' . $enrollment->certificate_number . '.pdf';
            $filePath = 'seminar_certificates/' . $fileName;

            \Storage::disk('public')->put($filePath, $pdf->output());

            $enrollment->update(['certificate_path' => $filePath]);

            // Send via email
            $this->sendCertificateEmail($enrollment);
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate: ' . $e->getMessage());
        }
    }

    /**
     * Send certificate and materials via email
     */
    private function sendCertificateEmail($enrollment)
    {
        try {
            Mail::send('emails.seminar-complete', [
                'enrollment' => $enrollment,
                'seminar' => $enrollment->seminar,
            ], function ($message) use ($enrollment) {
                $message->to($enrollment->customer_email)
                    ->subject('Sertifikat Seminar - ' . $enrollment->seminar->title);

                // Attach certificate
                if ($enrollment->certificate_path) {
                    $message->attach(
                        storage_path('app/public/' . $enrollment->certificate_path)
                    );
                }
            });

            $enrollment->update(['certificate_sent_via_email' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to send certificate email: ' . $e->getMessage());
        }
    }

    /**
     * Download certificate
     */
    public function downloadCertificate($enrollmentId)
    {
        $enrollment = SeminarEnrollment::findOrFail($enrollmentId);

        if (!$enrollment->certificate_path) {
            abort(404, 'Sertifikat tidak tersedia');
        }

        return \Storage::disk('public')->download($enrollment->certificate_path);
    }

    /**
     * Download seminar material
     */
    public function downloadMaterial($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)->firstOrFail();
        $enrollment = SeminarEnrollment::where('order_id', $order->id)->firstOrFail();

        if (!$enrollment->pre_test_passed) {
            abort(403, 'Selesaikan pre-test terlebih dahulu');
        }

        $seminar = $enrollment->seminar;

        if (!$seminar->material_pdf_path) {
            abort(404, 'Materi tidak tersedia');
        }

        // If Google Drive link, redirect
        if (str_contains($seminar->material_pdf_path, 'drive.google.com')) {
            return redirect($seminar->material_pdf_path);
        }

        // If local file, download
        return \Storage::disk('public')->download($seminar->material_pdf_path);
    }

    // In your SeminarController.php, add this method:

    /**
     * Store a newly created quiz
     */
    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'quiz_type' => 'required|in:pre,post',
        ]);

        try {
            // Generate slug
            $validated['slug'] = Str::slug($validated['title']);

            // Set the type based on the quiz_type parameter
            $validated['type'] = $validated['quiz_type'] . '_test';

            // Create quiz
            $quiz = Quiz::create($validated);

            return response()->json([
                'success' => true,
                'quiz' => $quiz
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
