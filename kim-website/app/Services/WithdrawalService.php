<?php

namespace App\Services;

use App\Models\WithdrawalRequest;
use App\Models\InstructorEarning;
use App\Models\InstructorBankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalService
{
    /**
     * Create withdrawal request
     */
    public function createWithdrawalRequest(
        int $instructorId,
        float $amount,
        ?int $bankAccountId = null
    ): WithdrawalRequest {
        $earning = InstructorEarning::where('instructor_id', $instructorId)->first();

        if (!$earning) {
            throw new \Exception('Earning record tidak ditemukan');
        }

        if (!$earning->canWithdraw($amount)) {
            throw new \Exception('Saldo tidak mencukupi untuk penarikan');
        }

        if ($amount < $earning->minimum_withdrawal) {
            throw new \Exception('Jumlah penarikan minimum adalah Rp ' . number_format($earning->minimum_withdrawal, 0, ',', '.'));
        }

        // Get bank account
        if ($bankAccountId) {
            $bankAccount = InstructorBankAccount::where('id', $bankAccountId)
                ->where('instructor_id', $instructorId)
                ->first();
        } else {
            $bankAccount = InstructorBankAccount::where('instructor_id', $instructorId)
                ->where('is_primary', true)
                ->first();
        }

        if (!$bankAccount) {
            throw new \Exception('Rekening bank tidak ditemukan. Silakan tambahkan rekening terlebih dahulu.');
        }

        return WithdrawalRequest::create([
            'request_code' => WithdrawalRequest::generateRequestCode(),
            'instructor_id' => $instructorId,
            'amount' => $amount,
            'balance_before' => $earning->available_balance,
            'balance_after' => $earning->available_balance - $amount,
            'bank_name' => $bankAccount->bank_name,
            'account_number' => $bankAccount->account_number,
            'account_holder_name' => $bankAccount->account_holder_name,
            'status' => 'pending',
        ]);
    }

    /**
     * Approve withdrawal request
     */
    public function approveWithdrawal(int $requestId, int $bendaharaId, ?string $notes = null): bool
    {
        try {
            DB::beginTransaction();

            $withdrawal = WithdrawalRequest::findOrFail($requestId);

            if (!$withdrawal->approve($bendaharaId, $notes)) {
                throw new \Exception('Gagal menyetujui penarikan');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject withdrawal request
     */
    public function rejectWithdrawal(int $requestId, int $bendaharaId, string $reason): bool
    {
        try {
            DB::beginTransaction();

            $withdrawal = WithdrawalRequest::findOrFail($requestId);

            if (!$withdrawal->reject($bendaharaId, $reason)) {
                throw new \Exception('Gagal menolak penarikan');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Complete withdrawal dengan bukti transfer
     */
    public function completeWithdrawal(int $requestId, $transferProof = null): bool
    {
        try {
            DB::beginTransaction();

            $withdrawal = WithdrawalRequest::findOrFail($requestId);

            $proofPath = null;
            if ($transferProof) {
                $proofPath = $transferProof->store('transfer_proofs', 'public');
            }

            if (!$withdrawal->complete($proofPath)) {
                throw new \Exception('Gagal menyelesaikan penarikan');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get pending withdrawals untuk bendahara
     */
    public function getPendingWithdrawals()
    {
        return WithdrawalRequest::with(['instructor', 'instructor.instructorEarning'])
            ->pending()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get withdrawal statistics
     */
    public function getWithdrawalStatistics(string $period = 'all')
    {
        $query = WithdrawalRequest::query();

        if ($period !== 'all') {
            $query = $this->applyPeriodFilter($query, $period);
        }

        return [
            'pending_count' => (clone $query)->where('status', 'pending')->count(),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('amount'),
            'approved_count' => (clone $query)->where('status', 'approved')->count(),
            'approved_amount' => (clone $query)->where('status', 'approved')->sum('amount'),
            'completed_count' => (clone $query)->where('status', 'completed')->count(),
            'completed_amount' => (clone $query)->where('status', 'completed')->sum('amount'),
            'rejected_count' => (clone $query)->where('status', 'rejected')->count(),
            'rejected_amount' => (clone $query)->where('status', 'rejected')->sum('amount'),
        ];
    }

    /**
     * Get instructor withdrawal history
     */
    public function getInstructorWithdrawals(int $instructorId, string $status = 'all')
    {
        $query = WithdrawalRequest::byInstructor($instructorId);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Apply period filter
     */
    private function applyPeriodFilter($query, string $period)
    {
        $now = now();

        return match ($period) {
            'today' => $query->whereDate('created_at', $now->toDateString()),
            'week' => $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]),
            'month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
            'year' => $query->whereYear('created_at', $now->year),
            default => $query,
        };
    }

    /**
     * Cancel withdrawal request (hanya bisa dilakukan oleh instruktor jika masih pending)
     */
    public function cancelWithdrawal(int $requestId, int $instructorId): bool
    {
        try {
            DB::beginTransaction();

            $withdrawal = WithdrawalRequest::where('id', $requestId)
                ->where('instructor_id', $instructorId)
                ->where('status', 'pending')
                ->firstOrFail();

            $withdrawal->update(['status' => 'cancelled']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
