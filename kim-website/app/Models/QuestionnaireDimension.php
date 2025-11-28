<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'code',
        'name',
        'description',
        'interpretations', // Legacy - akan di-migrate ke score_ranges
        'order',
    ];

    protected $casts = [
        'interpretations' => 'array',
    ];

    /**
     * Get the questionnaire that owns the dimension.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the questions for this dimension.
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'dimension_id')->orderBy('order');
    }

    /**
     * Get score ranges for this dimension.
     */
    public function scoreRanges()
    {
        return $this->hasMany(QuestionnaireScoreRange::class, 'dimension_id')->orderBy('order');
    }

    /**
     * Get interpretation for a score.
     * Prioritas: score_ranges table > interpretations JSON > default
     */
    public function getInterpretation($score)
    {
        // 1. Cek di score_ranges table (sistem baru)
        $range = $this->scoreRanges()
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();

        if ($range) {
            return $range->toInterpretation();
        }

        // 2. Fallback ke interpretations JSON (legacy)
        $interpretations = $this->interpretations;
        if ($interpretations) {
            // Hitung range berdasarkan jumlah pertanyaan
            $questionCount = $this->questions()->count();
            $minPossible = $questionCount; // Minimum: semua jawab 1
            $maxPossible = $questionCount * 5; // Maximum: semua jawab 5
            $range = $maxPossible - $minPossible;
            
            // Bagi ke 3 kategori
            $lowMax = $minPossible + ($range / 3);
            $mediumMax = $minPossible + (2 * $range / 3);

            if ($score <= $lowMax) {
                return $interpretations['low'] ?? null;
            } elseif ($score <= $mediumMax) {
                return $interpretations['medium'] ?? null;
            } else {
                return $interpretations['high'] ?? null;
            }
        }

        // 3. Default interpretation
        return $this->getDefaultInterpretation($score);
    }

    /**
     * Get default interpretation based on general scoring.
     */
    protected function getDefaultInterpretation($score)
    {
        $questionCount = $this->questions()->count();
        $maxPossible = $questionCount * 5;
        $percentage = ($score / $maxPossible) * 100;

        if ($percentage <= 33) {
            return [
                'level' => 'RENDAH',
                'class' => 'level-rendah',
                'description' => 'Tingkat pada aspek ini tergolong rendah.',
                'suggestions' => [],
            ];
        } elseif ($percentage <= 66) {
            return [
                'level' => 'SEDANG',
                'class' => 'level-sedang',
                'description' => 'Tingkat pada aspek ini tergolong sedang.',
                'suggestions' => [],
            ];
        } else {
            return [
                'level' => 'TINGGI',
                'class' => 'level-tinggi',
                'description' => 'Tingkat pada aspek ini tergolong tinggi.',
                'suggestions' => [],
            ];
        }
    }

    /**
     * Check if dimension has score ranges defined.
     */
    public function hasScoreRanges()
    {
        return $this->scoreRanges()->count() > 0;
    }

    /**
     * Calculate min and max possible scores.
     */
    public function getScoreBounds()
    {
        $questionCount = $this->questions()->count();
        return [
            'min' => $questionCount, // All answers = 1
            'max' => $questionCount * 5, // All answers = 5
            'questions' => $questionCount,
        ];
    }

    /**
     * Generate default score ranges based on question count.
     * Bisa dipanggil untuk auto-generate ranges.
     */
    public function generateDefaultRanges()
    {
        $bounds = $this->getScoreBounds();
        $min = $bounds['min'];
        $max = $bounds['max'];
        $range = $max - $min;

        // Hapus ranges yang ada
        $this->scoreRanges()->delete();

        // Generate 3 default ranges
        $ranges = [
            [
                'category' => 'rendah',
                'level' => 'RENDAH',
                'css_class' => 'level-rendah',
                'min_score' => $min,
                'max_score' => $min + floor($range / 3),
                'description' => 'Tingkat ' . $this->name . ' Anda tergolong rendah.',
                'suggestions' => [],
                'order' => 1,
            ],
            [
                'category' => 'sedang',
                'level' => 'SEDANG',
                'css_class' => 'level-sedang',
                'min_score' => $min + floor($range / 3) + 1,
                'max_score' => $min + floor(2 * $range / 3),
                'description' => 'Tingkat ' . $this->name . ' Anda tergolong sedang.',
                'suggestions' => [],
                'order' => 2,
            ],
            [
                'category' => 'tinggi',
                'level' => 'TINGGI',
                'css_class' => 'level-tinggi',
                'min_score' => $min + floor(2 * $range / 3) + 1,
                'max_score' => $max,
                'description' => 'Tingkat ' . $this->name . ' Anda tergolong tinggi.',
                'suggestions' => [],
                'order' => 3,
            ],
        ];

        foreach ($ranges as $rangeData) {
            $this->scoreRanges()->create($rangeData);
        }

        return $this->scoreRanges;
    }

    /**
     * Migrate from legacy interpretations JSON to score_ranges table.
     */
    public function migrateInterpretationsToRanges()
    {
        $interpretations = $this->interpretations;
        if (!$interpretations) {
            return $this->generateDefaultRanges();
        }

        $bounds = $this->getScoreBounds();
        $min = $bounds['min'];
        $max = $bounds['max'];
        $range = $max - $min;

        // Hapus ranges yang ada
        $this->scoreRanges()->delete();

        $order = 1;
        foreach (['low' => 'rendah', 'medium' => 'sedang', 'high' => 'tinggi'] as $key => $category) {
            if (isset($interpretations[$key])) {
                $interp = $interpretations[$key];
                
                // Calculate score range
                if ($key === 'low') {
                    $minScore = $min;
                    $maxScore = $min + floor($range / 3);
                } elseif ($key === 'medium') {
                    $minScore = $min + floor($range / 3) + 1;
                    $maxScore = $min + floor(2 * $range / 3);
                } else {
                    $minScore = $min + floor(2 * $range / 3) + 1;
                    $maxScore = $max;
                }

                $this->scoreRanges()->create([
                    'category' => $category,
                    'level' => $interp['level'] ?? strtoupper($category),
                    'css_class' => $interp['class'] ?? 'level-' . $category,
                    'min_score' => $minScore,
                    'max_score' => $maxScore,
                    'description' => $interp['description'] ?? '',
                    'suggestions' => $interp['suggestions'] ?? [],
                    'order' => $order++,
                ]);
            }
        }

        return $this->scoreRanges;
    }
}
