<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            DigitalProductCategorySeeder::class,
            IBAQuestionnaireSeeder::class,
            DigitalProductSeeder::class,
            AIGuruSeminarSeeder::class,
            DigitalAdminSeeder::class,
            EdutechSeeder::class,
            ModuleLessonSeeder::class,
            QuestionnaireAISeeder::class,
            QuizSeeder::class,
        ]);
    }
}
