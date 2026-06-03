<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'training_date',
        'start_time',
        'end_time',
        'trainer_name',
        'organizer',
        'thumbnail',
        'is_active',
        // hapus seminar_id — tidak dipakai lagi
    ];

    protected $casts = [
        'training_date' => 'date',
        'is_active'     => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(TrainingParticipant::class);
    }
    public function materials()
    {
        return $this->hasMany(TrainingMaterial::class)->orderBy('order');
    }
    public function questions()
    {
        return $this->hasMany(TrainingQuestion::class)->orderBy('order');
    }
    public function submissions()
    {
        return $this->hasMany(TrainingSubmission::class);
    }
    public function quizAttempts()
    {
        return $this->hasMany(TrainingQuizAttempt::class);
    }

    public function getTotalJpAttribute()
    {
        return $this->materials->sum('jp');
    }
}
