<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'is_primary',
        'is_verified',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function setPrimary(): void
    {
        // Set all other accounts for this instructor to non-primary
        self::where('instructor_id', $this->instructor_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this account as primary
        $this->update(['is_primary' => true]);
    }
}
