<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\DigitalProduct;
use App\Models\QuestionnaireResponse;
use App\Mail\OrderConfirmation;
use App\Mail\DigitalProductDelivery;
use App\Services\EbookAccessService; // TAMBAH INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class DigitalPaymentController extends Controller
{
    protected $ebookService; // TAMBAH INI

    // UPDATE CONSTRUCTOR
    public function __construct(EbookAccessService $ebookService)
    {
        $this->ebookService = $ebookService;
        
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|email|max:255',
        ]);

        $cart = session()->get('digital_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong');
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = collect($cart)->sum('price');
            $total = $subtotal + $tax = 0;

            // Create order
            $order = DigitalOrder::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_email' => $request->customer_email,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_type' => $item['type'],
                    'price' => $item['price'],
                    'quantity' => 1,
                    'subtotal' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget('digital_cart');

            return redirect()->route('digital.payment.show', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Display payment page with Midtrans Snap
     */
    public function show($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        if ($order->payment_status !== 'pending') {
            return redirect()->route('digital.payment.success', $order->order_number);
        }

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total,
                ],
                'customer_details' => [
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone ?? '',
                ],
                'item_details' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->product_id,
                        'price' => (int) $item->price,
                        'quantity' => $item->quantity,
                        'name' => substr($item->product_name, 0, 50),
                    ];
                })->toArray(),
            ];

            $snapToken = Snap::getSnapToken($params);

            return view('digital.payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Display payment success page
     */
    public function success($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->with(['items.product', 'responses.questionnaire'])
            ->firstOrFail();

        // Jika belum paid, cek status ke Midtrans langsung
        if ($order->payment_status !== 'paid') {
            $this->checkMidtransStatus($order);
            $order->refresh();
        }

        // Determine product types in order
        $hasQuestionnaire = $order->items->contains(function ($item) {
            return $item->product_type === 'questionnaire';
        });

        $hasDownloadable = $order->items->contains(function ($item) {
            return in_array($item->product_type, ['template', 'worksheet', 'document']);
        });

        $hasSeminar = $order->items->contains('product_type', 'seminar');

        // TAMBAH INI - Check e-book
        $hasEbook = $order->items->contains('product_type', 'ebook');
        
        $ebookItems = $order->items->filter(function($item) {
            return $item->product_type === 'ebook';
        });

        // Get downloadable products (EXCLUDE ebook karena punya sistem sendiri)
        $downloadableProducts = $order->items->filter(function ($item) {
            $hasFile = $item->product &&
                ($item->product->file_path || $item->product->file_url);
            $isDownloadableType = in_array($item->product_type, ['template', 'worksheet', 'document']);
            return $hasFile && $isDownloadableType;
        });

        // Get questionnaire responses (incomplete)
        $incompleteQuestionnaires = $order->responses->where('is_completed', false);

        return view('digital.payment-success', compact(
            'order',
            'hasQuestionnaire',
            'hasDownloadable',
            'downloadableProducts',
            'incompleteQuestionnaires',
            'hasSeminar',
            'hasEbook',      // TAMBAH INI
            'ebookItems'     // TAMBAH INI
        ));
    }

    /**
     * Check payment status directly from Midtrans API
     */
    private function checkMidtransStatus($order)
    {
        try {
            $serverKey = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);

            $baseUrl = $isProduction
                ? 'https://api.midtrans.com'
                : 'https://api.sandbox.midtrans.com';

            $response = Http::withBasicAuth($serverKey, '')
                ->get("{$baseUrl}/v2/{$order->order_number}/status");

            if ($response->successful()) {
                $data = $response->json();
                $order->update([
                    'payment_method' => $data['payment_type'] ?? null,
                    'midtrans_order_id' => $data['order_id'] ?? $order->order_number,
                    'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                    'midtrans_response' => $data,
                ]);
                $transactionStatus = $data['transaction_status'] ?? null;
                $fraudStatus = $data['fraud_status'] ?? null;

                Log::info('Midtrans status check', [
                    'order' => $order->order_number,
                    'status' => $transactionStatus,
                    'fraud' => $fraudStatus,
                ]);

                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $this->processSuccessfulPayment($order);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $this->processSuccessfulPayment($order);
                } elseif ($transactionStatus == 'pending') {
                    // Still pending
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Midtrans status check failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans payment notification (webhook)
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderNumber = $notification->order_id;

            Log::info('Midtrans notification received', [
                'order_id' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            $order = DigitalOrder::where('order_number', $orderNumber)->first();

            $order->update([
                'payment_method' => $notification->payment_type ?? null,
                'midtrans_order_id' => $notification->order_id ?? null,
                'midtrans_transaction_id' => $notification->transaction_id ?? null,
                'midtrans_response' => json_decode(json_encode($notification), true),
            ]);
            if (!$order) {
                Log::error('Order not found: ' . $orderNumber);
                return response()->json(['message' => 'Order not found'], 404);
            }

            DB::beginTransaction();
            try {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $this->processSuccessfulPayment($order);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $this->processSuccessfulPayment($order);
                } elseif ($transactionStatus == 'pending') {
                    $order->update([
                        'payment_status' => 'pending',
                        'status' => 'pending',
                    ]);
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled',
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'OK']);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Payment notification processing failed: ' . $e->getMessage());
                return response()->json(['message' => 'Error processing notification'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }
    }

    /**
     * Process successful payment - UPDATED WITH EBOOK
     */
    private function processSuccessfulPayment($order)
    {
        // Prevent double processing
        if ($order->payment_status === 'paid') {
            return;
        }

        Log::info('=== PROCESSING SUCCESSFUL PAYMENT ===', ['order' => $order->order_number]);

        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'paid_at' => $order->paid_at ?? now(),
        ]);

        // Update product sold count
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('sold_count');
            }
        }

        // Process items based on type
        $hasQuestionnaire = false;
        $hasDownloadable = false;
        $hasSeminar = false;
        $hasEbook = false; // TAMBAH INI

        foreach ($order->items as $item) {
            // Create questionnaire responses
            if ($item->product_type === 'questionnaire') {
                if ($item->product && $item->product->questionnaire_id) {
                    $existingResponse = QuestionnaireResponse::where('order_id', $order->id)
                        ->where('questionnaire_id', $item->product->questionnaire_id)
                        ->first();

                    if (!$existingResponse) {
                        QuestionnaireResponse::create([
                            'questionnaire_id' => $item->product->questionnaire_id,
                            'order_id' => $order->id,
                            'respondent_email' => $order->customer_email,
                            'answers' => [],
                            'is_completed' => false,
                        ]);
                    }
                }
                $hasQuestionnaire = true;
            }

            // Create seminar enrollment
            if ($item->product_type === 'seminar') {
                $digitalProduct = DigitalProduct::find($item->product_id);

                Log::info('Processing seminar enrollment', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'digital_product_seminar_id' => $digitalProduct ? $digitalProduct->seminar_id : 'NULL',
                ]);

                if ($digitalProduct && $digitalProduct->seminar_id) {
                    $seminarId = $digitalProduct->seminar_id;

                    $existingEnrollment = \App\Models\SeminarEnrollment::where('order_id', $order->id)
                        ->where('seminar_id', $seminarId)
                        ->first();

                    if (!$existingEnrollment) {
                        \App\Models\SeminarEnrollment::create([
                            'seminar_id' => $seminarId,
                            'customer_email' => $order->customer_email,
                            'order_id' => $order->id,
                        ]);

                        Log::info('Seminar enrollment created successfully', [
                            'order_id' => $order->id,
                            'seminar_id' => $seminarId,
                        ]);
                    }
                }
                $hasSeminar = true;
            }

            // TAMBAH INI - Process E-book
            if ($item->product_type === 'ebook') {
                $hasEbook = true;
            }

            // Mark downloadable products (EXCLUDE ebook)
            if (in_array($item->product_type, ['template', 'worksheet', 'document'])) {
                $hasDownloadable = true;
            }
        }

        // CREATE EBOOK ACCESS - TAMBAH INI
        if ($hasEbook) {
            Log::info('Creating ebook access for order', ['order_id' => $order->id]);
            try {
                $this->ebookService->createAccessForOrder($order);
                Log::info('Ebook access created successfully');
            } catch (\Exception $e) {
                Log::error('Failed to create ebook access', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Send appropriate email
        try {
            if ($hasSeminar) {
                Mail::to($order->customer_email)->send(new \App\Mail\SeminarAccessMail($order));
                Log::info('Seminar access email sent', ['order' => $order->order_number]);
            } elseif ($hasDownloadable) {
                Mail::to($order->customer_email)->send(new DigitalProductDelivery($order));
                Log::info('Digital product delivery email sent', ['order' => $order->order_number]);
            } else {
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                Log::info('Standard order confirmation email sent', ['order' => $order->order_number]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        Log::info('=== PAYMENT PROCESSING COMPLETE ===');
    }

    /**
     * Download digital product file
     */
    public function downloadProduct($orderNumber, $productId)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $orderItem = $order->items()->where('product_id', $productId)->firstOrFail();
        $product = DigitalProduct::findOrFail($productId);

        // TAMBAH INI - Block download untuk e-book
        if ($product->type === 'ebook') {
            abort(403, 'E-book tidak dapat didownload. Gunakan link akses yang dikirim via email.');
        }

        $fileSource = $product->file_url ?? $product->file_path;

        if (!$fileSource) {
            abort(404, 'File tidak ditemukan');
        }

        Log::info('Product download attempt', [
            'order' => $orderNumber,
            'product_id' => $productId,
            'file_source' => $fileSource,
        ]);

        if ($this->isExternalUrl($fileSource)) {
            return redirect()->away($fileSource);
        }

        $filePath = storage_path('app/public/' . $fileSource);

        if (!file_exists($filePath)) {
            Log::error('Product file not found', [
                'product_id' => $productId,
                'file_path' => $filePath,
            ]);
            abort(404, 'File tidak ditemukan di server');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = \Str::slug($product->name) . '.' . $extension;

        $product->increment('download_count');

        return response()->download($filePath, $filename);
    }

    /**
     * Check if string is external URL
     */
    private function isExternalUrl($string)
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Convert Google Drive share link to direct download link
     */
    public static function convertGoogleDriveLink($shareLink)
    {
        $patterns = [
            '/\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/id=([a-zA-Z0-9_-]+)/',
            '/\/d\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $shareLink, $matches)) {
                $fileId = $matches[1];
                return "https://drive.google.com/uc?export=download&id={$fileId}";
            }
        }

        return $shareLink;
    }

    /**
     * Generate invoice PDF
     */
    public function downloadInvoice($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->with(['items.product'])
            ->firstOrFail();

        $pdf = \PDF::loadView('pdf.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}