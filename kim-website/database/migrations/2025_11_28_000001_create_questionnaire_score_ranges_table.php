<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel ini memungkinkan admin menentukan:
     * - Range skor (min-max) untuk setiap kategori
     * - Jumlah kategori fleksibel (bisa 3, 4, 5, dst)
     * - Interpretasi berbeda per dimensi
     */
    public function up(): void
    {
        Schema::create('questionnaire_score_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')->constrained('questionnaire_dimensions')->onDelete('cascade');
            $table->integer('min_score');
            $table->integer('max_score');
            $table->string('category'); // 'sangat_rendah', 'rendah', 'sedang', 'tinggi', 'sangat_tinggi'
            $table->string('level'); // Label tampilan: "SANGAT RENDAH", "RENDAH", etc
            $table->string('css_class')->default('level-sedang'); // CSS class untuk styling
            $table->text('description'); // Deskripsi interpretasi
            $table->json('suggestions')->nullable(); // Array saran
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['dimension_id', 'min_score', 'max_score'], 'qs_ranges_dim_min_max_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_score_ranges');
    }
};
