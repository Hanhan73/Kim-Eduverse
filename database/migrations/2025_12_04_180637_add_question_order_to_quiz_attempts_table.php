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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->text('question_order')->nullable()->after('answers');
        });
    }

    public function down()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('question_order');
        });
    }
};
