<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id', // Pertahankan untuk backward compatibility
        'module_id',
        'title',
        'description',
        'type',
        'passing_score',
        'duration_minutes',
        'max_attempts',
        'randomize_questions',
        'is_active',
        'quizable_type', // Tambahkan
        'quizable_id',   // Tambahkan
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    // Polymorphic relationship
    public function quizable()
    {
        return $this->morphTo();
    }

    // Pertahankan untuk backward compatibility
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
     * Get questions dengan randomize jika diperlukan
     */
    public function getQuestionsForAttempt()
    {
        $query = $this->questions();

        if ($this->randomize_questions && ($this->type === 'pre_test' || $this->type === 'post_test')) {
            return $query->inRandomOrder()->get();
        }

        return $query->orderBy('order')->get();
    }

    public function canUserAttempt($userId)
    {
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    public function getBestAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->whereNotNull('submitted_at')
            ->orderBy('score', 'desc')
            ->first();
    }

    public function getPassedAttempt($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('is_passed', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }
}
