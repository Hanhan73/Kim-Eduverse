<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('ebook_accesses', function (Blueprint $table) {
        // Hapus FK & kolom user_id
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');

        // Tambah email customer
        $table->string('customer_email')->after('product_id');

        $table->index('customer_email');
    });
}

public function down()
{
    Schema::table('ebook_accesses', function (Blueprint $table) {
        // Balikin user_id (kalau rollback)
        $table->foreignId('user_id')
              ->nullable()
              ->constrained()
              ->onDelete('cascade');

        $table->dropIndex(['customer_email']);
        $table->dropColumn('customer_email');
    });
}

};