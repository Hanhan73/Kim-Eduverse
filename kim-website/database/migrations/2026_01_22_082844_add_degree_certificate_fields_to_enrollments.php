<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->timestamp('degree_certificate_issued_at')->nullable()->after('certificate_issued_at');
            $table->string('degree_certificate_number')->nullable()->after('certified_number');
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['degree_certificate_issued_at', 'degree_certificate_number']);
        });
    }
};
