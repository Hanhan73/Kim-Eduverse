<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_number',
        'answers',
        'score',
        'is_passed',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Helpers
    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->submitted_at) {
            return 0;
        }

        return $this->started_at->diffInMinutes($this->submitted_at);
    }

    public function getFormattedScoreAttribute()
    {
        return number_format($this->score, 2);
    }

    public function isPassed()
    {
        return $this->score >= $this->quiz->passing_score;
    }
}