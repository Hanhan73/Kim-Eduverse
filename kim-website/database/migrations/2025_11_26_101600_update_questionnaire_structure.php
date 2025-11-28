<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create dimension ranges table
        if (Schema::hasTable('questionnaire_dimensions') && !Schema::hasTable('questionnaire_dimension_ranges')) {
            Schema::create('questionnaire_dimension_ranges', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dimension_id');
                $table->integer('min_score');
                $table->integer('max_score');
                $table->string('category');
                $table->text('interpretation');
                $table->text('recommendations')->nullable();
                $table->timestamps();
                
                $table->foreign('dimension_id')->references('id')->on('questionnaire_dimensions')->onDelete('cascade');
            });
        }

        // Create pivot table ONLY if both parent tables exist
        if (Schema::hasTable('questionnaire_questions') && 
            Schema::hasTable('questionnaire_dimensions') && 
            !Schema::hasTable('questionnaire_question_dimension')) {
            
            Schema::create('questionnaire_question_dimension', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id');
                $table->unsignedBigInteger('dimension_id');
                $table->timestamps();
                
                $table->foreign('question_id')->references('id')->on('questionnaire_questions')->onDelete('cascade');
                $table->foreign('dimension_id')->references('id')->on('questionnaire_dimensions')->onDelete('cascade');
            });
        }

        // Update questionnaire_questions table if exists
        if (Schema::hasTable('questionnaire_questions')) {
            Schema::table('questionnaire_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('questionnaire_questions', 'question_type')) {
                    $table->string('question_type')->default('multiple_choice')->after('question_text');
                }
                if (!Schema::hasColumn('questionnaire_questions', 'is_required')) {
                    $table->boolean('is_required')->default(true)->after('question_type');
                }
            });
        }

        // Create options table ONLY if questions table exists
        if (Schema::hasTable('questionnaire_questions') && !Schema::hasTable('questionnaire_question_options')) {
            Schema::create('questionnaire_question_options', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id');
                $table->string('option_text');
                $table->integer('score')->default(0);
                $table->integer('order')->default(0);
                $table->timestamps();
                
                $table->foreign('question_id')->references('id')->on('questionnaire_questions')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('questionnaire_question_options');
        Schema::dropIfExists('questionnaire_question_dimension');
        Schema::dropIfExists('questionnaire_dimension_ranges');
    }
};
