<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom untuk AI analysis:
     * - ai_analysis: JSON hasil analisis dari Claude AI
     * - chart_data: JSON data untuk visualisasi chart
     * - ai_generated_at: Timestamp kapan AI analysis dibuat
     */
    public function up(): void
    {
        Schema::table('questionnaire_responses', function (Blueprint $table) {
            $table->json('ai_analysis')->nullable()->after('result_summary');
            $table->json('chart_data')->nullable()->after('ai_analysis');
            $table->timestamp('ai_generated_at')->nullable()->after('chart_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaire_responses', function (Blueprint $table) {
            $table->dropColumn(['ai_analysis', 'chart_data', 'ai_generated_at']);
        });
    }
};
