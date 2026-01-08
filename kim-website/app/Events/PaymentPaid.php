<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class PaymentPaid
{
    use InteractsWithSockets, SerializesModels;

    public $payment;

    /**
     * Create a new event instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }
}
