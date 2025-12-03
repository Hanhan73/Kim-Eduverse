<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Seminar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'instructor_name',
        'instructor_bio',
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



    public function enrollments()
    {
        return $this->hasMany(SeminarEnrollment::class);
    }

    public function digitalProduct()
    {
        return $this->hasOne(DigitalProduct::class, 'seminar_id');
    }

    // Helpers
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function incrementSoldCount()
    {
        $this->increment('sold_count');
    }

    public function preTest()
    {
        return $this->morphOne(Quiz::class, 'quizable')->where('type', 'pre_test');
    }

    public function postTest()
    {
        return $this->morphOne(Quiz::class, 'quizable')->where('type', 'post_test');
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'quizable');
    }
}
