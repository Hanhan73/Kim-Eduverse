<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Akses penuh ke seluruh sistem',
                'permissions' => json_encode([
                    'manage_users',
                    'manage_roles',
                    'manage_courses',
                    'manage_blog',
                    'manage_edutech',
                    'manage_kim_digital',
                    'view_revenue',
                    'manage_withdrawals',
                    'view_all_reports'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'bendahara',
                'display_name' => 'Bendahara',
                'description' => 'Mengelola keuangan dan pembayaran',
                'permissions' => json_encode([
                    'view_revenue',
                    'view_instructor_earnings',
                    'approve_withdrawals',
                    'view_financial_reports',
                    'manage_revenue_shares'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'instruktor',
                'display_name' => 'Instruktor',
                'description' => 'Mengajar dan mengelola kursus',
                'permissions' => json_encode([
                    'manage_own_courses',
                    'view_own_earnings',
                    'request_withdrawal',
                    'view_own_students',
                    'manage_course_content'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin_blog',
                'display_name' => 'Admin Blog',
                'description' => 'Mengelola konten blog',
                'permissions' => json_encode([
                    'manage_blog',
                    'manage_articles',
                    'manage_categories'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Mengakses dan belajar dari kursus',
                'permissions' => json_encode([
                    'enroll_courses',
                    'view_own_courses',
                    'take_quizzes',
                    'download_certificates'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
