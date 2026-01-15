<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Main Seminar Table
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->string('instructor_name');
            $table->text('instructor_bio')->nullable();

            // Materials
            $table->string('material_pdf_path')->nullable(); // Google Drive link atau path
            $table->text('material_description')->nullable();

            // Quiz IDs
            $table->foreignId('pre_test_id')->nullable()->constrained('quizzes')->onDelete('set null');
            $table->foreignId('post_test_id')->nullable()->constrained('quizzes')->onDelete('set null');

            // Certificate template
            $table->string('certificate_template')->nullable();

            // Pricing & Status
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sold_count')->default(0);
            $table->integer('order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        // Seminar Enrollments (User yang beli seminar)
        Schema::create('seminar_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id')->constrained()->onDelete('cascade');
            $table->string('customer_email');
            $table->foreignId('order_id')->nullable()->constrained('digital_orders')->onDelete('set null');

            // Progress tracking
            $table->boolean('pre_test_passed')->default(false);
            $table->timestamp('pre_test_completed_at')->nullable();
            $table->integer('pre_test_score')->nullable();

            $table->boolean('material_viewed')->default(false);
            $table->timestamp('material_viewed_at')->nullable();

            $table->boolean('post_test_passed')->default(false);
            $table->timestamp('post_test_completed_at')->nullable();
            $table->integer('post_test_score')->nullable();

            // Certificate
            $table->boolean('certificate_generated')->default(false);
            $table->string('certificate_number')->nullable()->unique();
            $table->string('certificate_path')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->boolean('certificate_sent_via_email')->default(false);

            // Completion
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });

        // Add seminar type to digital_products
        Schema::table('digital_products', function (Blueprint $table) {
            // Just make sure 'seminar' can be added to type enum if needed
            // If your type column is already string, no need to change
        });
    }

    public function down()
    {
        Schema::dropIfExists('seminar_enrollments');
        Schema::dropIfExists('seminars');
    }
};
