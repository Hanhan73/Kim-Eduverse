<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add batch_id to enrollments
        if (Schema::hasTable('enrollments') && !Schema::hasColumn('enrollments', 'batch_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->foreignId('batch_id')->nullable()->after('course_id')->constrained('course_batches')->onDelete('cascade');
            });
        }

        // Add batch_id to attendances
        if (Schema::hasTable('attendances') && !Schema::hasColumn('attendances', 'batch_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('batch_id')->nullable()->after('course_id')->constrained('course_batches')->onDelete('cascade');
            });
        }

        // Add batch_id to live_sessions
        if (Schema::hasTable('live_sessions') && !Schema::hasColumn('live_sessions', 'batch_id')) {
            Schema::table('live_sessions', function (Blueprint $table) {
                $table->foreignId('batch_id')->nullable()->after('course_id')->constrained('course_batches')->onDelete('cascade');
            });
        }
    }

};