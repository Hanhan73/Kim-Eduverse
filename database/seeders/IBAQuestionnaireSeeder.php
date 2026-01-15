<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DigitalProductCategory;
use App\Models\DigitalProduct;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;

class IBAQuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Questionnaire
        $questionnaire = Questionnaire::create([
            'name' => 'Indeks Burnout Akademik (IBA)',
            'slug' => 'indeks-burnout-akademik',
            'description' => 'Kuesioner ini dirancang untuk membantu mengidentifikasi tanda-tanda burnout akademik, yaitu kondisi kelelahan fisik, emosional, dan mental yang kronis akibat tuntutan akademis yang berkepanjangan.',
            'instructions' => 'Jawablah setiap pernyataan dengan jujur berdasarkan pengalaman Anda selama semester ini. Tidak ada jawaban benar atau salah, yang penting adalah kejujuran Anda.',
            'type' => 'burnout',
            'duration_minutes' => 15,
            'has_dimensions' => true,
            'is_active' => true,
        ]);

        // Create Dimensions
        $exhaustion = QuestionnaireDimension::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'exhaustion',
            'name' => 'Kelelahan Emosional (Exhaustion)',
            'description' => 'Mengukur perasaan kehabisan tenaga dan kelelahan akibat tuntutan kuliah',
            'order' => 1,
            'interpretations' => [
                'low' => [
                    'level' => 'RENDAH',
                    'class' => 'level-rendah',
                    'description' => 'Fantastis! Anda memiliki energi yang cukup untuk menghadapi tuntutan kuliah. Anda tampaknya mampu mengelola beban kerja tanpa merasa terkuras habis.',
                    'suggestions' => [
                        'Jaga Momentum: Teruslah menjaga rutinitas tidur, makan, dan olahraga yang sehat. Kebiasaan baik ini adalah fondasi energi Anda.',
                        'Tetap Waspada: Jangan sampai terlena. Tetap monitor tingkat energi Anda, terutama saat menjelang ujian atau deadline besar.',
                    ],
                ],
                'medium' => [
                    'level' => 'SEDANG',
                    'class' => 'level-sedang',
                    'description' => 'Anda mulai merasakan kelelahan. Mungkin energi Anda tidak sepenuhnya pulih setelah akhir pekan atau istirahat singkat. Ini adalah sinyal peringatan dini.',
                    'suggestions' => [
                        'PRIORITASKAN ISTIRAHAT: Jangan korbankan tidur demi belajar lebih lama. Tidur adalah investasi, bukan pemborosan waktu. Targetkan 7-8 jam tidur berkualitas.',
                        'Ambil Jeda Singkat: Teknik Pomodoro bisa membantu. Belajar 25 menit, istirahat 5 menit. Ini membantu otak Anda untuk tidak overheat.',
                    ],
                ],
                'high' => [
                    'level' => 'TINGGI',
                    'class' => 'level-tinggi',
                    'description' => 'PERHATIAN! Anda mengalami kelelahan yang serius. Anda mungkin merasa lelah sepanjang waktu, bahkan setelah beristirahat. Ini adalah tanda utama burnout dan perlu ditangani segera.',
                    'suggestions' => [
                        'ISTIRAHAT SEKARANG JUGA: Anda butuh jeda nyata. Pertimbangkan untuk mengambil cuti kuliah jika memungkinkan, atau setidaknya kurangi komitmen ekstrakurikuler Anda.',
                        'CARI BANTUAN: Segera temui dosen pembimbing akademik atau konselor kampus. Jangan pendam sendiri. Menceritakan beban Anda adalah langkah pertama yang penting.',
                    ],
                ],
            ],
        ]);

        $cynicism = QuestionnaireDimension::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'cynicism',
            'name' => 'Sikap Sinis / Depersonalisasi (Cynicism)',
            'description' => 'Mengukur sikap negatif, apatis, atau jarak terhadap proses pembelajaran',
            'order' => 2,
            'interpretations' => [
                'low' => [
                    'level' => 'TINGGI (Kepedulian)',
                    'class' => 'level-rendah',
                    'description' => 'Anda masih memiliki keterikatan yang baik dengan proses belajar-mengajar. Anda melihat nilai dan relevansi dari kuliah Anda dan tetap terhubung dengan lingkungan akademik.',
                    'suggestions' => [
                        'Bagikan Semangat Anda: Bantu teman sekelas yang mungkin terlihat apatis. Diskusi kelompok yang Anda lakukan bisa menjadi sumber motivasi bagi orang lain.',
                        'Terus Jaga Rasa Ingin Tahu: Tetaplah bertanya "mengapa" pada setiap materi yang dipelajari untuk menjaga rasa ingin tahu Anda tetap hidup.',
                    ],
                ],
                'medium' => [
                    'level' => 'SEDANG',
                    'class' => 'level-sedang',
                    'description' => 'Anda mulai menunjukkan sikap yang sedikit apatis atau sinis. Mungkin beberapa mata kuliah terasa membosankan atau Anda merasa beberapa aktivitas kampus tidak penting.',
                    'suggestions' => [
                        'Cari Koneksi Pribadi: Untuk mata kuliah yang membosankan, cobalah cari satu topik atau satu dosen yang menarik. Fokus pada hal kecil itu untuk membangun kembali minat.',
                        'Fokus pada Tujuan Akhir: Ingat kembali mengapa Anda memilih jurusan ini. Hubungkan tugas-tugas yang membosankan dengan tujuan karir Anda di masa depan.',
                    ],
                ],
                'high' => [
                    'level' => 'RENDAH (Sangat Sinis)',
                    'class' => 'level-tinggi',
                    'description' => 'Anda mengembangkan sikap sinis yang kuat terhadap kuliah. Anda mungkin merasa semua ini sia-sia dan tidak ada gunanya. Sikap ini adalah benteng pertahanan dari kelelahan, tapi pada akhirnya akan menghambat kesuksesan Anda.',
                    'suggestions' => [
                        'Identifikasi Akar Masalah: Apakah Anda kecewa dengan sistem, dosen, atau teman? Mengetahui sumber kekecewaan adalah langkah pertama.',
                        'Cari Perspektif Baru: Bicaralah dengan senior atau alumni yang sudah bekerja. Tanyakan bagaimana mata kuliah yang Anda benci ternyata berguna di dunia kerja.',
                    ],
                ],
            ],
        ]);

        $ineffective = QuestionnaireDimension::create([
            'questionnaire_id' => $questionnaire->id,
            'code' => 'ineffective',
            'name' => 'Rasa Tidak Efektif (Reduced Personal Accomplishment)',
            'description' => 'Mengukur perasaan tidak kompeten dan kurang berprestasi sebagai mahasiswa',
            'order' => 3,
            'interpretations' => [
                'low' => [
                    'level' => 'TINGGI (Percaya Diri)',
                    'class' => 'level-rendah',
                    'description' => 'Luar biasa! Anda memiliki rasa percaya diri yang tinggi pada kemampuan akademik Anda. Anda merasa mampu menghadapi tantangan dan melihat hasil dari usaha yang Anda lakukan.',
                    'suggestions' => [
                        'Tantang Diri Sendiri: Jangan takut untuk mengambil proyek atau mata kuliah yang lebih menantang untuk terus mengasah kemampuan Anda.',
                        'Bantu Teman Lain: Manfaatkan kepercayaan diri Anda untuk membantu teman yang kesulitan. Mengajari orang lain adalah cara terbaik untuk memperkuat pemahaman Anda.',
                    ],
                ],
                'medium' => [
                    'level' => 'SEDANG',
                    'class' => 'level-sedang',
                    'description' => 'Anda sedikit meragukan kemampuan Anda. Mungkin Anda sering membandingkan diri dengan teman lain yang sepertinya lebih pintar atau lebih berprestasi.',
                    'suggestions' => [
                        'Hentikan Perbandingan Sosial: Fokus pada perjalanan Anda sendiri. Bandingkan diri Anda dengan "diri Anda di bulan lalu", bukan dengan orang lain.',
                        'Buat Daftar Pencapaian: Buat daftar kecil semua tugas yang sudah Anda selesaikan atau materi yang sudah Anda kuasai. Ini akan membantu Anda melihat bahwa Anda sebenarnya sudah banyak berprestasi.',
                    ],
                ],
                'high' => [
                    'level' => 'RENDAH',
                    'class' => 'level-tinggi',
                    'description' => 'Anda merasa sangat tidak kompeten dan ragu pada kemampuan sendiri. Perasaan ini bisa menjadi "ramalan yang memenuhi diri sendiri" jika tidak diatasi, membuat Anda takut untuk mencoba dan akhirnya benar-benar gagal.',
                    'suggestions' => [
                        'Mulai dari Kemenangan Kecil: Pecah tugas besar menjadi langkah-langkah sangat kecil. Selesaikan satu langkah kecil, lalu rayakan. Ini akan membangun kembali kepercayaan diri Anda perlahan.',
                        'Minta Masukan Konstruktif: Jangan takut bertanya pada dosen atau senior, "Bagaimana cara saya bisa memperbaiki tugas ini?". Minta bimbingan spesifik, bukan sekadar memuji.',
                    ],
                ],
            ],
        ]);

        // Questions data - FIXED: changed 'text' to 'question_text'
        $questions = [
            // Exhaustion (4 pertanyaan)
            ['question_text' => 'Saya merasa sangat lelah bahkan sebelum hari kuliah dimulai.', 'dimension_id' => $exhaustion->id, 'order' => 1],
            ['question_text' => 'Setelah satu hari penuh kuliah, saya merasa benar-benar kelelahan.', 'dimension_id' => $exhaustion->id, 'order' => 2],
            ['question_text' => 'Saya merasa kehabisan energi ketika harus belajar atau mengerjakan tugas.', 'dimension_id' => $exhaustion->id, 'order' => 3],
            ['question_text' => 'Saya merasa tidak bertenaga untuk menghadapi tuntutan kuliah setiap hari.', 'dimension_id' => $exhaustion->id, 'order' => 4],

            // Cynicism (4 pertanyaan)
            ['question_text' => 'Saya merasa kehilangan minat terhadap mata kuliah yang saya ambil.', 'dimension_id' => $cynicism->id, 'order' => 5],
            ['question_text' => 'Saya berpikir apakah kuliah saya benar-benar bermanfaat.', 'dimension_id' => $cynicism->id, 'order' => 6],
            ['question_text' => 'Saya menjadi lebih sinis terhadap pentingnya kuliah yang saya jalani.', 'dimension_id' => $cynicism->id, 'order' => 7],
            ['question_text' => 'Saya merasa tidak peduli terhadap hasil studi saya.', 'dimension_id' => $cynicism->id, 'order' => 8],

            // Ineffective (4 pertanyaan) - REVERSE SCORED
            ['question_text' => 'Saya percaya bahwa saya dapat menyelesaikan tugas-tugas kuliah dengan baik.', 'dimension_id' => $ineffective->id, 'order' => 9, 'is_reverse_scored' => true],
            ['question_text' => 'Saya merasa kompeten dalam studi saya.', 'dimension_id' => $ineffective->id, 'order' => 10, 'is_reverse_scored' => true],
            ['question_text' => 'Saya merasa bahwa saya memberikan kontribusi yang berharga dalam diskusi kelas.', 'dimension_id' => $ineffective->id, 'order' => 11, 'is_reverse_scored' => true],
            ['question_text' => 'Saya merasa puas dengan pencapaian akademik saya.', 'dimension_id' => $ineffective->id, 'order' => 12, 'is_reverse_scored' => true],
        ];

        foreach ($questions as $questionData) {
            QuestionnaireQuestion::create(array_merge([
                'questionnaire_id' => $questionnaire->id,
                'is_reverse_scored' => false,
            ], $questionData));
        }

        // Create Digital Product
        DigitalProduct::create([
            'category_id' => 1,
            'name' => 'Indeks Burnout Akademik (IBA)',
            'slug' => 'indeks-burnout-akademik',
            'description' => 'Tes untuk mengukur tingkat burnout akademik Anda. Dapatkan hasil analisis lengkap dengan saran penanganan.',
            'features' => [
                '12 pertanyaan komprehensif',
                'Mengukur 3 dimensi: Kelelahan Emosional, Sikap Sinis, dan Rasa Tidak Efektif',
                'Analisis mendalam dengan interpretasi',
                'Saran tindakan praktis',
                'Hasil dikirim via email dalam format PDF',
            ],
            'price' => 50000,
            'type' => 'questionnaire',
            'questionnaire_id' => $questionnaire->id,
            'duration_minutes' => 15,
            'is_active' => true,
            'is_featured' => true,
            'order' => 1,
        ]);

        $this->command->info('✓ IBA Questionnaire seeded successfully!');
    }
}