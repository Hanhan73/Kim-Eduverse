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
        Schema::table('digital_products', function (Blueprint $table) {
            // Add short_description if not exists
            if (!Schema::hasColumn('digital_products', 'short_description')) {
                $table->string('short_description', 500)->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('digital_products', function (Blueprint $table) {
            if (Schema::hasColumn('digital_products', 'short_description')) {
                $table->dropColumn('short_description');
            }
        });
    }
};