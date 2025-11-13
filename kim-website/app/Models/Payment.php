<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrollment_id',
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'payment_url',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helpers
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isExpired()
    {
        return $this->status === 'expired';
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);

        // Update enrollment payment status
        if ($this->enrollment) {
            $this->enrollment->update([
                'payment_status' => 'paid',
            ]);
        }
    }
    

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);

        // Update enrollment payment status
        if ($this->enrollment) {
            $this->enrollment->update([
                'payment_status' => 'failed',
            ]);
        }
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}