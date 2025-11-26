<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questionnaire_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->string('code'); // 'exhaustion', 'cynicism', 'ineffective', etc.
            $table->string('name'); // 'Kelelahan Emosional', etc.
            $table->text('description')->nullable();
            $table->text('interpretations'); // JSON: low, medium, high categories with descriptions
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_dimensions');
    }
};
