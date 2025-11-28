<?php

namespace App\Http\Controllers;

use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class PaymentController extends Controller
{
    /**
     * Process checkout and create order.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|email|max:255',
        ]);

        $cart = Session::get('digital_cart', []);

        if (empty($cart)) {
            return redirect()->route('digital.catalog')->with('error', 'Keranjang kosong');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = 0;
        $total = $subtotal + $tax;

        // Create order
        $order = DigitalOrder::create([
            'customer_email' => $request->customer_email,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cart as $item) {
            DigitalOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_type' => $item['type'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            // Create questionnaire response if product is questionnaire
            if ($item['type'] === 'questionnaire') {
                $product = \App\Models\DigitalProduct::find($item['id']);
                if ($product && $product->questionnaire_id) {
                    QuestionnaireResponse::create([
                        'order_id' => $order->id,
                        'questionnaire_id' => $product->questionnaire_id,
                        'respondent_email' => $request->customer_email,
                        'answers' => [],
                        'is_completed' => false,
                    ]);
                }
            }
        }

        // Get Midtrans Snap Token
        $snapToken = $this->getSnapToken($order);
        $order->update(['midtrans_order_id' => $snapToken]);

        // Clear cart
        Session::forget('digital_cart');

        return view('digital.payment', compact('order', 'snapToken'));
    }

    /**
     * Get Midtrans Snap Token.
     */
    private function getSnapToken($order)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int)$item->price,
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int)$order->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return $snapToken;
    }

    /**
     * Handle Midtrans notification.
     */
    public function notification(Request $request)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);

        $notification = new \Midtrans\Notification();

        $orderNumber = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status ?? null;

        $order = DigitalOrder::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update order with Midtrans response
        $order->update([
            'midtrans_transaction_id' => $notification->transaction_id,
            'midtrans_response' => $notification->getResponse(),
        ]);

        // Handle transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $order->markAsPaid($paymentType);
                $this->sendOrderConfirmation($order);
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->markAsPaid($paymentType);
            $this->sendOrderConfirmation($order);
        } elseif ($transactionStatus == 'pending') {
            $order->update(['payment_status' => 'pending']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $order->update(['payment_status' => 'failed']);
        }

        return response()->json(['message' => 'Notification handled']);
    }

    /**
     * Send order confirmation email.
     */
    private function sendOrderConfirmation($order)
    {
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Payment success page.
     */
    public function success($orderNumber)
    {
        $order = DigitalOrder::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        return view('digital.payment-success', compact('order'));
    }
}
