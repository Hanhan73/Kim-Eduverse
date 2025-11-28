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
     * Get the questionnaire
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the dimension
     */
    public function dimension()
    {
        return $this->belongsTo(QuestionnaireDimension::class, 'dimension_id');
    }

    /**
     * Calculate score based on answer
     * For Likert scale 1-5:
     * - Normal: score = answer
     * - Reverse: score = 6 - answer
     * 
     * @param int $answer
     * @return int
     */
    public function calculateScore($answer)
    {
        $answer = (int) $answer;
        
        if ($this->is_reverse_scored) {
            return 6 - $answer; // Reverse: 5->1, 4->2, 3->3, 2->4, 1->5
        }
        
        return $answer;
    }

    /**
     * Get default Likert scale options
     * 
     * @return array
     */
    public static function getDefaultOptions()
    {
        return [
            1 => 'Sangat Tidak Setuju',
            2 => 'Tidak Setuju',
            3 => 'Netral',
            4 => 'Setuju',
            5 => 'Sangat Setuju',
        ];
    }
}
