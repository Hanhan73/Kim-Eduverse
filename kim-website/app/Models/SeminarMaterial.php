<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarMaterial extends Model
{
    protected $fillable = [
        'seminar_id',
        'title',
        'jp',
        'order',
    ];

    protected $casts = [
        'jp' => 'integer',
        'order' => 'integer',
    ];

    // Relation to Seminar
    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
