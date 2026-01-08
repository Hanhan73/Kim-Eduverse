<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RevenueShare;
use App\Models\WithdrawalRequest;
use App\Models\InstructorEarning;
use App\Services\RevenueService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class BendaharaController extends Controller
{
    // protected $revenueService;
    // protected $withdrawalService;

    // public function __construct(RevenueService $revenueService, WithdrawalService $withdrawalService)
    // {
    //     $this->middleware(['auth', 'role:bendahara']);
    //     $this->revenueService = $revenueService;
    //     $this->withdrawalService = $withdrawalService;
    // }

    /**
     * Dashboard bendahara
     */
    public function dashboard(RevenueService $revenueService, WithdrawalService $withdrawalService)
    {
        $stats = [
            'total_revenue' => RevenueShare::completed()->sum('total_amount'),
            'company_revenue' => RevenueShare::completed()->sum('company_share'),
            'instructor_revenue' => RevenueShare::completed()->sum('instructor_share'),
            'pending_withdrawals' => WithdrawalRequest::pending()->count(),
            'pending_amount' => WithdrawalRequest::pending()->sum('amount'),
            'monthly_revenue' => RevenueShare::completed()
                ->whereMonth('paid_at', now()->month)
                ->sum('total_amount'),
        ];

        $revenueStats = $revenueService->getRevenueStatistics('month');
        $breakdown = $revenueService->getRevenueBreakdown('month');
        $pendingWithdrawals = $withdrawalService->getPendingWithdrawals();

        return view('admin.bendahara.dashboard', compact('stats', 'revenueStats', 'breakdown', 'pendingWithdrawals'));
    }

    /**
     * Revenue / Pemasukan
     */
    public function revenue(Request $request, RevenueService $revenueService, WithdrawalService $withdrawalService)
    {
        $period = $request->get('period', 'month');
        $instructorId = $request->get('instructor_id');

        $stats = $revenueService->getRevenueStatistics($period);
        $breakdown = $revenueService->getRevenueBreakdown($period);

        $query = RevenueShare::with(['instructor', 'user'])->completed();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }

        $revenues = $query->latest('paid_at')->paginate(20);

        // Get all instructors for filter
        $instructors = InstructorEarning::with('instructor')
            ->has('instructor')
            ->get()
            ->pluck('instructor');

        return view('admin.bendahara.revenue', compact('stats', 'breakdown', 'revenues', 'period', 'instructors'));
    }

    /**
     * Instructor earnings
     */
    public function instructorEarnings()
    {
        $instructors = InstructorEarning::with(['instructor'])
            ->has('instructor')
            ->orderBy('total_earned', 'desc')
            ->paginate(20);

        return view('admin.bendahara.instructor-earnings', compact('instructors'));
    }

    public function instructorEarningDetail($instructorId)
    {
        $earning = InstructorEarning::with('instructor')
            ->where('instructor_id', $instructorId)
            ->firstOrFail();

        $revenues = RevenueShare::with('course')
            ->where('instructor_id', $instructorId)
            ->completed()
            ->latest('paid_at')
            ->paginate(20);

        $withdrawals = WithdrawalRequest::where('instructor_id', $instructorId)
            ->latest()
            ->paginate(10);

        return view('admin.bendahara.instructor-detail', compact('earning', 'revenues', 'withdrawals'));
    }

    /**
     * Withdrawal requests
     */
    public function withdrawals(Request $request, WithdrawalService $withdrawalService)
    {
        $status = $request->get('status', 'all');

        $query = WithdrawalRequest::with(['instructor', 'approvedBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(20);

        $stats = $withdrawalService->getWithdrawalStatistics('all');

        return view('admin.bendahara.withdrawals', compact('withdrawals', 'stats', 'status'));
    }

    public function withdrawalDetail(WithdrawalRequest $withdrawal)
    {
        $withdrawal->load(['instructor.instructorEarning', 'approvedBy']);

        return view('admin.bendahara.withdrawal-detail', compact('withdrawal'));
    }

    /**
     * Approve withdrawal
     */
    public function approveWithdrawal(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->withdrawalService->approveWithdrawal(
                $withdrawal->id,
                auth()->id(),
                $request->notes
            );

            return back()->with('success', 'Penarikan berhasil disetujui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Reject withdrawal
     */
    public function rejectWithdrawal(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $this->withdrawalService->rejectWithdrawal(
                $withdrawal->id,
                auth()->id(),
                $request->rejection_reason
            );

            return back()->with('success', 'Penarikan berhasil ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Complete withdrawal dengan bukti transfer
     */
    public function completeWithdrawal(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate([
            'transfer_proof' => 'required|image|max:2048',
        ]);

        try {
            $this->withdrawalService->completeWithdrawal(
                $withdrawal->id,
                $request->file('transfer_proof')
            );

            return back()->with('success', 'Penarikan berhasil diselesaikan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Reports
     */
    public function reports(Request $request, RevenueService $revenueService, WithdrawalService $withdrawalService)
    {
        $period = $request->get('period', 'month');

        $revenueStats = $revenueService->getRevenueStatistics($period);
        $withdrawalStats = $withdrawalService->getWithdrawalStatistics($period);
        $breakdown = $revenueService->getRevenueBreakdown($period);
        $topInstructors = $revenueService->getTopInstructors(10);

        return view('admin.bendahara.reports', compact('revenueStats', 'withdrawalStats', 'breakdown', 'topInstructors', 'period'));
    }

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
}