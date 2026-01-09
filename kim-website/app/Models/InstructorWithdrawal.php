<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorWithdrawal extends Model
{
    protected $fillable = [
        'instructor_id',
        'amount',
        'status',
        'bank_name',
        'account_number',
        'account_name',
        'notes',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function revenues()
    {
        return $this->hasMany(InstructorRevenue::class, 'withdrawal_id');
    }
}