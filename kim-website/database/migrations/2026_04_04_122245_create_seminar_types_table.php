<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seminar_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // "Pendidikan"
            $table->string('slug')->unique(); // "pendidikan"
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Seed data lama
        DB::table('seminar_types')->insert([
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'is_active' => true, 'order' => 1],
            ['name' => 'Manajemen',  'slug' => 'manajemen',  'is_active' => true, 'order' => 2],
            ['name' => 'Kearsipan',  'slug' => 'kearsipan',  'is_active' => true, 'order' => 3],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('seminar_types');
    }
};