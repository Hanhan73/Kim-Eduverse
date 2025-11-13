<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'student_id',
        'course_id',
        'meeting_number',
        'meeting_topic',
        'meeting_date',
        'attendance_date',
        'status',
        'notes',
        'live_session_id',
        'type',
        'check_in_time',
        'check_out_time'
    ];

    protected $casts = [
        'meeting_date' => 'date'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}