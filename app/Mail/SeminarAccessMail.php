<?php

namespace App\Mail;

use App\Models\DigitalOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SeminarAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $seminarLink;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\DigitalOrder $order
     * @return void
     */
    public function __construct(DigitalOrder $order)
    {
        $this->order = $order;

        // Generate seminar link
        $seminarItem = $order->items()->where('product_type', 'seminar')->first();
        if ($seminarItem) {
            $this->seminarLink = route('digital.seminar.learn', $order->order_number);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Akses Seminar Anda - ' . $this->order->order_number)
            ->view('emails.seminar-access')
            ->with([
                'order' => $this->order,
                'seminarLink' => $this->seminarLink
            ]);
    }
}
