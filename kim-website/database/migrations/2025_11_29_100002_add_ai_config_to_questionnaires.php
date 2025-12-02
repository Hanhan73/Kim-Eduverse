<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom untuk AI configuration per questionnaire:
     * - ai_enabled: Apakah AI analysis diaktifkan
     * - ai_context: Context/instruksi tambahan untuk AI
     * - ai_persona: Persona yang digunakan AI (psikolog, konselor, dll)
     */
    public function up(): void
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->boolean('ai_enabled')->default(true)->after('is_active');
            $table->text('ai_context')->nullable()->after('ai_enabled');
            $table->string('ai_persona')->default('psikolog')->after('ai_context');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn(['ai_enabled', 'ai_context', 'ai_persona']);
        });
    }
};
