<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'order',
        'type',
        'video_url',
        'content',
        'file_path',
        'duration_minutes',
        'is_preview',
        'is_published',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    // Helpers
    public function isCompletedBy($userId)
    {
        return $this->completions()
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->exists();
    }

    public function markAsCompletedBy($userId)
    {
        return $this->completions()->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' menit';
        }
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        return $hours . ' jam ' . ($minutes > 0 ? $minutes . ' menit' : '');
    }
}