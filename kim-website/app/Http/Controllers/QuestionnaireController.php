<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use App\Services\ClaudeAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\QuestionnaireResult;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireController extends Controller
{
    protected ClaudeAIService $aiService;

    public function __construct(ClaudeAIService $aiService)
    {
        $this->aiService = $aiService;
    }

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
                ->with('info', 'Semua CEKMA sudah selesai diisi');
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

        // Generate basic result summary
        $resultSummary = $this->generateResultSummary($questionnaire, $scores);

        // Update response with basic data first
        $response->update([
            'answers' => $answers,
            'scores' => $scores,
            'result_summary' => $resultSummary,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Generate AI analysis if enabled
        $aiAnalysis = null;
        $chartData = null;
        
        if ($questionnaire->ai_enabled) {
            try {
                Log::info('Starting AI analysis', ['response_id' => $response->id]);
                
                // Generate AI analysis
                $aiAnalysis = $this->aiService->generateAnalysis($response);
                
                // Generate chart data
                $chartData = $this->aiService->generateChartData($resultSummary, $questionnaire);
                
                // Update response with AI data
                $response->update([
                    'ai_analysis' => $aiAnalysis,
                    'chart_data' => $chartData,
                    'ai_generated_at' => now(),
                ]);
                
                Log::info('AI analysis completed', ['response_id' => $response->id]);
                
            } catch (\Exception $e) {
                Log::error('AI analysis failed', [
                    'response_id' => $response->id,
                    'error' => $e->getMessage()
                ]);
                // Continue without AI analysis - PDF will use basic results
            }
        }

        // Generate PDF (with or without AI)
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
                ->with('success', 'CEKMA berhasil diisi! Silakan isi CEKMA berikutnya.');
        }

        return redirect()->route('digital.payment.success', $order->order_number)
            ->with('success', 'Semua CEKMA berhasil diisi! Hasil telah dikirim ke email Anda.');
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
     * Generate result summary with score ranges.
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
                'dimension_name' => 'Total',
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
        if ($score < 30) {
            return [
                'level' => 'Rendah',
                'class' => 'level-rendah',
                'description' => 'Tingkat Anda pada aspek ini tergolong rendah.',
            ];
        } elseif ($score < 60) {
            return [
                'level' => 'Sedang',
                'class' => 'level-sedang',
                'description' => 'Tingkat Anda pada aspek ini tergolong sedang.',
            ];
        } else {
            return [
                'level' => 'Tinggi',
                'class' => 'level-tinggi',
                'description' => 'Tingkat Anda pada aspek ini tergolong tinggi.',
            ];
        }
    }

    /**
     * Generate PDF result with AI analysis.
     */
    private function generatePDF($response)
    {
        // Load fresh response with all relations
        $response->load(['questionnaire.dimensions.questions', 'order']);
        
        $pdf = Pdf::loadView('pdf.questionnaire-result-ai', [
            'response' => $response,
            'aiAnalysis' => $response->ai_analysis,
            'chartData' => $response->chart_data,
        ]);
        
        // Set paper size
        $pdf->setPaper('A4', 'portrait');
        
        $fileName = 'hasil_cekma_' . $response->id . '_' . time() . '.pdf';
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
            Log::error('Failed to send questionnaire result: ' . $e->getMessage());
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

    /**
     * Regenerate AI analysis (admin only).
     */
    public function regenerateAI($responseId)
    {
        $response = QuestionnaireResponse::with('questionnaire')->findOrFail($responseId);
        
        if (!$response->is_completed) {
            return back()->with('error', 'Response belum selesai diisi');
        }

        try {
            // Regenerate AI analysis
            $aiAnalysis = $this->aiService->generateAnalysis($response);
            $chartData = $this->aiService->generateChartData($response->result_summary, $response->questionnaire);
            
            $response->update([
                'ai_analysis' => $aiAnalysis,
                'chart_data' => $chartData,
                'ai_generated_at' => now(),
            ]);
            
            // Regenerate PDF
            $pdfPath = $this->generatePDF($response);
            $response->update(['result_pdf_path' => $pdfPath]);
            
            return back()->with('success', 'AI analysis berhasil di-regenerate');
            
        } catch (\Exception $e) {
            Log::error('Failed to regenerate AI analysis', [
                'response_id' => $responseId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal regenerate AI analysis: ' . $e->getMessage());
        }
    }
}