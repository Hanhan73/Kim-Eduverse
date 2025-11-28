<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaire_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('min_score');
            $table->integer('max_score');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // JANGAN buat pivot table di sini! 
        // Karena table questionnaire_questions belum ada!
        // Pivot table dibuat di migration update_questionnaire_structure nanti
    }

    public function down()
    {
        Schema::dropIfExists('questionnaire_dimensions');
    }
};