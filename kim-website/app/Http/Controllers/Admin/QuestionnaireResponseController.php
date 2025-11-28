<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use App\Mail\QuestionnaireResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class QuestionnaireResponseController extends Controller
{
    /**
     * Display a listing of responses.
     */
    public function index(Request $request)
    {
        $query = QuestionnaireResponse::with(['questionnaire', 'order']);

        // Filter by questionnaire
        if ($request->filled('questionnaire_id')) {
            $query->where('questionnaire_id', $request->questionnaire_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('is_completed', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_completed', false);
            }
        }

        // Search by respondent
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('respondent_name', 'like', "%{$search}%")
                  ->orWhere('respondent_email', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $responses = $query->latest()->paginate(20);
        $questionnaires = Questionnaire::orderBy('name')->get();

        // Stats
        $stats = [
            'total' => QuestionnaireResponse::count(),
            'completed' => QuestionnaireResponse::where('is_completed', true)->count(),
            'pending' => QuestionnaireResponse::where('is_completed', false)->count(),
            'sent' => QuestionnaireResponse::where('result_sent', true)->count(),
        ];

        return view('admin.digital.responses.index', compact('responses', 'questionnaires', 'stats'));
    }

    /**
     * Display the specified response.
     */
    public function show($id)
    {
        $response = QuestionnaireResponse::with([
            'questionnaire.dimensions',
            'questionnaire.questions.dimension',
            'order'
        ])->findOrFail($id);

        return view('admin.digital.responses.show', compact('response'));
    }

    /**
     * Regenerate PDF result.
     */
    public function regenerate($id)
    {
        $response = QuestionnaireResponse::with(['questionnaire.dimensions'])->findOrFail($id);

        if (!$response->is_completed) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat generate PDF untuk respons yang belum selesai!');
        }

        try {
            // Delete old PDF if exists
            if ($response->result_pdf_path && Storage::disk('public')->exists($response->result_pdf_path)) {
                Storage::disk('public')->delete($response->result_pdf_path);
            }

            // Generate new PDF
            $pdf = Pdf::loadView('pdf.questionnaire-result', compact('response'));
            
            $fileName = 'questionnaire_result_' . $response->id . '_' . time() . '.pdf';
            $filePath = 'questionnaire_results/' . $fileName;
            
            Storage::disk('public')->put($filePath, $pdf->output());
            
            $response->update(['result_pdf_path' => $filePath]);

            return redirect()
                ->back()
                ->with('success', 'PDF berhasil di-regenerate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Resend result email.
     */
    public function resend($id)
    {
        $response = QuestionnaireResponse::with('questionnaire')->findOrFail($id);

        if (!$response->is_completed) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat mengirim email untuk respons yang belum selesai!');
        }

        // Generate PDF if not exists
        if (!$response->result_pdf_path || !Storage::disk('public')->exists($response->result_pdf_path)) {
            $pdf = Pdf::loadView('pdf.questionnaire-result', compact('response'));
            
            $fileName = 'questionnaire_result_' . $response->id . '_' . time() . '.pdf';
            $filePath = 'questionnaire_results/' . $fileName;
            
            Storage::disk('public')->put($filePath, $pdf->output());
            $response->update(['result_pdf_path' => $filePath]);
        }

        try {
            Mail::to($response->respondent_email)->send(new QuestionnaireResult($response));
            
            $response->update([
                'result_sent' => true,
                'result_sent_at' => now(),
            ]);

            return redirect()
                ->back()
                ->with('success', 'Email berhasil dikirim ulang ke ' . $response->respondent_email);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF result.
     */
    public function download($id)
    {
        $response = QuestionnaireResponse::findOrFail($id);

        if (!$response->result_pdf_path || !Storage::disk('public')->exists($response->result_pdf_path)) {
            return redirect()
                ->back()
                ->with('error', 'PDF tidak tersedia!');
        }

        $fileName = 'Hasil_' . str_replace(' ', '_', $response->questionnaire->name) . '_' . $response->respondent_name . '.pdf';

        return Storage::disk('public')->download($response->result_pdf_path, $fileName);
    }

    /**
     * Export responses to CSV.
     */
    public function export(Request $request)
    {
        $query = QuestionnaireResponse::with(['questionnaire', 'order'])
            ->where('is_completed', true);

        if ($request->filled('questionnaire_id')) {
            $query->where('questionnaire_id', $request->questionnaire_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('completed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('completed_at', '<=', $request->date_to);
        }

        $responses = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="questionnaire_responses_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($responses) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID',
                'Angket',
                'Nama Responden',
                'Email',
                'Order Number',
                'Skor',
                'Tanggal Selesai',
                'Email Terkirim',
            ]);

            foreach ($responses as $response) {
                $scores = is_array($response->scores) ? json_encode($response->scores) : $response->scores;
                
                fputcsv($file, [
                    $response->id,
                    $response->questionnaire->name ?? '-',
                    $response->respondent_name,
                    $response->respondent_email,
                    $response->order->order_number ?? '-',
                    $scores,
                    $response->completed_at ? $response->completed_at->format('Y-m-d H:i:s') : '-',
                    $response->result_sent ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete response.
     */
    public function destroy($id)
    {
        $response = QuestionnaireResponse::findOrFail($id);

        // Delete PDF if exists
        if ($response->result_pdf_path && Storage::disk('public')->exists($response->result_pdf_path)) {
            Storage::disk('public')->delete($response->result_pdf_path);
        }

        $response->delete();

        return redirect()
            ->route('admin.digital.responses.index')
            ->with('success', 'Respons berhasil dihapus!');
    }
}
