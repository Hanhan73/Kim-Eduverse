<!-- resources/views/edutech/student/partials/quiz-taking.blade.php -->
<div class="quiz-taking">
    <form id="quizForm" action="{{ route('edutech.student.quiz.submit', $currentQuiz->id) }}" method="POST">
        @csrf
        <input type="hidden" id="quizDuration" value="{{ $currentQuiz->duration_minutes }}">

        <div class="timer-section">
            <i class="fas fa-clock"></i>
            <span>Time Remaining:</span>
            <span id="timer" class="timer">{{ $currentQuiz->duration_minutes }}:00</span>
        </div>

        @if($currentQuiz->randomize_questions && ($currentQuiz->type === 'pre_test' || $currentQuiz->type ===
        'post_test'))
        <div class="alert alert-info" style="background: white; color: #333; margin-bottom: 20px;">
            <i class="fas fa-random"></i> Pertanyaan dalam quiz ini diacak untuk setiap attempt
        </div>
        @endif

        <!-- Progress Bar -->
        <div style="background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #333; font-weight: 600;">Progress</span>
                <span style="color: #666;" id="progressText">Question 1 of {{ $currentQuiz->questions->count() }}</span>
            </div>
            <div style="width: 100%; height: 8px; background: #e0e0e0; border-radius: 10px; overflow: hidden;">
                <div id="progressBar"
                    style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 0.3s ease;">
                </div>
            </div>
        </div>

        <!-- Question Navigator -->
        <div class="question-navigator">
            @php
            // Debug: Log the question_order from attempt
            logger('Question Order from Attempt: ' . ($currentAttempt->question_order ?? 'NULL'));

            // Ambil urutan soal dari attempt (jika ada)
            if ($currentAttempt && $currentAttempt->question_order) {
            $questionOrder = json_decode($currentAttempt->question_order, true);
            logger('Decoded Question Order: ' . print_r($questionOrder, true));
            } else {
            // Jika tidak ada, gunakan urutan default
            $questionOrder = $currentQuiz->questions->pluck('id')->toArray();
            logger('Using Default Question Order: ' . print_r($questionOrder, true));
            }

            // Urutkan questions sesuai question_order
            $orderedQuestions = [];
            foreach ($questionOrder as $qId) {
            $question = $currentQuiz->questions->firstWhere('id', $qId);
            if ($question) {
            $orderedQuestions[] = $question;
            }
            }

            // Debug: Log the final ordered questions
            logger('Final Ordered Questions Count: ' . count($orderedQuestions));
            @endphp

            @foreach($orderedQuestions as $index => $question)
            <div class="nav-question" id="nav-{{ $question->id }}" onclick="goToQuestion({{ $index }})">
                {{ $index + 1 }}
            </div>
            @endforeach
        </div>

        <!-- Questions -->
        @foreach($orderedQuestions as $index => $question)
        <div class="question-card" id="question-{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
            <div style="margin-bottom: 20px;">
                <span class="question-number">{{ $index + 1 }}</span>
                <span class="question-points"><i class="fas fa-star"></i> {{ $question->points }} pts</span>
            </div>

            <div class="question-text">{{ $question->question }}</div>

            @if($question->type === 'multiple_choice')
            @foreach($question->options as $option)
            <label class="answer-option">
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                    onchange="markAnswered({{ $question->id }})">
                <span class="option-label">{{ $option }}</span>
            </label>
            @endforeach

            @elseif($question->type === 'true_false')
            <label class="answer-option">
                <input type="radio" name="answers[{{ $question->id }}]" value="True"
                    onchange="markAnswered({{ $question->id }})">
                <span class="option-label">True</span>
            </label>
            <label class="answer-option">
                <input type="radio" name="answers[{{ $question->id }}]" value="False"
                    onchange="markAnswered({{ $question->id }})">
                <span class="option-label">False</span>
            </label>

            @elseif($question->type === 'essay')
            <textarea name="answers[{{ $question->id }}]" class="form-control" rows="5"
                placeholder="Type your answer here..." onchange="markAnswered({{ $question->id }})"></textarea>
            @endif
        </div>
        @endforeach

        <!-- Navigation Buttons -->
        <div class="quiz-navigation">
            <div class="nav-buttons">
                <button type="button" id="prevBtn" class="btn btn-secondary" onclick="changeQuestion(-1)"
                    style="display: none;">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button type="button" id="nextBtn" class="btn btn-primary-quiz" onclick="changeQuestion(1)">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-submit" style="display: none;">
                <i class="fas fa-check-circle"></i> Submit Quiz
            </button>
        </div>
    </form>
</div>

<style>
    .quiz-taking {
        background: white;
        border-radius: 12px;
        padding: 30px;
        color: #333;
    }

    .timer-section {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .timer {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffd700;
    }

    .timer.warning {
        color: #ff6b6b;
        animation: pulse 1s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .question-navigator {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 10px;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 15px;
    }

    .nav-question {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e0e0e0;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-question.answered {
        background: var(--success);
        color: white;
        border-color: var(--success);
    }

    .nav-question.current {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .question-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        border: 2px solid #e0e0e0;
    }

    .question-number {
        display: inline-block;
        background: var(--primary);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        font-weight: 700;
        margin-right: 15px;
    }

    .question-text {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .question-points {
        display: inline-block;
        background: #ffd700;
        color: #333;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 10px;
    }

    .answer-option {
        display: block;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .answer-option:hover {
        border-color: var(--primary);
        background: #f0f4ff;
    }

    .answer-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 1;
    }

    .option-label {
        position: relative;
        z-index: 0;
        display: block;
        width: 100%;
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .answer-option input[type="radio"]:checked+.option-label {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .quiz-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #e0e0e0;
    }

    .nav-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
    }

    .btn-primary-quiz {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--success) 0%, #38a169 100%);
        color: white;
        padding: 15px 40px;
        font-size: 1.1rem;
    }
</style>

<script>
    // Make sure the first question is marked as current when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const firstNavQuestion = document.querySelector('.nav-question');
        if (firstNavQuestion) {
            firstNavQuestion.classList.add('current');
        }

        // Update progress text to show correct total
        const progressText = document.getElementById('progressText');
        if (progressText) {
            const totalQuestions = document.querySelectorAll('.question-card').length;
            progressText.textContent = 'Question 1 of ' + totalQuestions;
        }
    });
</script>