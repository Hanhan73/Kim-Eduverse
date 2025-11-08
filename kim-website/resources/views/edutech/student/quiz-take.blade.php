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
                    <i class="fas fa-arrow-left"></i> Back to Course
                </a>
            </div>
        </div>

        <!-- QUIZ SECTION -->
        <div class="quiz-container">
            <div class="quiz-header">
                <div class="quiz-title">
                    <i class="fas fa-clipboard-list"></i> {{ $quiz->title }}
                </div>
                <div style="color: rgba(255,255,255,0.9);">{{ $quiz->course->title }}</div>
                <div class="quiz-meta">
                    <div class="quiz-meta-item"><i class="fas fa-question-circle"></i> <span>{{ $quiz->questions->count() }} Questions</span></div>
                    <div class="quiz-meta-item"><i class="fas fa-trophy"></i> <span>Passing Score: {{ $quiz->passing_score }}%</span></div>
                    <div class="quiz-meta-item"><i class="fas fa-star"></i> <span>{{ $quiz->total_points }} Points Total</span></div>
                </div>
                <div class="timer-section">
                    <i class="fas fa-clock"></i>
                    <span>Time Remaining:</span>
                    <span id="timer" class="timer">{{ $quiz->duration_minutes }}:00</span>
                </div>
            </div>

            <div class="quiz-body">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressBar" style="width: 0%;"></div>
                </div>

                <div class="question-navigator">
                    @foreach($quiz->questions as $index => $q)
                        <div class="nav-question" id="nav-{{ $q->id }}" onclick="goToQuestion({{ $index }})">{{ $index + 1 }}</div>
                    @endforeach
                </div>

                <form id="quizForm" method="POST" action="{{ route('edutech.student.quiz.submit', $quiz->id) }}">
                    @csrf
                    <div id="questionsContainer">
                        @foreach($quiz->questions as $index => $question)
                            <div class="question-card" id="question-{{ $index }}" style="{{ $index > 0 ? 'display:none;' : '' }}">
                                <div>
                                    <span class="question-number">{{ $index + 1 }}</span>
                                    <span class="question-points">{{ $question->points }} points</span>
                                </div>
                                <div class="question-text">{{ $question->question }}</div>

                                <div class="answer-options">
                                    @if($question->type == 'multiple_choice')
                                        @foreach($question->options as $option)
                                            <label class="answer-option">
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" onchange="markAnswered({{ $question->id }})">
                                                <span class="option-label">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @elseif($question->type == 'true_false')
                                        <label class="answer-option">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="True" onchange="markAnswered({{ $question->id }})">
                                            <span class="option-label">True</span>
                                        </label>
                                        <label class="answer-option">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="False" onchange="markAnswered({{ $question->id }})">
                                            <span class="option-label">False</span>
                                        </label>
                                    @elseif($question->type == 'essay')
                                        <textarea class="essay-answer" name="answers[{{ $question->id }}]" placeholder="Type your answer here..." onchange="markAnswered({{ $question->id }})"></textarea>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="quiz-navigation">
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeQuestion(-1)"><i class="fas fa-arrow-left"></i> Previous</button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeQuestion(1)">Next <i class="fas fa-arrow-right"></i></button>
                        </div>
                        <button type="submit" class="btn btn-submit" id="submitBtn" style="display:none;"><i class="fas fa-check-circle"></i> Submit Quiz</button>
                    </div>
                </form>
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