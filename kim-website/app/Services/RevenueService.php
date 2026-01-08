<?php

namespace App\Services;

use App\Models\RevenueShare;
use App\Models\InstructorEarning;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RevenueService
{
    /**
     * Buat revenue share saat ada transaksi baru
     */
    public function createRevenueShare(
        int $userId,
        int $instructorId,
        string $courseType,
        int $courseId,
        float $totalAmount,
        string $transactionCode = null
    ): RevenueShare {
        $shares = RevenueShare::calculateShares($totalAmount);

        return RevenueShare::create([
            'transaction_code' => $transactionCode ?? RevenueShare::generateTransactionCode(),
            'user_id' => $userId,
            'instructor_id' => $instructorId,
            'course_type' => $courseType,
            'course_id' => $courseId,
            'total_amount' => $totalAmount,
            'instructor_share' => $shares['instructor_share'],
            'company_share' => $shares['company_share'],
            'instructor_percentage' => $shares['instructor_percentage'],
            'company_percentage' => $shares['company_percentage'],
            'status' => 'pending',
        ]);
    }

    /**
     * Complete revenue share dan update instructor earnings
     */
    public function completeRevenueShare(RevenueShare $revenueShare): bool
    {
        try {
            DB::beginTransaction();

            $revenueShare->markAsCompleted();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get revenue statistics untuk bendahara
     */
    public function getRevenueStatistics(string $period = 'all')
    {
        $query = RevenueShare::completed();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        $total = $query->sum('total_amount');
        $instructorShare = $query->sum('instructor_share');
        $companyShare = $query->sum('company_share');
        $salesCount = $query->count();

        return [
            'total_revenue' => $total,
            'instructor_share' => $instructorShare,
            'company_share' => $companyShare,
            'sales_count' => $salesCount,
            'average_sale' => $salesCount > 0 ? $total / $salesCount : 0,
        ];
    }

    /**
     * Get revenue by instructor
     */
    public function getInstructorRevenue(int $instructorId, string $period = 'all')
    {
        $query = RevenueShare::completed()->byInstructor($instructorId);

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        return [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('instructor_share'),
            'edutech_sales' => $query->where('course_type', 'edutech')->count(),
            'kim_digital_sales' => $query->where('course_type', 'kim_digital')->count(),
        ];
    }

    /**
     * Get top earning instructors
     */
    public function getTopInstructors(int $limit = 10)
    {
        return InstructorEarning::with('instructor')
            ->topEarners($limit)
            ->get()
            ->map(function ($earning) {
                return [
                    'instructor' => $earning->instructor,
                    'total_earned' => $earning->total_earned,
                    'total_sales' => $earning->total_sales,
                    'available_balance' => $earning->available_balance,
                ];
            });
    }

    /**
     * Get revenue breakdown by course type
     */
    public function getRevenueBreakdown(string $period = 'all')
    {
        $query = RevenueShare::completed();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        $edutech = $query->where('course_type', 'edutech')
            ->selectRaw('COUNT(*) as sales, SUM(total_amount) as revenue')
            ->first();

        $kimDigital = $query->where('course_type', 'kim_digital')
            ->selectRaw('COUNT(*) as sales, SUM(total_amount) as revenue')
            ->first();

        return [
            'edutech' => [
                'sales' => $edutech->sales ?? 0,
                'revenue' => $edutech->revenue ?? 0,
            ],
            'kim_digital' => [
                'sales' => $kimDigital->sales ?? 0,
                'revenue' => $kimDigital->revenue ?? 0,
            ],
        ];
    }

    /**
     * Apply period filter to query
     */
    private function applyPeriodFilter($query, string $period)
    {
        $now = now();

        return match ($period) {
            'today' => $query->whereDate('paid_at', $now->toDateString()),
            'week' => $query->whereBetween('paid_at', [$now->startOfWeek(), $now->endOfWeek()]),
            'month' => $query->whereMonth('paid_at', $now->month)->whereYear('paid_at', $now->year),
            'year' => $query->whereYear('paid_at', $now->year),
            default => $query,
        };
    }

    /**
     * Get company total revenue
     */
    public function getCompanyRevenue(string $period = 'all')
    {
        $query = RevenueShare::completed();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        return $query->sum('company_share');
    }
}
