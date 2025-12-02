<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDimension;
use App\Models\QuestionnaireQuestion;

class QuestionnaireAISeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Contoh questionnaire Indeks Burnout Akademik (IBA) dengan AI configuration
     */
    public function run(): void
    {
        // Create Questionnaire
        $questionnaire = Questionnaire::updateOrCreate(
            ['slug' => 'indeks-burnout-akademik'],
            [
                'name' => 'Indeks Burnout Akademik (IBA)',
                'description' => 'Angket ini mengukur tingkat kelelahan akademik (burnout) yang Anda alami dalam kegiatan belajar. Burnout akademik terdiri dari tiga dimensi: Kelelahan Emosional, Sinisme, dan Penurunan Efikasi Akademik.',
                'instructions' => 'Bacalah setiap pernyataan dengan seksama. Pilih jawaban yang paling sesuai dengan kondisi Anda dalam 2 minggu terakhir. Tidak ada jawaban benar atau salah.',
                'type' => 'burnout',
                'duration_minutes' => 15,
                'has_dimensions' => true,
                'is_active' => true,
                
                // AI Configuration
                'ai_enabled' => true,
                'ai_persona' => 'psikolog',
                'ai_context' => 'Fokus pada konteks mahasiswa Indonesia. Berikan rekomendasi yang praktis dan dapat dilakukan sendiri. Perhatikan aspek budaya dan sistem pendidikan Indonesia. Jika skor tinggi pada kelelahan emosional, tekankan pentingnya self-care dan work-life balance.',
            ]
        );

        $this->command->info('Questionnaire created: ' . $questionnaire->name);

        // Create Dimensions
        $dimensions = [
            [
                'code' => 'KE',
                'name' => 'Kelelahan Emosional',
                'description' => 'Mengukur perasaan lelah, terkuras, dan kehabisan energi secara emosional akibat tuntutan akademik.',
                'order' => 1,
                'interpretations' => [
                    'low' => [
                        'level' => 'Rendah',
                        'class' => 'level-rendah',
                        'description' => 'Anda memiliki energi emosional yang baik dalam menghadapi tuntutan akademik.',
                        'suggestions' => [
                            'Pertahankan keseimbangan aktivitas Anda',
                            'Terus jaga pola istirahat yang baik',
                        ]
                    ],
                    'medium' => [
                        'level' => 'Sedang',
                        'class' => 'level-sedang',
                        'description' => 'Anda mengalami kelelahan emosional pada tingkat sedang. Perlu waspada agar tidak meningkat.',
                        'suggestions' => [
                            'Evaluasi beban tugas Anda',
                            'Sisihkan waktu untuk relaksasi',
                            'Jangan ragu untuk meminta bantuan',
                        ]
                    ],
                    'high' => [
                        'level' => 'Tinggi',
                        'class' => 'level-tinggi',
                        'description' => 'Anda mengalami kelelahan emosional yang signifikan. Diperlukan tindakan segera untuk pemulihan.',
                        'suggestions' => [
                            'Prioritaskan istirahat dan pemulihan',
                            'Pertimbangkan untuk mengurangi beban',
                            'Konsultasikan dengan konselor kampus',
                        ]
                    ],
                ],
            ],
            [
                'code' => 'SI',
                'name' => 'Sinisme',
                'description' => 'Mengukur sikap sinis, skeptis, dan ketidakpedulian terhadap kegiatan akademik.',
                'order' => 2,
                'interpretations' => [
                    'low' => [
                        'level' => 'Rendah',
                        'class' => 'level-rendah',
                        'description' => 'Anda masih memiliki sikap positif dan keterlibatan yang baik dalam kegiatan akademik.',
                        'suggestions' => [
                            'Pertahankan sikap positif Anda',
                            'Terus cari makna dalam pembelajaran',
                        ]
                    ],
                    'medium' => [
                        'level' => 'Sedang',
                        'class' => 'level-sedang',
                        'description' => 'Anda mulai menunjukkan tanda-tanda keraguan terhadap nilai kegiatan akademik.',
                        'suggestions' => [
                            'Refleksikan kembali tujuan akademik Anda',
                            'Cari aspek yang masih menarik dari studi',
                            'Diskusikan dengan teman atau dosen',
                        ]
                    ],
                    'high' => [
                        'level' => 'Tinggi',
                        'class' => 'level-tinggi',
                        'description' => 'Anda menunjukkan tingkat sinisme yang tinggi terhadap akademik yang dapat mengganggu performa.',
                        'suggestions' => [
                            'Evaluasi ulang pilihan jurusan/karir',
                            'Cari dukungan dari mentor atau konselor',
                            'Pertimbangkan untuk mengambil cuti jika memungkinkan',
                        ]
                    ],
                ],
            ],
            [
                'code' => 'EA',
                'name' => 'Penurunan Efikasi Akademik',
                'description' => 'Mengukur penurunan keyakinan terhadap kemampuan dan pencapaian akademik.',
                'order' => 3,
                'interpretations' => [
                    'low' => [
                        'level' => 'Rendah',
                        'class' => 'level-rendah',
                        'description' => 'Anda memiliki kepercayaan diri yang baik terhadap kemampuan akademik Anda.',
                        'suggestions' => [
                            'Pertahankan kepercayaan diri Anda',
                            'Terus tantang diri dengan target baru',
                        ]
                    ],
                    'medium' => [
                        'level' => 'Sedang',
                        'class' => 'level-sedang',
                        'description' => 'Kepercayaan diri akademik Anda mengalami sedikit penurunan.',
                        'suggestions' => [
                            'Ingat kembali pencapaian Anda sebelumnya',
                            'Pecah target besar menjadi langkah kecil',
                            'Minta feedback konstruktif dari dosen',
                        ]
                    ],
                    'high' => [
                        'level' => 'Tinggi',
                        'class' => 'level-tinggi',
                        'description' => 'Anda mengalami penurunan signifikan dalam keyakinan kemampuan akademik.',
                        'suggestions' => [
                            'Fokus pada proses, bukan hanya hasil',
                            'Cari mentor atau tutor untuk bimbingan',
                            'Pertimbangkan konseling akademik',
                        ]
                    ],
                ],
            ],
        ];

        foreach ($dimensions as $dimData) {
            $dimension = QuestionnaireDimension::updateOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'code' => $dimData['code'],
                ],
                [
                    'name' => $dimData['name'],
                    'description' => $dimData['description'],
                    'order' => $dimData['order'],
                    'interpretations' => $dimData['interpretations'],
                ]
            );

            $this->command->info("  - Dimension created: {$dimension->name}");
        }

        // Create Sample Questions
        $questions = [
            // Kelelahan Emosional (KE)
            ['dimension' => 'KE', 'text' => 'Saya merasa lelah secara emosional karena tuntutan akademik.', 'order' => 1],
            ['dimension' => 'KE', 'text' => 'Saya merasa kehabisan energi di akhir hari perkuliahan.', 'order' => 2],
            ['dimension' => 'KE', 'text' => 'Saya merasa frustasi dengan tugas-tugas akademik saya.', 'order' => 3],
            ['dimension' => 'KE', 'text' => 'Belajar dan mengerjakan tugas membuat saya merasa stress.', 'order' => 4],
            
            // Sinisme (SI)
            ['dimension' => 'SI', 'text' => 'Saya merasa kegiatan akademik tidak bermakna.', 'order' => 5],
            ['dimension' => 'SI', 'text' => 'Saya menjadi kurang tertarik dengan materi kuliah.', 'order' => 6],
            ['dimension' => 'SI', 'text' => 'Saya meragukan pentingnya pendidikan yang saya jalani.', 'order' => 7],
            ['dimension' => 'SI', 'text' => 'Saya merasa apa yang dipelajari tidak akan berguna di masa depan.', 'order' => 8],
            
            // Efikasi Akademik (EA) - Reverse scored
            ['dimension' => 'EA', 'text' => 'Saya yakin dapat menyelesaikan tugas-tugas akademik dengan baik.', 'order' => 9, 'reverse' => true],
            ['dimension' => 'EA', 'text' => 'Saya merasa mampu menguasai materi yang diajarkan.', 'order' => 10, 'reverse' => true],
            ['dimension' => 'EA', 'text' => 'Saya percaya diri dengan kemampuan akademik saya.', 'order' => 11, 'reverse' => true],
            ['dimension' => 'EA', 'text' => 'Saya dapat mencapai target akademik yang saya tetapkan.', 'order' => 12, 'reverse' => true],
        ];

        foreach ($questions as $qData) {
            $dimension = $questionnaire->dimensions->where('code', $qData['dimension'])->first();
            
            QuestionnaireQuestion::updateOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'question_text' => $qData['text'],
                ],
                [
                    'dimension_id' => $dimension?->id,
                    'order' => $qData['order'],
                    'is_reverse_scored' => $qData['reverse'] ?? false,
                ]
            );
        }

        $this->command->info("  - {$questionnaire->questions()->count()} questions created");
        $this->command->info('');
        $this->command->info('✅ Questionnaire with AI configuration seeded successfully!');
        $this->command->info('');
        $this->command->info('AI Settings:');
        $this->command->info("  - AI Enabled: Yes");
        $this->command->info("  - Persona: Psikolog Profesional");
        $this->command->info("  - Custom Context: Fokus pada konteks mahasiswa Indonesia");
    }
}
