<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('order')->default(0);
            $table->enum('type', ['pdf', 'video', 'document'])->default('pdf');
            $table->string('file_path');
            $table->integer('duration_minutes')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_preview')->default(false); // bisa dilihat sebelum daftar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
