<?php
// database/migrations/2026_06_03_000001_create_training_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('training_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['pdf', 'ppt', 'youtube', 'gdrive'])->default('pdf');
            $table->string('url'); // link file/video
            $table->unsignedInteger('jp')->default(1); // jam pelajaran
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_materials');
    }
};