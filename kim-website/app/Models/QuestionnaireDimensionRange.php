<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireDimensionRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'min_score',
        'max_score',
        'category',
        'interpretation',
        'recommendations',
    ];

    public function dimension()
    {
        return $this->belongsTo(QuestionnaireDimension::class);
    }
}
