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
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('digital_product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('features')->nullable(); // JSON array of features
            $table->decimal('price', 10, 2);
            $table->string('thumbnail')->nullable();
            $table->string('type'); // 'questionnaire', 'ebook', 'worksheet', 'template', etc.
            $table->foreignId('questionnaire_id')->nullable()->constrained('questionnaires')->onDelete('set null');
            $table->string('file_path')->nullable(); // For downloadable products
            $table->integer('duration_minutes')->nullable(); // For questionnaires
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->integer('sold_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_products');
    }
};
