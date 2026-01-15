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
        Schema::table('quizzes', function (Blueprint $table) {
            // Tambah kolom polymorphic untuk relasi ke Seminar atau Course
            if (!Schema::hasColumn('quizzes', 'quizable_type')) {
                $table->string('quizable_type')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('quizzes', 'quizable_id')) {
                $table->unsignedBigInteger('quizable_id')->nullable()->after('quizable_type');
            }
            
            // Tambah kolom randomize_questions jika belum ada
            if (!Schema::hasColumn('quizzes', 'randomize_questions')) {
                $table->boolean('randomize_questions')->default(false)->after('max_attempts');
            }

            // Index untuk polymorphic relationship
            $table->index(['quizable_type', 'quizable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['quizable_type', 'quizable_id']);
            
            if (Schema::hasColumn('quizzes', 'quizable_type')) {
                $table->dropColumn('quizable_type');
            }
            
            if (Schema::hasColumn('quizzes', 'quizable_id')) {
                $table->dropColumn('quizable_id');
            }
            
            if (Schema::hasColumn('quizzes', 'randomize_questions')) {
                $table->dropColumn('randomize_questions');
            }
        });
    }
};