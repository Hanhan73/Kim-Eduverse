<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class EdutechSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 1. CREATE USERS
        // ========================================

        // Admin
        $admin = User::create([
            'name' => 'Admin Edutech',
            'email' => 'admin@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'bio' => 'Administrator KIM Edutech Platform',
            'is_active' => true,
        ]);

        // Instructors
        $instructor1 = User::create([
            'name' => 'Dr. Ahmad Rizki, M.Pd',
            'email' => 'ahmad@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '081234567891',
            'bio' => 'Expert in Education Technology with 10+ years experience',
            'is_active' => true,
        ]);

        $instructor2 = User::create([
            'name' => 'Siti Nurhaliza, M.Pd',
            'email' => 'siti@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '081234567892',
            'bio' => 'Language Expert specializing in English and Arabic',
            'is_active' => true,
        ]);

        $instructor3 = User::create([
            'name' => 'Budi Santoso, S.Kom, M.T',
            'email' => 'budi@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '081234567893',
            'bio' => 'Software Engineer & IT Specialist',
            'is_active' => true,
        ]);

        $instructor4 = User::create([
            'name' => 'Rina Wijaya, S.Ds',
            'email' => 'rina@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '081234567894',
            'bio' => 'Professional Designer with 8+ years experience',
            'is_active' => true,
        ]);

        $instructor5 = User::create([
            'name' => 'Ir. Bambang Suryanto, M.M',
            'email' => 'bambang@edutech.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '081234567895',
            'bio' => 'Industrial Management & Quality Expert',
            'is_active' => true,
        ]);

        // Students
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@edutech.com",
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);
        }

        // ========================================
        // 2. CREATE COURSES
        // ========================================

        // KATEGORI: EDUCATION
        Course::create([
            'instructor_id' => $instructor1->id,
            'title' => 'CBTS - Classroom Based Training System',
            'slug' => 'cbts-classroom-training-system',
            'description' => 'Pelajari metodologi CBTS untuk meningkatkan efektivitas pembelajaran di kelas. Course ini mencakup teori dan praktik langsung yang dapat diterapkan di institusi pendidikan Anda.',
            'category' => 'Education',
            'level' => 'intermediate',
            'price' => 500000,
            'duration_hours' => 20,
            'max_students' => 30,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 70,
        ]);

        Course::create([
            'instructor_id' => $instructor1->id,
            'title' => 'Teknik Alba - Metode Pembelajaran Modern',
            'slug' => 'teknik-alba-metode-pembelajaran',
            'description' => 'Memahami dan menerapkan Teknik Alba dalam pembelajaran modern. Metode yang telah terbukti meningkatkan engagement siswa hingga 80%.',
            'category' => 'Education',
            'level' => 'intermediate',
            'price' => 750000,
            'duration_hours' => 25,
            'max_students' => 25,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 70,
        ]);

        Course::create([
            'instructor_id' => $instructor1->id,
            'title' => 'Media Pembelajaran Digital Interaktif',
            'slug' => 'media-pembelajaran-digital',
            'description' => 'Belajar membuat media pembelajaran digital yang menarik dan interaktif menggunakan berbagai tools modern seperti Canva, PowerPoint, dan aplikasi multimedia lainnya.',
            'category' => 'Education',
            'level' => 'beginner',
            'price' => 400000,
            'duration_hours' => 18,
            'max_students' => 40,
            'is_published' => true,
            'is_featured' => false,
            'passing_score' => 65,
        ]);

        Course::create([
            'instructor_id' => $instructor1->id,
            'title' => 'AI untuk Pendidikan - ChatGPT & Tools AI',
            'slug' => 'ai-untuk-pendidikan',
            'description' => 'Manfaatkan kekuatan AI untuk meningkatkan kualitas pembelajaran. Dari ChatGPT hingga AI image generators untuk materi ajar yang lebih menarik.',
            'category' => 'Education',
            'level' => 'beginner',
            'price' => 0, // GRATIS
            'duration_hours' => 12,
            'max_students' => 100,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 60,
        ]);

        // KATEGORI: LANGUAGE
        Course::create([
            'instructor_id' => $instructor2->id,
            'title' => 'English for Business Communication',
            'slug' => 'english-business-communication',
            'description' => 'Master English communication skills untuk dunia profesional. Dari email writing hingga presentation skills, semua ada di course ini.',
            'category' => 'Language',
            'level' => 'beginner',
            'price' => 0, // GRATIS
            'duration_hours' => 15,
            'max_students' => 50,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 60,
        ]);

        Course::create([
            'instructor_id' => $instructor2->id,
            'title' => 'Bahasa Arab untuk Pemula - Al-Quran & Conversation',
            'slug' => 'bahasa-arab-pemula',
            'description' => 'Belajar Bahasa Arab dari nol hingga mahir. Fokus pada pembacaan Al-Quran dan conversation sehari-hari.',
            'category' => 'Language',
            'level' => 'beginner',
            'price' => 450000,
            'duration_hours' => 30,
            'max_students' => 30,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 65,
        ]);

        // KATEGORI: TEKNOLOGI INFORMASI
        Course::create([
            'instructor_id' => $instructor3->id,
            'title' => 'Office Computer - Excel, Word, PowerPoint Mahir',
            'slug' => 'office-computer-excel-word-powerpoint',
            'description' => 'Kuasai Microsoft Office dari basic hingga advanced. Excel formulas, Word professional documents, dan PowerPoint presentations yang WOW!',
            'category' => 'Teknologi Informasi',
            'level' => 'beginner',
            'price' => 350000,
            'duration_hours' => 20,
            'max_students' => 50,
            'is_published' => true,
            'is_featured' => false,
            'passing_score' => 70,
        ]);

        Course::create([
            'instructor_id' => $instructor3->id,
            'title' => 'Web Development dengan Laravel - Full Stack',
            'slug' => 'web-development-laravel-fullstack',
            'description' => 'Belajar coding Laravel dari dasar hingga mahir. Dari routing, database, authentication, hingga deployment. Build real projects!',
            'category' => 'Teknologi Informasi',
            'level' => 'intermediate',
            'price' => 1200000,
            'duration_hours' => 50,
            'max_students' => 25,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 75,
        ]);

        Course::create([
            'instructor_id' => $instructor3->id,
            'title' => 'Python Programming - From Zero to Hero',
            'slug' => 'python-programming-zero-to-hero',
            'description' => 'Belajar Python dari nol. Perfect untuk pemula yang ingin masuk ke dunia programming atau data science.',
            'category' => 'Teknologi Informasi',
            'level' => 'beginner',
            'price' => 800000,
            'duration_hours' => 35,
            'max_students' => 40,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 70,
        ]);

        // KATEGORI: DESAIN
        Course::create([
            'instructor_id' => $instructor4->id,
            'title' => 'Desain Interior - Dari Konsep hingga Realisasi',
            'slug' => 'desain-interior-konsep-realisasi',
            'description' => 'Belajar desain interior dari konsep, space planning, material selection, hingga rendering 3D dengan SketchUp dan 3ds Max.',
            'category' => 'Desain',
            'level' => 'intermediate',
            'price' => 950000,
            'duration_hours' => 40,
            'max_students' => 20,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 70,
        ]);

        Course::create([
            'instructor_id' => $instructor4->id,
            'title' => 'DKV - Desain Grafis dengan Adobe Creative Suite',
            'slug' => 'dkv-desain-grafis-adobe',
            'description' => 'Kuasai Adobe Illustrator, Photoshop, dan InDesign untuk membuat desain grafis profesional. Dari logo design hingga branding.',
            'category' => 'Desain',
            'level' => 'beginner',
            'price' => 600000,
            'duration_hours' => 30,
            'max_students' => 35,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 70,
        ]);

        Course::create([
            'instructor_id' => $instructor4->id,
            'title' => 'UI/UX Design - Mobile & Web Apps',
            'slug' => 'uiux-design-mobile-web',
            'description' => 'Belajar UI/UX Design dengan Figma. Dari wireframing, prototyping, hingga user testing. Build portfolio-ready projects!',
            'category' => 'Desain',
            'level' => 'intermediate',
            'price' => 850000,
            'duration_hours' => 28,
            'max_students' => 30,
            'is_published' => true,
            'is_featured' => false,
            'passing_score' => 70,
        ]);

        // KATEGORI: MANAJEMEN DAN TEKNIK INDUSTRI
        Course::create([
            'instructor_id' => $instructor5->id,
            'title' => 'ISO 9001:2015 Quality Management System',
            'slug' => 'iso-9001-2015-quality-management',
            'description' => 'Pelajari standar ISO 9001:2015 dan cara implementasinya di organisasi Anda. Dari dokumentasi hingga audit internal.',
            'category' => 'Manajemen dan Teknik Industri',
            'level' => 'advanced',
            'price' => 1500000,
            'duration_hours' => 35,
            'max_students' => 20,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 80,
        ]);

        Course::create([
            'instructor_id' => $instructor5->id,
            'title' => '7 Tools Quality Control - Statistical Process Control',
            'slug' => '7-tools-quality-control',
            'description' => 'Kuasai 7 Tools QC untuk problem solving dan quality improvement: Check Sheet, Histogram, Pareto, Fishbone, Scatter Diagram, Control Chart, Stratification.',
            'category' => 'Manajemen dan Teknik Industri',
            'level' => 'intermediate',
            'price' => 700000,
            'duration_hours' => 24,
            'max_students' => 30,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 75,
        ]);

        Course::create([
            'instructor_id' => $instructor5->id,
            'title' => 'New 7 Tools - Management & Planning Tools',
            'slug' => 'new-7-tools-management-planning',
            'description' => 'Pelajari New 7 Tools untuk management dan planning: Affinity Diagram, Relations Diagram, Tree Diagram, Matrix Diagram, PDPC, Arrow Diagram, Matrix Data Analysis.',
            'category' => 'Manajemen dan Teknik Industri',
            'level' => 'advanced',
            'price' => 850000,
            'duration_hours' => 28,
            'max_students' => 25,
            'is_published' => true,
            'is_featured' => false,
            'passing_score' => 75,
        ]);

        Course::create([
            'instructor_id' => $instructor5->id,
            'title' => 'Quality Management - Six Sigma & Lean Manufacturing',
            'slug' => 'quality-management-six-sigma-lean',
            'description' => 'Comprehensive quality management course covering Six Sigma DMAIC methodology dan Lean Manufacturing principles untuk continuous improvement.',
            'category' => 'Manajemen dan Teknik Industri',
            'level' => 'advanced',
            'price' => 1800000,
            'duration_hours' => 45,
            'max_students' => 20,
            'is_published' => true,
            'is_featured' => true,
            'passing_score' => 80,
        ]);

        echo "✅ Edutech Seeder completed successfully!\n\n";
        echo "📧 Test Credentials:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Admin      : admin@edutech.com / password\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Instructor 1: ahmad@edutech.com / password (Education)\n";
        echo "Instructor 2: siti@edutech.com / password (Language)\n";
        echo "Instructor 3: budi@edutech.com / password (IT)\n";
        echo "Instructor 4: rina@edutech.com / password (Design)\n";
        echo "Instructor 5: bambang@edutech.com / password (Management)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Students   : student1@edutech.com s/d student10@edutech.com\n";
        echo "Password   : password (semua student)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "🎓 Created Courses:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📖 Education (4 courses)\n";
        echo "   - CBTS\n";
        echo "   - Teknik Alba\n";
        echo "   - Media Pembelajaran\n";
        echo "   - AI untuk Pendidikan (FREE)\n";
        echo "\n";
        echo "🗣️  Language (2 courses)\n";
        echo "   - English for Business (FREE)\n";
        echo "   - Bahasa Arab\n";
        echo "\n";
        echo "💻 Teknologi Informasi (3 courses)\n";
        echo "   - Office Computer\n";
        echo "   - Web Development Laravel\n";
        echo "   - Python Programming\n";
        echo "\n";
        echo "🎨 Desain (3 courses)\n";
        echo "   - Desain Interior\n";
        echo "   - DKV Adobe Creative Suite\n";
        echo "   - UI/UX Design\n";
        echo "\n";
        echo "📊 Manajemen & Teknik Industri (4 courses)\n";
        echo "   - ISO 9001:2015\n";
        echo "   - 7 Tools Quality Control\n";
        echo "   - New 7 Tools\n";
        echo "   - Quality Management Six Sigma\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✨ Total: 16 courses created!\n";
        echo "💰 Including 2 FREE courses\n";
    }
}
