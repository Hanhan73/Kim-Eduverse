<?php

namespace App\Listeners;

use App\Events\PaymentPaid;
use App\Services\RevenueService;
use App\Models\RevenueShare;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateRevenueShare implements ShouldQueue
{
    use InteractsWithQueue;

    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentPaid $event)
    {
        $payment = $event->payment;

        try {
            // Delegate mapping and completion to RevenueService (idempotent)
            $revenue = $this->revenueService->handlePaidPayment($payment);
            Log::info('CreateRevenueShare processed for ' . $revenue->transaction_code);
        } catch (\Exception $e) {
            Log::error('CreateRevenueShare error: ' . $e->getMessage());
            // Let the job retry if queued
            throw $e;
        }
    }
}
