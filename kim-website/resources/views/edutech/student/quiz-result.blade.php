<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Quiz</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --dark: #2d3748;
            --gray: #718096;
            --light: #f7fafc;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
        }

        /* === TOP BAR === */
        .top-bar {
            background: #1e293b;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .top-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .progress-info {
            text-align: right;
        }

        .progress-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 5px;
        }

        .progress-bar-top {
            width: 150px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill-top {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #38a169);
            transition: width 0.3s ease;
        }

        .btn-back {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover { background: rgba(255, 255, 255, 0.2); }

        /* === QUIZ === */
        .quiz-container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .quiz-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 30px;
            color: white;
        }

        .quiz-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .quiz-meta {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .quiz-meta-item { display: flex; align-items: center; gap: 8px; }

        .timer-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .timer { font-size: 1.5rem; font-weight: 700; color: #ffd700; }
        .timer.warning { color: #ff6b6b; animation: pulse 1s infinite; }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .quiz-body { padding: 40px; }

        .progress-bar {
            background: #e0e0e0;
            height: 10px;
            border-radius: 10px;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .question-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.1);
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

        .question-text { font-size: 1.1rem; color: #2c3e50; margin-bottom: 20px; line-height: 1.6; }

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

        .answer-option:hover { border-color: var(--primary); background: #f0f4ff; }

        .answer-option input[type="radio"] { position: absolute; opacity: 0; }

        .answer-option input[type="radio"]:checked + .option-label {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .essay-answer {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            resize: vertical;
        }

        .quiz-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .nav-buttons { display: flex; gap: 15px; }

        .btn {
            padding: 12px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .btn-submit {
            background: linear-gradient(135deg, var(--success) 0%, #38a169 100%);
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
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

        .nav-question.answered { background: var(--success); color: white; border-color: var(--success); }
        .nav-question.current { background: var(--primary); color: white; border-color: var(--primary); animation: pulse 2s infinite; }
    </style>
</head>
<body>
    <div class="learning-layout">
        <div class="top-bar">
            <div class="course-info">
                <h2>{{ $course->title }}</h2>
                <p>{{ $course->instructor->name }}</p>
            </div>
            <div class="top-actions">
                <div class="progress-info">
                    <div class="progress-text">Progress: {{ $enrollment->progress_percentage }}%</div>
                    <div class="progress-bar-top">
                        <div class="progress-fill-top" style="width: {{ $enrollment->progress_percentage }}%"></div>
                    </div>
                </div>
                <a href="{{ route('edutech.student.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- QUIZ SECTION -->
<div class="result-container">
    <!-- Result Header -->
    <div class="result-header {{ $attempt->is_passed ? 'passed' : 'failed' }}">
        <div class="result-icon">
            @if($attempt->is_passed)
                <i class="fas fa-trophy"></i>
            @else
                <i class="fas fa-times-circle"></i>
            @endif
        </div>
        
        <h1 class="result-title">
            @if($attempt->is_passed)
                Congratulations! You Passed!
            @else
                Keep Trying! You Can Do Better!
            @endif
        </h1>
        
        <p class="result-message">
            {{ $quiz->title }}
        </p>

        <div class="score-display">
            <div class="score-circle">
                <div>
                    <div class="score-number">{{ number_format($attempt->score, 0) }}%</div>
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
            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-value">
                    {{ collect($attempt->answers)->where('is_correct', true)->count() }}
                </div>
                <div class="stat-label">Correct Answers</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-times-circle stat-icon" style="color: #f56565;"></i>
                <div class="stat-value">
                    {{ collect($attempt->answers)->where('is_correct', false)->count() }}
                </div>
                <div class="stat-label">Incorrect Answers</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-clock stat-icon"></i>
                <div class="stat-value">
                    {{ $attempt->duration ?? 0 }} min
                </div>
                <div class="stat-label">Time Taken</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-redo stat-icon"></i>
                <div class="stat-value">
                    {{ $attempt->attempt_number }}/{{ $quiz->max_attempts }}
                </div>
                <div class="stat-label">Attempt</div>
            </div>
        </div>

        <!-- Certificate Notice (if passed post-test with 100% progress) -->
        @if($attempt->is_passed && $quiz->type == 'post_test' && $enrollment->progress_percentage >= 100)
        <div class="certificate-notice">
            <i class="fas fa-certificate"></i>
            <h3>Certificate Earned!</h3>
            <p>Congratulations! You've completed the course and passed the final test. Your certificate is now available.</p>
            <a href="{{ route('edutech.student.courses') }}" class="btn btn-success" style="margin-top: 15px;">
                <i class="fas fa-download"></i> View Certificate
            </a>
        </div>
        @endif

        <!-- Answers Review -->
        <div class="answers-review">
            <h2 class="review-header">Review Your Answers</h2>
            
            @foreach($quiz->questions as $index => $question)
                @php
                    $userAnswer = $attempt->answers[$question->id] ?? null;
                    $isCorrect = $userAnswer && isset($userAnswer['is_correct']) && $userAnswer['is_correct'];
                @endphp
                
                <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }}">
                    <div class="review-question">
                        Question {{ $index + 1 }}: {{ $question->question }}
                        <span class="review-status {{ $isCorrect ? 'correct' : 'incorrect' }}">
                            {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                            ({{ $userAnswer['points_earned'] ?? 0 }}/{{ $question->points }} points)
                        </span>
                    </div>
                    
                    @if($userAnswer)
                    <div class="review-answer">
                        <div class="answer-label">Your Answer:</div>
                        <div class="answer-text">{{ $userAnswer['answer'] ?? 'No answer' }}</div>
                    </div>
                    @endif
                    
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
            <a href="{{ route('edutech.student.learn', $enrollment->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Course
            </a>
            
            @if(!$attempt->is_passed && $quiz->canUserAttempt(session('edutech_user_id')))
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
    </div>
    </div>


<script>
    let currentQuestion = 0;
    const totalQuestions = {{ $quiz->questions->count() }};
    const duration = {{ $quiz->duration_minutes }} * 60;
    let timeRemaining = duration;
    let answeredQuestions = new Set();

    function startTimer() {
        const timerInterval = setInterval(() => {
            timeRemaining--;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            const timerElement = document.getElementById('timer');
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2,'0')}`;
            if (timeRemaining <= 300) timerElement.classList.add('warning');
            if (timeRemaining <= 0) { clearInterval(timerInterval); document.getElementById('quizForm').submit(); }
        }, 1000);
    }

    function changeQuestion(direction) {
        document.getElementById(`question-${currentQuestion}`).style.display = 'none';
        document.getElementById(`nav-${getQuestionId(currentQuestion)}`).classList.remove('current');
        currentQuestion += direction;
        document.getElementById(`question-${currentQuestion}`).style.display = 'block';
        document.getElementById(`nav-${getQuestionId(currentQuestion)}`).classList.add('current');
        updateNavigationButtons(); updateProgressBar();
    }

    function goToQuestion(index) {
        document.getElementById(`question-${currentQuestion}`).style.display = 'none';
        document.getElementById(`nav-${getQuestionId(currentQuestion)}`).classList.remove('current');
        currentQuestion = index;
        document.getElementById(`question-${currentQuestion}`).style.display = 'block';
        document.getElementById(`nav-${getQuestionId(currentQuestion)}`).classList.add('current');
        updateNavigationButtons(); updateProgressBar();
    }

    function updateNavigationButtons() {
        document.getElementById('prevBtn').style.display = currentQuestion === 0 ? 'none' : 'block';
        document.getElementById('nextBtn').style.display = currentQuestion === totalQuestions - 1 ? 'none' : 'block';
        document.getElementById('submitBtn').style.display = currentQuestion === totalQuestions - 1 ? 'block' : 'none';
    }

    function markAnswered(questionId) {
        answeredQuestions.add(questionId);
        document.getElementById(`nav-${questionId}`).classList.add('answered');
        updateProgressBar();
    }

    function updateProgressBar() {
        const progress = ((currentQuestion + 1) / totalQuestions) * 100;
        document.getElementById('progressBar').style.width = `${progress}%`;
    }

    function getQuestionId(index) {
        const questions = @json($quiz->questions->pluck('id'));
        return questions[index];
    }

    document.getElementById('quizForm').addEventListener('submit', e => {
        e.preventDefault();
        const unanswered = totalQuestions - answeredQuestions.size;
        const msg = unanswered > 0
            ? `You have ${unanswered} unanswered question(s). Are you sure you want to submit?`
            : 'Are you sure you want to submit your quiz?';
        if (confirm(msg)) e.target.submit();
    });

    document.addEventListener('DOMContentLoaded', () => {
        startTimer(); updateNavigationButtons(); updateProgressBar();
        document.getElementById(`nav-${getQuestionId(0)}`).classList.add('current');
    });

    window.addEventListener('beforeunload', e => {
        e.preventDefault();
        e.returnValue = 'Your quiz progress may be lost if you leave this page.';
    });
</script>

</body>
</html>