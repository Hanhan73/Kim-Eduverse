<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('course_materials')->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            // Unique: tracking per user per material
            $table->unique(['user_id', 'course_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
