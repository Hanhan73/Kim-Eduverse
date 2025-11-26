<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'code',
        'name',
        'description',
        'interpretations',
        'order',
    ];

    protected $casts = [
        'interpretations' => 'array',
    ];

    /**
     * Get the questionnaire that owns the dimension.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the questions for this dimension.
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'dimension_id')->orderBy('order');
    }

    /**
     * Get interpretation for a score.
     */
    public function getInterpretation($score)
    {
        $interpretations = $this->interpretations;
        
        // Determine category based on score ranges
        if ($score >= 4 && $score <= 9) {
            return $interpretations['low'] ?? null;
        } elseif ($score >= 10 && $score <= 14) {
            return $interpretations['medium'] ?? null;
        } else {
            return $interpretations['high'] ?? null;
        }
    }
}
