<?php

namespace App\Helpers;

use App\Models\RevenueShare;
use App\Services\RevenueService;

class PaymentHelper
{
    /**
     * Process payment dan create revenue share
     * 
     * Panggil method ini setelah payment berhasil
     * 
     * @param int $userId - ID user yang membeli
     * @param int $instructorId - ID instruktor
     * @param string $courseType - 'edutech' atau 'kim_digital'
     * @param int $courseId - ID course
     * @param float $amount - Total harga
     * @param string $transactionCode - Kode transaksi dari Midtrans
     * @return RevenueShare
     */
    public static function processPaymentRevenue(
        int $userId,
        int $instructorId,
        string $courseType,
        int $courseId,
        float $amount,
        string $transactionCode
    ): RevenueShare {
        $revenueService = app(RevenueService::class);

        // Create revenue share
        $revenueShare = $revenueService->createRevenueShare(
            $userId,
            $instructorId,
            $courseType,
            $courseId,
            $amount,
            $transactionCode
        );

        // Complete revenue share dan update instructor earnings
        $revenueService->completeRevenueShare($revenueShare);

        return $revenueShare;
    }
}


/**
 * CONTOH PENGGUNAAN DALAM PAYMENT CONTROLLER
 * 
 * Di dalam method yang handle payment callback/notification dari Midtrans:
 */

/*
use App\Helpers\PaymentHelper;

// ... setelah verifikasi payment success ...

if ($transaction->status_code == '200') {
    // Get course dan instructor info
    $enrollment = CourseEnrollment::where('transaction_code', $transactionCode)->first();
    $course = $enrollment->course;
    
    // Tentukan course type
    $courseType = ($course instanceof EdutechCourse) ? 'edutech' : 'kim_digital';
    
    // Process revenue sharing
    try {
        PaymentHelper::processPaymentRevenue(
            userId: $enrollment->user_id,
            instructorId: $course->instructor_id,
            courseType: $courseType,
            courseId: $course->id,
            amount: $transaction->gross_amount,
            transactionCode: $transactionCode
        );
        
        \Log::info('Revenue sharing processed for ' . $transactionCode);
    } catch (\Exception $e) {
        \Log::error('Failed to process revenue sharing: ' . $e->getMessage());
        // Note: jangan fail payment karena ini, bisa diproses manual
    }
}
*/