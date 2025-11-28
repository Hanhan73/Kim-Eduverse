<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'questionnaire_id',
        'respondent_email',
        'answers',
        'scores',
        'result_summary',
        'result_pdf_path',
        'is_completed',
        'completed_at',
        'result_sent',
        'result_sent_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'scores' => 'array',
        'result_summary' => 'array',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'result_sent' => 'boolean',
        'result_sent_at' => 'datetime',
    ];

    /**
     * Get the questionnaire
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'order_id');
    }
}
