<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Seminar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'collaborator_id',
        'product_id', // BELONGS TO DigitalProduct
        'title',
        'slug',
        'description',
        'thumbnail',
        'instructor_name',  // NULLABLE - Optional override
        'instructor_bio',   // NULLABLE - Optional override
        'material_pdf_path',
        'material_description',
        'pre_test_id',
        'post_test_id',
        'certificate_template',
        'price',
        'duration_minutes',
        'is_active',
        'is_featured',
        'sold_count',
        'order',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($seminar) {
            if (empty($seminar->slug)) {
                $seminar->slug = Str::slug($seminar->title);
            }
        });
    }

    // RELASI KE COLLABORATOR (INSTRUCTOR)
    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    // BELONGS TO DigitalProduct (bukan hasOne)
    public function digitalProduct()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    // Alias untuk backward compatibility
    public function product()
    {
        return $this->digitalProduct();
    }

    public function creator() 
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function enrollments()
    {
        return $this->hasMany(SeminarEnrollment::class);
    }

    public function preTest()
    {
        return $this->belongsTo(Quiz::class, 'pre_test_id');
    }

    public function postTest()
    {
        return $this->belongsTo(Quiz::class, 'post_test_id');
    }

    // HELPERS
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function incrementSoldCount()
    {
        $this->increment('sold_count');
    }

    /**
     * Get instructor name - prioritize override, fallback to collaborator
     */
    public function getInstructorDisplayNameAttribute()
    {
        // If there's an override, use it
        if (!empty($this->instructor_name)) {
            return $this->instructor_name;
        }
        
        // Otherwise use collaborator name
        return $this->collaborator?->name ?? 'Unknown Instructor';
    }

    /**
     * Get instructor bio - prioritize override, fallback to collaborator
     */
    public function getInstructorDisplayBioAttribute()
    {
        // If there's an override, use it
        if (!empty($this->instructor_bio)) {
            return $this->instructor_bio;
        }
        
        // Otherwise use collaborator bio
        return $this->collaborator?->bio ?? '';
    }
}