<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorBankAccount;
use App\Models\WithdrawalRequest;
use App\Models\RevenueShare;
use App\Services\WithdrawalService;
use App\Services\RevenueService;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    protected $withdrawalService;
    protected $revenueService;

    public function __construct(WithdrawalService $withdrawalService, RevenueService $revenueService)
    {
        // $this->middleware(['auth', 'role:instruktor']);
        $this->withdrawalService = $withdrawalService;
        $this->revenueService = $revenueService;
    }

    /**
     * Dashboard instruktor
     */
    public function dashboard()
    {
        $user = auth()->user();
        $earning = $user->getOrCreateEarning();

        $stats = [
            'available_balance' => $earning->available_balance,
            'total_earned' => $earning->total_earned,
            'withdrawn' => $earning->withdrawn,
            'pending_withdrawal' => $earning->pending_withdrawal,
            'total_sales' => $earning->total_sales,
            'edutech_sales' => $earning->edutech_sales,
            'kim_digital_sales' => $earning->kim_digital_sales,
        ];

        $recentRevenues = RevenueShare::with('course')
            ->where('instructor_id', $user->id)
            ->completed()
            ->latest('paid_at')
            ->limit(10)
            ->get();

        $recentWithdrawals = WithdrawalRequest::where('instructor_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('instructor.dashboard', compact('stats', 'recentRevenues', 'recentWithdrawals'));
    }

    /**
     * Earnings / Pemasukan
     */
    public function earnings(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month');

        $earning = $user->getOrCreateEarning();
        $revenueStats = $this->revenueService->getInstructorRevenue($user->id, $period);

        $query = RevenueShare::with('course')
            ->where('instructor_id', $user->id)
            ->completed();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        $revenues = $query->latest('paid_at')->paginate(20);

        return view('instructor.earnings', compact('earning', 'revenueStats', 'revenues', 'period'));
    }

    /**
     * Withdrawals
     */
    public function withdrawals()
    {
        $user = auth()->user();
        $earning = $user->getOrCreateEarning();

        $withdrawals = $this->withdrawalService->getInstructorWithdrawals($user->id);
        $bankAccounts = $user->bankAccounts;

        return view('instructor.withdrawals', compact('earning', 'withdrawals', 'bankAccounts'));
    }

    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
            'bank_account_id' => 'nullable|exists:instructor_bank_accounts,id',
        ]);

        try {
            $withdrawal = $this->withdrawalService->createWithdrawalRequest(
                auth()->id(),
                $request->amount,
                $request->bank_account_id
            );

            return back()->with('success', 'Request penarikan berhasil dibuat dengan kode: ' . $withdrawal->request_code);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat request penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel withdrawal
     */
    public function cancelWithdrawal(WithdrawalRequest $withdrawal)
    {
        try {
            $this->withdrawalService->cancelWithdrawal($withdrawal->id, auth()->id());

            return back()->with('success', 'Request penarikan berhasil dibatalkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan request: ' . $e->getMessage());
        }
    }

    /**
     * Bank accounts
     */
    public function bankAccounts()
    {
        $bankAccounts = auth()->user()->bankAccounts;

        return view('instructor.bank-accounts', compact('bankAccounts'));
    }

    /**
     * Store bank account
     */
    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'is_primary' => 'boolean',
        ]);

        $bankAccount = InstructorBankAccount::create([
            'instructor_id' => auth()->id(),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        if ($bankAccount->is_primary) {
            $bankAccount->setPrimary();
        }

        return back()->with('success', 'Rekening bank berhasil ditambahkan');
    }

    /**
     * Update bank account
     */
    public function updateBankAccount(Request $request, InstructorBankAccount $bankAccount)
    {
        // Check ownership
        if ($bankAccount->instructor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'is_primary' => 'boolean',
        ]);

        $bankAccount->update($request->only(['bank_name', 'account_number', 'account_holder_name']));

        if ($request->boolean('is_primary')) {
            $bankAccount->setPrimary();
        }

        return back()->with('success', 'Rekening bank berhasil diupdate');
    }

    /**
     * Delete bank account
     */
    public function deleteBankAccount(InstructorBankAccount $bankAccount)
    {
        // Check ownership
        if ($bankAccount->instructor_id !== auth()->id()) {
            abort(403);
        }

        // Tidak bisa delete jika ada withdrawal pending dengan rekening ini
        $hasPendingWithdrawal = WithdrawalRequest::where('instructor_id', auth()->id())
            ->where('account_number', $bankAccount->account_number)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasPendingWithdrawal) {
            return back()->with('error', 'Tidak bisa menghapus rekening yang sedang digunakan untuk penarikan');
        }

        $bankAccount->delete();

        return back()->with('success', 'Rekening bank berhasil dihapus');
    }

    /**
     * My courses
     */
    public function courses()
    {
        $user = auth()->user();

        $edutechCourses = $user->edutechCourses()->withCount('students')->get();
        $kimDigitalCourses = $user->kimDigitalCourses()->withCount('enrollments')->get();

        return view('instructor.courses', compact('edutechCourses', 'kimDigitalCourses'));
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
