<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ebook_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('digital_products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained('digital_orders')->onDelete('cascade');
            $table->string('access_token')->unique();
            $table->timestamp('expires_at');
            $table->integer('view_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->ipAddress('last_ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['access_token', 'is_active']);
            $table->index(['user_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ebook_accesses');
    }
};