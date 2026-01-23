<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ebook_accesses', function (Blueprint $table) {
            $table->dateTime('expires_at')->change();
        });
    }

    public function down()
    {
        Schema::table('ebook_accesses', function (Blueprint $table) {
            $table->timestamp('expires_at')->change();
        });
    }
};