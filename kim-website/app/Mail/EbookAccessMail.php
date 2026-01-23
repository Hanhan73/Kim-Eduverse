<?php

namespace App\Mail;

use App\Models\EbookAccess;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EbookAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $access;

    public function __construct(EbookAccess $access)
    {
        $this->access = $access;
    }

    public function build()
    {
        return $this->subject('Akses E-Book Anda - ' . $this->access->product->name)
                    ->view('emails.ebook-access');
    }
}