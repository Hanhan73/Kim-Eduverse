<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;

class ModuleLessonSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Seeding Modules & Lessons...\n";

        // Get existing courses or create one
        $courses = Course::where('is_published', true)->get();

        if ($courses->isEmpty()) {
            echo "⚠️ No courses found! Creating sample course...\n";
            
            $instructor = User::where('role', 'instructor')->first();
            if (!$instructor) {
                echo "❌ No instructor found! Please run EdutechSeeder first.\n";
                return;
            }

            $courses = collect([
                Course::create([
                    'instructor_id' => $instructor->id,
                    'title' => 'Laravel Untuk Pemula',
                    'slug' => 'laravel-untuk-pemula',
                    'description' => 'Belajar Laravel dari dasar sampai mahir',
                    'category' => 'Teknologi Informasi',
                    'level' => 'beginner',
                    'price' => 0,
                    'duration_hours' => 10,
                    'is_published' => true,
                    'is_featured' => true,
                ])
            ]);
        }

        foreach ($courses->take(2) as $course) {
            echo "📚 Adding modules to: {$course->title}\n";

            // Module 1: Introduction
            $module1 = Module::create([
                'course_id' => $course->id,
                'title' => 'Pengenalan Laravel',
                'description' => 'Memahami dasar-dasar Laravel framework',
                'order' => 1,
                'duration_minutes' => 120,
                'is_published' => true,
            ]);

            // Lessons for Module 1
            Lesson::create([
                'module_id' => $module1->id,
                'title' => 'Apa itu Laravel?',
                'description' => 'Pengenalan tentang Laravel framework dan keunggulannya',
                'order' => 1,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE',
                'video_id' => 'ImtZ5yENzgE', // Laravel Tutorial for Beginners
                'duration_minutes' => 30,
                'is_preview' => true,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module1->id,
                'title' => 'Instalasi Laravel',
                'description' => 'Cara menginstall Laravel di komputer Anda',
                'order' => 2,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                'video_id' => 'MYyJ4PuL4pY', // Laravel Installation
                'duration_minutes' => 25,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module1->id,
                'title' => 'Struktur Folder Laravel',
                'description' => 'Memahami struktur folder dan file di Laravel',
                'order' => 3,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=376vZ1wNYPA',
                'video_id' => '376vZ1wNYPA', // Laravel Folder Structure
                'duration_minutes' => 20,
                'is_published' => true,
                'file_path' => 'https://drive.google.com/file/d/1example/view', // Example PDF link
            ]);

            // Module 2: Routing & Controllers
            $module2 = Module::create([
                'course_id' => $course->id,
                'title' => 'Routing & Controllers',
                'description' => 'Belajar routing dan controllers di Laravel',
                'order' => 2,
                'duration_minutes' => 90,
                'is_published' => true,
            ]);

            // Lessons for Module 2
            Lesson::create([
                'module_id' => $module2->id,
                'title' => 'Dasar Routing',
                'description' => 'Memahami konsep routing di Laravel',
                'order' => 1,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=_EWRdVBEGPQ',
                'video_id' => '_EWRdVBEGPQ', // Laravel Routing
                'duration_minutes' => 25,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module2->id,
                'title' => 'Membuat Controller',
                'description' => 'Cara membuat dan menggunakan controller',
                'order' => 2,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=0urHl2i6h2M',
                'video_id' => '0urHl2i6h2M', // Laravel Controllers
                'duration_minutes' => 30,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module2->id,
                'title' => 'Route Parameters',
                'description' => 'Menggunakan parameter di routing',
                'order' => 3,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=0zB75pLFxeM',
                'video_id' => '0zB75pLFxeM', // Route Parameters
                'duration_minutes' => 20,
                'is_published' => true,
            ]);

            // Module 3: Database & Eloquent
            $module3 = Module::create([
                'course_id' => $course->id,
                'title' => 'Database & Eloquent ORM',
                'description' => 'Belajar database dan Eloquent ORM',
                'order' => 3,
                'duration_minutes' => 150,
                'is_published' => true,
            ]);

            // Lessons for Module 3
            Lesson::create([
                'module_id' => $module3->id,
                'title' => 'Migration Database',
                'description' => 'Membuat dan menjalankan migration',
                'order' => 1,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=Sm_dbzN87Oo',
                'video_id' => 'Sm_dbzN87Oo', // Laravel Migration
                'duration_minutes' => 30,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module3->id,
                'title' => 'Eloquent Model',
                'description' => 'Membuat dan menggunakan Eloquent Model',
                'order' => 2,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=3ngy_6T5t2E',
                'video_id' => '3ngy_6T5t2E', // Eloquent Model
                'duration_minutes' => 35,
                'is_published' => true,
            ]);

            Lesson::create([
                'module_id' => $module3->id,
                'title' => 'Eloquent Relationships',
                'description' => 'Memahami relationship di Eloquent',
                'order' => 3,
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=DCYkfb8V7Rg',
                'video_id' => 'DCYkfb8V7Rg', // Eloquent Relationships
                'duration_minutes' => 40,
                'is_published' => true,
            ]);

            echo "✅ Added 3 modules with 9 lessons to: {$course->title}\n";
        }

        echo "\n🎉 Seeding completed!\n";
        echo "📊 Summary:\n";
        echo "   - Modules: " . Module::count() . "\n";
        echo "   - Lessons: " . Lesson::count() . "\n";
        echo "\n💡 Now you can test the learning page!\n";
    }
}