<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'instructions',
        'type',
        'duration_minutes',
        'has_dimensions',
        'scoring_rules',
        'is_active',
        'ai_enabled',
        'ai_context',
        'ai_persona',
        'created_by'
    ];

    protected $casts = [
        'has_dimensions' => 'boolean',
        'scoring_rules' => 'array',
        'is_active' => 'boolean',
        'ai_enabled' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($questionnaire) {
            if (empty($questionnaire->slug)) {
                $questionnaire->slug = Str::slug($questionnaire->name);
            }
        });
    }


    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the dimensions for the questionnaire.
     */
    public function dimensions()
    {
        return $this->hasMany(QuestionnaireDimension::class)->orderBy('order');
    }

    /**
     * Get the questions for the questionnaire.
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class)->orderBy('order');
    }

    /**
     * Get the responses for the questionnaire.
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get the products using this questionnaire.
     */
    public function products()
    {
        return $this->hasMany(DigitalProduct::class);
    }

    /**
     * Get AI persona display name.
     */
    public function getAIPersonaDisplayAttribute(): string
    {
        $personas = [
            'psikolog' => 'Psikolog Profesional',
            'konselor' => 'Konselor',
            'coach' => 'Life Coach',
            'mentor' => 'Mentor Akademik',
            'hr' => 'HR Professional',
        ];

        return $personas[$this->ai_persona] ?? 'Psikolog Profesional';
    }

    /**
     * Get AI persona description for prompt.
     */
    public function getAIPersonaPrompt(): string
    {
        $prompts = [
            'psikolog' => 'Anda adalah seorang psikolog profesional yang berpengalaman dalam menganalisis hasil asesmen psikologis. Gunakan pendekatan ilmiah dan berikan interpretasi yang mendalam.',
            'konselor' => 'Anda adalah seorang konselor yang empatis dan suportif. Fokus pada memberikan dukungan emosional dan saran praktis yang dapat diterapkan segera.',
            'coach' => 'Anda adalah seorang life coach yang berorientasi pada solusi dan aksi. Fokus pada pengembangan diri dan pencapaian tujuan.',
            'mentor' => 'Anda adalah seorang mentor akademik yang memahami tantangan mahasiswa. Berikan saran yang relevan dengan konteks pendidikan.',
            'hr' => 'Anda adalah seorang profesional HR yang memahami dinamika kerja. Fokus pada aspek profesionalisme dan pengembangan karir.',
        ];

        return $prompts[$this->ai_persona] ?? $prompts['psikolog'];
    }

    /**
     * Scope for active questionnaires.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for AI-enabled questionnaires.
     */
    public function scopeAIEnabled($query)
    {
        return $query->where('ai_enabled', true);
    }
}