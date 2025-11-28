<?php

namespace App\Mail;

use App\Models\QuestionnaireResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class QuestionnaireResult extends Mailable
{
    use Queueable, SerializesModels;

    public $response;

    /**
     * Create a new message instance.
     */
    public function __construct(QuestionnaireResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        Log::info('Building QuestionnaireResult email', [
            'response_id' => $this->response->id,
            'pdf_path' => $this->response->result_pdf_path
        ]);

        $mail = $this->subject('Hasil ' . $this->response->questionnaire->name)
                     ->view('emails.questionnaire-result');

        // Attach PDF if exists
        if ($this->response->result_pdf_path && Storage::exists($this->response->result_pdf_path)) {
            $fullPath = Storage::path($this->response->result_pdf_path);
            
            Log::info('Attaching PDF', [
                'path' => $fullPath,
                'exists' => file_exists($fullPath)
            ]);
            
            $mail->attach(
                $fullPath,
                [
                    'as' => 'Hasil-' . $this->response->questionnaire->name . '.pdf',
                    'mime' => 'application/pdf',
                ]
            );
            
            Log::info('PDF attached successfully');
        } else {
            Log::warning('PDF not found for attachment', [
                'pdf_path' => $this->response->result_pdf_path,
                'exists' => $this->response->result_pdf_path ? Storage::exists($this->response->result_pdf_path) : false
            ]);
        }

        return $mail;
    }
}