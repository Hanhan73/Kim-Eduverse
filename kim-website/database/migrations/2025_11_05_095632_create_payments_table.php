<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // dari Midtrans
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); // bank_transfer, credit_card, gopay, dll
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->text('payment_url')->nullable(); // Snap URL dari Midtrans
            $table->json('metadata')->nullable(); // Response dari Midtrans
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};