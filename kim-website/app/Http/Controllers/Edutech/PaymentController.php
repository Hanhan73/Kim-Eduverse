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

        // Check if user is the owner
        if ($enrollment->student_id !== session('edutech_user_id')) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        if ($enrollment->payment_status === 'paid') {
            return redirect()
                ->route('edutech.courses.learn', $enrollment->course->slug)
                ->with('info', 'Pembayaran sudah berhasil');
        }

        // Get or create payment record
        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            // Create new payment record
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

        // Check authorization
        if ($enrollment->student_id !== session('edutech_user_id')) {
            abort(403, 'Unauthorized');
        }

        // Create or get payment
        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            $payment = $this->createPayment($enrollment);
        }

        // Initialize Midtrans Snap Token
        $snapToken = $this->getMidtransSnapToken($payment, $enrollment);

        $payment->update([
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
        // Verify Midtrans signature
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Handle payment status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->markAsPaid();
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->markAsPaid();
        } elseif ($transactionStatus == 'pending') {
            // Still pending
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->markAsFailed();
        }

        // Update metadata
        $payment->update([
            'metadata' => array_merge($payment->metadata ?? [], [
                'midtrans_response' => $request->all(),
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

        return response()->json([
            'payment_status' => $enrollment->payment_status,
            'payment' => $payment,
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
        // Set Midtrans configuration
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
}