<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'meeting_url',
        'scheduled_at',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
