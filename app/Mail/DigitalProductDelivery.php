<?php

namespace App\Mail;

use App\Models\DigitalOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DigitalProductDelivery extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $downloadableProducts;

    /**
     * Create a new message instance.
     */
    public function __construct(DigitalOrder $order)
    {
        $this->order = $order;
        
        // Get downloadable products (support file_path atau file_url)
        $this->downloadableProducts = $order->items->filter(function ($item) {
            $hasFile = $item->product && 
                       ($item->product->file_path || $item->product->file_url);
            $isDownloadableType = in_array($item->product_type, ['ebook', 'template', 'worksheet', 'document']);
            return $hasFile && $isDownloadableType;
        });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Produk Digital Anda Siap Didownload - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.digital-product-delivery',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
