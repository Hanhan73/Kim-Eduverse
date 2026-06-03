<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingQuizAttempt extends Model
{
    protected $fillable = [
        'training_id','participant_id','type',
        'question_order','shuffled_options','answers',
        'score','is_passed','is_submitted',
        'started_at','submitted_at',
    ];
 
    protected $casts = [
        'question_order'   => 'array',
        'shuffled_options' => 'array',
        'answers'          => 'array',
        'is_passed'        => 'boolean',
        'is_submitted'     => 'boolean',
        'started_at'       => 'datetime',
        'submitted_at'     => 'datetime',
    ];
 
    public function training()    { return $this->belongsTo(Training::class); }
    public function participant() { return $this->belongsTo(TrainingParticipant::class); }
}