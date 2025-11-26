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
    ];

    protected $casts = [
        'has_dimensions' => 'boolean',
        'scoring_rules' => 'array',
        'is_active' => 'boolean',
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
}
