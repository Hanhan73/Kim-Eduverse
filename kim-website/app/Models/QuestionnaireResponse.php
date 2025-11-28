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
     * Get the order that owns the response.
     */
    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'order_id');
    }

    /**
     * Get the questionnaire that owns the response.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Mark response as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark result as sent.
     */
    public function markResultAsSent()
    {
        $this->update([
            'result_sent' => true,
            'result_sent_at' => now(),
        ]);
    }
}
