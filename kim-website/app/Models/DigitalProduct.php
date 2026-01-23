<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigitalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'features',
        'price',
        'thumbnail',
        'type',
        'questionnaire_id',
        'file_path',
        'file_url',
        'duration_minutes',
        'is_active',
        'is_featured',
        'order',
        'sold_count',
        'ebook_access_duration_days', // Tambahan untuk e-book
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Get the collaborator (creator) of the product.
     */
    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(DigitalProductCategory::class, 'category_id');
    }

    /**
     * Get the questionnaire associated with the product.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(DigitalOrderItem::class, 'product_id');
    }

    /**
     * Get collaborator revenues for this product
     */
    public function collaboratorRevenues()
    {
        return $this->hasMany(CollaboratorRevenue::class, 'product_id');
    }

    /**
     * Get the seminar associated with this product (HAS ONE)
     */
    public function seminar()
    {
        return $this->hasOne(\App\Models\Seminar::class, 'product_id');
    }

    /**
     * Relasi ke Landing Page
     */
    public function landingPage()
    {
        return $this->hasOne(\App\Models\ProductLandingPage::class, 'product_id');
    }

    /**
     * Get ebook accesses for this product
     */
    public function ebookAccesses()
    {
        return $this->hasMany(EbookAccess::class, 'product_id');
    }

    /**
     * Increment sold count.
     */
    public function incrementSoldCount()
    {
        $this->increment('sold_count');
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Check if product is a questionnaire.
     */
    public function isQuestionnaire()
    {
        return $this->type === 'questionnaire';
    }

    /**
     * Check if product is a seminar.
     */
    public function isSeminar()
    {
        return $this->type === 'seminar';
    }

    /**
     * Check if product is an ebook.
     */
    public function isEbook()
    {
        return $this->type === 'ebook';
    }

    /**
     * Check apakah produk punya landing page yang aktif
     */
    public function hasLandingPage()
    {
        return $this->landingPage && $this->landingPage->is_active;
    }

    /**
     * Get URL landing page (jika ada)
     */
    public function getLandingPageUrlAttribute()
    {
        if ($this->hasLandingPage()) {
            return route('digital.landing', $this->slug);
        }
        return null;
    }

    /**
     * Get display instructor name
     */
    public function getInstructorNameAttribute()
    {
        if ($this->isSeminar() && $this->seminar) {
            return $this->seminar->instructor_display_name;
        }
        
        return $this->collaborator?->name ?? 'Unknown';
    }

    /**
     * Get formatted ebook access duration
     */
    public function getFormattedAccessDurationAttribute()
    {
        if (!$this->isEbook()) {
            return null;
        }

        $days = $this->ebook_access_duration_days ?? 90;
        
        if ($days >= 365) {
            $years = floor($days / 365);
            return $years . ' tahun';
        } elseif ($days >= 30) {
            $months = floor($days / 30);
            return $months . ' bulan';
        }
        
        return $days . ' hari';
    }
}