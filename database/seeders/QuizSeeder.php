<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🌱 Seeding Quizzes...\n";

        // Get all published courses
        $courses = Course::where('is_published', true)->get();

        if ($courses->isEmpty()) {
            echo "⚠️ No published courses found! Please run CourseSeeder first.\n";
            return;
        }

        foreach ($courses as $course) {
            echo "📝 Adding quizzes to: {$course->title}\n";

            // ===================================
            // CREATE PRE-TEST
            // ===================================
            $preTest = Quiz::create([
                'course_id' => $course->id,
                'module_id' => null,
                'title' => 'Pre-Test: ' . $course->title,
                'type' => 'pre_test',
                'description' => 'Ujian awal untuk mengukur pemahaman dasar Anda sebelum memulai course ini.',
                'passing_score' => 60,
                'duration_minutes' => 30,
                'max_attempts' => 1,
                'randomize_questions' => true,
                'is_active' => true,
            ]);

            $this->createPrePostTestQuestions($preTest, $course);
            echo "   ✅ Pre-test created with " . $preTest->questions()->count() . " questions\n";

            // ===================================
            // CREATE POST-TEST (Identical questions)
            // ===================================
            $postTest = Quiz::create([
                'course_id' => $course->id,
                'module_id' => null,
                'title' => 'Post-Test: ' . $course->title,
                'type' => 'post_test',
                'description' => 'Ujian akhir untuk mengukur peningkatan pemahaman Anda setelah menyelesaikan course ini.',
                'passing_score' => 70,
                'duration_minutes' => 30,
                'max_attempts' => 3,
                'randomize_questions' => true,
                'is_active' => true,
            ]);

            // Copy questions from pre-test to post-test
            $this->copyQuestionsFromPreTest($preTest, $postTest);
            echo "   ✅ Post-test created with " . $postTest->questions()->count() . " questions (identical to pre-test)\n";

            // ===================================
            // CREATE MODULE QUIZZES
            // ===================================
            $modules = Module::where('course_id', $course->id)->get();

            foreach ($modules as $module) {
                $moduleQuiz = Quiz::create([
                    'course_id' => $course->id,
                    'module_id' => $module->id,
                    'title' => 'Quiz: ' . $module->title,
                    'type' => 'module_quiz',
                    'description' => 'Kuis untuk menguji pemahaman Anda terhadap materi ' . $module->title,
                    'passing_score' => 70,
                    'duration_minutes' => 15,
                    'max_attempts' => 3,
                    'randomize_questions' => false,
                    'is_active' => true,
                ]);

                $this->createModuleQuizQuestions($moduleQuiz, $module, $course);
                echo "   ✅ Module quiz created for '{$module->title}' with " . $moduleQuiz->questions()->count() . " questions\n";
            }
        }

        echo "\n🎉 Quiz seeding completed!\n";
        echo "📊 Summary:\n";
        echo "   - Total Quizzes: " . Quiz::count() . "\n";
        echo "   - Total Questions: " . QuizQuestion::count() . "\n";
        echo "   - Pre-tests: " . Quiz::where('type', 'pre_test')->count() . "\n";
        echo "   - Post-tests: " . Quiz::where('type', 'post_test')->count() . "\n";
        echo "   - Module quizzes: " . Quiz::where('type', 'module_quiz')->count() . "\n";
    }

    /**
     * Create Pre/Post Test Questions
     */
    private function createPrePostTestQuestions(Quiz $quiz, Course $course)
    {
        // Customize questions based on course category
        $category = strtolower($course->category);

        if (str_contains($category, 'teknologi') || str_contains($category, 'programming')) {
            $this->createTechQuestions($quiz);
        } elseif (str_contains($category, 'bisnis') || str_contains($category, 'manajemen')) {
            $this->createBusinessQuestions($quiz);
        } elseif (str_contains($category, 'desain') || str_contains($category, 'design')) {
            $this->createDesignQuestions($quiz);
        } else {
            $this->createGeneralQuestions($quiz);
        }
    }

    /**
     * Create Technology/Programming Questions
     * 
     * KUNCI JAWABAN:
     * 1. Model View Controller
     * 2. PHP
     * 3. Benar
     * 4. Version control system
     * 5. Benar
     * 6. Laravel
     * 7. Desain yang menyesuaikan dengan ukuran layar
     * 8. Salah
     * 9. Menghubungkan database dengan object oriented code
     * 10. Benar
     */
    private function createTechQuestions(Quiz $quiz)
    {
        $questions = [
            [
                'question' => 'Apa kepanjangan dari MVC dalam pengembangan web?',
                'type' => 'multiple_choice',
                'options' => ['Model View Controller', 'Multiple View Components', 'Main Visual Content', 'Modern Verified Code'],
                'correct_answer' => 'Model View Controller',
                'points' => 10,
            ],
            [
                'question' => 'Bahasa pemrograman mana yang digunakan untuk pengembangan web backend?',
                'type' => 'multiple_choice',
                'options' => ['HTML', 'CSS', 'PHP', 'Adobe Photoshop'],
                'correct_answer' => 'PHP',
                'points' => 10,
            ],
            [
                'question' => 'Apakah database relasional menggunakan SQL untuk query data?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah', 'Tergantung jenis database', 'Hanya untuk MySQL'],
                'correct_answer' => 'Benar',
                'points' => 10,
            ],
            [
                'question' => 'Apa fungsi utama dari Git dalam pengembangan software?',
                'type' => 'multiple_choice',
                'options' => ['Membuat desain grafis', 'Version control system', 'Database management', 'Web hosting'],
                'correct_answer' => 'Version control system',
                'points' => 10,
            ],
            [
                'question' => 'Apakah RESTful API menggunakan protokol HTTP untuk komunikasi?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah', 'Hanya HTTPS', 'Menggunakan FTP'],
                'correct_answer' => 'Benar',
                'points' => 10,
            ],
            [
                'question' => 'Framework PHP populer untuk pengembangan web adalah?',
                'type' => 'multiple_choice',
                'options' => ['Laravel', 'Bootstrap', 'jQuery', 'React'],
                'correct_answer' => 'Laravel',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan responsive design?',
                'type' => 'multiple_choice',
                'options' => ['Desain yang cepat loading', 'Desain yang menyesuaikan dengan ukuran layar', 'Desain dengan banyak warna', 'Desain yang interaktif'],
                'correct_answer' => 'Desain yang menyesuaikan dengan ukuran layar',
                'points' => 10,
            ],
            [
                'question' => 'Apakah JavaScript hanya bisa dijalankan di browser?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah, bisa di server dengan Node.js', 'Hanya di Chrome', 'Hanya di Firefox'],
                'correct_answer' => 'Salah, bisa di server dengan Node.js',
                'points' => 10,
            ],
            [
                'question' => 'Apa fungsi dari ORM (Object-Relational Mapping)?',
                'type' => 'multiple_choice',
                'options' => ['Menghubungkan database dengan object oriented code', 'Membuat tampilan website', 'Mengoptimasi gambar', 'Mengelola server'],
                'correct_answer' => 'Menghubungkan database dengan object oriented code',
                'points' => 10,
            ],
            [
                'question' => 'Apa kepanjangan dari API?',
                'type' => 'multiple_choice',
                'options' => ['Application Programming Interface', 'Advanced Program Integration', 'Automatic Process Indicator', 'Applied Programming Instructions'],
                'correct_answer' => 'Application Programming Interface',
                'points' => 10,
            ],
        ];

        $this->insertQuestions($quiz, $questions);
    }

    /**
     * Create Business/Management Questions
     */
    private function createBusinessQuestions(Quiz $quiz)
    {
        $questions = [
            [
                'question' => 'Apa yang dimaksud dengan SWOT Analysis?',
                'type' => 'multiple_choice',
                'options' => ['Sales, Work, Order, Target', 'Strengths, Weaknesses, Opportunities, Threats', 'System Work Operational Tool', 'Strategic Work Output Target'],
                'correct_answer' => 'Strengths, Weaknesses, Opportunities, Threats',
                'points' => 10,
            ],
            [
                'question' => 'Apakah manajemen strategis hanya fokus pada jangka pendek?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah, fokus jangka panjang', 'Tergantung perusahaan', 'Fokus jangka menengah'],
                'correct_answer' => 'Salah, fokus jangka panjang',
                'points' => 10,
            ],
            [
                'question' => 'Apa fungsi utama dari marketing mix 4P?',
                'type' => 'multiple_choice',
                'options' => ['Mengelola karyawan', 'Product, Price, Place, Promotion', 'Membuat laporan keuangan', 'Mengatur jadwal kerja'],
                'correct_answer' => 'Product, Price, Place, Promotion',
                'points' => 10,
            ],
            [
                'question' => 'Apa kepanjangan dari KPI?',
                'type' => 'multiple_choice',
                'options' => ['Key Performance Indicator', 'Knowledge Process Integration', 'Kinetic Process Index', 'Key Product Innovation'],
                'correct_answer' => 'Key Performance Indicator',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan Break-Even Point?',
                'type' => 'multiple_choice',
                'options' => ['Titik maksimal keuntungan', 'Titik dimana pendapatan sama dengan biaya', 'Titik terendah penjualan', 'Titik tertinggi produksi'],
                'correct_answer' => 'Titik dimana pendapatan sama dengan biaya',
                'points' => 10,
            ],
            [
                'question' => 'Apakah leadership dan management adalah hal yang sama?',
                'type' => 'multiple_choice',
                'options' => ['Benar, sama saja', 'Salah, berbeda fokus dan pendekatan', 'Hanya berbeda istilah', 'Tergantung industri'],
                'correct_answer' => 'Salah, berbeda fokus dan pendekatan',
                'points' => 10,
            ],
            [
                'question' => 'Metode analisis keuangan untuk menilai investasi adalah?',
                'type' => 'multiple_choice',
                'options' => ['SWOT Analysis', 'PESTEL Analysis', 'NPV dan ROI', 'Porter Five Forces'],
                'correct_answer' => 'NPV dan ROI',
                'points' => 10,
            ],
            [
                'question' => 'Berapa jumlah building blocks dalam Business Model Canvas?',
                'type' => 'multiple_choice',
                'options' => ['7 blocks', '9 blocks', '12 blocks', '5 blocks'],
                'correct_answer' => '9 blocks',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan Blue Ocean Strategy?',
                'type' => 'multiple_choice',
                'options' => ['Strategi bersaing di pasar yang ramai', 'Strategi menciptakan pasar baru tanpa kompetisi', 'Strategi menguasai market share', 'Strategi menurunkan harga'],
                'correct_answer' => 'Strategi menciptakan pasar baru tanpa kompetisi',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang ditunjukkan oleh cash flow statement?',
                'type' => 'multiple_choice',
                'options' => ['Aliran kas perusahaan', 'Laba rugi perusahaan', 'Aset dan liabilitas', 'Market share'],
                'correct_answer' => 'Aliran kas perusahaan',
                'points' => 10,
            ],
        ];

        $this->insertQuestions($quiz, $questions);
    }

    /**
     * Create Design Questions
     */
    private function createDesignQuestions(Quiz $quiz)
    {
        $questions = [
            [
                'question' => 'Apa yang dimaksud dengan prinsip desain "Balance"?',
                'type' => 'multiple_choice',
                'options' => ['Keseimbangan visual dalam komposisi', 'Penggunaan warna yang sama', 'Ukuran yang sama untuk semua elemen', 'Font yang konsisten'],
                'correct_answer' => 'Keseimbangan visual dalam komposisi',
                'points' => 10,
            ],
            [
                'question' => 'Apakah RGB adalah color mode untuk media cetak?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah, RGB untuk digital', 'Tergantung printer', 'Bisa untuk keduanya'],
                'correct_answer' => 'Salah, RGB untuk digital',
                'points' => 10,
            ],
            [
                'question' => 'Apa kepanjangan dari UI/UX Design?',
                'type' => 'multiple_choice',
                'options' => ['User Interface / User Experience', 'Universal Integration / User Extension', 'Unique Illustration / User Expert', 'Unified Interface / Ultimate Experience'],
                'correct_answer' => 'User Interface / User Experience',
                'points' => 10,
            ],
            [
                'question' => 'Apakah typography hanya berkaitan dengan pemilihan font?',
                'type' => 'multiple_choice',
                'options' => ['Benar', 'Salah, termasuk spacing, size, hierarchy', 'Hanya untuk print design', 'Hanya untuk web design'],
                'correct_answer' => 'Salah, termasuk spacing, size, hierarchy',
                'points' => 10,
            ],
            [
                'question' => 'Resolusi standar untuk desain web adalah?',
                'type' => 'multiple_choice',
                'options' => ['300 DPI', '72 PPI', '150 DPI', '600 DPI'],
                'correct_answer' => '72 PPI',
                'points' => 10,
            ],
            [
                'question' => 'Apakah white space dalam desain memiliki fungsi penting?',
                'type' => 'multiple_choice',
                'options' => ['Tidak penting', 'Sangat penting untuk readability dan fokus', 'Hanya untuk desain minimalis', 'Hanya pemborosan ruang'],
                'correct_answer' => 'Sangat penting untuk readability dan fokus',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan "Hierarchy" dalam desain?',
                'type' => 'multiple_choice',
                'options' => ['Pengaturan lapisan di software desain', 'Urutan kepentingan visual elemen', 'Struktur folder file', 'Tingkatan kualitas gambar'],
                'correct_answer' => 'Urutan kepentingan visual elemen',
                'points' => 10,
            ],
            [
                'question' => 'Apakah vector graphic dapat di-scale tanpa kehilangan kualitas?',
                'type' => 'multiple_choice',
                'options' => ['Tidak bisa', 'Bisa, tanpa kehilangan kualitas', 'Hanya sampai 200%', 'Tergantung software'],
                'correct_answer' => 'Bisa, tanpa kehilangan kualitas',
                'points' => 10,
            ],
            [
                'question' => 'Apa perbedaan utama CMYK dan RGB?',
                'type' => 'multiple_choice',
                'options' => ['CMYK untuk cetak, RGB untuk digital', 'CMYK untuk digital, RGB untuk cetak', 'Tidak ada perbedaan', 'CMYK lebih bagus dari RGB'],
                'correct_answer' => 'CMYK untuk cetak, RGB untuk digital',
                'points' => 10,
            ],
            [
                'question' => 'Apa fungsi Gestalt principles dalam desain?',
                'type' => 'multiple_choice',
                'options' => ['Memilih warna', 'Memahami persepsi visual manusia', 'Membuat logo', 'Mengedit foto'],
                'correct_answer' => 'Memahami persepsi visual manusia',
                'points' => 10,
            ],
        ];

        $this->insertQuestions($quiz, $questions);
    }

    /**
     * Create General Questions (fallback)
     */
    private function createGeneralQuestions(Quiz $quiz)
    {
        $questions = [
            [
                'question' => 'Apakah pembelajaran online memberikan fleksibilitas waktu dan tempat belajar?',
                'type' => 'multiple_choice',
                'options' => ['Tidak', 'Ya, sangat fleksibel', 'Hanya waktu saja', 'Hanya tempat saja'],
                'correct_answer' => 'Ya, sangat fleksibel',
                'points' => 10,
            ],
            [
                'question' => 'Apa manfaat utama dari continuous learning?',
                'type' => 'multiple_choice',
                'options' => ['Mendapat sertifikat banyak', 'Meningkatkan kompetensi dan adaptasi dengan perubahan', 'Menghabiskan waktu luang', 'Menambah teman'],
                'correct_answer' => 'Meningkatkan kompetensi dan adaptasi dengan perubahan',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan critical thinking?',
                'type' => 'multiple_choice',
                'options' => ['Mengkritik orang lain', 'Kemampuan menganalisis informasi secara objektif', 'Berpikir negatif', 'Menghafal fakta'],
                'correct_answer' => 'Kemampuan menganalisis informasi secara objektif',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan self-directed learning?',
                'type' => 'multiple_choice',
                'options' => ['Belajar dengan guru', 'Belajar secara mandiri dengan inisiatif sendiri', 'Belajar dalam kelompok', 'Belajar dengan buku'],
                'correct_answer' => 'Belajar secara mandiri dengan inisiatif sendiri',
                'points' => 10,
            ],
            [
                'question' => 'Apakah problem solving skill perlu dilatih?',
                'type' => 'multiple_choice',
                'options' => ['Tidak perlu', 'Ya, perlu dilatih terus menerus', 'Hanya untuk pekerjaan tertentu', 'Sudah bawaan lahir'],
                'correct_answer' => 'Ya, perlu dilatih terus menerus',
                'points' => 10,
            ],
            [
                'question' => 'Metode pembelajaran aktif yang efektif adalah?',
                'type' => 'multiple_choice',
                'options' => ['Hanya mendengarkan ceramah', 'Praktek dan diskusi', 'Menghafal materi', 'Membaca buku saja'],
                'correct_answer' => 'Praktek dan diskusi',
                'points' => 10,
            ],
            [
                'question' => 'Apakah feedback penting dalam proses pembelajaran?',
                'type' => 'multiple_choice',
                'options' => ['Tidak penting', 'Sangat penting untuk perbaikan', 'Hanya membuat stress', 'Hanya untuk ujian'],
                'correct_answer' => 'Sangat penting untuk perbaikan',
                'points' => 10,
            ],
            [
                'question' => 'Apa tujuan dari reflective learning?',
                'type' => 'multiple_choice',
                'options' => ['Mengulang materi', 'Mengevaluasi dan memperbaiki proses belajar', 'Mendapat nilai tinggi', 'Menyelesaikan tugas cepat'],
                'correct_answer' => 'Mengevaluasi dan memperbaiki proses belajar',
                'points' => 10,
            ],
            [
                'question' => 'Apakah kolaborasi dalam pembelajaran bermanfaat?',
                'type' => 'multiple_choice',
                'options' => ['Tidak bermanfaat', 'Sangat bermanfaat untuk pemahaman', 'Hanya membuang waktu', 'Membuat bingung'],
                'correct_answer' => 'Sangat bermanfaat untuk pemahaman',
                'points' => 10,
            ],
            [
                'question' => 'Apa yang dimaksud dengan growth mindset?',
                'type' => 'multiple_choice',
                'options' => ['Kemampuan sudah tetap sejak lahir', 'Keyakinan bahwa kemampuan dapat dikembangkan', 'Fokus pada hasil saja', 'Takut gagal'],
                'correct_answer' => 'Keyakinan bahwa kemampuan dapat dikembangkan',
                'points' => 10,
            ],
        ];

        $this->insertQuestions($quiz, $questions);
    }

    /**
     * Create Module Quiz Questions
     */
    private function createModuleQuizQuestions(Quiz $quiz, Module $module, Course $course)
    {
        // Generate 5 questions per module quiz
        $questions = [
            [
                'question' => "Apa konsep utama yang dipelajari dalam modul '{$module->title}'?",
                'type' => 'multiple_choice',
                'options' => ['Konsep dasar dan fundamental', 'Teknik advanced', 'Best practices', 'Semua benar'],
                'correct_answer' => 'Semua benar',
                'points' => 20,
            ],
            [
                'question' => "Materi dalam modul '{$module->title}' dapat langsung dipraktekkan.",
                'type' => 'true_false',
                'options' => ['True', 'False'],
                'correct_answer' => 'True',
                'points' => 20,
            ],
            [
                'question' => "Penerapan utama dari '{$module->title}' dalam dunia nyata adalah?",
                'type' => 'multiple_choice',
                'options' => ['Meningkatkan efisiensi', 'Menyelesaikan masalah kompleks', 'Mengoptimalkan proses', 'Semua benar'],
                'correct_answer' => 'Semua benar',
                'points' => 20,
            ],
            [
                'question' => "Tools atau teknologi yang relevan dengan modul ini perlu dikuasai.",
                'type' => 'true_false',
                'options' => ['True', 'False'],
                'correct_answer' => 'True',
                'points' => 20,
            ],
            [
                'question' => "Apa langkah selanjutnya setelah menguasai '{$module->title}'?",
                'type' => 'multiple_choice',
                'options' => ['Praktik lebih banyak', 'Lanjut ke modul berikutnya', 'Eksplorasi topik lanjutan', 'Semua benar'],
                'correct_answer' => 'Semua benar',
                'points' => 20,
            ],
        ];

        $this->insertQuestions($quiz, $questions);
    }

    /**
     * Copy questions from pre-test to post-test
     */
    private function copyQuestionsFromPreTest(Quiz $preTest, Quiz $postTest)
    {
        $preTestQuestions = $preTest->questions()->orderBy('order')->get();

        foreach ($preTestQuestions as $question) {
            QuizQuestion::create([
                'quiz_id' => $postTest->id,
                'question' => $question->question,
                'type' => $question->type,
                'options' => $question->options,
                'correct_answer' => $question->correct_answer,
                'points' => $question->points,
                'order' => $question->order,
            ]);
        }
    }

    /**
     * Insert questions to quiz
     */
    private function insertQuestions(Quiz $quiz, array $questions)
    {
        foreach ($questions as $index => $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'],
                'correct_answer' => $questionData['correct_answer'],
                'points' => $questionData['points'],
                'order' => $index + 1,
            ]);
        }
    }
}
