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
        // Migration untuk seminars table
        Schema::table('seminars', function (Blueprint $table) {
            $table->integer('total_jp')->nullable()->after('duration_minutes');
        });

        // Migration untuk seminar_materials (hapus kolom jp)
        Schema::table('seminar_materials', function (Blueprint $table) {
            $table->dropColumn('jp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            //
        });
    }
};