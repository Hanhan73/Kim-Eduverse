<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_email',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_response',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'DIG-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(DigitalOrderItem::class, 'order_id');
    }

    /**
     * Get the questionnaire responses for the order.
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class, 'order_id');
    }

    public function seminarEnrollments()
    {
        return $this->hasMany(SeminarEnrollment::class, 'order_id');
    }

    /**
     * Mark order as paid.
     */
    public function markAsPaid($paymentMethod = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
            'status' => 'processing',
        ]);

        // Increment sold count for each product
        foreach ($this->items as $item) {
            $item->product->incrementSoldCount();
        }
    }

    /**
     * Check if order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get formatted total.
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}