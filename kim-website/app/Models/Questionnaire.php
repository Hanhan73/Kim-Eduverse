<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'has_dimensions' => 'boolean',
        'is_active' => 'boolean',
        'scoring_rules' => 'array',
    ];

    /**
     * Get dimensions for this questionnaire
     */
    public function dimensions()
    {
        return $this->hasMany(QuestionnaireDimension::class)->orderBy('order');
    }

    /**
     * Get questions for this questionnaire
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class)->orderBy('order');
    }

    /**
     * Get responses for this questionnaire
     */
    public function responses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Get products using this questionnaire
     */
    public function products()
    {
        return $this->hasMany(DigitalProduct::class);
    }
}
