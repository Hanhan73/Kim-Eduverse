<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DigitalProduct;
use App\Models\DigitalProductCategory;
use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\CollaboratorRevenue;
use App\Models\CollaboratorWithdrawal;
use Illuminate\Support\Facades\Hash;

class DigitalCollaboratorSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KIM Digital Collaborator System Seeder ===\n\n";

        // 1. Create Bendahara Digital
        $bendahara = User::firstOrCreate(
            ['email' => 'bendahara.digital@kim.com'],
            [
                'name' => 'Bendahara Digital',
                'password' => Hash::make('password'),
                'role' => 'bendahara_digital',
                'email_verified_at' => now(),
            ]
        );
        echo "✓ Bendahara Digital: bendahara.digital@kim.com / password\n";

        // 2. Create Collaborators
        $collab1 = User::firstOrCreate(
            ['email' => 'andi.collab@kim.com'],
            [
                'name' => 'Andi Prasetyo',
                'password' => Hash::make('password'),
                'role' => 'collaborator',
                'phone' => '081234567801',
                'email_verified_at' => now(),
            ]
        );

        $collab2 = User::firstOrCreate(
            ['email' => 'sari.collab@kim.com'],
            [
                'name' => 'Sari Handayani',
                'password' => Hash::make('password'),
                'role' => 'collaborator',
                'phone' => '081234567802',
                'email_verified_at' => now(),
            ]
        );
        echo "✓ Collaborators created\n";

        // 3. Create Categories if not exist
        $categories = [
            [
                'name' => 'CEKMA (Cek Mandiri Langkah Anda)',
                'slug' => 'cekma',
                'icon' => 'fa-brain',
                'order' => 1,
            ],
            [
                'name' => 'E-Book & Modul',
                'slug' => 'ebook-modul',
                'icon' => 'fa-book',
                'order' => 2,
            ],
        ];

        foreach ($categories as $data) {
            DigitalProductCategory::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['is_active' => true])
            );
        }
        echo "✓ Categories ready\n";

        // 4. Create Sample Products with Collaborators
        $products = [
            [
                'collaborator' => $collab1,
                'name' => 'Cekma Minat Bakat Siswa',
                'slug' => 'cekma-minat-bakat',
                'category_id' => 1,
                'type' => 'questionnaire',
                'price' => 150000,
                'description' => 'Cekma untuk mengukur minat dan bakat siswa',
                'sales' => 8,
            ],
            [
                'collaborator' => $collab1,
                'name' => 'Template Laporan Psikologi Lengkap',
                'slug' => 'template-laporan-psikologi',
                'category_id' => 2,
                'type' => 'template',
                'price' => 75000,
                'description' => 'Template profesional untuk laporan psikologi',
                'sales' => 12,
            ],
            [
                'collaborator' => $collab2,
                'name' => 'E-Book Panduan Konseling Remaja',
                'slug' => 'ebook-konseling-remaja',
                'category_id' => 2,
                'type' => 'ebook',
                'price' => 95000,
                'description' => 'Panduan lengkap konseling untuk remaja',
                'sales' => 6,
            ],
            [
                'collaborator' => $collab2,
                'name' => 'CEKMA Kepribadian MBTI',
                'slug' => 'cekma-mbti',
                'category_id' => 1,
                'type' => 'questionnaire',
                'price' => 120000,
                'description' => 'CEKMA untuk mengukur tipe kepribadian MBTI',
                'sales' => 10,
            ],
        ];

        $allProducts = [];
        foreach ($products as $data) {
            $product = DigitalProduct::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'collaborator_id' => $data['collaborator']->id,
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'type' => $data['type'],
                    'price' => $data['price'],
                    'is_active' => true,
                    'is_featured' => false,
                ]
            );
            
            $allProducts[] = [
                'product' => $product,
                'collaborator' => $data['collaborator'],
                'sales' => $data['sales'],
            ];
        }
        echo "✓ Products created with collaborators\n";

        // 5. Create Sample Orders & Revenues
        $customer = User::firstOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        $totalRevenue = 0;
        foreach ($allProducts as $data) {
            for ($i = 0; $i < $data['sales']; $i++) {
                $orderNumber = 'DIG-' . strtoupper(uniqid());
                
                $order = DigitalOrder::create([
                    'order_number' => $orderNumber,
                    'customer_email' => $customer->email,
                    'subtotal' => $data['product']->price,
                    'tax' => 0,
                    'total' => $data['product']->price,
                    'payment_status' => 'paid',
                    'payment_method' => ['bank_transfer', 'gopay', 'qris'][rand(0, 2)],
                    'paid_at' => now()->subDays(rand(1, 30)),
                    'status' => 'completed',
                ]);

                DigitalOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $data['product']->id,
                    'product_name' => $data['product']->name,
                    'product_type' => $data['product']->type,
                    'price' => $data['product']->price,
                    'quantity' => 1,
                    'subtotal' => $data['product']->price,
                ]);

                // Create Collaborator Revenue (70%)
                $collaboratorShare = $data['product']->price * 0.70;
                $platformShare = $data['product']->price * 0.30;
                
                CollaboratorRevenue::create([
                    'collaborator_id' => $data['collaborator']->id,
                    'order_id' => $order->id,
                    'product_id' => $data['product']->id,
                    'product_price' => $data['product']->price,
                    'collaborator_share' => $collaboratorShare,
                    'platform_share' => $platformShare,
                    'status' => 'available',
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                $totalRevenue += $collaboratorShare;
            }
        }
        echo "✓ Created orders and revenues\n";

        // 6. Create Sample Withdrawals
        // Pending withdrawal from collab1
        $collab1Revenue = CollaboratorRevenue::where('collaborator_id', $collab1->id)
            ->where('status', 'available')
            ->sum('collaborator_share');
        
        if ($collab1Revenue >= 300000) {
            CollaboratorWithdrawal::create([
                'collaborator_id' => $collab1->id,
                'amount' => 300000,
                'status' => 'pending',
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'Andi Prasetyo',
                'notes' => 'Penarikan rutin bulanan',
                'created_at' => now()->subDays(2),
            ]);
            echo "✓ Pending withdrawal: Andi (Rp 300.000)\n";
        }

        // Approved withdrawal from collab2
        $collab2Revenues = CollaboratorRevenue::where('collaborator_id', $collab2->id)
            ->where('status', 'available')
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();
        
        if ($collab2Revenues->sum('collaborator_share') >= 200000) {
            $withdrawal = CollaboratorWithdrawal::create([
                'collaborator_id' => $collab2->id,
                'amount' => 200000,
                'status' => 'approved',
                'bank_name' => 'Mandiri',
                'account_number' => '9876543210',
                'account_name' => 'Sari Handayani',
                'notes' => 'Untuk kebutuhan operasional',
                'approved_by' => $bendahara->id,
                'approved_at' => now()->subDays(5),
                'created_at' => now()->subDays(7),
            ]);

            // Mark revenues as withdrawn
            $remainingAmount = 200000;
            foreach ($collab2Revenues as $revenue) {
                if ($remainingAmount <= 0) break;
                
                if ($revenue->collaborator_share <= $remainingAmount) {
                    $revenue->update([
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);
                    $remainingAmount -= $revenue->collaborator_share;
                }
            }
            echo "✓ Approved withdrawal: Sari (Rp 200.000)\n";
        }

        echo "\n=== SUMMARY ===\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Andi Prasetyo - Available: Rp " . number_format(
            CollaboratorRevenue::where('collaborator_id', $collab1->id)->where('status', 'available')->sum('collaborator_share'),
            0, ',', '.'
        ) . "\n";
        echo "Sari Handayani - Available: Rp " . number_format(
            CollaboratorRevenue::where('collaborator_id', $collab2->id)->where('status', 'available')->sum('collaborator_share'),
            0, ',', '.'
        ) . "\n";
        echo "\n🔐 LOGIN CREDENTIALS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Bendahara: bendahara.digital@kim.com / password\n";
        echo "Collab 1:  andi.collab@kim.com / password\n";
        echo "Collab 2:  sari.collab@kim.com / password\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n✨ Digital Collaborator System ready!\n";
    }
}