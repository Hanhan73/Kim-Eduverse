<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('meeting_number')->after('batch_id');
            $table->string('meeting_topic')->nullable()->after('meeting_number');
            $table->date('meeting_date')->nullable()->after('meeting_topic');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['meeting_number', 'meeting_topic', 'meeting_date', 'notes']);
        });
    }
};