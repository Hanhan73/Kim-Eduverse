<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'total_earned',
        'available_balance',
        'withdrawn',
        'pending_withdrawal',
        'total_sales',
        'edutech_sales',
        'kim_digital_sales',
    ];

    protected $casts = [
        'total_earned' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'withdrawn' => 'decimal:2',
        'pending_withdrawal' => 'decimal:2',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function canWithdraw(float $amount): bool
    {
        return $this->available_balance >= $amount && $amount > 0;
    }

    public function getMinimumWithdrawalAttribute(): float
    {
        return 50000; // Minimum withdrawal Rp 50.000
    }

    public function getTotalIncomeAttribute(): float
    {
        return $this->total_earned;
    }

    public function getWithdrawableBalanceAttribute(): float
    {
        return $this->available_balance;
    }

    public function scopeHasBalance($query)
    {
        return $query->where('available_balance', '>', 0);
    }

    public function scopeTopEarners($query, $limit = 10)
    {
        return $query->orderBy('total_earned', 'desc')->limit($limit);
    }
}
