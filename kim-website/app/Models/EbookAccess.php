<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EbookAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_email',
        'order_id',
        'access_token',
        'view_count',
        'expires_at',
        'last_accessed_at',
        'last_ip',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($access) {
            if (empty($access->access_token)) {
                $access->access_token = self::generateUniqueToken();
            }
            if (is_null($access->expires_at)) {
            $days = $access->product->ebook_access_duration_days ?? 90;
            $access->expires_at = now()->addDays($days);
        }
        });
    }

    /**
     * Generate unique access token
     */
    public static function generateUniqueToken()
    {
        do {
            $token = Str::random(64);
        } while (self::where('access_token', $token)->exists());

        return $token;
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }


    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'order_id');
    }

    /**
     * Check if access is still valid
     */
    public function isValid()
    {
        return $this->is_active 
            && $this->expires_at->isFuture();
    }

    /**
     * Check if access is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Record access
     */
    public function recordAccess($ip = null)
    {
        $this->increment('view_count');
        $this->update([
            'last_accessed_at' => now(),
            'last_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Revoke access
     */
    public function revoke()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Extend expiration
     */
    public function extend($days)
    {
        $this->update([
            'expires_at' => $this->expires_at->addDays($days)
        ]);
    }

    /**
     * Get access URL
     */
    public function getAccessUrlAttribute()
    {
        return route('ebook.view', $this->access_token);
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return 0;
        }

        return max(
            0,
            now()->startOfDay()->diffInDays(
                $this->expires_at->startOfDay(),
                false
            )
        );
    }
}