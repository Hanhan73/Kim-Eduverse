<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'material_id',
        'is_completed',
        'time_spent_seconds',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }
}