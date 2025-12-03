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
            // Tambahkan kolom untuk polymorphic relation
            $table->unsignedBigInteger('quizable_id')->nullable()->after('id');
            $table->string('quizable_type')->nullable()->after('quizable_id');

            // Buat index untuk performa query
            $table->index(['quizable_id', 'quizable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['quizable_id', 'quizable_type']);
            $table->dropColumn(['quizable_id', 'quizable_type']);
        });
    }
};
