<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seminar;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\DigitalProduct;
use Illuminate\Support\Str;

class AIGuruSeminarSeeder extends Seeder
{
    public function run()
    {
        echo "🎓 Membuat Seminar: AI untuk Guru...\n";
        $categoryId = 2;

        // ========================================
        // 1. BUAT PRODUK UNTUK SEMINAR
        // ========================================
        $product = DigitalProduct::create([
            'category_id' => $categoryId,
            'name' => 'AI untuk Guru: Panduan Praktis',
            'slug' => 'ai-untuk-guru',
            'description' => 'On-demand seminar untuk guru dalam memanfaatkan AI seperti ChatGPT, Gemini, dan Copilot dalam pembelajaran.',
            'features' => [
                'On-demand seminar durasi 90 menit',
                'Tutorial penggunaan ChatGPT, Gemini, Copilot',
                'Contoh prompt engineering untuk guru',
                'Sertifikat penyelesaian',
            ],
            'price' => 150000,
            'type' => 'seminar',
            'thumbnail' => 'default-seminar.jpg',
            'duration_minutes' => 90,
            'is_active' => true,
            'is_featured' => true,
            'order' => 1,
            'sold_count' => 0,
        ]);

        // ========================================
        // 1. BUAT SEMINAR
        // ========================================
        $seminar = Seminar::create([
            'title' => 'AI untuk Guru: Panduan Praktis Mengintegrasikan Kecerdasan Buatan dalam Pembelajaran',
            'slug' => 'ai-untuk-guru',
            'description' => 'On-demand seminar komprehensif yang dirancang khusus untuk para pendidik yang ingin memahami dan memanfaatkan teknologi Artificial Intelligence (AI) dalam praktik pembelajaran sehari-hari. Materi mencakup pengenalan platform AI populer seperti ChatGPT, Google Gemini, dan Microsoft Copilot, serta teknik prompt engineering untuk menghasilkan konten pembelajaran berkualitas.',
            'instructor_name' => 'Tim Pengembang KIM Eduverse',
            'instructor_bio' => 'Tim ahli teknologi pendidikan yang berpengalaman dalam pengembangan solusi pembelajaran digital dan integrasi AI dalam dunia pendidikan Indonesia.',
            'material_pdf_path' => 'https://drive.google.com/file/d/1DIkvS2cj8e3L9jHvkyv4dM1Cz9RylUw_/view?usp=sharing',
            'material_description' => 'Materi lengkap mencakup: Pengenalan AI dalam Pendidikan, Platform AI untuk Guru, Teknik Prompt Engineering, Pembuatan RPP dengan AI, Pembuatan Soal HOTS dengan AI, serta Best Practices dan Etika Penggunaan AI.',
            'price' => 150000,
            'duration_minutes' => 120,
            'is_active' => true,
            'is_featured' => true,
            'sold_count' => 0,
            'order' => 1,
            'certificate_template' => 'modern',
            'product_id' => $product->id,
        ]);

        echo "✅ Seminar berhasil dibuat: {$seminar->title}\n";

        // ========================================
        // 2. BUAT PRE-TEST
        // ========================================
        $preTest = Quiz::create([
            'quizable_type' => Seminar::class,
            'quizable_id' => $seminar->id,
            'title' => 'Pre-Test: Evaluasi Awal Pemahaman AI untuk Guru',
            'description' => 'Tes awal untuk mengukur pemahaman Anda tentang penggunaan AI dalam pendidikan sebelum mengikuti seminar. Passing grade: 70% (minimal 11 jawaban benar dari 15 soal).',
            'type' => 'pre_test',
            'duration_minutes' => 30,
            'passing_score' => 70,
            'max_attempts' => 3,
            'randomize_questions' => true,
            'is_active' => true,
        ]);

        // Update seminar dengan pre_test_id
        $seminar->update(['pre_test_id' => $preTest->id]);

        echo "✅ Pre-Test berhasil dibuat: {$preTest->title}\n";

        // ========================================
        // 3. BUAT POST-TEST
        // ========================================
        $postTest = Quiz::create([
            'quizable_type' => Seminar::class,
            'quizable_id' => $seminar->id,
            'title' => 'Post-Test: Evaluasi Akhir Pemahaman AI untuk Guru',
            'description' => 'Tes akhir untuk mengukur pemahaman Anda setelah mengikuti seminar. Anda harus lulus post-test untuk mendapatkan sertifikat. Passing grade: 70% (minimal 11 jawaban benar dari 15 soal).',
            'type' => 'post_test',
            'duration_minutes' => 30,
            'passing_score' => 70,
            'max_attempts' => 3,
            'randomize_questions' => true,
            'is_active' => true,
        ]);

        // Update seminar dengan post_test_id
        $seminar->update(['post_test_id' => $postTest->id]);

        echo "✅ Post-Test berhasil dibuat: {$postTest->title}\n";

        // ========================================
        // 4. BUAT PERTANYAAN (15 SOAL)
        // ========================================
        $questions = $this->getQuestions();

        echo "📝 Menambahkan {$questions->count()} pertanyaan ke Pre-Test dan Post-Test...\n";

        $order = 1;
        foreach ($questions as $q) {
            // Tambahkan ke Pre-Test
            QuizQuestion::create([
                'quiz_id' => $preTest->id,
                'question' => $q['question'],
                'type' => 'multiple_choice',
                'options' => [
                    'A' => $q['option_a'],
                    'B' => $q['option_b'],
                    'C' => $q['option_c'],
                    'D' => $q['option_d'],
                    'E' => $q['option_e'],
                ],
                'correct_answer' => $q['correct_answer'],
                'points' => $q['points'],
                'order' => $order,
            ]);

            // Tambahkan ke Post-Test (soal sama)
            QuizQuestion::create([
                'quiz_id' => $postTest->id,
                'question' => $q['question'],
                'type' => 'multiple_choice',
                'options' => [
                    'A' => $q['option_a'],
                    'B' => $q['option_b'],
                    'C' => $q['option_c'],
                    'D' => $q['option_d'],
                    'E' => $q['option_e'],
                ],
                'correct_answer' => $q['correct_answer'],
                'points' => $q['points'],
                'order' => $order,
            ]);

            $order++;
        }

        echo "✅ Semua pertanyaan berhasil ditambahkan!\n\n";



        echo "🛒 Produk berhasil dibuat: {$product->title}\n";

        // ========================================
        // SUMMARY
        // ========================================
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📊 SUMMARY SEEDER\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🎓 Seminar: {$seminar->title}\n";
        echo "💰 Harga: Rp " . number_format($seminar->price, 0, ',', '.') . "\n";
        echo "⏱️  Durasi: {$seminar->duration_minutes} menit\n";
        echo "📋 Pre-Test: {$preTest->questions()->count()} soal\n";
        echo "📋 Post-Test: {$postTest->questions()->count()} soal\n";
        echo "✅ Passing Grade: {$preTest->passing_score}%\n";
        echo "🔄 Max Attempts: {$preTest->max_attempts}x\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🔗 Material PDF: {$seminar->material_pdf_path}\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    /**
     * Get all questions data
     */
    private function getQuestions()
    {
        return collect([
            // ========================================
            // BAGIAN I: SOAL TINGKAT MUDAH (1-5)
            // ========================================
            [
                'question' => 'Dalam konteks pendidikan, Artificial Intelligence (AI) paling tepat didefinisikan sebagai:',
                'option_a' => 'Sistem pembelajaran adaptif yang secara otomatis menyesuaikan tingkat kesulitan materi berdasarkan respons peserta didik',
                'option_b' => 'Teknologi komputasi yang memungkinkan mesin melakukan tugas kognitif seperti pemahaman bahasa dan pengenalan pola',
                'option_c' => 'Platform digital yang mengintegrasikan berbagai tools pendidikan untuk meningkatkan efisiensi administrasi sekolah',
                'option_d' => 'Aplikasi berbasis cloud yang menyediakan konten pembelajaran interaktif dan assessment otomatis untuk guru',
                'option_e' => 'Software pendidikan yang menggunakan algoritma untuk menganalisis data performa siswa dan memberikan rekomendasi',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'mudah',
            ],
            [
                'question' => 'Di antara platform AI berikut, yang dikembangkan oleh Google dan memiliki keunggulan dalam akses informasi terkini serta integrasi dengan Google Workspace adalah:',
                'option_a' => 'ChatGPT yang dikembangkan OpenAI dengan kemampuan conversational dan pemahaman konteks percakapan panjang',
                'option_b' => 'Microsoft Copilot yang terintegrasi dengan aplikasi Office seperti Word, PowerPoint, dan Excel',
                'option_c' => 'Google Gemini yang dapat mengakses informasi up-to-date dan terhubung dengan ekosistem Google',
                'option_d' => 'Canva AI yang memiliki fitur text-to-image generation untuk membuat visual pembelajaran menarik',
                'option_e' => 'Claude yang dikembangkan Anthropic dengan fokus pada keamanan dan pemahaman instruksi kompleks',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'mudah',
            ],
            [
                'question' => 'Ketika guru pertama kali ingin menggunakan platform AI seperti ChatGPT atau Google Gemini, langkah awal yang paling esensial adalah:',
                'option_a' => 'Mengikuti workshop atau pelatihan formal tentang AI literacy selama beberapa hari untuk memahami fundamental',
                'option_b' => 'Mempersiapkan komputer dengan spesifikasi tinggi dan bandwidth internet minimal 10 Mbps untuk akses optimal',
                'option_c' => 'Mempersiapkan perangkat dengan koneksi internet stabil dan mendaftar akun menggunakan alamat email aktif',
                'option_d' => 'Mempelajari dokumentasi teknis platform AI dan memahami cara kerja algoritma machine learning yang mendasarinya',
                'option_e' => 'Berkonsultasi dengan tim IT sekolah untuk setup dan konfigurasi sistem AI sesuai kebijakan institusi',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'mudah',
            ],
            [
                'question' => 'Manfaat paling signifikan dari penggunaan AI bagi guru dalam praktik pembelajaran sehari-hari adalah:',
                'option_a' => 'Mengotomasi proses penilaian sehingga guru tidak perlu lagi memberikan feedback manual kepada peserta didik',
                'option_b' => 'Mengurangi interaksi langsung dengan peserta didik karena AI dapat menjawab sebagian besar pertanyaan mereka',
                'option_c' => 'Meningkatkan efisiensi dalam tugas administratif sehingga guru memiliki lebih banyak waktu untuk interaksi pedagogis',
                'option_d' => 'Menstandarisasi metode pengajaran agar semua guru menggunakan pendekatan yang sama dan konsisten',
                'option_e' => 'Menggantikan kebutuhan guru untuk melakukan persiapan pembelajaran karena AI dapat menghasilkan semuanya',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'mudah',
            ],
            [
                'question' => 'Pernyataan yang merupakan MITOS (kesalahpahaman) tentang AI dalam pendidikan adalah:',
                'option_a' => 'AI dapat membantu guru menghasilkan variasi soal penilaian dengan berbagai tingkat kesulitan secara efisien',
                'option_b' => 'Platform AI modern dirancang dengan interface yang sederhana sehingga mudah digunakan oleh non-teknisi',
                'option_c' => 'AI memiliki kemampuan untuk memahami dan merespons instruksi yang diberikan dalam bahasa natural',
                'option_d' => 'Penggunaan AI dalam pembelajaran akan membuat guru kehilangan kemampuan berpikir kreatif dan inovatif',
                'option_e' => 'Banyak platform AI seperti ChatGPT dan Gemini tersedia dalam versi gratis yang fungsional',
                'correct_answer' => 'D',
                'points' => 10,
                'difficulty' => 'mudah',
            ],

            // ========================================
            // BAGIAN II: SOAL TINGKAT SEDANG (6-10)
            // ========================================
            [
                'question' => 'Dalam proses penyusunan Rencana Pelaksanaan Pembelajaran (RPP) dengan bantuan AI, peran yang paling tepat bagi guru adalah:',
                'option_a' => 'Menerima output AI sebagai produk final karena AI telah dilatih dengan data pembelajaran berkualitas tinggi',
                'option_b' => 'Menggunakan AI hanya untuk mendapatkan inspirasi ide, lalu menyusun RPP sepenuhnya secara manual dari awal',
                'option_c' => 'Menggunakan AI untuk menghasilkan draft awal, kemudian melakukan review, validasi, dan personalisasi sesuai konteks',
                'option_d' => 'Memberikan seluruh informasi kepada AI dan membiarkan AI menentukan pendekatan pembelajaran yang optimal',
                'option_e' => 'Membandingkan output dari beberapa platform AI yang berbeda dan memilih yang paling lengkap tanpa modifikasi',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'sedang',
            ],
            [
                'question' => 'Prinsip "verifikasi dan validasi" dalam penggunaan AI untuk pendidikan mengacu pada keharusan guru untuk:',
                'option_a' => 'Memastikan bahwa platform AI yang digunakan telah tersertifikasi oleh lembaga pendidikan resmi',
                'option_b' => 'Memeriksa dan memvalidasi kebenaran serta kesesuaian output AI sebelum digunakan dalam pembelajaran',
                'option_c' => 'Memverifikasi identitas peserta didik yang menggunakan AI untuk mencegah plagiasi dan kecurangan akademik',
                'option_d' => 'Mengecek apakah lisensi platform AI yang digunakan sesuai dengan regulasi perlindungan data peserta didik',
                'option_e' => 'Memastikan bahwa semua guru di sekolah menggunakan platform AI yang sama untuk konsistensi',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'sedang',
            ],
            [
                'question' => 'Kualitas output yang dihasilkan oleh AI sangat bergantung pada kualitas "prompt" yang diberikan. Yang dimaksud dengan "prompt" adalah:',
                'option_a' => 'Template standar yang disediakan oleh platform AI untuk memandu pengguna dalam menghasilkan konten tertentu',
                'option_b' => 'Instruksi atau pertanyaan yang diberikan pengguna kepada AI dalam bahasa natural untuk menghasilkan output',
                'option_c' => 'Algoritma pembelajaran mesin yang digunakan AI untuk memproses input dan menghasilkan respons yang relevan',
                'option_d' => 'Hasil akhir atau output yang dihasilkan AI berdasarkan data training dan parameter yang telah dikonfigurasi',
                'option_e' => 'Metadata atau tag yang ditambahkan pada output AI untuk memudahkan kategorisasi dan pencarian',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'sedang',
            ],
            [
                'question' => 'Salah satu keterbatasan fundamental AI yang harus dipahami guru adalah bahwa AI tidak mampu:',
                'option_a' => 'Menghasilkan berbagai format dokumen pembelajaran seperti RPP, silabus, atau modul dengan struktur yang sistematis',
                'option_b' => 'Memberikan penjelasan tentang konsep-konsep kompleks dengan menggunakan analogi dan contoh yang beragam',
                'option_c' => 'Membuat keputusan pedagogis yang mempertimbangkan nuansa emosional dan dinamika kelas yang kompleks',
                'option_d' => 'Menyediakan variasi aktivitas pembelajaran yang dapat disesuaikan dengan berbagai gaya belajar peserta didik',
                'option_e' => 'Menganalisis data hasil belajar dan mengidentifikasi pola-pola yang dapat membantu personalisasi pembelajaran',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'sedang',
            ],
            [
                'question' => 'Dalam membuat instrumen penilaian berbasis HOTS (Higher Order Thinking Skills) dengan bantuan AI, aspek yang paling krusial untuk diverifikasi guru adalah:',
                'option_a' => 'Format penulisan soal apakah sudah sesuai dengan standar template yang digunakan sekolah',
                'option_b' => 'Jumlah soal yang dihasilkan apakah sudah sesuai dengan jumlah yang diminta dalam prompt',
                'option_c' => 'Kesesuaian level kognitif soal dengan indikator HOTS dan relevansi dengan materi yang telah diajarkan',
                'option_d' => 'Waktu yang dibutuhkan AI untuk menghasilkan soal sebagai indikator kompleksitas dan kualitas',
                'option_e' => 'Variasi jenis soal yang dihasilkan apakah sudah mencakup pilihan ganda, uraian, dan praktik',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'sedang',
            ],

            // ========================================
            // BAGIAN III: SOAL TINGKAT TINGGI (11-15)
            // ========================================
            [
                'question' => 'Bu Sari menggunakan AI untuk membuat materi tentang sistem tata surya. AI memberikan penjelasan yang akurat namun menggunakan analogi dengan sistem GPS dan satelit modern. Bu Sari mengajar di daerah pedesaan di mana peserta didiknya lebih familiar dengan pertanian. Respon paling tepat yang harus dilakukan Bu Sari adalah:',
                'option_a' => 'Menggunakan materi AI apa adanya sambil memberikan penjelasan tambahan tentang teknologi GPS dan satelit',
                'option_b' => 'Mengganti analogi dengan contoh yang lebih dekat dengan kehidupan peserta didik seperti sistem irigasi sawah',
                'option_c' => 'Membuang seluruh materi AI dan membuat ulang dari awal dengan konteks pertanian sejak awal',
                'option_d' => 'Meminta peserta didik untuk mencari informasi tentang GPS terlebih dahulu sebelum mempelajari tata surya',
                'option_e' => 'Menggunakan materi AI tersebut untuk melatih peserta didik agar terbiasa dengan konteks teknologi modern',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'tinggi',
            ],
            [
                'question' => 'Pak Budi merancang proyek di mana siswa menggunakan AI untuk research awal, tetapi harus cross-check dengan jurnal ilmiah dan sumber kredibel lain sebelum menarik kesimpulan. Tujuan pedagogis utama dari pendekatan ini adalah mengembangkan:',
                'option_a' => 'Keterampilan teknis peserta didik dalam mengoperasikan berbagai platform digital dan AI untuk keperluan akademik',
                'option_b' => 'Pemahaman bahwa AI adalah sumber informasi yang paling efisien dan sebaiknya menjadi referensi utama',
                'option_c' => 'Kemampuan berpikir kritis dalam mengevaluasi informasi dan tidak menerima output AI secara mentah',
                'option_d' => 'Kebiasaan menggunakan multiple tools dalam research untuk meningkatkan produktivitas dan efisiensi waktu',
                'option_e' => 'Kesadaran bahwa teknologi AI masih memiliki keterbatasan sehingga sebaiknya dihindari dalam research serius',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'tinggi',
            ],
            [
                'question' => '"Hallucination" dalam konteks AI merujuk pada fenomena di mana AI menghasilkan:',
                'option_a' => 'Output yang terlalu panjang dan verbose sehingga sulit dipahami oleh pengguna yang kurang berpengalaman',
                'option_b' => 'Respons yang sangat lambat atau timeout karena kompleksitas query yang diberikan oleh pengguna',
                'option_c' => 'Informasi yang tampak faktual dan meyakinkan namun sebenarnya tidak akurat atau tidak berdasar',
                'option_d' => 'Konten yang tidak relevan dengan pertanyaan karena AI salah menginterpretasi maksud pengguna',
                'option_e' => 'Output yang terlalu teknis dan menggunakan jargon yang tidak sesuai dengan level pemahaman audiens',
                'correct_answer' => 'C',
                'points' => 10,
                'difficulty' => 'tinggi',
            ],
            [
                'question' => 'Ibu Ani mengajar di daerah dengan akses internet yang sangat terbatas (hanya tersedia 2 jam per hari di warung internet desa). Strategi paling realistis untuk Ibu Ani mulai memanfaatkan AI adalah:',
                'option_a' => 'Menunggu hingga pemerintah daerah menyediakan infrastruktur internet yang memadai di seluruh wilayah',
                'option_b' => 'Menggunakan AI di warung internet untuk membuat perangkat pembelajaran, kemudian menggunakannya secara offline di kelas',
                'option_c' => 'Memfokuskan penggunaan AI untuk tugas-tugas yang bisa dikerjakan siswa di rumah masing-masing',
                'option_d' => 'Mengadvokasi kepada sekolah untuk segera mengalokasikan anggaran besar untuk instalasi jaringan internet',
                'option_e' => 'Tidak menggunakan AI sama sekali dan mempertahankan metode pembelajaran konvensional yang sudah ada',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'tinggi',
            ],
            [
                'question' => 'Dalam mengimplementasikan diferensiasi pembelajaran sesuai Kurikulum Merdeka, AI dapat dimanfaatkan secara optimal untuk:',
                'option_a' => 'Mengelompokkan siswa berdasarkan kemampuan dan memberikan label klasifikasi untuk memudahkan tracking',
                'option_b' => 'Menghasilkan variasi materi pembelajaran dengan tingkat kompleksitas berbeda sesuai profil belajar siswa',
                'option_c' => 'Menggantikan observasi dan assessment guru dalam memahami kebutuhan individual peserta didik',
                'option_d' => 'Membuat sistem ranking otomatis yang mengkategorikan siswa ke dalam level kemampuan yang tetap',
                'option_e' => 'Menstandarisasi pendekatan pembelajaran agar semua siswa mencapai kompetensi dengan cara yang sama',
                'correct_answer' => 'B',
                'points' => 10,
                'difficulty' => 'tinggi',
            ],
        ]);
    }
}