<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'html_content',
        'navbar_button_text',
        'navbar_logo_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke DigitalProduct
     */
    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }
}