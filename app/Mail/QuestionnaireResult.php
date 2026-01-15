<?php

namespace App\Mail;

use App\Models\QuestionnaireResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class QuestionnaireResult extends Mailable
{
    use Queueable, SerializesModels;

    public QuestionnaireResponse $response;

    /**
     * Create a new message instance.
     */
    public function __construct(QuestionnaireResponse $response)
    {
        $this->response = $response->load(['questionnaire', 'order']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $questionnaireName = $this->response->questionnaire->name;
        
        return new Envelope(
            subject: "Hasil Analisis: {$questionnaireName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Use AI template if AI analysis is available
        $view = $this->response->hasAIAnalysis() 
            ? 'emails.questionnaire-result-ai' 
            : 'emails.questionnaire-result';

        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach PDF result if available
        if ($this->response->result_pdf_path) {
            $pdfPath = Storage::disk('public')->path($this->response->result_pdf_path);
            
            if (file_exists($pdfPath)) {
                $questionnaireName = str_replace(' ', '_', $this->response->questionnaire->name);
                $fileName = "Hasil_Analisis_{$questionnaireName}.pdf";
                
                $attachments[] = Attachment::fromPath($pdfPath)
                    ->as($fileName)
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}
