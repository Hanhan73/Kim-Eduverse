<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->enum('type', ['video', 'text', 'pdf', 'quiz'])->default('video');
            $table->string('video_url')->nullable();
            $table->text('content')->nullable(); // untuk text lesson
            $table->string('file_path')->nullable(); // untuk PDF
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_preview')->default(false); // bisa dilihat tanpa enroll
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};