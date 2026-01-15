<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom:
     * - file_url: untuk link external (Google Drive, dll)
     * - download_count: tracking jumlah download
     */
    public function up(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            // Kolom file_url untuk external links (Google Drive, Dropbox, dll)
            $table->string('file_url')->nullable()->after('file_path');
            
            // Download counter
            $table->unsignedInteger('download_count')->default(0)->after('sold_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'download_count']);
        });
    }
};
