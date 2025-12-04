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
        try {
            // Log untuk debugging
            Log::info('Attempting to access seminar for order: ' . $orderNumber);
            
            // 1. Cari order yang sudah dibayar
            $order = DigitalOrder::where('order_number', $orderNumber)
                ->where('payment_status', 'paid')
                ->with(['items.product']) // Eager load relasi product
                ->first();
                
            if (!$order) {
                Log::error('Order not found or not paid: ' . $orderNumber);
                return redirect()->route('digital.payment.success', $orderNumber)
                    ->with('error', 'Pesanan tidak ditemukan atau belum dibayar');
            }
            
            Log::info('Order found: ' . $order->id);

            // 2. Cari item order yang bertipe 'seminar'
            $seminarOrderItem = $order->items()->where('product_type', 'seminar')->first();

            if (!$seminarOrderItem) {
                Log::error('Seminar item not found in order: ' . $order->id);
                return redirect()->route('digital.payment.success', $orderNumber)
                    ->with('error', 'Seminar tidak ditemukan dalam pesanan Anda.');
            }
            
            Log::info('Seminar item found: ' . $seminarOrderItem->id);

            // 3. Dapatkan objek DigitalProduct dari item tersebut
            $digitalProduct = $seminarOrderItem->product;

            if (!$digitalProduct) {
                Log::error('Digital Product not found for ID: ' . $seminarOrderItem->product_id);
                return redirect()->route('digital.payment.success', $orderNumber)
                    ->with('error', 'Produk digital tidak ditemukan.');
            }

            if ($digitalProduct->type !== 'seminar') {
                Log::error('Product type mismatch. Expected: seminar, Found: ' . $digitalProduct->product_type . ' for Product ID: ' . $digitalProduct->id);
                return redirect()->route('digital.payment.success', $orderNumber)
                    ->with('error', 'Tipe produk tidak sesuai. Diharapkan: seminar, Ditemukan: ' . $digitalProduct->product_type);
            }
            
            Log::info('Digital product found: ' . $digitalProduct->id . ' with seminar_id: ' . $digitalProduct->seminar_id);

            // 4. Dapatkan data Seminar dari DigitalProduct menggunakan relasi Eloquent
            $seminar = $digitalProduct->seminar;

            // Tambahkan pengecekan jika seminar tidak ditemukan
            if (!$seminar) {
                Log::error('Seminar not found for product ID: ' . $digitalProduct->id);
                return redirect()->route('digital.payment.success', $orderNumber)
                    ->with('error', 'Data seminar tidak ditemukan untuk produk ini. Silakan hubungi admin.');
            }

            Log::info('Seminar found: ' . $seminar->id . ' - ' . $seminar->title);

            // 5. Dapatkan atau buat enrollment
            $enrollment = SeminarEnrollment::firstOrCreate([
                'seminar_id' => $seminar->id,
                'customer_email' => $order->customer_email,
                'order_id' => $order->id,
            ]);
            
            Log::info('Enrollment created or found: ' . $enrollment->id);

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
            }  elseif ($enrollment->post_test_passed && !$enrollment->participant_name) {
                // TAMPILKAN FORM INPUT NAMA
                    $currentView = 'name_form';
                } elseif ($enrollment->is_completed) {
                    $currentView = 'completed';
                }
                        
            Log::info('Current view: ' . $currentView);

            return view('digital.seminar-learn', compact(
                'order',
                'seminar',
                'enrollment',
                'currentView',
                'currentQuiz',
                'ongoingAttempt'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani error jika model tidak ditemukan dengan lebih spesifik
            Log::error('Model not found in seminar access: ' . $e->getMessage());
            return redirect()->route('digital.payment.success', $orderNumber)
                ->with('error', 'Terjadi kesalahan: Data seminar atau produk tidak ditemukan. Silakan hubungi admin.');
        } catch (\Exception $e) {
            Log::error('Error accessing seminar: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('digital.payment.success', $orderNumber)
                ->with('error', 'Terjadi kesalahan saat mengakses seminar: ' . $e->getMessage());
        }
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

        // Acak urutan pertanyaan
        $shuffledQuestions = $quiz->questions->shuffle();
        
        // Simpan ID pertanyaan dalam urutan acak untuk digunakan saat submit
        $questionOrder = $shuffledQuestions->pluck('id')->toArray();

        // Create quiz attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_email' => $order->customer_email,
            'started_at' => now(),
            'answers' => [],
            'question_order' => json_encode($questionOrder), // Tambahkan kolom ini di database
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

        // Ambil urutan pertanyaan yang disimpan saat start
        $questionOrder = json_decode($attempt->question_order, true) ?? [];
        
        // Urutkan pertanyaan sesuai dengan yang disimpan
        $orderedQuestions = [];
        foreach ($questionOrder as $questionId) {
            $question = $quiz->questions->where('id', $questionId)->first();
            if ($question) {
                $orderedQuestions[] = $question;
            }
        }
        
        // Jika tidak ada urutan yang tersimpan, gunakan urutan default
        if (empty($orderedQuestions)) {
            $orderedQuestions = $quiz->questions->all();
        }

        // Calculate score
        $answers = $request->input('answers', []);
        $totalScore = 0;
        $correctCount = 0;

        foreach ($orderedQuestions as $question) {
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
                try {
                    $this->generateCertificate($enrollment);
                    return redirect()->route('digital.seminar.learn', $orderNumber)
                        ->with('success', 'Selamat! Anda telah menyelesaikan seminar. Sertifikat telah dikirim ke email.');
                } catch (\Exception $e) {
                    Log::error('Certificate generation failed during quiz submission: ' . $e->getMessage());
                    return redirect()->route('digital.seminar.learn', $orderNumber)
                        ->with('error', 'Anda telah menyelesaikan seminar, tetapi terjadi kesalahan saat membuat sertifikat. Silakan hubungi admin.');
                }

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
            Log::info('Starting certificate generation for enrollment: ' . $enrollment->id);

            // Generate certificate number
            $enrollment->generateCertificate();
            
            // Reload enrollment to get updated certificate_number
            $enrollment->refresh();
            
            Log::info('Certificate number generated: ' . $enrollment->certificate_number);

            // Load seminar relation if not loaded
            if (!$enrollment->relationLoaded('seminar')) {
                $enrollment->load('seminar');
            }

            // Generate PDF certificate
            $pdf = Pdf::loadView('pdf.seminar-certificate', [
                'enrollment' => $enrollment,
                'seminar' => $enrollment->seminar,
            ]);

            $fileName = 'sertifikat_' . $enrollment->certificate_number . '.pdf';
            $filePath = 'seminar_certificates/' . $fileName;

            // Ensure directory exists
            if (!\Storage::disk('public')->exists('seminar_certificates')) {
                \Storage::disk('public')->makeDirectory('seminar_certificates');
                Log::info('Created seminar_certificates directory');
            }

            // Save PDF
            \Storage::disk('public')->put($filePath, $pdf->output());
            
            Log::info('Certificate PDF saved to: ' . $filePath);

            // Update certificate path
            $enrollment->update(['certificate_path' => $filePath]);

            // Send via email
            $this->sendCertificateEmail($enrollment);
            
            Log::info('Certificate generation completed for enrollment: ' . $enrollment->id);
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Send certificate and materials via email
     */
    private function sendCertificateEmail($enrollment)
    {
        try {
            Log::info('Preparing to send certificate email to: ' . $enrollment->customer_email);

            // Verify certificate file exists
            $certificatePath = storage_path('app/public/' . $enrollment->certificate_path);
            
            if (!file_exists($certificatePath)) {
                Log::error('Certificate file not found at: ' . $certificatePath);
                return;
            }

            Log::info('Certificate file exists at: ' . $certificatePath);

            // Check if view exists
            if (!view()->exists('emails.seminar-complete')) {
                Log::error('Email view not found: emails.seminar-complete');
                return;
            }

            Mail::send('emails.seminar-complete', [
                'enrollment' => $enrollment,
                'seminar' => $enrollment->seminar,
            ], function ($message) use ($enrollment, $certificatePath) {
                $message->to($enrollment->customer_email)
                    ->subject('Sertifikat Seminar - ' . $enrollment->seminar->title);

                // Attach certificate
                $message->attach($certificatePath, [
                    'as' => 'sertifikat_' . $enrollment->certificate_number . '.pdf',
                    'mime' => 'application/pdf',
                ]);
            });

            Log::info('Certificate email sent successfully to: ' . $enrollment->customer_email);

            $enrollment->update(['certificate_sent_via_email' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to send certificate email: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Download certificate
     */
    public function downloadCertificate($enrollmentId)
    {
        try {
            $enrollment = SeminarEnrollment::findOrFail($enrollmentId);

            if (!$enrollment->certificate_path) {
                Log::error('Download failed: Certificate path is null for enrollment ID: ' . $enrollmentId);
                abort(404, 'Sertifikat tidak tersedia (path not set).');
            }

            $fullPath = storage_path('app/public/' . $enrollment->certificate_path);

            // Periksa apakah file benar-benar ada di server
            if (!\Storage::disk('public')->exists($enrollment->certificate_path)) {
                Log::error('Download failed: File not found at path: ' . $fullPath);
                abort(404, 'File sertifikat tidak ditemukan di server.');
            }

            Log::info('User downloading certificate: ' . $fullPath);
            return \Storage::disk('public')->download($enrollment->certificate_path);

        } catch (\Exception $e) {
            Log::error('Error downloading certificate: ' . $e->getMessage());
            // Aborsi dengan pesan yang jelas
            abort(404, 'Terjadi kesalahan saat mengunduh sertifikat.');
        }
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

    /**
     * Save participant name after post-test
     */
    public function saveParticipantName(Request $request, $orderNumber)
    {
        Log::info('Starting saveParticipantName process for order: ' . $orderNumber);

        // Log data yang diterima
        Log::info('Request data: ', $request->all());

        // Validasi
        try {
            $validated = $request->validate([
                'participant_name' => 'required|string|max:255',
            ]);
            Log::info('Validation successful. Participant name: ' . $validated['participant_name']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Nama tidak valid. Silakan coba lagi.')
                ->withInput();
        }

        try {
            $order = DigitalOrder::where('order_number', $orderNumber)->firstOrFail();
            Log::info('Order found: ' . $order->id);

            $enrollment = SeminarEnrollment::where('order_id', $order->id)->firstOrFail();
            Log::info('Enrollment found: ' . $enrollment->id);

            // Log data enrollment SEBELUM diupdate
            Log::info('Enrollment data BEFORE update: ', $enrollment->toArray());

            // Update participant name
            $updateResult = $enrollment->update(['participant_name' => $validated['participant_name']]);
            Log::info('Update result: ' . ($updateResult ? 'true' : 'false'));

            // Refresh model untuk mendapatkan data terbaru
            $enrollment->refresh();

            // Log data enrollment SESUDAH diupdate
            Log::info('Enrollment data AFTER update: ', $enrollment->toArray());

            // Periksa apakah participant_name sudah terisi
            if ($enrollment->participant_name) {
                Log::info('Participant_name successfully saved: ' . $enrollment->participant_name);
            } else {
                Log::error('Participant_name is still NULL after update!');
            }

            // Generate certificate now that we have the name
            Log::info('Calling generateCertificate...');
            $this->generateCertificate($enrollment);
            Log::info('generateCertificate process finished.');

            return redirect()->route('digital.seminar.learn', $orderNumber)
                ->with('success', 'Nama berhasil disimpan! Sertifikat Anda sedang dibuat.');

        } catch (\Exception $e) {
            Log::error('Failed to save participant name: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('digital.seminar.learn', $orderNumber)
                ->with('error', 'Gagal menyimpan nama. Silakan coba lagi.');
        }
    }
}