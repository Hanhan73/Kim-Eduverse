<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\QuestionnaireResponse;
use App\Models\Questionnaire;

class ClaudeAIService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.anthropic.com/v1/messages';
    protected string $model;
    protected int $timeout;
    protected int $maxTokens;
    
    public function __construct()
    {
        // FIXED: Use same config as ClaudeService (services.anthropic)
        $this->apiKey = config('services.anthropic.api_key') ?: config('claude.api_key');
        $this->model = config('services.anthropic.model', 'claude-3-5-sonnet-20240620');
        $this->timeout = config('services.anthropic.timeout', 300);
        $this->maxTokens = config('services.anthropic.max_tokens', 8192);
        
        // Set PHP execution time limit
        set_time_limit($this->timeout);
        
        Log::info('ClaudeAIService initialized', [
            'model' => $this->model,
            'timeout' => $this->timeout,
            'max_tokens' => $this->maxTokens
        ]);
    }

    /**
     * Generate comprehensive analysis for questionnaire response
     */
    public function generateAnalysis(QuestionnaireResponse $response): array
    {
        Log::info('Starting AI analysis', ['response_id' => $response->id]);
        
        $questionnaire = $response->questionnaire;
        $scores = $response->scores;
        $resultSummary = $response->result_summary;
        
        // Build context for AI
        $context = $this->buildAnalysisContext($response, $questionnaire, $scores, $resultSummary);
        
        // Generate AI analysis
        $aiResponse = $this->callClaudeAPI($context);
        
        if (!$aiResponse['success']) {
            Log::error('Claude AI analysis failed', [
                'response_id' => $response->id,
                'error' => $aiResponse['error'] ?? 'Unknown error'
            ]);
            
            // Return fallback analysis
            return $this->generateFallbackAnalysis($response, $resultSummary);
        }
        
        Log::info('AI analysis completed successfully', ['response_id' => $response->id]);
        
        return $this->parseAIResponse($aiResponse['content'], $resultSummary);
    }

    /**
     * Build context prompt for analysis - MORE NATURAL
     */
    protected function buildAnalysisContext(
        QuestionnaireResponse $response,
        Questionnaire $questionnaire,
        array $scores,
        array $resultSummary
    ): string {
        
        // Get persona prompt
        $personaPrompt = $questionnaire->getAIPersonaPrompt();
        $customContext = $questionnaire->ai_context ?? '';
        
        $context = "Kamu berperan sebagai {$personaPrompt}. yang memiliki keahlian dalam menganalisis tentang {$questionnaire->name}. ";
        $context .= "Kamu berbicara dengan gaya yang hangat, natural, dan mudah dipahami - seperti sedang berbincang dengan klien secara langsung. ";
        $context .= "Hindari bahasa yang terlalu formal, kaku, atau seperti robot. Gunakan bahasa Indonesia sehari-hari yang tetap profesional.\n\n";
        
        if ($customContext) {
            $context .= "KONTEKS TAMBAHAN: {$customContext}\n\n";
        }
        
        // Questionnaire info
        $context .= "=== TENTANG CEKMA INI ===\n";
        $context .= "Nama: {$questionnaire->name}\n";
        $context .= "Deskripsi: {$questionnaire->description}\n";
        $context .= "Tipe: {$questionnaire->type}\n\n";
        
        // Respondent info
        $context .= "=== RESPONDEN ===\n";
        $context .= "Nama: {$response->respondent_name}\n\n";
        
        // Scores and results
        $context .= "=== HASIL SKOR PER DIMENSI ===\n";
        
        foreach ($resultSummary as $dimensionCode => $result) {
            $context .= "\n{$result['dimension_name']}\n";
            $context .= "   Kode Dimensi: {$dimensionCode}\n";  // Tambahkan kode dimensi
            $context .= "   Skor: {$result['score']} poin\n";
            $context .= "   Level: " . ($result['interpretation']['level'] ?? 'Sedang') . "\n";
            
            if ($questionnaire->has_dimensions) {
                $dimension = $questionnaire->dimensions->where('code', $dimensionCode)->first();
                if ($dimension) {
                    $context .= "   Tentang dimensi ini: {$dimension->description}\n";
                    
                    $questionCount = $dimension->questions->count();
                    $minScore = $questionCount * 1;
                    $maxScore = $questionCount * 5;
                    $context .= "   Range skor: {$minScore} - {$maxScore} (dari {$questionCount} pertanyaan)\n";
                }
            }
        }
        
        // Include individual answers for deeper analysis
        $context .= "\n=== JAWABAN DETAIL ===\n";
        $answers = $response->answers;
        
        foreach ($questionnaire->questions as $question) {
            $answer = $answers[$question->id] ?? null;
            if ($answer) {
                $answerLabel = $this->getAnswerLabel($answer);
                $dimensionName = $question->dimension ? $question->dimension->name : 'Umum';
                $dimensionCode = $question->dimension ? $question->dimension->code : 'UMUM';  // Tambahkan kode dimensi
                $context .= "• [{$dimensionName} - {$dimensionCode}] {$question->question_text}\n";
                $context .= "  → Jawaban: {$answerLabel}";
                if ($question->is_reverse_scored) {
                    $context .= " (skor dibalik)";
                }
                $context .= "\n";
            }
        }
        
        // IMPROVED Instructions for AI - more natural output
        $context .= "\n=== TUGAS KAMU ===\n";
        $context .= "Buatkan analisis hasil angket ini dalam format JSON. Ingat:\n\n";
        
        $context .= "1. RINGKASAN KESELURUHAN (overall_summary):\n";
        $context .= "   - Tulis 2-3 paragraf yang mengalir natural\n";
        $context .= "   - Sapa responden dengan namanya di awal\n";
        $context .= "   - Jelaskan gambaran umum kondisinya dengan bahasa yang hangat\n";
        $context .= "   - Jangan terlalu teknis, fokus pada insight yang bermakna\n\n";
        
        $context .= "2. INTERPRETASI PER DIMENSI (dimension_analyses):\n";
        $context .= "   - PENTING: Untuk SETIAP dimensi yang disebutkan di bagian HASIL SKOR PER DIMENSI, buatkan analisis lengkap\n";
        $context .= "   - Gunakan KODE DIMENSI yang disebutkan sebagai kunci di JSON\n";
        $context .= "   - Untuk setiap dimensi, tulis 'interpretation' yang PANJANG dan DETAIL (minimal 4-5 kalimat)\n";
        $context .= "   - Jelaskan APA ARTINYA hasil ini bagi kehidupan sehari-hari responden\n";
        $context .= "   - Hubungkan dengan konteks nyata (kuliah, kerja, relasi, dll)\n";
        $context .= "   - Jangan hanya bilang 'skor tinggi/rendah', tapi jelaskan IMPLIKASINYA\n";
        $context .= "   - Buat personal dengan menyebut nama responden sesekali\n\n";
        
        $context .= "3. SARAN/REKOMENDASI (recommendations):\n";
        $context .= "   - Berikan 3-5 saran SPESIFIK dan PRAKTIS per dimensi\n";
        $context .= "   - Saran harus bisa langsung dilakukan, bukan abstrak\n";
        $context .= "   - Contoh bagus: 'Coba luangkan 15 menit sebelum tidur untuk menulis 3 hal yang kamu syukuri hari ini'\n";
        $context .= "   - Contoh buruk: 'Tingkatkan kesejahteraan psikologis Anda'\n";
        $context .= "   - Gunakan bahasa 'kamu', bukan 'Anda'\n\n";
        
        $context .= "4. PESAN MOTIVASI (motivational_message):\n";
        $context .= "   - Tulis pesan yang personal dan menyemangati\n";
        $context .= "   - Gunakan kata 'kamu'\n";
        $context .= "   - Buat mereka merasa dipahami dan didukung\n\n";
        
        $context .= "FORMAT JSON YANG DIHARAPKAN:\n";
        $context .= "```json\n";
        $context .= "{\n";
        $context .= '  "overall_summary": "Berdasarkan hasil CEKMA yang kamu isi... (lanjutkan dengan 2-3 paragraf natural)",'."\n";
        $context .= '  "dimension_analyses": {'."\n";
        
        // List dimension codes with explicit mention of using the codes from the scores section
        foreach ($resultSummary as $code => $result) {
            $context .= '    "'.$code.'": {'."\n";
            $context .= '      "interpretation": "Penjelasan PANJANG dan DETAIL tentang apa arti hasil dimensi ini (minimal 4-5 kalimat, hubungkan dengan kehidupan sehari-hari)",'."\n";
            $context .= '      "recommendations": ["Saran praktis 1", "Saran praktis 2", "Saran praktis 3", "Saran praktis 4", "Saran praktis 5"]'."\n";
            $context .= '    },'."\n";
        }
        
        $context .= '  },'."\n";
        $context .= '  "professional_note": "Catatan jika ada hal yang perlu perhatian khusus atau saran untuk konsultasi",'."\n";
        $context .= '  "motivational_message": "Pesan personal yang menyemangati untuk kamu..."'."\n";
        $context .= "}\n";
        $context .= "```\n\n";
        
        $context .= "PENTING:\n";
        $context .= "- Gunakan bahasa yang HANGAT dan NATURAL, seperti sedang berbicara langsung\n";
        $context .= "- Gunakan kata 'kamu' untuk menyapa, JANGAN pakai nama responden\n";
        $context .= "- JANGAN pakai bahasa formal berlebihan seperti 'Saudara', 'responden', 'individu'\n";
        $context .= "- JANGAN sebutkan angka skor mentah, fokus pada makna dan implikasi\n";
        $context .= "- Interpretasi harus PANJANG dan BERMAKNA, bukan template singkat\n";
        $context .= "- Saran harus SPESIFIK dan BISA DILAKUKAN, minimal 3-5 per dimensi\n";
        $context .= "- Output HANYA JSON valid, tanpa teks lain di luar JSON\n";
        
        $context .= "\n=== CRITICAL JSON FORMAT RULES ===\n";
        $context .= "- Output HANYA JSON valid, tanpa teks tambahan apapun\n";
        $context .= "- Untuk paragraf panjang atau multi-line text, gunakan \\n (escaped newline)\n";
        $context .= "- Contoh BENAR: \"Paragraf pertama.\\n\\nParagraf kedua.\"\n";
        $context .= "- Contoh SALAH: String dengan actual line breaks (tekan enter)\n";
        $context .= "- Escape semua quotes dengan \\\"\n";
        $context .= "- JANGAN wrap dengan ```json blocks\n";
        $context .= "- JSON harus bisa langsung di-parse tanpa cleaning\n";
        return $context;
    }

    /**
     * Get answer label from numeric value
     */
    protected function getAnswerLabel($answer): string
    {
        if (!is_numeric($answer)) {
            return (string) $answer;
        }
        
        $labels = [
            1 => 'Sangat Tidak Setuju (1)',
            2 => 'Tidak Setuju (2)',
            3 => 'Netral (3)',
            4 => 'Setuju (4)',
            5 => 'Sangat Setuju (5)',
        ];
        
        return $labels[$answer] ?? "Nilai: {$answer}";
    }

    /**
     * Call Claude API - FIXED
     */
    protected function callClaudeAPI(string $prompt): array
    {
        try {
            Log::info('Calling Claude API', [
                'model' => $this->model,
                'timeout' => $this->timeout,
                'max_tokens' => $this->maxTokens
            ]);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])
            ->timeout($this->timeout)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'max_tokens' => $this->maxTokens,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['content'][0]['text'] ?? '';
                
                Log::info('Claude API success', [
                    'model' => $this->model,
                    'input_tokens' => $data['usage']['input_tokens'] ?? 0,
                    'output_tokens' => $data['usage']['output_tokens'] ?? 0,
                    'content_length' => strlen($content)
                ]);
                
                return [
                    'success' => true,
                    'content' => $content,
                ];
            }

            Log::error('Claude API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status()
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Claude API connection timeout', [
                'message' => $e->getMessage(),
                'timeout' => $this->timeout
            ]);

            return [
                'success' => false,
                'error' => 'Connection timeout after ' . $this->timeout . ' seconds'
            ];
            
        } catch (\Exception $e) {
            Log::error('Claude API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Parse AI response and merge with existing summary - FIXED with better JSON cleaning and guaranteed completeness
     */
    protected function parseAIResponse(string $content, array $existingSummary): array
    {
        try {
            Log::info('Parsing AI response', ['content_length' => strlen($content)]);
            
            // STEP 1: Remove markdown code blocks
            $content = trim($content);
            $content = preg_replace('/^```json\s*/i', '', $content);
            $content = preg_replace('/\s*```$/i', '', $content);
            $content = trim($content);
            
            // STEP 2: Fix common JSON issues that cause "Control character error"
            $content = preg_replace_callback(
                '/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s',
                function($matches) {
                    $str = $matches[1];
                    $str = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $str);
                    return '"' . $str . '"';
                },
                $content
            );
            
            // STEP 3: Try to decode
            $aiAnalysis = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse AI JSON response', [
                    'error' => json_last_error_msg(),
                    'content_preview' => substr($content, 0, 500),
                    'full_content' => $content
                ]);
                
                // Try alternative cleaning
                $content = preg_replace('/[\x00-\x08\x0B-\x0C\x0E-\x1F\x7F]/', '', $content);
                $aiAnalysis = json_decode($content, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('All JSON parsing attempts failed');
                    return $this->generateFallbackAnalysis(null, $existingSummary);
                }
            }
            
            // STEP 4: Validate and ensure ALL dimensions have complete data
            if (!isset($aiAnalysis['dimension_analyses']) || !is_array($aiAnalysis['dimension_analyses'])) {
                Log::warning('AI response missing dimension_analyses');
                return $this->generateFallbackAnalysis(null, $existingSummary);
            }
            
            // STEP 5: Build enhanced summary with GUARANTEED completeness
            $enhancedSummary = [];
            $missingDimensions = [];
            
            foreach ($existingSummary as $code => $result) {
                $dimensionName = $result['dimension_name'] ?? $code;
                $level = strtolower($result['interpretation']['level'] ?? 'sedang');
                
                // Start with original data
                $enhancedSummary[$code] = [
                    'dimension_name' => $dimensionName,
                    'score' => $result['score'] ?? 0,
                    'interpretation' => $result['interpretation'] ?? [],
                ];
                
                // Check if AI provided data for this dimension
                if (isset($aiAnalysis['dimension_analyses'][$code])) {
                    $dimAnalysis = $aiAnalysis['dimension_analyses'][$code];
                    
                    // Strip emojis and icons from interpretation
                    $interpretation = $dimAnalysis['interpretation'] ?? '';
                    $interpretation = $this->stripEmojis($interpretation);
                    
                    // Strip emojis and icons from recommendations
                    $recommendations = $dimAnalysis['recommendations'] ?? [];
                    if (is_array($recommendations)) {
                        $recommendations = array_map([$this, 'stripEmojis'], $recommendations);
                    }
                    
                    // Validate AI data completeness
                    $hasInterpretation = !empty($interpretation);
                    $hasRecommendations = !empty($recommendations) && 
                                         is_array($recommendations) && 
                                         count($recommendations) >= 3;
                    
                    if ($hasInterpretation && $hasRecommendations) {
                        // AI data is complete
                        $enhancedSummary[$code]['ai_interpretation'] = $interpretation;
                        $enhancedSummary[$code]['ai_recommendations'] = $recommendations;
                    } else {
                        // AI data incomplete, use fallback
                        Log::warning('Incomplete AI data for dimension', [
                            'code' => $code,
                            'has_interpretation' => $hasInterpretation,
                            'has_recommendations' => $hasRecommendations
                        ]);
                        
                        $enhancedSummary[$code]['ai_interpretation'] = $hasInterpretation 
                            ? $interpretation 
                            : $this->getFallbackInterpretation($dimensionName, $level);
                        
                        $enhancedSummary[$code]['ai_recommendations'] = $hasRecommendations 
                            ? $recommendations 
                            : $this->getFallbackRecommendations($dimensionName, $level);
                        
                        $missingDimensions[] = $code;
                    }
                    
                    // Backward compatibility
                    $enhancedSummary[$code]['ai_strengths'] = $dimAnalysis['strengths'] ?? [];
                    $enhancedSummary[$code]['ai_concerns'] = $dimAnalysis['concerns'] ?? [];
                    
                } else {
                    // AI completely missing this dimension - use fallback
                    Log::warning('AI missing dimension completely', ['code' => $code]);
                    
                    $enhancedSummary[$code]['ai_interpretation'] = $this->getFallbackInterpretation($dimensionName, $level);
                    $enhancedSummary[$code]['ai_recommendations'] = $this->getFallbackRecommendations($dimensionName, $level);
                    $enhancedSummary[$code]['ai_strengths'] = [];
                    $enhancedSummary[$code]['ai_concerns'] = [];
                    
                    $missingDimensions[] = $code;
                }
            }
            
            // Log if any dimensions used fallback
            if (!empty($missingDimensions)) {
                Log::info('Some dimensions used fallback data', [
                    'missing_dimensions' => $missingDimensions,
                    'total_dimensions' => count($existingSummary)
                ]);
            }
            
            // STEP 6: Ensure overall content exists and strip emojis
            $overallSummary = $aiAnalysis['overall_summary'] ?? '';
            $overallSummary = $this->stripEmojis($overallSummary);
            if (empty(trim($overallSummary))) {
                Log::warning('AI missing overall_summary, using fallback');
                $overallSummary = "Terima kasih sudah mengisi CEKMA ini. Hasil yang kamu dapatkan memberikan gambaran tentang kondisimu saat ini di berbagai aspek yang diukur.\n\nSetiap dimensi memiliki interpretasi dan saran yang bisa kamu baca di bawah ini. Gunakan insight ini untuk terus berkembang dan menjadi versi terbaik dari dirimu.";
            }
            
            $motivationalMessage = $aiAnalysis['motivational_message'] ?? '';
            $motivationalMessage = $this->stripEmojis($motivationalMessage);
            if (empty(trim($motivationalMessage))) {
                Log::warning('AI missing motivational_message, using fallback');
                $motivationalMessage = "Ingat bahwa setiap langkah yang kamu ambil untuk memahami diri sendiri adalah langkah yang berani. Terus semangat dalam perjalananmu!";
            }
            
            $professionalNote = $aiAnalysis['professional_note'] ?? '';
            $professionalNote = $this->stripEmojis($professionalNote);
            
            Log::info('AI response parsed with complete data', [
                'dimensions_count' => count($enhancedSummary),
                'fallback_used' => !empty($missingDimensions)
            ]);
            
            return [
                'dimension_results' => $enhancedSummary,
                'overall_summary' => $overallSummary,
                'professional_note' => $professionalNote,
                'motivational_message' => $motivationalMessage,
                'ai_generated' => true,
                'generated_at' => now()->toIso8601String(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Error parsing AI response', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->generateFallbackAnalysis(null, $existingSummary);
        }
    }

    /**
     * Strip emojis and icons from text
     */
    protected function stripEmojis(string $text): string
    {
        // Remove emoji characters (comprehensive regex for all emoji ranges)
        $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text); // Emoticons
        $text = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $text); // Misc Symbols and Pictographs
        $text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $text); // Transport and Map
        $text = preg_replace('/[\x{1F1E0}-\x{1F1FF}]/u', '', $text); // Flags
        $text = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $text);   // Misc symbols
        $text = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $text);   // Dingbats
        $text = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $text); // Supplemental Symbols and Pictographs
        $text = preg_replace('/[\x{1FA00}-\x{1FA6F}]/u', '', $text); // Chess Symbols
        $text = preg_replace('/[\x{1FA70}-\x{1FAFF}]/u', '', $text); // Symbols and Pictographs Extended-A
        $text = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $text);   // Variation Selectors
        $text = preg_replace('/[\x{200D}]/u', '', $text);            // Zero Width Joiner
        
        // Remove common icon characters used in text
        $text = str_replace(['►', '■', '▪', '●', '○', '◆', '◇', '★', '☆', '✓', '✔', '✗', '✘', '⚠', '📊', '📈', '📉', '💡', '🎯', '⭐'], '', $text);
        
        // Clean up multiple spaces and trim
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    /**
     * Generate fallback analysis when AI fails
     */
    protected function generateFallbackAnalysis(?QuestionnaireResponse $response, array $existingSummary): array
    {
        Log::info('Generating fallback analysis');
        
        $enhancedSummary = [];
        
        foreach ($existingSummary as $code => $result) {
            $level = strtolower($result['interpretation']['level'] ?? 'sedang');
            $dimensionName = $result['dimension_name'] ?? $code;
            
            $enhancedSummary[$code] = [
                'dimension_name' => $dimensionName,
                'score' => $result['score'] ?? 0,
                'interpretation' => $result['interpretation'] ?? [],
                'ai_interpretation' => $this->getFallbackInterpretation($dimensionName, $level),
                'ai_recommendations' => $this->getFallbackRecommendations($dimensionName, $level),
                'ai_strengths' => [],
                'ai_concerns' => [],
            ];
        }
        
        return [
            'dimension_results' => $enhancedSummary,
            'overall_summary' => "Terima kasih sudah meluangkan waktu untuk mengisi CEKMA ini. Hasil yang kamu dapatkan memberikan gambaran tentang kondisimu saat ini di beberapa aspek yang diukur.\n\nSetiap orang memiliki perjalanan dan tantangan masing-masing, dan tidak ada hasil yang 'salah' dalam asesmen ini. Yang penting adalah bagaimana kamu menggunakan insight ini untuk terus berkembang dan menjadi versi terbaik dari dirimu.\n\nSilakan baca interpretasi setiap dimensi di bawah ini untuk memahami lebih dalam tentang kondisimu, beserta saran-saran praktis yang bisa langsung kamu coba.",
            'professional_note' => 'Hasil ini bersifat informatif dan edukatif. Jika kamu merasa membutuhkan dukungan lebih lanjut, jangan ragu untuk berkonsultasi dengan psikolog atau konselor profesional.',
            'motivational_message' => "Ingat bahwa mengambil langkah untuk memahami diri sendiri sudah merupakan tanda keberanian. Apapun hasilnya, kamu punya kemampuan untuk terus tumbuh dan berkembang. Semangat!",
            'ai_generated' => false,
            'generated_at' => now()->toIso8601String(),
        ];
    }

    protected function getFallbackInterpretation(string $dimensionName, string $level): string
    {
        $templates = [
            'sangat rendah' => "Hasil pada dimensi {$dimensionName} menunjukkan bahwa area ini memerlukan perhatian khusus. Ini bukan sesuatu yang perlu dikhawatirkan berlebihan, tapi bisa jadi sinyal bahwa kamu perlu lebih memperhatikan aspek ini dalam keseharian. Mungkin ada tekanan atau tantangan yang sedang kamu hadapi yang mempengaruhi area ini. Yang penting, kondisi ini bisa diperbaiki dengan langkah-langkah yang tepat dan konsisten. Cobalah untuk tidak terlalu keras pada diri sendiri - setiap orang punya area yang perlu dikembangkan, dan menyadarinya adalah langkah pertama yang penting.",
            
            'rendah' => "Pada dimensi {$dimensionName}, hasilmu menunjukkan ada ruang untuk pengembangan. Ini artinya kamu mungkin sedang mengalami beberapa tantangan di area ini, dan itu wajar - banyak orang merasakan hal serupa. Kabar baiknya, dengan kesadaran dan usaha yang tepat, kamu bisa mulai memperbaiki aspek ini secara bertahap. Coba perhatikan hal-hal kecil dalam keseharianmu yang berkaitan dengan dimensi ini. Perubahan tidak harus besar dan dramatis - langkah kecil yang konsisten justru lebih efektif dalam jangka panjang.",
            
            'sedang' => "Hasil pada dimensi {$dimensionName} berada di level moderat. Ini menunjukkan bahwa kamu sudah memiliki fondasi yang cukup baik di area ini, meskipun masih ada ruang untuk peningkatan. Kamu mungkin sudah menerapkan beberapa hal positif, tapi belum sepenuhnya konsisten. Dengan sedikit penyesuaian dan fokus yang lebih terarah, kamu bisa meningkatkan aspek ini ke level yang lebih optimal. Coba identifikasi apa yang sudah berjalan baik dan pertahankan, sambil mencari peluang untuk perbaikan di area yang masih perlu dikembangkan.",
            
            'tinggi' => "Selamat! Hasil pada dimensi {$dimensionName} menunjukkan bahwa kamu sudah cukup baik di area ini. Ini mencerminkan usaha dan kebiasaan positif yang sudah kamu bangun selama ini. Meskipun demikian, tetap ada hal-hal yang bisa kamu lakukan untuk mempertahankan dan bahkan meningkatkan kondisi ini. Jangan lupa untuk terus menjaga keseimbangan dan konsistensi dalam hal-hal yang sudah kamu lakukan dengan baik. Kekuatanmu di area ini juga bisa menjadi fondasi untuk membantu mengembangkan aspek-aspek lain.",
            
            'sangat tinggi' => "Hasil yang sangat baik pada dimensi {$dimensionName}! Ini menunjukkan kamu memiliki kekuatan yang solid di area ini. Kamu sudah menerapkan banyak hal positif dan ini tercermin dari hasil asesmen. Pertahankan kebiasaan-kebiasaan baik ini dan pertimbangkan untuk berbagi pengalamanmu dengan orang lain yang mungkin membutuhkan. Kamu juga bisa menggunakan kekuatan di area ini untuk membantu mengembangkan aspek-aspek lain dalam hidupmu. Ingat, menjaga kondisi baik sama pentingnya dengan mencapainya.",
        ];
        
        // Normalize level
        $level = strtolower(trim($level));
        if (strpos($level, 'sangat') !== false && strpos($level, 'rendah') !== false) {
            $level = 'sangat rendah';
        } elseif (strpos($level, 'sangat') !== false && strpos($level, 'tinggi') !== false) {
            $level = 'sangat tinggi';
        } elseif (strpos($level, 'rendah') !== false) {
            $level = 'rendah';
        } elseif (strpos($level, 'tinggi') !== false) {
            $level = 'tinggi';
        } else {
            $level = 'sedang';
        }
        
        return $templates[$level] ?? $templates['sedang'];
    }

    protected function getFallbackRecommendations(string $dimensionName, string $level): array
    {
        $level = strtolower(trim($level));
        
        // Normalize level
        if (strpos($level, 'sangat') !== false && strpos($level, 'rendah') !== false) {
            $level = 'sangat rendah';
        } elseif (strpos($level, 'sangat') !== false && strpos($level, 'tinggi') !== false) {
            $level = 'sangat tinggi';
        } elseif (strpos($level, 'rendah') !== false) {
            $level = 'rendah';
        } elseif (strpos($level, 'tinggi') !== false) {
            $level = 'tinggi';
        } else {
            $level = 'sedang';
        }
        
        $recommendations = [
            'sangat rendah' => [
                'Luangkan 10 menit setiap pagi untuk refleksi dan menyusun prioritas hari ini',
                'Ceritakan perasaanmu pada orang yang kamu percaya, minimal seminggu sekali',
                'Coba teknik pernapasan 4-7-8 saat merasa overwhelmed (hirup 4 detik, tahan 7 detik, buang 8 detik)',
                'Buat jurnal sederhana untuk mencatat 3 hal baik yang terjadi setiap hari',
                'Pertimbangkan untuk berkonsultasi dengan psikolog atau konselor untuk pendampingan lebih lanjut',
            ],
            'rendah' => [
                'Sisihkan waktu 15-20 menit per hari untuk aktivitas yang kamu nikmati tanpa gangguan',
                'Buat to-do list sederhana setiap pagi dan rayakan setiap item yang selesai',
                'Coba kurangi paparan media sosial atau berita negatif, terutama sebelum tidur',
                'Latih diri untuk mengatakan "tidak" pada hal-hal yang menguras energimu tanpa memberikan manfaat',
                'Bangun rutinitas tidur yang konsisten - usahakan tidur dan bangun di jam yang sama setiap hari',
            ],
            'sedang' => [
                'Tingkatkan konsistensi dalam kebiasaan positif yang sudah kamu punya',
                'Coba tantang dirimu dengan satu target kecil baru setiap minggunya',
                'Luangkan waktu untuk hobi atau aktivitas yang membuatmu merasa hidup dan berenergi',
                'Jaga keseimbangan antara produktivitas dan istirahat - keduanya sama pentingnya',
                'Lakukan evaluasi mingguan: apa yang berjalan baik dan apa yang perlu diperbaiki',
            ],
            'tinggi' => [
                'Pertahankan rutinitas dan kebiasaan positif yang sudah kamu bangun selama ini',
                'Bagikan tips atau pengalamanmu dengan teman atau keluarga yang mungkin membutuhkan',
                'Tantang diri dengan goal baru yang lebih menantang untuk terus berkembang',
                'Jangan lupa tetap menjaga work-life balance meski sedang dalam kondisi produktif',
                'Gunakan kekuatanmu di area ini untuk membantu mengembangkan aspek lain yang masih berkembang',
            ],
            'sangat tinggi' => [
                'Luar biasa! Pertahankan semua kebiasaan baik yang sudah kamu jalankan',
                'Pertimbangkan untuk menjadi mentor atau berbagi pengalaman dengan orang lain yang membutuhkan',
                'Gunakan kekuatan di area ini sebagai fondasi untuk pengembangan diri di area lain',
                'Tetap humble dan terus belajar - selalu ada ruang untuk tumbuh lebih baik lagi',
                'Dokumentasikan perjalananmu - ini bisa jadi inspirasi untuk dirimu sendiri dan orang lain',
            ],
        ];
        
        return $recommendations[$level] ?? $recommendations['sedang'];
    }

    /**
     * Generate chart data for visualization
     */
    public function generateChartData(array $resultSummary, Questionnaire $questionnaire): array
    {
        $radarData = [];
        $barData = [];
        
        foreach ($resultSummary as $code => $result) {
            $dimension = $questionnaire->dimensions->where('code', $code)->first();
            $questionCount = $dimension ? $dimension->questions->count() : 5;
            $maxScore = $questionCount * 5;
            $percentage = round(($result['score'] / $maxScore) * 100);
            
            $radarData[] = [
                'dimension' => $result['dimension_name'],
                'score' => $result['score'],
                'percentage' => $percentage,
                'maxScore' => $maxScore,
            ];
            
            $barData[] = [
                'name' => $result['dimension_name'],
                'score' => $result['score'],
                'percentage' => $percentage,
                'level' => $result['interpretation']['level'] ?? 'Sedang',
                'color' => $this->getLevelColor($result['interpretation']['level'] ?? 'Sedang'),
            ];
        }
        
        return [
            'radar' => $radarData,
            'bar' => $barData,
            'type' => count($radarData) >= 3 ? 'radar' : 'bar',
        ];
    }

    protected function getLevelColor(string $level): string
    {
        $level = strtolower($level);
        
        // For burnout-type questionnaires (high = bad)
        $colors = [
            'sangat rendah' => '#276749',  // Dark green - very good
            'rendah' => '#48bb78',          // Green - good
            'sedang' => '#ed8936',          // Orange - moderate
            'tinggi' => '#f56565',          // Red - concerning
            'sangat tinggi' => '#742a2a',   // Dark red - very concerning
        ];
        
        foreach ($colors as $key => $color) {
            if (strpos($level, $key) !== false || $level === $key) {
                return $color;
            }
        }
        
        return '#667eea'; // Default purple
    }
}