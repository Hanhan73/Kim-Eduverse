<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'image',
        'author',
        'is_published',
        'views'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    // Auto generate slug from title
    public static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Scope for published articles
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope for latest articles
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    // Get reading time (estimate)
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed
        return $minutes . ' menit';
    }

    // Increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}