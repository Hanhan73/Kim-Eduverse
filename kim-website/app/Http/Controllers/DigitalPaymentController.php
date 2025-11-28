<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\DigitalProduct;
use App\Models\QuestionnaireResponse;
use App\Mail\OrderConfirmation;
use App\Mail\DigitalProductDelivery;
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
    public function __construct()
    {
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
            $tax = 0; // No tax
            $total = $subtotal + $tax;

            // Create order
            $order = DigitalOrder::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_email' => $request->customer_email,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            // Create order items
            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_type' => $item['type'],
                    'price' => $item['price'],
                    'quantity' => 1,
                    'subtotal' => $item['price'], // quantity * price
                ]);
            }

            DB::commit();

            // Clear cart after successful order creation
            session()->forget('digital_cart');

            // Redirect to payment page
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

        // Only show payment if order is still pending
        if ($order->payment_status !== 'pending') {
            return redirect()->route('digital.payment.success', $order->order_number);
        }

        try {
            // Create Midtrans Snap transaction
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
                        'name' => substr($item->product_name, 0, 50), // Max 50 chars
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
     * 
     * PENTING: Karena webhook tidak bekerja di localhost, 
     * kita cek status langsung ke Midtrans API di halaman success
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
            return in_array($item->product_type, ['ebook', 'template', 'worksheet', 'document']);
        });

        // Get downloadable products (support file_path atau file_url)
        $downloadableProducts = $order->items->filter(function ($item) {
            $hasFile = $item->product && 
                       ($item->product->file_path || $item->product->file_url);
            $isDownloadableType = in_array($item->product_type, ['ebook', 'template', 'worksheet', 'document']);
            return $hasFile && $isDownloadableType;
        });

        // Get questionnaire responses (incomplete)
        $incompleteQuestionnaires = $order->responses->where('is_completed', false);

        return view('digital.payment-success', compact(
            'order',
            'hasQuestionnaire',
            'hasDownloadable',
            'downloadableProducts',
            'incompleteQuestionnaires'
        ));
    }

    /**
     * Check payment status directly from Midtrans API
     * Solusi untuk localhost yang tidak bisa terima webhook
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
                $transactionStatus = $data['transaction_status'] ?? null;
                $fraudStatus = $data['fraud_status'] ?? null;

                Log::info('Midtrans status check', [
                    'order' => $order->order_number,
                    'status' => $transactionStatus,
                    'fraud' => $fraudStatus,
                ]);

                // Handle status
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $this->processSuccessfulPayment($order);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $this->processSuccessfulPayment($order);
                } elseif ($transactionStatus == 'pending') {
                    // Still pending, do nothing
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $order->update([
                        'payment_status' => 'failed',
                        'order_status' => 'cancelled',
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

            if (!$order) {
                Log::error('Order not found: ' . $orderNumber);
                return response()->json(['message' => 'Order not found'], 404);
            }

            DB::beginTransaction();
            try {
                // Handle different transaction statuses
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $this->processSuccessfulPayment($order);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $this->processSuccessfulPayment($order);
                } elseif ($transactionStatus == 'pending') {
                    $order->update([
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $order->update([
                        'payment_status' => 'failed',
                        'order_status' => 'cancelled',
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
     * Process successful payment
     */
    private function processSuccessfulPayment($order)
    {
        // Prevent double processing
        if ($order->payment_status === 'paid') {
            return;
        }

        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'paid_at' => now(),
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

        foreach ($order->items as $item) {
            // Create questionnaire responses for questionnaire products
            if ($item->product_type === 'questionnaire') {
                if ($item->product && $item->product->questionnaire_id) {
                    // Check if response already exists
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
            
            // Mark downloadable products
            if (in_array($item->product_type, ['ebook', 'template', 'worksheet', 'document'])) {
                $hasDownloadable = true;
            }
        }

        // Send appropriate email
        try {
            if ($hasDownloadable) {
                // Send email with download links for downloadable products
                Mail::to($order->customer_email)->send(new DigitalProductDelivery($order));
            } else {
                // Send standard confirmation email
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            }

            Log::info('Order confirmation email sent', ['order' => $order->order_number]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Download digital product file
     * 
     * Support 2 tipe sumber file:
     * 1. Local storage: 'digital-products/ebook.pdf'
     * 2. External URL: 'https://drive.google.com/...'
     * 
     * Tips Google Drive direct download link:
     * - Format: https://drive.google.com/uc?export=download&id=FILE_ID
     * - Ambil FILE_ID dari share link: https://drive.google.com/file/d/FILE_ID/view
     */
    public function downloadProduct($orderNumber, $productId)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        // Check if product is in this order
        $orderItem = $order->items()->where('product_id', $productId)->firstOrFail();

        // Get product
        $product = DigitalProduct::findOrFail($productId);

        // Check if product has file source (file_path atau file_url)
        $fileSource = $product->file_url ?? $product->file_path;
        
        if (!$fileSource) {
            abort(404, 'File tidak ditemukan');
        }

        // Log download attempt
        Log::info('Product download attempt', [
            'order' => $orderNumber,
            'product_id' => $productId,
            'file_source' => $fileSource,
        ]);

        // Check apakah external URL atau local path
        if ($this->isExternalUrl($fileSource)) {
            // External URL - redirect ke Google Drive / URL eksternal
            return redirect()->away($fileSource);
        }

        // Local storage
        $filePath = storage_path('app/public/' . $fileSource);

        if (!file_exists($filePath)) {
            Log::error('Product file not found', [
                'product_id' => $productId,
                'file_path' => $filePath,
            ]);
            abort(404, 'File tidak ditemukan di server');
        }

        // Generate download filename
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = \Str::slug($product->name) . '.' . $extension;

        // Increment download count (optional)
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
     * Helper: Convert Google Drive share link to direct download link
     * 
     * Input:  https://drive.google.com/file/d/1ABC123xyz/view?usp=sharing
     * Output: https://drive.google.com/uc?export=download&id=1ABC123xyz
     */
    public static function convertGoogleDriveLink($shareLink)
    {
        // Pattern untuk extract file ID dari berbagai format Google Drive link
        $patterns = [
            '/\/file\/d\/([a-zA-Z0-9_-]+)/',  // /file/d/FILE_ID/
            '/id=([a-zA-Z0-9_-]+)/',           // ?id=FILE_ID
            '/\/d\/([a-zA-Z0-9_-]+)/',         // /d/FILE_ID/
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $shareLink, $matches)) {
                $fileId = $matches[1];
                return "https://drive.google.com/uc?export=download&id={$fileId}";
            }
        }

        // Jika tidak match, return original link
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

        // Generate PDF using DomPDF
        $pdf = \PDF::loadView('pdf.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
