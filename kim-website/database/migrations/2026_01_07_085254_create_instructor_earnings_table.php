<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->unique()
                ->constrained('users')->onDelete('cascade');
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->decimal('available_balance', 15, 2)->default(0);
            $table->decimal('withdrawn', 15, 2)->default(0);
            $table->decimal('pending_withdrawal', 15, 2)->default(0);
            $table->integer('total_sales')->default(0);
            $table->integer('edutech_sales')->default(0);
            $table->integer('kim_digital_sales')->default(0);
            $table->timestamps();
        });

        // TAMBAH instructor_id KE courses SAJA
        if (
            Schema::hasTable('courses') &&
            !Schema::hasColumn('courses', 'instructor_id')
        ) {

            Schema::table('courses', function (Blueprint $table) {
                $table->foreignId('instructor_id')->nullable()
                    ->after('id')->constrained('users')
                    ->onDelete('set null');
            });
        }
    }


    public function down(): void
    {
        if (
            Schema::hasTable('courses') &&
            Schema::hasColumn('courses', 'instructor_id')
        ) {

            Schema::table('courses', function (Blueprint $table) {
                $table->dropForeign(['instructor_id']);
                $table->dropColumn('instructor_id');
            });
        }

        Schema::dropIfExists('instructor_earnings');
    }
};
