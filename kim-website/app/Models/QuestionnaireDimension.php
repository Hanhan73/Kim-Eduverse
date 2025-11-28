<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'name',
        'description',
        'min_score',
        'max_score',
        'order',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function ranges()
    {
        return $this->hasMany(QuestionnaireDimensionRange::class, 'dimension_id');
    }

    public function questions()
    {
        return $this->belongsToMany(QuestionnaireQuestion::class, 'questionnaire_question_dimension', 'dimension_id', 'question_id');
    }
}
