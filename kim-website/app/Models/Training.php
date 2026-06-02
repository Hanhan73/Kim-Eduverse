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
        'seminar_id',
        'is_active',
    ];

    protected $casts = [
        'training_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function participants()
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function submissions()
    {
        return $this->hasMany(TrainingSubmission::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->training_date->translatedFormat('d F Y');
    }
}