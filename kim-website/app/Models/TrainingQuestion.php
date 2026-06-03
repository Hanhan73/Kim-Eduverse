<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingQuestion extends Model
{
    protected $fillable = [
        'training_id','question',
        'option_a','option_b','option_c','option_d','option_e',
        'correct_answer','order',
    ];
 
    public function training() { return $this->belongsTo(Training::class); }
 
    public function getOptionsAttribute()
    {
        $opts = [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
        if ($this->option_e) $opts['E'] = $this->option_e;
        return array_filter($opts);
    }
}