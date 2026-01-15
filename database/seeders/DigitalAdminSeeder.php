<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DigitalAdmin;
use Illuminate\Support\Facades\Hash;

class DigitalAdminSeeder extends Seeder
{
    public function run()
    {
        DigitalAdmin::create([
            'name' => 'Admin KIM Digital',
            'email' => 'admin@kimdigital.com',
            'password' => Hash::make('admin123'),
        ]);

        echo "✅ Digital Admin created!\n";
        echo "Email: admin@kimdigital.com\n";
        echo "Password: admin123\n";
    }
}
