<?php
// app/Models/TrainingCertificateMaterial.php — NEW FILE

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCertificateMaterial extends Model
{
    protected $fillable = ['training_id', 'title', 'order'];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}