<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus kolom lesson_id jika ada
        if (Schema::hasColumn('quizzes', 'lesson_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropForeign(['lesson_id']);
                $table->dropColumn('lesson_id');
            });
        }

        Schema::table('quizzes', function (Blueprint $table) {
            // Tambah module_id
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            
            // Tambah kolom randomize untuk pre/post test
            $table->boolean('randomize_questions')->default(false)->after('max_attempts');
            
            // Drop dan recreate type enum
            $table->dropColumn('type');
        });
        
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('type', ['pre_test', 'post_test', 'module_quiz'])->after('module_id');
        });
    }

    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id', 'randomize_questions', 'type']);
        });
        
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('type', ['pre_test', 'post_test'])->after('course_id');
        });
    }
};