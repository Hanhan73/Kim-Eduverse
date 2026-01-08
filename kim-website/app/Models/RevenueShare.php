<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'instructor_id',
        'course_type',
        'course_id',
        'total_amount',
        'instructor_share',
        'company_share',
        'instructor_percentage',
        'company_percentage',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'instructor_share' => 'decimal:2',
        'company_share' => 'decimal:2',
        'instructor_percentage' => 'decimal:2',
        'company_percentage' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course()
    {
        if ($this->course_type === 'edutech') {
            return $this->belongsTo(Course::class, 'course_id');
        }
        return $this->belongsTo(Course::class, 'course_id');
    }

    public static function calculateShares(float $totalAmount, float $instructorPercentage = 70.00)
    {
        $instructorShare = $totalAmount * ($instructorPercentage / 100);
        $companyShare = $totalAmount - $instructorShare;

        return [
            'instructor_share' => round($instructorShare, 2),
            'company_share' => round($companyShare, 2),
            'instructor_percentage' => $instructorPercentage,
            'company_percentage' => 100 - $instructorPercentage,
        ];
    }

    public static function generateTransactionCode(): string
    {
        return 'REV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update instructor earnings
        $earning = InstructorEarning::firstOrCreate(
            ['instructor_id' => $this->instructor_id],
            [
                'total_earned' => 0,
                'available_balance' => 0,
                'withdrawn' => 0,
                'pending_withdrawal' => 0,
                'total_sales' => 0,
                'edutech_sales' => 0,
                'kim_digital_sales' => 0,
            ]
        );

        $earning->increment('total_earned', $this->instructor_share);
        $earning->increment('available_balance', $this->instructor_share);
        $earning->increment('total_sales');

        if ($this->course_type === 'edutech') {
            $earning->increment('edutech_sales');
        } else {
            $earning->increment('kim_digital_sales');
        }
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }
}
