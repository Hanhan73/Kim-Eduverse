<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'type',
        'description',
        'passing_score',
        'duration_minutes',
        'max_attempts',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePreTest($query)
    {
        return $query->where('type', 'pre_test');
    }

    public function scopePostTest($query)
    {
        return $query->where('type', 'post_test');
    }

    // Helpers
    public function getTotalPointsAttribute()
    {
        return $this->questions->sum('points');
    }

    public function getQuestionsCountAttribute()
    {
        return $this->questions->count();
    }

    public function canUserAttempt($userId)
    {
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    public function getUserBestScore($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->max('score') ?? 0;
    }
}