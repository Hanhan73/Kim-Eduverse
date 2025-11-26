<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'dimension_id',
        'question_text',
        'order',
        'is_reverse_scored',
        'options',
    ];

    protected $casts = [
        'is_reverse_scored' => 'boolean',
        'options' => 'array',
    ];

    /**
     * Get the questionnaire that owns the question.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the dimension that owns the question.
     */
    public function dimension()
    {
        return $this->belongsTo(QuestionnaireDimension::class, 'dimension_id');
    }

    /**
     * Calculate score for an answer.
     */
    public function calculateScore($answer)
    {
        if ($this->is_reverse_scored) {
            return 6 - (int)$answer;
        }
        return (int)$answer;
    }
}
