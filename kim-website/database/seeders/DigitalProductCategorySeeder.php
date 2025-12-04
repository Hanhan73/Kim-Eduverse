<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DigitalProductCategory;

class DigitalProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DigitalProductCategory::create([
            'name' => 'Angket Psikologi',
            'slug' => 'angket-psikologi', // boleh dihapus karena MODEL auto slug, tapi tetap aku ikutkan biar konsisten
            'description' => 'Angket dan tes psikologi untuk mengukur berbagai aspek psikologis',
            'icon' => 'fa-brain',
            'order' => 1,
            'is_active' => true,
        ]);

        DigitalProductCategory::create([
            'name' => 'Seminar on-demand',
            'slug' => 'seminar-on-demand',
            'description' => 'Kumpulan seminar on-demand dari berbagai topik edukasi',
            'icon' => 'fa-chalkboard-teacher',
            'order' => 2,
            'is_active' => true,
        ]);

        DigitalProductCategory::create([
            'name' => 'E-Book & Modul',
            'slug' => 'ebook-modul',
            'description' => 'Buku digital, modul pembelajaran, dan materi edukasi lainnya',
            'icon' => 'fa-book',
            'order' => 3,
            'is_active' => true,
        ]);

        echo "✅ DigitalProductCategorySeeder berhasil di-seed!\n";
    }
}