<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Tambah kolom module_id (nullable karena pre/post test tidak punya module)
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            
            // Ubah type enum untuk include module_quiz
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
            $table->dropColumn('module_id');
            $table->dropColumn('type');
        });
        
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('type', ['pre_test', 'post_test'])->after('course_id');
        });
    }
};  
