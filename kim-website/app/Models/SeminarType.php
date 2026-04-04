<?php
// app/Models/SeminarType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeminarType extends Model
{
    protected $fillable = ['name', 'slug', 'is_active', 'order'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function seminars()
    {
        return $this->hasMany(Seminar::class, 'type', 'slug');
    }

    public static function activeOrdered()
    {
        return static::where('is_active', true)->orderBy('order')->orderBy('name')->get();
    }
}