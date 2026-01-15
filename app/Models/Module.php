<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'duration_minutes',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * RELASI BARU: Quiz untuk module ini
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class)->where('type', 'module_quiz');
    }
}