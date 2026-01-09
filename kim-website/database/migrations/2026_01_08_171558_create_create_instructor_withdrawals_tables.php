<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create withdrawals table first (no foreign key to revenues yet)
        Schema::create('instructor_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Create revenues table - pakai payments table, bukan orders
        Schema::create('instructor_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('payment_id'); // dari tabel payments
            $table->unsignedBigInteger('course_id');
            $table->decimal('course_price', 15, 2);
            $table->decimal('instructor_share', 15, 2); // 70%
            $table->decimal('platform_share', 15, 2); // 30%
            $table->enum('status', ['pending', 'available', 'withdrawn'])->default('pending');
            $table->unsignedBigInteger('withdrawal_id')->nullable();
            $table->timestamps();
            
            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('withdrawal_id')->references('id')->on('instructor_withdrawals')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('instructor_revenues');
        Schema::dropIfExists('instructor_withdrawals');
    }
};