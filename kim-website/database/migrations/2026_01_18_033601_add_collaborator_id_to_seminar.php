<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seminars', function (Blueprint $table) {
            // Tambah kolom collaborator_id
            $table->foreignId('collaborator_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            
            // instructor_name & instructor_bio tetap ada untuk fallback/display
            // tapi sekarang optional karena bisa ambil dari relasi collaborator
        });
    }

    public function down()
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->dropForeign(['collaborator_id']);
            $table->dropColumn('collaborator_id');
        });
    }
};