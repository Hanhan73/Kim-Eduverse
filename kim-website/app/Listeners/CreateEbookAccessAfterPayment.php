<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Services\EbookAccessService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateEbookAccessAfterPayment implements ShouldQueue
{
    use InteractsWithQueue;

    protected $ebookService;

    public function __construct(EbookAccessService $ebookService)
    {
        $this->ebookService = $ebookService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaid $event)
    {
        $order = $event->order;

        // Cek apakah ada produk e-book dalam order
        $hasEbook = $order->items()->whereHas('product', function($q) {
            $q->where('type', 'ebook');
        })->exists();

        if ($hasEbook) {
            // Buat access untuk semua e-book dalam order
            $this->ebookService->createAccessForOrder($order);
        }
    }
}