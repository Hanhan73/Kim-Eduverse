<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\DigitalProduct;
use App\Models\QuestionnaireResponse;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

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
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $cart = session()->get('digital_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong');
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = collect($cart)->sum('price');
            $tax = round($subtotal * 0.01);
            $total = $subtotal + $tax;

            // Create order
            $order = DigitalOrder::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
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
                    'first_name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone ?? '',
                ],
                'item_details' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->product_id,
                        'price' => (int) $item->price,
                        'quantity' => $item->quantity,
                        'name' => $item->product_name,
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
            ->with(['items.product'])
            ->firstOrFail();

        return view('digital.payment-success', compact('order'));
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

        // Create questionnaire responses for questionnaire products
        foreach ($order->items as $item) {
            if ($item->product_type === 'questionnaire' && $item->product && $item->product->questionnaire_id) {
                QuestionnaireResponse::create([
                    'questionnaire_id' => $item->product->questionnaire_id,
                    'order_id' => $order->id,
                    'respondent_name' => $order->customer_name,
                    'respondent_email' => $order->customer_email,
                    'status' => 'pending',
                ]);
            }
        }

        // Send confirmation email
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }
}
