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
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('dimension_id')->nullable()->constrained('questionnaire_dimensions')->onDelete('set null');
            $table->text('question_text');
            $table->integer('order');
            $table->boolean('is_reverse_scored')->default(false); // For reverse scoring questions
            $table->text('options')->nullable(); // JSON array of options if multiple choice
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
