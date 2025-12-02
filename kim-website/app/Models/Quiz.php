<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'module_id', // NEW: untuk quiz di module
        'title',
        'description',
        'type', // pre_test, post_test, module_quiz
        'passing_score',
        'duration_minutes',
        'max_attempts',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Cek apakah user bisa attempt quiz ini
     */
    public function canUserAttempt($userId)
    {
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();
            
        return $attemptCount < $this->max_attempts;
    }

    /**
     * Get best attempt score untuk user
     */
    public function getBestAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->whereNotNull('submitted_at')
            ->orderBy('score', 'desc')
            ->first();
    }

    /**
     * Get passed attempt untuk user
     */
    public function getPassedAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('is_passed', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get total points dari semua questions
     */
    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }
}