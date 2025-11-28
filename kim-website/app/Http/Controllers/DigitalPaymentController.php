<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\DigitalProduct;
use App\Models\QuestionnaireResponse;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        Log::info('=== CHECKOUT STARTED ===');
        
        $request->validate([
            'customer_email' => 'required|email|max:255',
        ]);

        Log::info('Validation passed');

        $cart = session()->get('digital_cart', []);

        if (empty($cart)) {
            Log::warning('Cart is empty');
            return back()->with('error', 'Keranjang belanja kosong');
        }

        Log::info('Cart has items: ' . count($cart));

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = collect($cart)->sum('price');
            $tax = round($subtotal * 0.01);
            $total = $subtotal + $tax;

            Log::info('Totals calculated', ['subtotal' => $subtotal, 'tax' => $tax, 'total' => $total]);

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

            Log::info('Order created', ['order_number' => $order->order_number]);

            // Create order items
            foreach ($cart as $item) {
                $itemSubtotal = $item['price'] * 1; // quantity is always 1
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_type' => $item['type'],
                    'price' => $item['price'],
                    'quantity' => 1,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            Log::info('Order items created: ' . count($cart));

            DB::commit();
            Log::info('Transaction committed');

            // Clear cart after successful order creation
            session()->forget('digital_cart');
            Log::info('Cart cleared');

            // Redirect to payment page
            Log::info('Redirecting to payment page', ['order_number' => $order->order_number]);
            return redirect()->route('digital.payment.show', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
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

        Log::info('=== PAYMENT SUCCESS PAGE ===');
        Log::info('Order: ' . $orderNumber);
        Log::info('Current payment status: ' . $order->payment_status);

        // Jika masih pending, cek status dari Midtrans (COPY DARI EDUTECH!)
        if ($order->payment_status === 'pending') {
            Log::info('Order still pending, checking Midtrans status...');
            $this->checkMidtransStatus($order);
            $order->refresh();
            Log::info('After Midtrans check, payment status: ' . $order->payment_status);
        }

        // Check if order has questionnaire and if it's completed
        $hasQuestionnaire = $order->items->contains(function($item) {
            return $item->product_type === 'questionnaire';
        });

        $questionnaireCompleted = false;
        if ($hasQuestionnaire) {
            $response = QuestionnaireResponse::where('order_id', $order->id)->first();
            $questionnaireCompleted = $response && $response->completed_at !== null;
            
            Log::info('Questionnaire status check', [
                'has_questionnaire' => $hasQuestionnaire,
                'response_exists' => $response ? true : false,
                'completed_at' => $response ? $response->completed_at : null,
                'is_completed' => $questionnaireCompleted
            ]);
        }

        return view('digital.payment-success', compact('order', 'hasQuestionnaire', 'questionnaireCompleted'));
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
     * Manual payment confirmation (for localhost without webhook)
     */
    public function confirmPayment(Request $request, $orderNumber)
    {
        Log::info('=== MANUAL CONFIRMATION STARTED ===');
        Log::info('Manual payment confirmation', [
            'order_number' => $orderNumber,
            'transaction_id' => $request->transaction_id,
            'status_code' => $request->status_code
        ]);

        $order = DigitalOrder::where('order_number', $orderNumber)->firstOrFail();
        
        Log::info('Order found', [
            'order_id' => $order->id,
            'current_payment_status' => $order->payment_status,
            'current_order_status' => $order->order_status
        ]);

        // Only process if still pending
        if ($order->payment_status === 'pending') {
            Log::info('Order is pending, processing payment...');
            
            DB::beginTransaction();
            try {
                $this->processSuccessfulPayment($order);
                DB::commit();
                
                Log::info('Manual confirmation successful', [
                    'order_number' => $orderNumber,
                    'new_payment_status' => 'paid',
                    'new_order_status' => 'completed'
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Manual confirmation failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation failed: ' . $e->getMessage()
                ], 500);
            }
        }

        Log::info('Order already confirmed', [
            'payment_status' => $order->payment_status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Already confirmed'
        ]);
    }

    /**
     * Process successful payment
     */
    private function processSuccessfulPayment($order)
    {
        Log::info('=== PROCESSING SUCCESSFUL PAYMENT ===');
        Log::info('Starting payment processing', ['order_id' => $order->id]);
        
        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'paid_at' => now(),
        ]);
        
        Log::info('Order status updated', [
            'order_id' => $order->id,
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'paid_at' => now()
        ]);

        // Update product sold count
        $soldCountUpdates = 0;
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('sold_count');
                $soldCountUpdates++;
                Log::info('Product sold count updated', [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'new_sold_count' => $item->product->sold_count
                ]);
            }
        }
        Log::info('Total sold counts updated: ' . $soldCountUpdates);

        // Create questionnaire responses for questionnaire products
        $responsesCreated = 0;
        foreach ($order->items as $item) {
            if ($item->product_type === 'questionnaire' && $item->product && $item->product->questionnaire_id) {
                Log::info('Creating questionnaire response', [
                    'product_id' => $item->product_id,
                    'questionnaire_id' => $item->product->questionnaire_id,
                    'order_id' => $order->id
                ]);
                
                try {
                    $response = QuestionnaireResponse::create([
                        'questionnaire_id' => $item->product->questionnaire_id,
                        'order_id' => $order->id,
                        'respondent_email' => $order->customer_email,
                        'answers' => [],
                        'result_summary' => [],
                    ]);
                    
                    $responsesCreated++;
                    Log::info('Questionnaire response created', [
                        'response_id' => $response->id,
                        'questionnaire_id' => $response->questionnaire_id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create questionnaire response: ' . $e->getMessage());
                    throw $e;
                }
            }
        }
        Log::info('Total questionnaire responses created: ' . $responsesCreated);

        // Send confirmation email
        Log::info('Attempting to send confirmation email', ['to' => $order->customer_email]);
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            Log::info('Confirmation email sent successfully', ['to' => $order->customer_email]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            Log::error('Email error stack trace: ' . $e->getTraceAsString());
        }
        
        Log::info('=== PAYMENT PROCESSING COMPLETED ===');
    }

    /**
     * Check transaction status from Midtrans (COPY DARI EDUTECH!)
     */
    private function checkMidtransStatus($order)
    {
        try {
            Log::info('Checking Midtrans transaction status', ['order_number' => $order->order_number]);
            
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production', false);

            $status = \Midtrans\Transaction::status($order->order_number);

            Log::info('Midtrans status response', [
                'order_number' => $order->order_number,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null
            ]);

            if ($status->transaction_status == 'settlement' || 
                ($status->transaction_status == 'capture' && $status->fraud_status == 'accept')) {
                
                Log::info('Transaction confirmed, processing payment...');
                
                DB::beginTransaction();
                try {
                    $this->processSuccessfulPayment($order);
                    DB::commit();
                    Log::info('Payment successfully processed via Midtrans status check');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to process payment: ' . $e->getMessage());
                    throw $e;
                }
            } else {
                Log::info('Transaction not yet completed', ['status' => $status->transaction_status]);
            }

            return $status;
        } catch (\Exception $e) {
            Log::error('Error checking Midtrans status: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }
}