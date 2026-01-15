<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseBatch extends Model
{
    protected $fillable = [
        'course_id',
        'batch_name',
        'batch_code',
        'schedule_type',
        'schedule_days',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'max_students',
        'status',
        'notes',
    ];

    protected $casts = [
        'schedule_days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'batch_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'batch_id', 'student_id')
            ->withPivot('status', 'progress_percentage', 'enrolled_at')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'batch_id');
    }

    public function liveSessions()
    {
        return $this->hasMany(LiveSession::class, 'batch_id');
    }

    // Helper methods
    public function getCurrentStudentsCount()
    {
        return $this->enrollments()->count();
    }

    public function getAvailableSeats()
    {
        return $this->max_students - $this->getCurrentStudentsCount();
    }

    public function isFull()
    {
        return $this->getCurrentStudentsCount() >= $this->max_students;
    }

    public function getScheduleDaysFormatted()
    {
        if (!$this->schedule_days) return '-';
        
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        return collect($this->schedule_days)
            ->map(fn($day) => $days[$day] ?? $day)
            ->join(', ');
    }
}