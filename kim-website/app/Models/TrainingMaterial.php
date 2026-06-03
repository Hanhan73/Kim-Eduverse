<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingMaterial extends Model
{
    protected $fillable = ['training_id','title','type','url','jp','order'];
 
    public function training() { return $this->belongsTo(Training::class); }
 
    // Ambil embed URL untuk iframe (YouTube & GDrive)
    public function getEmbedUrlAttribute()
    {
        if ($this->type === 'youtube') {
            preg_match('/(?:v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $this->url, $m);
            return isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] : $this->url;
        }
        if (in_array($this->type, ['pdf','ppt','gdrive'])) {
            preg_match('/\/d\/(.*?)\//', $this->url, $m);
            return isset($m[1]) ? 'https://drive.google.com/file/d/' . $m[1] . '/preview' : $this->url;
        }
        return $this->url;
    }
 
    public function getIconAttribute()
    {
        return match($this->type) {
            'youtube' => 'fab fa-youtube',
            'ppt'     => 'fas fa-file-powerpoint',
            'pdf'     => 'fas fa-file-pdf',
            'gdrive'  => 'fab fa-google-drive',
            default   => 'fas fa-link',
        };
    }
 
    public function getColorAttribute()
    {
        return match($this->type) {
            'youtube' => '#ef4444',
            'ppt'     => '#f59e0b',
            'pdf'     => '#3b82f6',
            'gdrive'  => '#10b981',
            default   => '#667eea',
        };
    }
}

