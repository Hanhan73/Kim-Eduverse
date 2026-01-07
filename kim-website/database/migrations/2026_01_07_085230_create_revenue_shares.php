<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_shares', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // pembeli
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('course_type'); // 'edutech' atau 'kim_digital'
            $table->foreignId('course_id'); // ID course (bisa dari edutech atau kim_digital)
            $table->decimal('total_amount', 15, 2); // total harga
            $table->decimal('instructor_share', 15, 2); // 70%
            $table->decimal('company_share', 15, 2); // 30%
            $table->decimal('instructor_percentage', 5, 2)->default(70.00);
            $table->decimal('company_percentage', 5, 2)->default(30.00);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['instructor_id', 'status']);
            $table->index(['course_type', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_shares');
    }
};
