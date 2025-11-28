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
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->foreignId('dimension_id')->nullable()->constrained('questionnaire_dimensions')->onDelete('set null');
            $table->text('question_text');
            $table->integer('order')->default(0);
            $table->boolean('is_reverse_scored')->default(false);
            $table->json('options')->nullable(); // JSON: {1: "Sangat Tidak Setuju", 2: "Tidak Setuju", ...}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_questions');
    }
};
