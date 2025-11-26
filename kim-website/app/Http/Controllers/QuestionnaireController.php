<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuestionnaireResult;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireController extends Controller
{
    /**
     * Display questionnaire form.
     */
    public function show($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->with('responses.questionnaire')
            ->firstOrFail();

        // Get incomplete questionnaire responses
        $incompleteResponses = $order->responses()
            ->where('is_completed', false)
            ->with('questionnaire.questions.dimension')
            ->get();

        if ($incompleteResponses->isEmpty()) {
            return redirect()->route('digital.payment.success', $orderNumber)
                ->with('info', 'Semua angket sudah selesai diisi');
        }

        // Get first incomplete response
        $response = $incompleteResponses->first();
        $questionnaire = $response->questionnaire;

        return view('digital.questionnaire', compact('order', 'response', 'questionnaire'));
    }

    /**
     * Submit questionnaire answers.
     */
    public function submit(Request $request, $responseId)
    {
        $response = QuestionnaireResponse::findOrFail($responseId);
        $questionnaire = $response->questionnaire;

        // Validate that all questions are answered
        $questionCount = $questionnaire->questions()->count();
        $answers = $request->input('answers', []);

        if (count($answers) !== $questionCount) {
            return redirect()->back()
                ->with('error', 'Mohon jawab semua pertanyaan')
                ->withInput();
        }

        // Calculate scores
        $scores = $this->calculateScores($questionnaire, $answers);

        // Generate result summary
        $resultSummary = $this->generateResultSummary($questionnaire, $scores);

        // Update response
        $response->update([
            'answers' => $answers,
            'scores' => $scores,
            'result_summary' => $resultSummary,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Generate PDF
        $pdfPath = $this->generatePDF($response);
        $response->update(['result_pdf_path' => $pdfPath]);

        // Send result via email
        $this->sendResultEmail($response);

        // Check if there are more questionnaires to fill
        $order = $response->order;
        $remainingResponses = $order->responses()
            ->where('is_completed', false)
            ->count();

        if ($remainingResponses > 0) {
            return redirect()->route('digital.questionnaire.show', $order->order_number)
                ->with('success', 'Angket berhasil diisi! Silakan isi angket berikutnya.');
        }

        return redirect()->route('digital.payment.success', $order->order_number)
            ->with('success', 'Semua angket berhasil diisi! Hasil telah dikirim ke email Anda.');
    }

    /**
     * Calculate scores for questionnaire.
     */
    private function calculateScores($questionnaire, $answers)
    {
        $scores = [];

        if ($questionnaire->has_dimensions) {
            // Calculate scores by dimension
            foreach ($questionnaire->dimensions as $dimension) {
                $dimensionScore = 0;
                foreach ($dimension->questions as $question) {
                    $answer = $answers[$question->id] ?? 0;
                    $dimensionScore += $question->calculateScore($answer);
                }
                $scores[$dimension->code] = $dimensionScore;
            }
        } else {
            // Calculate total score
            $totalScore = 0;
            foreach ($questionnaire->questions as $question) {
                $answer = $answers[$question->id] ?? 0;
                $totalScore += $question->calculateScore($answer);
            }
            $scores['total'] = $totalScore;
        }

        return $scores;
    }

    /**
     * Generate result summary.
     */
    private function generateResultSummary($questionnaire, $scores)
    {
        $summary = [];

        if ($questionnaire->has_dimensions) {
            foreach ($questionnaire->dimensions as $dimension) {
                $score = $scores[$dimension->code] ?? 0;
                $interpretation = $dimension->getInterpretation($score);

                $summary[$dimension->code] = [
                    'dimension_name' => $dimension->name,
                    'score' => $score,
                    'interpretation' => $interpretation,
                ];
            }
        } else {
            $totalScore = $scores['total'] ?? 0;
            $summary['total'] = [
                'score' => $totalScore,
                'interpretation' => $this->getGeneralInterpretation($totalScore),
            ];
        }

        return $summary;
    }

    /**
     * Get general interpretation for total score.
     */
    private function getGeneralInterpretation($score)
    {
        // This is a generic interpretation, can be customized per questionnaire
        if ($score < 30) {
            return [
                'level' => 'Rendah',
                'description' => 'Tingkat Anda pada aspek ini tergolong rendah.',
            ];
        } elseif ($score < 60) {
            return [
                'level' => 'Sedang',
                'description' => 'Tingkat Anda pada aspek ini tergolong sedang.',
            ];
        } else {
            return [
                'level' => 'Tinggi',
                'description' => 'Tingkat Anda pada aspek ini tergolong tinggi.',
            ];
        }
    }

    /**
     * Generate PDF result.
     */
    private function generatePDF($response)
    {
        $pdf = Pdf::loadView('pdf.questionnaire-result', compact('response'));
        
        $fileName = 'questionnaire_result_' . $response->id . '_' . time() . '.pdf';
        $filePath = 'questionnaire_results/' . $fileName;
        
        \Storage::disk('public')->put($filePath, $pdf->output());
        
        return $filePath;
    }

    /**
     * Send result via email.
     */
    private function sendResultEmail($response)
    {
        try {
            Mail::to($response->respondent_email)->send(new QuestionnaireResult($response));
            $response->markResultAsSent();
        } catch (\Exception $e) {
            \Log::error('Failed to send questionnaire result: ' . $e->getMessage());
        }
    }

    /**
     * Download result PDF.
     */
    public function downloadResult($responseId)
    {
        $response = QuestionnaireResponse::findOrFail($responseId);
        
        if (!$response->result_pdf_path) {
            abort(404, 'PDF tidak tersedia');
        }

        return \Storage::disk('public')->download($response->result_pdf_path);
    }
}
