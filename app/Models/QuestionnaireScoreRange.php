<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireScoreRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'min_score',
        'max_score',
        'category',
        'level',
        'css_class',
        'description',
        'suggestions',
        'order',
    ];

    protected $casts = [
        'suggestions' => 'array',
        'min_score' => 'integer',
        'max_score' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the dimension that owns this score range.
     */
    public function dimension()
    {
        return $this->belongsTo(QuestionnaireDimension::class, 'dimension_id');
    }

    /**
     * Check if a score falls within this range.
     */
    public function containsScore($score)
    {
        return $score >= $this->min_score && $score <= $this->max_score;
    }

    /**
     * Get interpretation data as array.
     */
    public function toInterpretation()
    {
        return [
            'level' => $this->level,
            'class' => $this->css_class,
            'description' => $this->description,
            'suggestions' => $this->suggestions ?? [],
            'category' => $this->category,
            'min_score' => $this->min_score,
            'max_score' => $this->max_score,
        ];
    }

    /**
     * Get available CSS classes.
     */
    public static function getCssClasses()
    {
        return [
            'level-sangat-rendah' => 'Sangat Rendah (Hijau Tua)',
            'level-rendah' => 'Rendah (Hijau)',
            'level-sedang' => 'Sedang (Kuning/Orange)',
            'level-tinggi' => 'Tinggi (Merah)',
            'level-sangat-tinggi' => 'Sangat Tinggi (Merah Tua)',
        ];
    }

    /**
     * Get available categories.
     */
    public static function getCategories()
    {
        return [
            'sangat_rendah' => 'Sangat Rendah',
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            'sangat_tinggi' => 'Sangat Tinggi',
        ];
    }
}
