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
     * Get the questionnaire
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get questions in this dimension
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'dimension_id')->orderBy('order');
    }

    /**
     * Get interpretation based on score
     * 
     * @param int $score
     * @return array|null
     */
    public function getInterpretation($score)
    {
        $interpretations = $this->interpretations;
        
        if (!$interpretations) {
            return null;
        }

        // Default scoring ranges (can be customized per dimension)
        // Assuming 4 questions per dimension with Likert 1-5
        // Min: 4, Max: 20
        // Low: 4-9, Medium: 10-14, High: 15-20
        
        if ($score <= 9) {
            return $interpretations['low'] ?? null;
        } elseif ($score <= 14) {
            return $interpretations['medium'] ?? null;
        } else {
            return $interpretations['high'] ?? null;
        }
    }
}
