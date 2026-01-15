<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'order_id',
        'product_id',
        'withdrawal_id',
        'product_price',
        'collaborator_share',
        'platform_share',
        'status',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'collaborator_share' => 'decimal:2',
        'platform_share' => 'decimal:2',
    ];

    // Relationships
    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    public function withdrawal()
    {
        return $this->belongsTo(CollaboratorWithdrawal::class, 'withdrawal_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeWithdrawn($query)
    {
        return $query->where('status', 'withdrawn');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForCollaborator($query, $collaboratorId)
    {
        return $query->where('collaborator_id', $collaboratorId);
    }

    // Helpers
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isWithdrawn()
    {
        return $this->status === 'withdrawn';
    }

    public function markAsWithdrawn($withdrawalId)
    {
        $this->update([
            'status' => 'withdrawn',
            'withdrawal_id' => $withdrawalId,
        ]);
    }
}