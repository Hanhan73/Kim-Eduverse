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
        Schema::create('questionnaire_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('digital_orders')
                ->onDelete('cascade');
                  $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->string('respondent_email');
            $table->json('answers')->nullable(); // JSON: {question_id: answer_value, ...}
            $table->json('scores')->nullable(); // JSON: {dimension_code: score, ...}
            $table->json('result_summary')->nullable(); // JSON result
            $table->string('result_pdf_path')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->boolean('result_sent')->default(false);
            $table->timestamp('result_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_responses');
    }
};
