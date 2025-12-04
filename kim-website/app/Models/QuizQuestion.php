<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'question_text', // alias untuk backward compatibility
        'type',
        'options',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'correct_answer',
        'points',
        'order',
        'explanation',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Accessors untuk backward compatibility
    public function getQuestionTextAttribute()
    {
        return $this->question;
    }

    public function setQuestionTextAttribute($value)
    {
        $this->attributes['question'] = $value;
    }

    // Accessors untuk options (jika disimpan sebagai array JSON)
    public function getOptionAAttribute()
    {
        return $this->options['A'] ?? null;
    }

    public function getOptionBAttribute()
    {
        return $this->options['B'] ?? null;
    }

    public function getOptionCAttribute()
    {
        return $this->options['C'] ?? null;
    }

    public function getOptionDAttribute()
    {
        return $this->options['D'] ?? null;
    }

    public function getOptionEAttribute()
    {
        return $this->options['E'] ?? null;
    }

    // Helpers
    public function checkAnswer($answer)
    {
        return strtoupper(trim($answer)) === strtoupper(trim($this->correct_answer));
    }

    /**
     * Get all options as array
     */
    public function getOptionsArray()
    {
        if ($this->options && is_array($this->options)) {
            return array_filter($this->options);
        }

        // Fallback ke kolom individual jika ada
        return array_filter([
            'A' => $this->attributes['option_a'] ?? null,
            'B' => $this->attributes['option_b'] ?? null,
            'C' => $this->attributes['option_c'] ?? null,
            'D' => $this->attributes['option_d'] ?? null,
            'E' => $this->attributes['option_e'] ?? null,
        ]);
    }

    /**
     * Check if option exists
     */
    public function hasOption($letter)
    {
        $options = $this->getOptionsArray();
        return isset($options[$letter]) && !empty($options[$letter]);
    }
}