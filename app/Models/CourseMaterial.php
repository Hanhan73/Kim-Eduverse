<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'order',
        'type',
        'file_path',
        'duration_minutes',
        'description',
        'is_preview',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'material_id');
    }

    public function getFileUrlAttribute()
    {
        return asset($this->file_path);
    }
}