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

    public function collaboratorRevenues()
    {
        return $this->hasMany(CollaboratorRevenue::class, 'order_id');
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

        // Create Collaborator Revenue (70/30 split) - NEW!
        $this->createCollaboratorRevenues();
    }

    /**
     * Create collaborator revenues after successful payment
     */
    private function createCollaboratorRevenues()
    {
        // Skip jika sudah ada revenue untuk order ini
        if ($this->collaboratorRevenues()->exists()) {
            \Log::info('CollaboratorRevenue already exists for order: ' . $this->id);
            return;
        }

        foreach ($this->items as $item) {
            // Skip jika product gratis
            if ($item->price <= 0) {
                continue;
            }

            $product = $item->product;
            if (!$product) {
                \Log::error('Product not found for order item: ' . $item->id);
                continue;
            }

            // Skip jika product tidak punya collaborator
            if (!$product->collaborator_id) {
                \Log::info('Product has no collaborator, skipping revenue: ' . $product->id);
                continue;
            }

            // Calculate revenue split (70% collaborator, 30% platform)
            $collaboratorShare = $item->subtotal * 0.70;
            $platformShare = $item->subtotal * 0.30;

            // Create revenue record
            $revenue = CollaboratorRevenue::create([
                'collaborator_id' => $product->collaborator_id,
                'order_id' => $this->id,
                'product_id' => $product->id,
                'product_price' => $item->subtotal,
                'collaborator_share' => $collaboratorShare,
                'platform_share' => $platformShare,
                'status' => 'available',
            ]);

            \Log::info('CollaboratorRevenue created', [
                'order_id' => $this->id,
                'revenue_id' => $revenue->id,
                'collaborator_id' => $product->collaborator_id,
                'product_price' => $item->subtotal,
                'collaborator_share' => $collaboratorShare,
                'platform_share' => $platformShare,
            ]);
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