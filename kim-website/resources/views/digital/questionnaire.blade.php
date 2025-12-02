<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $questionnaire->name }} - KIM Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            color: #2d3748;
            line-height: 1.6;
        }

        .questionnaire-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px 20px 50px;
        }

        /* Header */
        .questionnaire-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 40px;
            border-radius: 20px 20px 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .questionnaire-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }

        .questionnaire-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
        }

        .questionnaire-header p {
            font-size: 1.05rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .kim-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .kim-badge i {
            font-size: 1rem;
        }

        /* Body */
        .questionnaire-body {
            background: white;
            padding: 40px;
            border: 2px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        /* Progress */
        .progress-section {
            margin-bottom: 35px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #718096;
            font-weight: 500;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e2e8f0;
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 50px;
            transition: width 0.4s ease;
        }

        /* Instructions Box */
        .instructions-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border: 2px solid #e0e7ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 35px;
        }

        .instructions-box h3 {
            font-size: 1.15rem;
            color: #2d3748;
            margin-bottom: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .instructions-box h3 i {
            color: #667eea;
        }

        .instructions-box p {
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .instructions-list {
            list-style: none;
            padding: 0;
        }

        .instructions-list li {
            color: #4a5568;
            margin-bottom: 10px;
            padding-left: 28px;
            position: relative;
        }

        .instructions-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: 700;
        }

        /* Question Card */
        .question-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 28px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            border-color: #c3dafe;
            box-shadow: 0 5px 25px rgba(102, 126, 234, 0.1);
        }

        .question-card.answered {
            border-color: #9ae6b4;
            background: linear-gradient(135deg, #f0fff4 0%, #f7fff9 100%);
        }

        .question-card.answered .question-number {
            background: linear-gradient(135deg, #38a169, #48bb78);
        }

        .dimension-badge {
            display: inline-block;
            background: #e0e7ff;
            color: #5a67d8;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .question-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 22px;
        }

        .question-number {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .question-text {
            flex: 1;
            font-size: 1.05rem;
            font-weight: 600;
            color: #2d3748;
            line-height: 1.6;
            padding-top: 8px;
        }

        /* Options */
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-item {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            background: #fafbfc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .option-item:hover {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateX(4px);
        }

        .option-item input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-right: 14px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .option-item label {
            flex: 1;
            cursor: pointer;
            font-size: 0.95rem;
            color: #4a5568;
            font-weight: 500;
        }

        .option-item.selected {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8edff 100%);
            border-color: #667eea;
            border-width: 2px;
        }

        .option-item.selected label {
            color: #5a67d8;
            font-weight: 600;
        }

        /* Submit Section */
        .submit-section {
            margin-top: 40px;
            text-align: center;
            padding: 35px;
            background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
            border-radius: 15px;
            border: 2px solid #e9ecef;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 18px 50px;
            border-radius: 50px;
            font-size: 1.15rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .validation-message {
            color: #e53e3e;
            font-size: 0.9rem;
            margin-top: 15px;
            display: none;
            font-weight: 500;
        }

        .validation-message.show {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Footer */
        .questionnaire-footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #718096;
            font-size: 0.85rem;
        }

        .questionnaire-footer a {
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .questionnaire-container {
                padding: 15px 15px 40px;
            }

            .questionnaire-header {
                padding: 35px 25px;
            }

            .questionnaire-header h1 {
                font-size: 1.7rem;
            }

            .questionnaire-body {
                padding: 25px 20px;
            }

            .question-card {
                padding: 22px 18px;
            }

            .question-header {
                flex-direction: column;
                gap: 12px;
            }

            .question-text {
                padding-top: 0;
            }

            .options-list {
                gap: 8px;
            }

            .option-item {
                padding: 12px 14px;
            }

            .btn-submit {
                padding: 15px 35px;
                font-size: 1rem;
            }
        }

        /* Animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .question-card {
            animation: fadeIn 0.4s ease forwards;
        }

        /* Loading State */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading-overlay.show {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #e2e8f0;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 20px;
            font-size: 1.1rem;
            color: #4a5568;
            font-weight: 600;
        }

        .loading-subtext {
            margin-top: 8px;
            font-size: 0.9rem;
            color: #718096;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memproses Jawaban...</div>
        <div class="loading-subtext">Mohon tunggu, AI sedang menganalisis hasil angket kamu</div>
    </div>

    <div class="questionnaire-container">
        <div class="questionnaire-header">
            <div class="kim-badge">
                <i class="fas fa-clipboard-check"></i>
                KIM Digital Assessment
            </div>
            <h1>{{ $questionnaire->name }}</h1>
            <p>{{ $questionnaire->description }}</p>
        </div>

        <div class="questionnaire-body">
            <!-- Progress -->
            <div class="progress-section">
                <div class="progress-label">
                    <span>Progress Pengisian</span>
                    <span id="progressText">0 / {{ $questionnaire->questions->count() }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressBar" style="width: 0%"></div>
                </div>
            </div>

            <!-- Instructions -->
            @if($questionnaire->instructions)
            <div class="instructions-box">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    Petunjuk Pengisian
                </h3>
                <p>{{ $questionnaire->instructions }}</p>
                <ul class="instructions-list">
                    <li><strong>Jawab dengan jujur</strong> — Tidak ada jawaban benar atau salah</li>
                    <li><strong>Pilih satu jawaban</strong> untuk setiap pertanyaan</li>
                    <li><strong>Estimasi waktu:</strong> {{ $questionnaire->duration_minutes ?? 15 }} menit</li>
                </ul>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('digital.questionnaire.submit', $response->id) }}" method="POST" id="questionnaireForm">
                @csrf

                @foreach($questionnaire->questions->sortBy('order') as $question)
                <div class="question-card" data-question-id="{{ $question->id }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    @if($question->dimension)
                    <div class="dimension-badge">{{ $question->dimension->name }}</div>
                    @endif

                    <div class="question-header">
                        <div class="question-number">{{ $loop->iteration }}</div>
                        <div class="question-text">{{ $question->question_text }}</div>
                    </div>

                    <div class="options-list">
                        @php
                            $options = [
                                1 => 'Sangat Tidak Setuju',
                                2 => 'Tidak Setuju',
                                3 => 'Netral',
                                4 => 'Setuju',
                                5 => 'Sangat Setuju',
                            ];
                        @endphp
                        
                        @foreach($options as $value => $label)
                        <div class="option-item" onclick="selectOption(this)">
                            <input type="radio" 
                                   id="q{{ $question->id }}_{{ $value }}" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $value }}"
                                   required>
                            <label for="q{{ $question->id }}_{{ $value }}">
                                {{ $value }} — {{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Submit -->
                <div class="submit-section">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Jawaban
                    </button>
                    <div class="validation-message" id="validationMsg">
                        <i class="fas fa-exclamation-circle"></i>
                        Mohon jawab semua pertanyaan sebelum mengirim
                    </div>
                </div>
            </form>
        </div>

        <div class="questionnaire-footer">
            <p>© {{ date('Y') }} <a href="{{ url('/') }}">PT KIM Eduverse</a> — Psikologi • Pendidikan • Digital Assessment</p>
        </div>
    </div>

    <script>
        const totalQuestions = {{ $questionnaire->questions->count() }};

        // Select option
        function selectOption(element) {
            const radio = element.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remove selected class from all options in this question
            const questionCard = element.closest('.question-card');
            questionCard.querySelectorAll('.option-item').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class
            element.classList.add('selected');
            
            // Mark question as answered
            questionCard.classList.add('answered');
            
            // Update progress
            updateProgress();
            
            // Hide validation message
            document.getElementById('validationMsg').classList.remove('show');
        }

        // Update progress bar
        function updateProgress() {
            const answeredQuestions = document.querySelectorAll('.question-card.answered').length;
            const percentage = (answeredQuestions / totalQuestions) * 100;
            
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = answeredQuestions + ' / ' + totalQuestions;
        }

        // Form submission
        document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
            const answeredQuestions = document.querySelectorAll('.question-card.answered').length;
            
            if (answeredQuestions < totalQuestions) {
                e.preventDefault();
                document.getElementById('validationMsg').classList.add('show');
                
                // Scroll to first unanswered
                const firstUnanswered = document.querySelector('.question-card:not(.answered)');
                if (firstUnanswered) {
                    firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstUnanswered.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        firstUnanswered.style.animation = '';
                    }, 500);
                }
                
                return false;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.add('show');
            
            // Disable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });

        // Initialize - check if any pre-filled
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="radio"]:checked').forEach(function(radio) {
                const optionItem = radio.closest('.option-item');
                if (optionItem) {
                    optionItem.classList.add('selected');
                    optionItem.closest('.question-card').classList.add('answered');
                }
            });
            updateProgress();
        });
    </script>

    @if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
    @endif
</body>
</html>
