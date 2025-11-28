<?php

namespace App\Mail;

use App\Models\DigitalOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(DigitalOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        Log::info('Building OrderConfirmation email', [
            'order_number' => $this->order->order_number,
            'customer_email' => $this->order->customer_email
        ]);

        return $this->subject('Konfirmasi Pesanan - ' . $this->order->order_number)
                    ->view('emails.order-confirmation');
    }
}