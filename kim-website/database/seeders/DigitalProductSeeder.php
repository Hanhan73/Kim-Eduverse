<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DigitalProduct;
use App\Http\Controllers\DigitalPaymentController;

class DigitalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh produk dengan Google Drive link
        $products = [
            [
                'name' => 'Sample E-Book Digital',
                'slug' => 'sample-ebook-digital',
                'category_id' => 3,
                'description' => 'Contoh e-book digital untuk testing sistem download dari Google Drive.',
                'type' => 'ebook',
                'price' => 25000,
                'file_path' => null,
                'file_url' => 'https://drive.google.com/uc?export=download&id=0B18mXGGKnIqvVlVUNGU2SjZOZ1U',
                'is_active' => true,
                'sold_count' => 0,
                'download_count' => 0,
            ],
            
            // Tambahkan produk lain jika diperlukan
            // [
            //     'name' => 'Template Laporan Psikologi',
            //     'slug' => 'template-laporan-psikologi',
            //     'description' => 'Template lengkap untuk membuat laporan psikologi profesional.',
            //     'type' => 'template',
            //     'price' => 75000,
            //     'file_path' => null,
            //     'file_url' => 'https://drive.google.com/uc?export=download&id=YOUR_FILE_ID',
            //     'is_active' => true,
            //     'sold_count' => 0,
            //     'download_count' => 0,
            // ],
        ];

        foreach ($products as $product) {
            DigitalProduct::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }

        $this->command->info('Digital products seeded successfully!');
        $this->command->info('Total products: ' . count($products));
    }
}