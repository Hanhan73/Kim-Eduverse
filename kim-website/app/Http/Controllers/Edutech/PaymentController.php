<?php

namespace App\Http\Controllers\Edutech;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show($enrollmentId)
    {
        $enrollment = Enrollment::with(['course', 'student'])
            ->findOrFail($enrollmentId);

        if ($enrollment->student_id !== session('edutech_user_id')) {
            abort(403, 'Unauthorized');
        }

        if ($enrollment->payment_status === 'paid') {
            return redirect()
                ->route('edutech.courses.learn', $enrollment->course->slug)
                ->with('info', 'Pembayaran sudah berhasil');
        }

        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            $payment = $this->createPayment($enrollment);
        }

        return view('edutech.payment.show', compact('enrollment', 'payment'));
    }

    /**
     * Process payment with Midtrans
     */
    public function process(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);

        if ($enrollment->student_id !== session('edutech_user_id')) {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            $payment = $this->createPayment($enrollment);
        }

        $snapToken = $this->getMidtransSnapToken($payment, $enrollment);

        $payment->update([
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            'metadata' => array_merge($payment->metadata ?? [], [
                'snap_token' => $snapToken,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
        ]);
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        \Log::info('Midtrans Notification:', $request->all());

        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            \Log::error('Invalid Midtrans signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;
        $paymentType = $request->payment_type ?? null;

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            \Log::error('Payment not found: ' . $transactionId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        \Log::info('Processing payment status: ' . $transactionStatus . ' for ' . $transactionId);

        // Update payment method
        if ($paymentType) {
            $payment->update([
                'payment_method' => $this->formatPaymentMethod($paymentType),
            ]);
        }

        // Handle payment status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->markAsPaid();
                \Log::info('Payment marked as paid (capture): ' . $transactionId);
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->markAsPaid();
            \Log::info('Payment marked as paid (settlement): ' . $transactionId);
        } elseif ($transactionStatus == 'pending') {
            \Log::info('Payment still pending: ' . $transactionId);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->markAsFailed();
            \Log::info('Payment marked as failed: ' . $transactionId);
        }

        // Update metadata
        $payment->update([
            'metadata' => array_merge($payment->metadata ?? [], [
                'midtrans_response' => $request->all(),
                'last_update' => now()->toDateTimeString(),
            ]),
        ]);

        return response()->json(['message' => 'Notification handled']);
    }

    /**
     * Payment success callback
     */
    public function success($enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);
        
        // Pastikan payment terupdate
        $payment = Payment::where('enrollment_id', $enrollment->id)->latest()->first();
        
        if ($payment && $payment->status === 'pending') {
            // Check status dari Midtrans
            $this->checkMidtransStatus($payment);
        }

        return view('edutech.payment.success', compact('enrollment'));
    }

    /**
     * Payment failed callback
     */
    public function failed($enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);
        return view('edutech.payment.failed', compact('enrollment'));
    }

    /**
     * Check payment status
     */
    public function checkStatus($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $payment = Payment::where('enrollment_id', $enrollment->id)->latest()->first();

        // Cek status dari Midtrans jika masih pending
        if ($payment && $payment->status === 'pending') {
            $this->checkMidtransStatus($payment);
            $payment->refresh();
        }

        return response()->json([
            'payment_status' => $enrollment->fresh()->payment_status,
            'payment' => $payment->fresh(),
        ]);
    }

    /**
     * Create payment record
     */
    private function createPayment($enrollment)
    {
        $transactionId = 'TRX-' . strtoupper(Str::random(12)) . '-' . time();

        return Payment::create([
            'user_id' => $enrollment->student_id,
            'course_id' => $enrollment->course_id,
            'enrollment_id' => $enrollment->id,
            'transaction_id' => $transactionId,
            'amount' => $enrollment->payment_amount,
            'status' => 'pending',
            'metadata' => [
                'created_via' => 'web',
            ],
        ]);
    }

    /**
     * Get Midtrans Snap Token
     */
    private function getMidtransSnapToken($payment, $enrollment)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $payment->transaction_id,
                'gross_amount' => (int) $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $enrollment->student->name,
                'email' => $enrollment->student->email,
                'phone' => $enrollment->student->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => $enrollment->course->id,
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                    'name' => $enrollment->course->title,
                ]
            ],
            'callbacks' => [
                'finish' => route('edutech.payment.success', $enrollment->id),
                'error' => route('edutech.payment.failed', $enrollment->id),
                'pending' => route('edutech.payment.show', $enrollment->id),
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check transaction status from Midtrans
     */
    private function checkMidtransStatus($payment)
    {
        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

            $status = \Midtrans\Transaction::status($payment->transaction_id);

            if ($status->transaction_status == 'settlement' || 
                ($status->transaction_status == 'capture' && $status->fraud_status == 'accept')) {
                
                $payment->update([
                    'payment_method' => $this->formatPaymentMethod($status->payment_type ?? null),
                ]);
                
                $payment->markAsPaid();
            }

            return $status;
        } catch (\Exception $e) {
            \Log::error('Error checking Midtrans status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format payment method name
     */
    private function formatPaymentMethod($paymentType)
    {
        $methods = [
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'echannel' => 'Mandiri Bill',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'cstore' => 'Convenience Store',
            'akulaku' => 'Akulaku',
        ];

        return $methods[$paymentType] ?? ucwords(str_replace('_', ' ', $paymentType));
    }
}