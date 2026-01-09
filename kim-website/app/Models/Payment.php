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

    public function instructorRevenue()
    {
        return $this->hasOne(InstructorRevenue::class);
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
                'status' => 'active',
            ]);
        }

        // Create Instructor Revenue (70/30 split) - CRITICAL!
        $this->createInstructorRevenue();
    }

    /**
     * Create instructor revenue after successful payment
     */
    private function createInstructorRevenue()
    {
        // Skip jika sudah ada revenue untuk payment ini
        if ($this->instructorRevenue()->exists()) {
            \Log::info('InstructorRevenue already exists for payment: ' . $this->id);
            return;
        }

        // Skip jika course gratis (price = 0)
        if ($this->amount <= 0) {
            \Log::info('Skipping revenue creation for free course, payment: ' . $this->id);
            return;
        }

        // Get course
        $course = $this->course;
        if (!$course) {
            \Log::error('Course not found for payment: ' . $this->id);
            return;
        }

        // Calculate revenue split (70% instructor, 30% platform)
        $instructorShare = $this->amount * 0.70;
        $platformShare = $this->amount * 0.30;

        // Create revenue record
        $revenue = InstructorRevenue::create([
            'instructor_id' => $course->instructor_id,
            'payment_id' => $this->id,
            'course_id' => $course->id,
            'course_price' => $this->amount,
            'instructor_share' => $instructorShare,
            'platform_share' => $platformShare,
            'status' => 'available',
        ]);

        \Log::info('InstructorRevenue created', [
            'payment_id' => $this->id,
            'revenue_id' => $revenue->id,
            'instructor_id' => $course->instructor_id,
            'amount' => $this->amount,
            'instructor_share' => $instructorShare,
            'platform_share' => $platformShare,
        ]);
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