<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tabel baru khusus materi sertifikat
        Schema::create('training_certificate_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->string('title'); // nama materi yang tampil di sertifikat
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Hapus kolom jp dari training_materials kalau masih ada
        if (Schema::hasColumn('training_materials', 'jp')) {
            Schema::table('training_materials', function (Blueprint $table) {
                $table->dropColumn('jp');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('training_certificate_materials');
    }
};