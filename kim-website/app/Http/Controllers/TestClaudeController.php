<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ClaudeAIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TestClaudeController extends Controller
{
    protected ClaudeAIService $aiService;

    public function __construct(ClaudeAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Test Claude AI Configuration
     */
    public function testConfig()
    {
        Log::info('=== TEST CLAUDE CONFIG START ===');
        
        $configTests = [
            'claude_config' => [
                'api_key' => config('claude.api_key'),
                'model' => config('claude.model'),
                'timeout' => config('claude.timeout'),
                'max_tokens' => config('claude.max_tokens'),
            ],
            'services_config' => [
                'api_key' => config('services.anthropic.api_key'),
                'model' => config('services.anthropic.model'),
                'timeout' => config('services.anthropic.timeout'),
                'max_tokens' => config('services.anthropic.max_tokens'),
            ],
            'env_direct' => [
                'CLAUDE_API_KEY' => env('CLAUDE_API_KEY'),
                'CLAUDE_MODEL' => env('CLAUDE_MODEL'),
                'ANTHROPIC_API_KEY' => env('ANTHROPIC_API_KEY'),
                'ANTHROPIC_MODEL' => env('ANTHROPIC_MODEL'),
            ],
        ];

        // Mask API keys for security
        foreach ($configTests as $key => &$section) {
            if (isset($section['api_key']) && $section['api_key']) {
                $section['api_key_preview'] = substr($section['api_key'], 0, 15) . '...';
                $section['api_key_length'] = strlen($section['api_key']);
                unset($section['api_key']);
            }
            if (isset($section['CLAUDE_API_KEY']) && $section['CLAUDE_API_KEY']) {
                $section['CLAUDE_API_KEY'] = substr($section['CLAUDE_API_KEY'], 0, 15) . '...';
            }
            if (isset($section['ANTHROPIC_API_KEY']) && $section['ANTHROPIC_API_KEY']) {
                $section['ANTHROPIC_API_KEY'] = substr($section['ANTHROPIC_API_KEY'], 0, 15) . '...';
            }
        }

        Log::info('Config Test Results', $configTests);
        Log::info('=== TEST CLAUDE CONFIG END ===');

        return view('test.claude-config', compact('configTests'));
    }

    /**
     * Test Simple Claude API Call
     */
    public function testSimpleCall()
    {
        Log::info('=== TEST SIMPLE CLAUDE API CALL START ===');
        
        $startTime = microtime(true);
        
        try {
            $testPrompt = "Please respond with exactly: 'Test successful! I am Claude and I am working correctly.'";
            
            Log::info('Sending test prompt to Claude', [
                'prompt' => $testPrompt,
                'timestamp' => now()->toIso8601String()
            ]);

            $response = $this->callClaudeDirectly($testPrompt);
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            
            Log::info('Claude API Response Received', [
                'success' => $response['success'],
                'duration_seconds' => $duration,
                'response_preview' => isset($response['content']) ? substr($response['content'], 0, 200) : null,
                'error' => $response['error'] ?? null,
            ]);

            Log::info('=== TEST SIMPLE CLAUDE API CALL END ===');

            return view('test.claude-simple', [
                'response' => $response,
                'duration' => $duration,
                'prompt' => $testPrompt,
            ]);

        } catch (\Exception $e) {
            Log::error('Test Simple Call Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('test.claude-simple', [
                'response' => [
                    'success' => false,
                    'error' => 'Exception: ' . $e->getMessage(),
                ],
                'duration' => 0,
                'prompt' => $testPrompt ?? 'N/A',
            ]);
        }
    }

    /**
     * Test Full Analysis (like Questionnaire)
     */
    public function testFullAnalysis()
    {
        Log::info('=== TEST FULL ANALYSIS START ===');
        
        $startTime = microtime(true);
        
        try {
            // Simulate questionnaire data
            $mockData = [
                'questionnaire_name' => 'Test Stress Scale',
                'respondent_name' => 'Test User',
                'dimensions' => [
                    'stress' => [
                        'name' => 'Tingkat Stress',
                        'score' => 35,
                        'level' => 'Tinggi',
                        'questions' => 10,
                    ],
                    'anxiety' => [
                        'name' => 'Kecemasan',
                        'score' => 28,
                        'level' => 'Sedang',
                        'questions' => 8,
                    ],
                ],
            ];

            Log::info('Testing with mock questionnaire data', $mockData);

            $prompt = $this->buildTestAnalysisPrompt($mockData);
            
            Log::info('Analysis prompt built', [
                'prompt_length' => strlen($prompt),
                'prompt_preview' => substr($prompt, 0, 300) . '...',
            ]);

            $response = $this->callClaudeDirectly($prompt);
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            // Try to parse JSON response
            $parsedAnalysis = null;
            if ($response['success'] && isset($response['content'])) {
                try {
                    $content = trim($response['content']);
                    $content = preg_replace('/^```json\s*/i', '', $content);
                    $content = preg_replace('/\s*```$/i', '', $content);
                    $parsedAnalysis = json_decode($content, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        Log::info('Successfully parsed JSON response', [
                            'has_overall_summary' => isset($parsedAnalysis['overall_summary']),
                            'has_dimension_analyses' => isset($parsedAnalysis['dimension_analyses']),
                            'dimension_count' => isset($parsedAnalysis['dimension_analyses']) ? count($parsedAnalysis['dimension_analyses']) : 0,
                        ]);
                    } else {
                        Log::warning('JSON parse error', [
                            'error' => json_last_error_msg(),
                            'content_preview' => substr($content, 0, 500),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception parsing JSON', [
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Full analysis test completed', [
                'success' => $response['success'],
                'duration_seconds' => $duration,
                'parsed_successfully' => $parsedAnalysis !== null,
            ]);

            Log::info('=== TEST FULL ANALYSIS END ===');

            return view('test.claude-analysis', [
                'response' => $response,
                'parsedAnalysis' => $parsedAnalysis,
                'mockData' => $mockData,
                'duration' => $duration,
                'prompt' => $prompt,
            ]);

        } catch (\Exception $e) {
            Log::error('Test Full Analysis Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('test.claude-analysis', [
                'response' => [
                    'success' => false,
                    'error' => 'Exception: ' . $e->getMessage(),
                ],
                'parsedAnalysis' => null,
                'mockData' => $mockData ?? [],
                'duration' => 0,
                'prompt' => $prompt ?? 'N/A',
            ]);
        }
    }

    /**
     * Direct API call to Claude (bypassing service for testing)
     */
    private function callClaudeDirectly(string $prompt): array
    {
        try {
            // Try services.anthropic first, fallback to claude config
            $apiKey = config('services.anthropic.api_key') ?: config('claude.api_key');
            $model = config('services.anthropic.model') ?: config('claude.model', 'claude-3-5-sonnet-20240620');
            $timeout = config('services.anthropic.timeout') ?: config('claude.timeout', 120);
            $maxTokens = config('services.anthropic.max_tokens') ?: config('claude.max_tokens', 4096);

            Log::info('Making direct Claude API call', [
                'api_key_preview' => substr($apiKey, 0, 15) . '...',
                'model' => $model,
                'timeout' => $timeout,
                'max_tokens' => $maxTokens,
                'base_url' => 'https://api.anthropic.com/v1/messages',
            ]);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
            ])
            ->timeout($timeout)
            ->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => $maxTokens,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);

            Log::info('Claude API HTTP Response', [
                'status_code' => $response->status(),
                'successful' => $response->successful(),
                'response_size' => strlen($response->body()),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Claude API Success Response', [
                    'model' => $data['model'] ?? 'unknown',
                    'input_tokens' => $data['usage']['input_tokens'] ?? 0,
                    'output_tokens' => $data['usage']['output_tokens'] ?? 0,
                    'stop_reason' => $data['stop_reason'] ?? 'unknown',
                    'content_type' => $data['content'][0]['type'] ?? 'unknown',
                    'content_length' => isset($data['content'][0]['text']) ? strlen($data['content'][0]['text']) : 0,
                ]);

                return [
                    'success' => true,
                    'content' => $data['content'][0]['text'] ?? '',
                    'usage' => $data['usage'] ?? null,
                    'model' => $data['model'] ?? $model,
                    'stop_reason' => $data['stop_reason'] ?? null,
                    'full_response' => $data,
                ];
            }

            // Error response
            $errorData = $response->json();
            
            Log::error('Claude API Error Response', [
                'status_code' => $response->status(),
                'error_type' => $errorData['error']['type'] ?? 'unknown',
                'error_message' => $errorData['error']['message'] ?? 'unknown',
                'full_error' => $errorData,
            ]);

            return [
                'success' => false,
                'error' => 'API Error: ' . $response->status(),
                'error_type' => $errorData['error']['type'] ?? 'unknown',
                'error_message' => $errorData['error']['message'] ?? 'No message',
                'full_response' => $errorData,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Claude API Connection Exception', [
                'message' => $e->getMessage(),
                'timeout' => $timeout ?? 'unknown',
            ]);

            return [
                'success' => false,
                'error' => 'Connection timeout or network error',
                'exception_message' => $e->getMessage(),
            ];

        } catch (\Exception $e) {
            Log::error('Claude API General Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'error' => 'Exception occurred',
                'exception_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build test analysis prompt
     */
    private function buildTestAnalysisPrompt(array $mockData): string
    {
        $prompt = "Kamu adalah psikolog profesional yang berpengalaman. ";
        $prompt .= "Berdasarkan data berikut, buatkan analisis dalam format JSON:\n\n";
        
        $prompt .= "=== DATA RESPONDEN ===\n";
        $prompt .= "Nama: {$mockData['respondent_name']}\n";
        $prompt .= "Angket: {$mockData['questionnaire_name']}\n\n";
        
        $prompt .= "=== HASIL SKOR ===\n";
        foreach ($mockData['dimensions'] as $code => $dim) {
            $prompt .= "- {$dim['name']}: {$dim['score']} poin (Level: {$dim['level']})\n";
        }
        
        $prompt .= "\nFormat JSON yang diharapkan:\n";
        $prompt .= "```json\n";
        $prompt .= "{\n";
        $prompt .= '  "overall_summary": "Ringkasan 2-3 paragraf...",'."\n";
        $prompt .= '  "dimension_analyses": {'."\n";
        foreach ($mockData['dimensions'] as $code => $dim) {
            $prompt .= '    "'.$code.'": {'."\n";
            $prompt .= '      "interpretation": "Interpretasi detail...",'."\n";
            $prompt .= '      "recommendations": ["Saran 1", "Saran 2", "Saran 3"]'."\n";
            $prompt .= '    },'."\n";
        }
        $prompt .= '  },'."\n";
        $prompt .= '  "motivational_message": "Pesan motivasi..."'."\n";
        $prompt .= "}\n```\n";
        
        return $prompt;
    }

    /**
     * View all logs
     */
    public function viewLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return view('test.claude-logs', [
                'logs' => 'Log file not found',
                'error' => true,
            ]);
        }

        // Get last 100 lines
        $logs = shell_exec("tail -n 100 {$logFile}");
        
        // Or if on Windows, use PHP
        if (empty($logs)) {
            $file = new \SplFileObject($logFile, 'r');
            $file->seek(PHP_INT_MAX);
            $lastLine = $file->key();
            $lines = [];
            $lineCount = min($lastLine, 100);
            
            for ($i = $lastLine - $lineCount; $i < $lastLine; $i++) {
                $file->seek($i);
                $lines[] = $file->current();
            }
            
            $logs = implode('', $lines);
        }

        return view('test.claude-logs', [
            'logs' => $logs,
            'error' => false,
            'lastUpdate' => date('Y-m-d H:i:s', filemtime($logFile)),
        ]);
    }
}