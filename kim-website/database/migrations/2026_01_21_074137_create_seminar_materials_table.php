<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seminar_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Nama materi (misal: "Pengenalan dan Konsep Dasar")
            $table->integer('jp'); // Jam Pelajaran (misal: 2)
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seminar_materials');
    }
};
