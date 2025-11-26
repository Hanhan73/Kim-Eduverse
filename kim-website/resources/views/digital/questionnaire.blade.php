@extends('layouts.app')

@section('title', $questionnaire->name . ' - KIM Digital')

@push('styles')
<style>
    .questionnaire-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .questionnaire-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 50px 40px;
        border-radius: 20px 20px 0 0;
        text-align: center;
    }

    .questionnaire-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .questionnaire-header p {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .questionnaire-body {
        background: white;
        padding: 40px;
        border: 2px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 20px 20px;
    }

    .progress-section {
        margin-bottom: 40px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: #718096;
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
        transition: width 0.3s ease;
    }

    .instructions-box {
        background: #f8f9ff;
        border: 2px solid #e0e7ff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 40px;
    }

    .instructions-box h3 {
        font-size: 1.2rem;
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
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .instructions-box ul {
        margin-top: 15px;
        padding-left: 25px;
    }

    .instructions-box li {
        color: #4a5568;
        margin-bottom: 8px;
    }

    .question-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.1);
    }

    .question-card.answered {
        border-color: #48bb78;
        background: #f0fff4;
    }

    .question-header {
        display: flex;
        align-items: start;
        gap: 15px;
        margin-bottom: 20px;
    }

    .question-number {
        width: 40px;
        height: 40px;
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
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        line-height: 1.6;
    }

    .options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .option-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-item:hover {
        border-color: #667eea;
        background: #f8f9ff;
        transform: translateX(5px);
    }

    .option-item input[type="radio"] {
        width: 22px;
        height: 22px;
        margin-right: 15px;
        cursor: pointer;
        accent-color: #667eea;
    }

    .option-item label {
        flex: 1;
        cursor: pointer;
        font-size: 1rem;
        color: #4a5568;
    }

    .option-item.selected {
        background: linear-gradient(135deg, #f8f9ff, #f0f4ff);
        border-color: #667eea;
        border-width: 3px;
    }

    .option-item.selected label {
        color: #667eea;
        font-weight: 600;
    }

    .dimension-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #667eea;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .submit-section {
        margin-top: 40px;
        text-align: center;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 15px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 18px 50px;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .validation-message {
        color: #f56565;
        font-size: 0.9rem;
        margin-top: 10px;
        display: none;
    }

    .validation-message.show {
        display: block;
    }

    @media (max-width: 768px) {
        .questionnaire-header {
            padding: 30px 20px;
        }

        .questionnaire-header h1 {
            font-size: 2rem;
        }

        .questionnaire-body {
            padding: 25px 20px;
        }

        .question-card {
            padding: 20px;
        }

        .options-list {
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="questionnaire-container">
    <div class="questionnaire-header">
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
            <ul>
                <li><strong>Jawablah dengan jujur</strong> - Tidak ada jawaban benar atau salah</li>
                <li><strong>Pilih satu jawaban</strong> untuk setiap pertanyaan</li>
                <li><strong>Waktu estimasi:</strong> {{ $questionnaire->duration_minutes }} menit</li>
            </ul>
        </div>
        @endif

        <!-- Questionnaire Form -->
        <form action="{{ route('digital.questionnaire.submit', $response->id) }}" method="POST" id="questionnaireForm">
            @csrf

            @foreach($questionnaire->questions as $question)
            <div class="question-card" data-question-id="{{ $question->id }}">
                @if($question->dimension)
                <div class="dimension-badge">{{ $question->dimension->name }}</div>
                @endif

                <div class="question-header">
                    <div class="question-number">{{ $loop->iteration }}</div>
                    <div class="question-text">{{ $question->question_text }}</div>
                </div>

                <div class="options-list">
                    @for($value = 1; $value <= 5; $value++)
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" 
                               id="q{{ $question->id }}_{{ $value }}" 
                               name="answers[{{ $question->id }}]" 
                               value="{{ $value }}"
                               onchange="updateProgress()"
                               required>
                        <label for="q{{ $question->id }}_{{ $value }}">
                            {{ $value }} - 
                            @if($value == 1) Sangat Tidak Setuju
                            @elseif($value == 2) Tidak Setuju
                            @elseif($value == 3) Netral
                            @elseif($value == 4) Setuju
                            @else Sangat Setuju
                            @endif
                        </label>
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach

            <!-- Submit Section -->
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
</div>

<script>
// Select option
function selectOption(element) {
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;
    
    // Remove selected class from all options in this question
    const questionCard = element.closest('.question-card');
    questionCard.querySelectorAll('.option-item').forEach(opt => {
        opt.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    element.classList.add('selected');
    
    // Mark question as answered
    questionCard.classList.add('answered');
    
    // Update progress
    updateProgress();
}

// Update progress bar
function updateProgress() {
    const totalQuestions = {{ $questionnaire->questions->count() }};
    const answeredQuestions = document.querySelectorAll('.question-card.answered').length;
    const percentage = (answeredQuestions / totalQuestions) * 100;
    
    document.getElementById('progressBar').style.width = percentage + '%';
    document.getElementById('progressText').textContent = answeredQuestions + ' / ' + totalQuestions;
}

// Form validation
document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
    const totalQuestions = {{ $questionnaire->questions->count() }};
    const answeredQuestions = document.querySelectorAll('.question-card.answered').length;
    
    if (answeredQuestions < totalQuestions) {
        e.preventDefault();
        document.getElementById('validationMsg').classList.add('show');
        
        // Scroll to first unanswered question
        const firstUnanswered = document.querySelector('.question-card:not(.answered)');
        if (firstUnanswered) {
            firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstUnanswered.style.animation = 'shake 0.5s';
        }
        
        return false;
    }
    
    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
});

// Shake animation for unanswered questions
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);
</script>

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
@endsection
