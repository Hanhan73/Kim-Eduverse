{{-- resources/views/edutech/student/partials/quiz-result.blade.php --}}

@php
// Dapatkan data quiz dari attempt
$quiz = $attempt->quiz;
@endphp

<div class="result-container">
    <!-- Result Header -->
    <div class="result-header {{ $attempt->is_passed ? 'passed' : 'failed' }}">
        <div class="result-icon-wrapper">
            <div class="result-icon">
                @if($attempt->is_passed)
                <i class="fas fa-trophy"></i>
                @else
                <i class="fas fa-times-circle"></i>
                @endif
            </div>
        </div>

        <h1 class="result-title animate-fade-in-up">
            @if($attempt->is_passed)
            Congratulations! You Passed!
            @else
            Keep Trying! You Can Do Better!
            @endif
        </h1>

        <p class="result-message animate-fade-in-up-delay-1">
            {{ $quiz->title }}
        </p>

        <div class="score-display animate-fade-in-up-delay-2">
            <div class="score-circle animate-scale-in">
                <div>
                    <div class="score-number" id="score-number">{{ number_format($attempt->score, 0) }}%</div>
                </div>
            </div>
            <div class="score-label">
                @if($attempt->is_passed)
                You scored above the passing score of {{ $quiz->passing_score }}%
                @else
                You need {{ $quiz->passing_score }}% to pass
                @endif
            </div>
        </div>
    </div>

    <!-- Result Body -->
    <div class="result-body">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card animate-fade-in-up">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-value"
                    data-target="{{ collect($attempt->answers)->where('is_correct', true)->count() }}">0</div>
                <div class="stat-label">Correct Answers</div>
            </div>

            <div class="stat-card animate-fade-in-up">
                <i class="fas fa-times-circle stat-icon" style="color: #f56565;"></i>
                <div class="stat-value"
                    data-target="{{ collect($attempt->answers)->where('is_correct', false)->count() }}">0</div>
                <div class="stat-label">Incorrect Answers</div>
            </div>

            <div class="stat-card animate-fade-in-up">
                <i class="fas fa-clock stat-icon"></i>
                <div class="stat-value">{{ $attempt->duration ?? 0 }} min</div>
                <div class="stat-label">Time Taken</div>
            </div>

            <div class="stat-card animate-fade-in-up">
                <i class="fas fa-redo stat-icon"></i>
                <div class="stat-value">{{ $attempt->attempt_number }}/{{ $quiz->max_attempts }}</div>
                <div class="stat-label">Attempt</div>
            </div>
        </div>

        <!-- Certificate Notice -->
        @if($attempt->is_passed && $quiz->type == 'post_test' && $enrollment->progress_percentage >= 100)
        <div class="certificate-notice">
            <i class="fas fa-certificate"></i>
            <h3>Certificate Earned!</h3>
            <p>Congratulations! You've completed the course and passed the final test. Your certificate is now
                available.</p>
            <a href="{{ route('edutech.student.courses') }}" class="btn btn-success" style="margin-top: 15px;">
                <i class="fas fa-download"></i> View Certificate
            </a>
        </div>
        @endif

        <!-- Answers Review -->
        <div class="answers-review">
            <h2 class="review-header">Review Your Answers</h2>

            @php
            // Ambil urutan pertanyaan dari attempt
            $questionOrder = json_decode($attempt->question_order, true) ?? [];

            // Urutkan pertanyaan sesuai dengan yang disimpan
            $orderedQuestions = [];
            foreach ($questionOrder as $questionId) {
            $question = $quiz->questions->where('id', $questionId)->first();
            if ($question) {
            $orderedQuestions[] = $question;
            }
            }

            // Jika tidak ada urutan yang tersimpan, gunakan urutan default
            if (empty($orderedQuestions)) {
            $orderedQuestions = $quiz->questions->all();
            }
            @endphp

            @foreach($orderedQuestions as $index => $question)
            @php
            $userAnswerData = null;

            if (is_array($attempt->answers) && isset($attempt->answers[$question->id])) {
            $userAnswerData = $attempt->answers[$question->id];
            }

            $isCorrect = $userAnswerData && isset($userAnswerData['is_correct']) ? $userAnswerData['is_correct'] :
            false;
            $userAnswer = $userAnswerData['answer'] ?? 'No answer';
            $pointsEarned = $userAnswerData['points_earned'] ?? 0;
            @endphp

            <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }} animate-fade-in-up"
                style="animation-delay: {{ $index * 0.1 }}s;">
                <div class="review-question">
                    Question {{ $index + 1 }}: {{ $question->question }}
                    <span class="review-status {{ $isCorrect ? 'correct' : 'incorrect' }}">
                        {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                        ({{ $pointsEarned }}/{{ $question->points }} points)
                    </span>
                </div>

                <div class="review-answer">
                    <div class="answer-label">Your Answer:</div>
                    <div class="answer-text">{{ $userAnswer }}</div>
                </div>

                @if(!$isCorrect && $question->type != 'essay')
                <div class="correct-answer">
                    <i class="fas fa-check"></i> Correct Answer: {{ $question->correct_answer }}
                </div>
                @endif

                @if($question->type == 'essay')
                <div style="margin-top: 10px; color: #6c757d; font-style: italic;">
                    <i class="fas fa-info-circle"></i> Essay answers will be reviewed by the instructor
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('edutech.courses.learn', $enrollment->course->slug) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Course
            </a>

            @if(!$attempt->is_passed && $attempt->attempt_number < $quiz->max_attempts)
                <a href="{{ route('edutech.student.quiz.start', $quiz->id) }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Try Again
                </a>
                @endif

                <a href="{{ route('edutech.student.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go to Dashboard
                </a>
        </div>
    </div>
</div>

<style>
    /* --- Enhanced & Wider Styles --- */
    body {
        background: #f0f2f5;
        /* Lighter background for better contrast */
    }

    .result-container {
        width: 95%;
        /* Take up most of the screen width */
        max-width: 1400px;
        /* But cap it at a reasonable size for ultra-wide screens */
        margin: 30px auto;
        /* Reduced top/bottom margin */
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        /* Softer shadow */
        font-family: 'Inter', sans-serif;
    }

    .result-header {
        padding: 60px 60px;
        /* Increased horizontal padding */
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .result-header.passed {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .result-header.failed {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        color: white;
    }

    .result-icon-wrapper {
        margin-bottom: 20px;
    }

    .result-icon {
        font-size: 5rem;
        display: inline-block;
        animation: icon-bounce 1s ease-out;
    }

    .result-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .result-message {
        font-size: 1.2rem;
        opacity: 0.95;
        max-width: 80%;
        margin: 0 auto;
    }

    .score-display {
        margin: 40px 0;
    }

    .score-circle {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 5px solid rgba(255, 255, 255, 0.3);
    }

    .score-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: #333;
        line-height: 1;
    }

    .score-label {
        font-size: 1rem;
        margin-top: 20px;
        opacity: 0.9;
    }

    .result-body {
        padding: 50px 60px;
        /* Increased horizontal padding */
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        /* Slightly larger min-width */
        gap: 25px;
        margin-bottom: 50px;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 30px 20px;
        border-radius: 20px;
        text-align: center;
        border: 2px solid #e0e0e0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .certificate-notice {
        background: linear-gradient(135deg, #ffd700 0%, #ffb347 100%);
        color: #333;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        margin: 40px 0;
        box-shadow: 0 10px 25px rgba(255, 215, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .certificate-notice i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .answers-review {
        margin-top: 50px;
    }

    .review-header {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    .question-review {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        border-left: 5px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .question-review.correct {
        border-left-color: #48bb78;
        background: #f0fff4;
    }

    .question-review.incorrect {
        border-left-color: #f56565;
        background: #fff5f5;
    }

    .review-question {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .review-status {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 10px;
    }

    .review-status.correct {
        background: #48bb78;
        color: white;
    }

    .review-status.incorrect {
        background: #f56565;
        color: white;
    }

    .review-answer {
        margin-top: 15px;
        padding: 15px;
        border-radius: 10px;
        background: white;
    }

    .answer-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .answer-text {
        color: #2c3e50;
        font-size: 1rem;
    }

    .correct-answer {
        margin-top: 15px;
        padding: 15px;
        background: #d4edda;
        border-radius: 10px;
        color: #155724;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 50px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 15px 35px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background: #d0d0d0;
        transform: translateY(-3px);
    }

    .btn-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
    }

    /* --- Animations --- */
    @keyframes icon-bounce {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scale-in {
        from {
            transform: scale(0);
        }

        to {
            transform: scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-up-delay-1 {
        animation: fade-in-up 0.6s 0.2s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-up-delay-2 {
        animation: fade-in-up 0.6s 0.4s ease-out forwards;
        opacity: 0;
    }

    .animate-scale-in {
        animation: scale-in 0.5s 0.6s ease-out forwards;
        transform: scale(0);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Animate Score Counter ---
        const scoreElement = document.getElementById('score-number');
        if (scoreElement) {
            const finalScore = parseInt(scoreElement.innerText);
            let currentScore = 0;
            const increment = finalScore / 50; // Duration of animation
            const timer = setInterval(() => {
                currentScore += increment;
                if (currentScore >= finalScore) {
                    currentScore = finalScore;
                    clearInterval(timer);
                }
                scoreElement.innerText = Math.floor(currentScore) + '%';
            }, 30);
        }

        // --- Animate Stat Counters ---
        const statValues = document.querySelectorAll('.stat-value[data-target]');
        statValues.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-target'));
            let count = 0;
            const increment = target / 40;
            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    count = target;
                    clearInterval(timer);
                }
                stat.innerText = Math.floor(count);
            }, 30);
        });
    });
</script>