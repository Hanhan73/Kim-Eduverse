<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('digital_products')->onDelete('cascade');
            $table->longText('html_content'); // HTML mentah dari admin
            $table->string('navbar_button_text')->default('Beli Sekarang'); // Teks tombol navbar
            $table->string('navbar_logo_text')->nullable(); // Opsional: teks logo di navbar
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_landing_pages');
    }
};