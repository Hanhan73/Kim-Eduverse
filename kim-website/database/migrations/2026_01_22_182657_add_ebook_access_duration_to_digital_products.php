<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('digital_products', function (Blueprint $table) {
            // Durasi akses e-book dalam hari (default 90 hari / 3 bulan)
            $table->integer('ebook_access_duration_days')->default(90)->after('file_url');
        });
    }

    public function down()
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropColumn('ebook_access_duration_days');
        });
    }
};