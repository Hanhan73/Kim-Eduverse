<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSubmission extends Model
{
    protected $fillable = [
        'training_id',
        'participant_id',
        'drive_link',
        'notes',
        'status',
        'feedback',
        'reviewed_at',
        'submitted_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function participant()
    {
        return $this->belongsTo(TrainingParticipant::class, 'participant_id');
    }
}