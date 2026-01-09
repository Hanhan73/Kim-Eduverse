<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\InstructorRevenue;
use App\Models\InstructorWithdrawal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EdutechSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== EduTech Complete System Seeder ===\n\n";

        // ========================================
        // 1. CREATE USERS
        // ========================================
        
        echo "📝 Creating users...\n";

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@edutech.com'],
            [
                'name' => 'Admin Edutech',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'bio' => 'Administrator KIM Edutech Platform',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Bendahara
        $bendahara = User::firstOrCreate(
            ['email' => 'bendahara.edutech@kim.com'],
            [
                'name' => 'Bendahara EduTech',
                'password' => Hash::make('password'),
                'role' => 'bendahara_edutech',
                'email_verified_at' => now(),
            ]
        );

        // Instructors
        $instructor1 = User::firstOrCreate(
            ['email' => 'ahmad@edutech.com'],
            [
                'name' => 'Dr. Ahmad Rizki, M.Pd',
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'phone' => '081234567891',
                'bio' => 'Expert in Education Technology with 10+ years experience',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $instructor2 = User::firstOrCreate(
            ['email' => 'siti@edutech.com'],
            [
                'name' => 'Siti Nurhaliza, M.Pd',
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'phone' => '081234567892',
                'bio' => 'Language Expert specializing in English and Arabic',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $instructor3 = User::firstOrCreate(
            ['email' => 'budi@edutech.com'],
            [
                'name' => 'Budi Santoso, S.Kom, M.T',
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'phone' => '081234567893',
                'bio' => 'Software Engineer & IT Specialist',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Students
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = User::firstOrCreate(
                ['email' => "student{$i}@edutech.com"],
                [
                    'name' => "Student {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'phone' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        echo "✓ Users created: Admin, Bendahara, 3 Instructors, 10 Students\n\n";

        // ========================================
        // 2. CREATE COURSES
        // ========================================
        
        echo "📚 Creating courses...\n";

        $coursesData = [
            // Instructor 1 - Ahmad (Education)
            [
                'instructor' => $instructor1,
                'title' => 'CBTS - Classroom Based Training System',
                'slug' => 'cbts-classroom-training-system',
                'description' => 'Pelajari metodologi CBTS untuk meningkatkan efektivitas pembelajaran di kelas.',
                'category' => 'Education',
                'level' => 'intermediate',
                'price' => 500000,
                'duration_hours' => 20,
            ],
            [
                'instructor' => $instructor1,
                'title' => 'AI untuk Pendidikan - ChatGPT & Tools AI',
                'slug' => 'ai-untuk-pendidikan',
                'description' => 'Manfaatkan kekuatan AI untuk meningkatkan kualitas pembelajaran.',
                'category' => 'Education',
                'level' => 'beginner',
                'price' => 0, // FREE
                'duration_hours' => 12,
            ],
            
            // Instructor 2 - Siti (Language)
            [
                'instructor' => $instructor2,
                'title' => 'English for Business Communication',
                'slug' => 'english-business-communication',
                'description' => 'Master English communication skills untuk dunia profesional.',
                'category' => 'Language',
                'level' => 'beginner',
                'price' => 0, // FREE
                'duration_hours' => 15,
            ],
            [
                'instructor' => $instructor2,
                'title' => 'Bahasa Arab untuk Pemula',
                'slug' => 'bahasa-arab-pemula',
                'description' => 'Belajar Bahasa Arab dari nol hingga mahir.',
                'category' => 'Language',
                'level' => 'beginner',
                'price' => 450000,
                'duration_hours' => 30,
            ],
            
            // Instructor 3 - Budi (IT)
            [
                'instructor' => $instructor3,
                'title' => 'Web Development dengan Laravel',
                'slug' => 'web-development-laravel-fullstack',
                'description' => 'Belajar coding Laravel dari dasar hingga mahir.',
                'category' => 'Teknologi Informasi',
                'level' => 'intermediate',
                'price' => 1200000,
                'duration_hours' => 50,
            ],
            [
                'instructor' => $instructor3,
                'title' => 'Python Programming - Zero to Hero',
                'slug' => 'python-programming-zero-to-hero',
                'description' => 'Belajar Python dari nol untuk pemula.',
                'category' => 'Teknologi Informasi',
                'level' => 'beginner',
                'price' => 800000,
                'duration_hours' => 35,
            ],
        ];

        $courses = [];
        foreach ($coursesData as $data) {
            $courses[] = Course::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'instructor_id' => $data['instructor']->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'level' => $data['level'],
                    'price' => $data['price'],
                    'duration_hours' => $data['duration_hours'],
                    'max_students' => 50,
                    'is_published' => true,
                    'is_featured' => $data['price'] > 0,
                    'passing_score' => 70,
                ]
            );
        }

        echo "✓ Courses created: 6 courses (2 FREE, 4 PAID)\n\n";

        // ========================================
        // 3. CREATE ENROLLMENTS & PAYMENTS
        // ========================================
        
        echo "💳 Creating enrollments with payments...\n";

        // Simulate berbagai skenario enrollment
        $enrollmentScenarios = [
            // Student 1 - 3 paid courses
            ['student_idx' => 0, 'course_idx' => 0, 'days_ago' => 25, 'status' => 'active', 'progress' => 60],
            ['student_idx' => 0, 'course_idx' => 3, 'days_ago' => 20, 'status' => 'completed', 'progress' => 100],
            ['student_idx' => 0, 'course_idx' => 4, 'days_ago' => 15, 'status' => 'active', 'progress' => 40],
            
            // Student 2 - 2 paid courses
            ['student_idx' => 1, 'course_idx' => 0, 'days_ago' => 30, 'status' => 'completed', 'progress' => 100],
            ['student_idx' => 1, 'course_idx' => 5, 'days_ago' => 18, 'status' => 'active', 'progress' => 55],
            
            // Student 3-5 - berbagai courses
            ['student_idx' => 2, 'course_idx' => 3, 'days_ago' => 22, 'status' => 'active', 'progress' => 75],
            ['student_idx' => 2, 'course_idx' => 4, 'days_ago' => 12, 'status' => 'active', 'progress' => 30],
            ['student_idx' => 3, 'course_idx' => 0, 'days_ago' => 28, 'status' => 'completed', 'progress' => 100],
            ['student_idx' => 3, 'course_idx' => 5, 'days_ago' => 10, 'status' => 'active', 'progress' => 20],
            ['student_idx' => 4, 'course_idx' => 3, 'days_ago' => 19, 'status' => 'active', 'progress' => 65],
            ['student_idx' => 4, 'course_idx' => 4, 'days_ago' => 8, 'status' => 'active', 'progress' => 15],
        ];

        $totalRevenue = 0;
        foreach ($enrollmentScenarios as $scenario) {
            $student = $students[$scenario['student_idx']];
            $course = $courses[$scenario['course_idx']];
            
            // Skip free courses untuk payment
            if ($course->price == 0) continue;

            $transactionId = 'TRX-' . strtoupper(Str::random(12)) . '-' . time();
            $enrollDate = now()->subDays($scenario['days_ago']);
            
            // Create Enrollment
            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => $scenario['status'],
                'progress_percentage' => $scenario['progress'],
                'enrolled_at' => $enrollDate,
                'completed_at' => $scenario['status'] === 'completed' ? $enrollDate->addDays(rand(5, 15)) : null,
                'payment_status' => 'paid',
                'payment_amount' => $course->price,
            ]);

            // Create Payment
            $payment = Payment::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'enrollment_id' => $enrollment->id,
                'transaction_id' => $transactionId,
                'amount' => $course->price,
                'payment_method' => ['bank_transfer', 'gopay', 'qris'][rand(0, 2)],
                'status' => 'success',
                'paid_at' => $enrollDate,
                'created_at' => $enrollDate,
            ]);

            // Create Instructor Revenue (70%)
            $instructorShare = $course->price * 0.70;
            $platformShare = $course->price * 0.30;
            
            InstructorRevenue::create([
                'instructor_id' => $course->instructor_id,
                'payment_id' => $payment->id,
                'course_id' => $course->id,
                'course_price' => $course->price,
                'instructor_share' => $instructorShare,
                'platform_share' => $platformShare,
                'status' => 'available',
                'created_at' => $enrollDate,
            ]);

            $totalRevenue += $instructorShare;
        }

        echo "✓ Created " . count($enrollmentScenarios) . " enrollments with payments & revenues\n";
        echo "  Total instructor revenue generated: Rp " . number_format($totalRevenue, 0, ',', '.') . "\n\n";

        // ========================================
        // 4. CREATE SAMPLE WITHDRAWALS
        // ========================================
        
        echo "💰 Creating sample withdrawals...\n";

        // Ahmad (instructor1) - Pending withdrawal
        $ahmad_revenue = InstructorRevenue::where('instructor_id', $instructor1->id)
            ->where('status', 'available')
            ->sum('instructor_share');
        
        if ($ahmad_revenue >= 500000) {
            InstructorWithdrawal::create([
                'instructor_id' => $instructor1->id,
                'amount' => 500000,
                'status' => 'pending',
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'Dr. Ahmad Rizki',
                'notes' => 'Penarikan rutin bulanan',
                'created_at' => now()->subDays(2),
            ]);
            echo "✓ Pending withdrawal: Ahmad (Rp 500.000)\n";
        }

        // Siti (instructor2) - Approved withdrawal
        $siti_revenues = InstructorRevenue::where('instructor_id', $instructor2->id)
            ->where('status', 'available')
            ->orderBy('created_at', 'asc')
            ->limit(2)
            ->get();
        
        if ($siti_revenues->sum('instructor_share') >= 200000) {
            $withdrawal = InstructorWithdrawal::create([
                'instructor_id' => $instructor2->id,
                'amount' => 200000,
                'status' => 'approved',
                'bank_name' => 'Mandiri',
                'account_number' => '9876543210',
                'account_name' => 'Siti Nurhaliza',
                'notes' => 'Kebutuhan pribadi',
                'approved_by' => $bendahara->id,
                'approved_at' => now()->subDays(5),
                'created_at' => now()->subDays(7),
            ]);

            // Mark revenues as withdrawn
            $remainingAmount = 200000;
            foreach ($siti_revenues as $revenue) {
                if ($remainingAmount <= 0) break;
                
                if ($revenue->instructor_share <= $remainingAmount) {
                    $revenue->update([
                        'status' => 'withdrawn',
                        'withdrawal_id' => $withdrawal->id
                    ]);
                    $remainingAmount -= $revenue->instructor_share;
                }
            }
            echo "✓ Approved withdrawal: Siti (Rp 200.000)\n";
        }

        echo "\n";

        // ========================================
        // 5. SUMMARY
        // ========================================
        
        echo "=== SUMMARY ===\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "👥 USERS:\n";
        echo "   • 1 Admin\n";
        echo "   • 1 Bendahara\n";
        echo "   • 3 Instructors\n";
        echo "   • 10 Students\n";
        echo "\n";
        echo "📚 COURSES:\n";
        echo "   • Education: 2 courses (1 free)\n";
        echo "   • Language: 2 courses (1 free)\n";
        echo "   • IT: 2 courses (all paid)\n";
        echo "\n";
        echo "💰 REVENUE STATUS:\n";
        
        foreach ([$instructor1, $instructor2, $instructor3] as $instructor) {
            $available = InstructorRevenue::where('instructor_id', $instructor->id)
                ->where('status', 'available')
                ->sum('instructor_share');
            $withdrawn = InstructorRevenue::where('instructor_id', $instructor->id)
                ->where('status', 'withdrawn')
                ->sum('instructor_share');
            
            echo "   • {$instructor->name}:\n";
            echo "     Available: Rp " . number_format($available, 0, ',', '.') . "\n";
            echo "     Withdrawn: Rp " . number_format($withdrawn, 0, ',', '.') . "\n";
        }
        
        echo "\n";
        echo "🔐 LOGIN CREDENTIALS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Admin:      admin@edutech.com / password\n";
        echo "Bendahara:  bendahara.edutech@kim.com / password\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Instructor: ahmad@edutech.com / password (Education)\n";
        echo "            siti@edutech.com / password (Language)\n";
        echo "            budi@edutech.com / password (IT)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Students:   student1@edutech.com s/d student10@edutech.com\n";
        echo "            password (all students)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n✨ Seeder completed successfully!\n";
    }
}