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
        'respondent_name',
        'respondent_email',
        'answers',
        'scores',
        'result_summary',
        'ai_analysis',
        'chart_data',
        'ai_generated_at',
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
        'ai_analysis' => 'array',
        'chart_data' => 'array',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'ai_generated_at' => 'datetime',
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
     * Get the questionnaire that this response belongs to.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
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

    /**
     * Check if AI analysis is available.
     */
    public function hasAIAnalysis(): bool
    {
        return !empty($this->ai_analysis) && ($this->ai_analysis['ai_generated'] ?? false);
    }

    /**
     * Get overall summary from AI analysis.
     */
    public function getOverallSummary(): ?string
    {
        return $this->ai_analysis['overall_summary'] ?? null;
    }

    /**
     * Get action plan from AI analysis.
     */
    public function getActionPlan(): array
    {
        return $this->ai_analysis['action_plan'] ?? [];
    }

    /**
     * Get motivational message from AI analysis.
     */
    public function getMotivationalMessage(): ?string
    {
        return $this->ai_analysis['motivational_message'] ?? null;
    }

    /**
     * Get dimension result with AI enhancement.
     */
    public function getDimensionResult(string $code): ?array
    {
        $aiDimensions = $this->ai_analysis['dimension_results'] ?? [];
        
        if (isset($aiDimensions[$code])) {
            return $aiDimensions[$code];
        }
        
        return $this->result_summary[$code] ?? null;
    }

    /**
     * Get chart type recommendation.
     */
    public function getRecommendedChartType(): string
    {
        return $this->chart_data['type'] ?? 'bar';
    }

    /**
     * Scope for completed responses.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for responses with AI analysis.
     */
    public function scopeWithAI($query)
    {
        return $query->whereNotNull('ai_analysis');
    }
}
