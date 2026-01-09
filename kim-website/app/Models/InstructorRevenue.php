<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorRevenue extends Model
{
    protected $fillable = [
        'instructor_id',
        'payment_id', // ganti dari order_id
        'course_id',
        'course_price',
        'instructor_share',
        'platform_share',
        'status',
        'withdrawal_id'
    ];

    protected $casts = [
        'course_price' => 'decimal:2',
        'instructor_share' => 'decimal:2',
        'platform_share' => 'decimal:2'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function payment() // ganti dari order
    {
        return $this->belongsTo(Payment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function withdrawal()
    {
        return $this->belongsTo(InstructorWithdrawal::class, 'withdrawal_id');
    }
}