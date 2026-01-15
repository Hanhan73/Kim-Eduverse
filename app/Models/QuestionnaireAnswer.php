<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_id',
        'answer_value',
    ];

    protected $casts = [
        'answer_value' => 'integer',
    ];

    /**
     * Get the response that owns the answer
     */
    public function response()
    {
        return $this->belongsTo(QuestionnaireResponse::class, 'response_id');
    }

    /**
     * Get the question that this answer belongs to
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}