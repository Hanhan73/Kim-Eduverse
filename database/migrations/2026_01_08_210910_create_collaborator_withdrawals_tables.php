<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Collaborator Withdrawals Table - BUAT DULU
        Schema::create('collaborator_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Bank Details
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_name', 100);
            
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['collaborator_id', 'status']);
            $table->index('status');
        });

        // Collaborator Revenues Table - BUAT SETELAH WITHDRAWALS
        Schema::create('collaborator_revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('digital_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('digital_products')->onDelete('cascade');
            $table->unsignedBigInteger('withdrawal_id')->nullable(); // JANGAN LANGSUNG FOREIGN KEY
            
            $table->decimal('product_price', 15, 2);
            $table->decimal('collaborator_share', 15, 2); // 70%
            $table->decimal('platform_share', 15, 2);     // 30%
            
            $table->enum('status', ['pending', 'available', 'withdrawn'])->default('available');
            
            $table->timestamps();
            
            $table->index(['collaborator_id', 'status']);
            $table->index('order_id');
            $table->index('product_id');
            $table->index('withdrawal_id');
            
            // TAMBAHKAN FOREIGN KEY SETELAH KOLOM DIBUAT
            $table->foreign('withdrawal_id')
                  ->references('id')
                  ->on('collaborator_withdrawals')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborator_revenues');
        Schema::dropIfExists('collaborator_withdrawals');
    }
};