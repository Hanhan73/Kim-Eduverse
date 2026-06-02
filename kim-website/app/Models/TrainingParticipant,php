<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainingParticipant extends Model
{
    protected $fillable = [
        'training_id',
        'name',
        'nip',
        'email',
        'phone',
        'institution',
        'access_token',
        'token_sent',
        'token_sent_at',
        'checked_in_at',
        'checked_out_at',
        'seminar_enrollment_id',
        'certificate_number',
        'certificate_path',
        'certificate_issued_at',
    ];

    protected $casts = [
        'token_sent' => 'boolean',
        'token_sent_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->access_token)) {
                $model->access_token = Str::random(40);
            }
        });
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(SeminarEnrollment::class, 'seminar_enrollment_id');
    }

    public function submission()
    {
        return $this->hasOne(TrainingSubmission::class, 'participant_id');
    }

    public function getIsCheckedInAttribute()
    {
        return !is_null($this->checked_in_at);
    }

    public function getIsCheckedOutAttribute()
    {
        return !is_null($this->checked_out_at);
    }

    public function getHasCertificateAttribute()
    {
        return !is_null($this->certificate_path);
    }

    public function generateCertificateNumber()
    {
        $number = 'SRT/TRN/' . date('Y') . '/' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
        $this->update(['certificate_number' => $number]);
        return $number;
    }
}