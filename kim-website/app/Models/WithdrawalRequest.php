<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'instructor_id',
        'amount',
        'balance_before',
        'balance_after',
        'bank_name',
        'account_number',
        'account_holder_name',
        'status',
        'approved_by',
        'notes',
        'rejection_reason',
        'approved_at',
        'completed_at',
        'transfer_proof',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function generateRequestCode(): string
    {
        return 'WD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function approve($bendaharaId, $notes = null): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $earning = InstructorEarning::where('instructor_id', $this->instructor_id)->first();

        if (!$earning || $earning->available_balance < $this->amount) {
            return false;
        }

        // Update withdrawal request
        $this->update([
            'status' => 'approved',
            'approved_by' => $bendaharaId,
            'approved_at' => now(),
            'notes' => $notes,
        ]);

        // Update instructor earnings
        $earning->decrement('available_balance', $this->amount);
        $earning->increment('pending_withdrawal', $this->amount);

        return true;
    }

    public function reject($bendaharaId, $reason): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'approved_by' => $bendaharaId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return true;
    }

    public function complete($transferProof = null): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $earning = InstructorEarning::where('instructor_id', $this->instructor_id)->first();

        if (!$earning) {
            return false;
        }

        // Update withdrawal request
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'transfer_proof' => $transferProof,
        ]);

        // Update instructor earnings
        $earning->decrement('pending_withdrawal', $this->amount);
        $earning->increment('withdrawn', $this->amount);

        return true;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-info">Disetujui</span>',
            'completed' => '<span class="badge bg-success">Selesai</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
