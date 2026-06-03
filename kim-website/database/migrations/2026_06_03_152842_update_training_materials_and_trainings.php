<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tambah total_jp ke trainings
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedInteger('total_jp')->default(0)->after('organizer');
        });

        // Hapus kolom jp dari training_materials (kalau sudah ada)
        if (Schema::hasColumn('training_materials', 'jp')) {
            Schema::table('training_materials', function (Blueprint $table) {
                $table->dropColumn('jp');
            });
        }
    }

    public function down()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('total_jp');
        });
        Schema::table('training_materials', function (Blueprint $table) {
            $table->unsignedInteger('jp')->default(1);
        });
    }
};