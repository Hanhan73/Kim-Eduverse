<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireResponse;
use App\Models\Questionnaire;
use App\Models\DigitalOrder;
use App\Mail\QuestionnaireResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireController extends Controller
{
    /**
     * Show questionnaire form
     */
    public function show($orderNumber)
    {
        // Find order and response
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $response = QuestionnaireResponse::where('order_id', $order->id)
            ->with(['questionnaire.questions.dimension', 'order'])
            ->firstOrFail();

        // Check if already completed
        if ($response->completed_at) {
            return redirect()->route('digital.payment.success', $order->order_number)
                ->with('info', 'Anda sudah menyelesaikan angket ini. Hasil telah dikirim ke email.');
        }

        $questionnaire = $response->questionnaire;

        return view('digital.questionnaire', compact('questionnaire', 'response'));
    }

    /**
     * Submit questionnaire answers
     */
    public function submit(Request $request, $responseId)
    {
        $response = QuestionnaireResponse::with('questionnaire.questions')
            ->findOrFail($responseId);

        // Check if already completed (check if result_summary exists)
        if ($response->result_summary && !empty($response->result_summary)) {
            return redirect()->route('digital.payment.success', $response->order->order_number)
                ->with('info', 'Angket ini sudah pernah diisi.');
        }

        // Validate all questions answered
        $questionCount = $response->questionnaire->questions->count();
        $answerCount = count($request->answers ?? []);

        if ($answerCount !== $questionCount) {
            return back()->with('error', 'Mohon jawab semua pertanyaan sebelum mengirim');
        }

        // Validate answer values (1-5)
        foreach ($request->answers as $questionId => $value) {
            if (!in_array($value, [1, 2, 3, 4, 5])) {
                return back()->with('error', 'Nilai jawaban tidak valid');
            }
        }

        DB::beginTransaction();
        try {
            // Save answers to JSON column
            $answersArray = [];
            foreach ($request->answers as $questionId => $value) {
                $answersArray[$questionId] = $value;
            }
            
            $response->update(['answers' => $answersArray]);
            
            Log::info('Answers saved', ['response_id' => $response->id, 'answers_count' => count($answersArray)]);

            // Calculate results
            $results = $this->calculateResults($response);

            // Update response with results
            $response->update([
                'result_summary' => $results,
                'completed_at' => now(),
                'is_completed' => true,
            ]);

            // Generate PDF
            $pdfPath = $this->generatePDF($response);
            $response->update(['result_pdf_path' => $pdfPath]);

            DB::commit();

            // Send result email
            try {
                Mail::to($response->respondent_email)->send(new QuestionnaireResult($response));
            } catch (\Exception $e) {
                Log::error('Failed to send questionnaire result email: ' . $e->getMessage());
            }

            return redirect()->route('digital.payment.success', $response->order->order_number)
                ->with('success', 'Terima kasih! Hasil analisis telah dikirim ke email Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Questionnaire submission failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Download result PDF
     */
    public function downloadResult($responseId)
    {
        $response = QuestionnaireResponse::findOrFail($responseId);

        if (!$response->result_pdf_path || !Storage::exists($response->result_pdf_path)) {
            // Regenerate PDF if not found
            $pdfPath = $this->generatePDF($response);
            $response->update(['result_pdf_path' => $pdfPath]);
        }

        $fileName = 'Hasil-' . $response->questionnaire->name . '-' . $response->id . '.pdf';

        return Storage::download($response->result_pdf_path, $fileName);
    }

    /**
     * Calculate questionnaire results by dimension
     */
    private function calculateResults($response)
    {
        $response->load(['questionnaire.dimensions', 'questionnaire.questions']);
        $results = [];

        // Get answers from JSON column
        $answers = $response->answers ?? [];

        foreach ($response->questionnaire->dimensions as $dimension) {
            // Get all questions for this dimension
            $dimensionQuestions = $response->questionnaire->questions()
                ->where('dimension_id', $dimension->id)
                ->get();

            // Calculate score for this dimension
            $score = 0;
            foreach ($dimensionQuestions as $question) {
                if (isset($answers[$question->id])) {
                    $score += (int) $answers[$question->id];
                }
            }

            $questionCount = $dimensionQuestions->count();
            $maxScore = $questionCount * 5;
            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;

            // Interpret the score
            $interpretation = $this->interpretScore($dimension, $score, $percentage, $questionCount);

            $results[$dimension->code] = [
                'dimension_name' => $dimension->name,
                'score' => $score,
                'max_score' => $maxScore,
                'percentage' => round($percentage, 2),
                'interpretation' => $interpretation,
            ];
        }

        return $results;
    }

    /**
     * Interpret score based on percentage
     */
    private function interpretScore($dimension, $score, $percentage, $questionCount)
    {
        if ($percentage < 40) {
            return [
                'level' => 'Rendah',
                'class' => 'level-rendah',
                'description' => 'Skor Anda pada dimensi ' . $dimension->name . ' berada pada tingkat rendah. Hal ini menunjukkan bahwa aspek ini perlu mendapat perhatian lebih.',
                'suggestions' => $this->getSuggestions($dimension, 'low'),
            ];
        } elseif ($percentage < 70) {
            return [
                'level' => 'Sedang',
                'class' => 'level-sedang',
                'description' => 'Skor Anda pada dimensi ' . $dimension->name . ' berada pada tingkat sedang. Anda sudah memiliki dasar yang cukup baik, namun masih ada ruang untuk pengembangan.',
                'suggestions' => $this->getSuggestions($dimension, 'medium'),
            ];
        } else {
            return [
                'level' => 'Tinggi',
                'class' => 'level-tinggi',
                'description' => 'Skor Anda pada dimensi ' . $dimension->name . ' berada pada tingkat tinggi. Ini menunjukkan bahwa Anda memiliki kemampuan yang baik dalam aspek ini.',
                'suggestions' => $this->getSuggestions($dimension, 'high'),
            ];
        }
    }

    /**
     * Get suggestions based on dimension and level
     */
    private function getSuggestions($dimension, $level)
    {
        // Default suggestions
        $defaultSuggestions = [
            'low' => [
                'Lakukan self-reflection untuk memahami area yang perlu diperbaiki',
                'Konsultasikan dengan profesional untuk mendapat bimbingan yang tepat',
                'Buat rencana pengembangan diri yang terstruktur',
                'Ikuti pelatihan atau workshop terkait',
            ],
            'medium' => [
                'Pertahankan dan tingkatkan kemampuan yang sudah dimiliki',
                'Identifikasi area spesifik yang bisa ditingkatkan',
                'Terus berlatih dan konsisten dalam pengembangan diri',
                'Cari mentor atau role model untuk pembelajaran lebih lanjut',
            ],
            'high' => [
                'Pertahankan pencapaian yang sudah baik ini',
                'Bagikan pengalaman dan pengetahuan kepada orang lain',
                'Terus tantang diri untuk mencapai level yang lebih tinggi',
                'Jadilah role model bagi orang lain',
            ],
        ];

        // Check if dimension has custom suggestions
        $suggestionField = $level . '_suggestions';
        if ($dimension->{$suggestionField} && is_array($dimension->{$suggestionField})) {
            return $dimension->{$suggestionField};
        }

        return $defaultSuggestions[$level] ?? [];
    }

    /**
     * Generate PDF from response
     */
    private function generatePDF($response)
    {
        // Refresh response with all relations
        $response->load([
            'questionnaire.dimensions',
            'questionnaire.questions.dimension',
            'order'
        ]);

        // Generate PDF using view
        $pdf = Pdf::loadView('pdf.questionnaire-result', [
            'response' => $response
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Create directory if not exists
        $directory = 'questionnaire-results';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Generate filename
        $filename = $directory . '/' . $response->id . '-' . time() . '.pdf';

        // Save PDF
        Storage::put($filename, $pdf->output());

        return $filename;
    }
}