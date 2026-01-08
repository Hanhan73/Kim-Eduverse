<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Learning</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #667eea; --secondary: #764ba2; --success: #48bb78;
            --warning: #ed8936; --danger: #f56565; --info: #4299e1;
            --dark: #2d3748; --gray: #718096; --light: #f7fafc;
        }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; }
        
        /* Layout */
        .learning-layout { display: flex; height: 100vh; overflow: hidden; }
        .main-area { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        
        /* Top Bar */
        .top-bar { background: #1e293b; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .course-info h2 { font-size: 1.2rem; margin-bottom: 5px; }
        .course-info p { font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); }
        .top-actions { display: flex; gap: 15px; align-items: center; }
        .progress-info { text-align: right; }
        .progress-text { font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-bottom: 5px; }
        .progress-bar-top { width: 150px; height: 6px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; overflow: hidden; }
        .progress-fill-top { height: 100%; background: linear-gradient(90deg, var(--success), #38a169); transition: width 0.3s ease; }
        .btn-back { padding: 10px 20px; background: rgba(255, 255, 255, 0.1); border: none; border-radius: 8px; color: white; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
        .btn-back:hover { background: rgba(255, 255, 255, 0.2); }

        /* Content Area */
        .content-wrapper { flex: 1; overflow-y: auto; display: flex; }
        .content-display { flex: 1; padding: 30px; }

        /* Video Player */
        .video-container { position: relative; padding-bottom: 56.25%; height: 0; background: #000; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

        /* PDF Viewer */
        .pdf-viewer { background: white; border-radius: 12px; overflow: hidden; height: 80vh; }
        .pdf-viewer iframe { width: 100%; height: 100%; border: none; }

        /* Lesson Info */
        .lesson-header { margin-bottom: 25px; }
        .lesson-header h1 { font-size: 2rem; margin-bottom: 10px; }
        .lesson-meta { display: flex; gap: 20px; color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; }
        .lesson-description { background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 12px; margin-bottom: 20px; line-height: 1.6; }

        /* Lesson Actions */
        .lesson-actions { display: flex; gap: 15px; margin-top: 20px; }
        .btn { padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .btn-complete { background: linear-gradient(135deg, var(--success), #38a169); color: white; }
        .btn-complete:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3); }
        .btn-complete.completed { background: gray; cursor: not-allowed; }
        .btn-next { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; }
        .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); }

        /* Quiz Display */
        .quiz-display { background: rgba(255, 255, 255, 0.05); padding: 30px; border-radius: 12px; margin-bottom: 20px; }
        .quiz-header-display { display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; }
        .quiz-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 10px; }
        .quiz-badge { padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .quiz-badge.pre-test { background: #feebc8; color: #7c2d12; }
        .quiz-badge.post-test { background: #c6f6d5; color: #22543d; }
        .quiz-badge.module-quiz { background: #e6fffa; color: #234e52; }
        .quiz-info { display: flex; gap: 30px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .quiz-info-item { display: flex; align-items: center; gap: 10px; color: rgba(255, 255, 255, 0.7); }
        .quiz-info-item i { color: var(--info); }

        /* Quiz Taking */
        .quiz-taking { background: white; border-radius: 12px; padding: 30px; color: #333; }
        .timer-section { background: rgba(255, 255, 255, 0.1); padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px; color: white; }
        .timer { font-size: 1.5rem; font-weight: 700; color: #ffd700; }
        .timer.warning { color: #ff6b6b; animation: pulse 1s infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        
        .question-navigator { display: grid; grid-template-columns: repeat(10, 1fr); gap: 10px; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 15px; }
        .nav-question { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #e0e0e0; background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
        .nav-question.answered { background: var(--success); color: white; border-color: var(--success); }
        .nav-question.current { background: var(--primary); color: white; border-color: var(--primary); }

        .question-card { background: #f8f9fa; border-radius: 15px; padding: 30px; margin-bottom: 30px; border: 2px solid #e0e0e0; }
        .question-number { display: inline-block; background: var(--primary); color: white; width: 35px; height: 35px; border-radius: 50%; text-align: center; line-height: 35px; font-weight: 700; margin-right: 15px; }
        .question-text { font-size: 1.1rem; color: #2c3e50; margin-bottom: 20px; line-height: 1.6; }
        .question-points { display: inline-block; background: #ffd700; color: #333; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-left: 10px; }

        .answer-option { display: block; background: white; border: 2px solid #e0e0e0; border-radius: 10px; padding: 15px 20px; margin-bottom: 12px; cursor: pointer; transition: all 0.3s ease; position: relative; }
        .answer-option:hover { border-color: var(--primary); background: #f0f4ff; }
        .answer-option input[type="radio"] { position: absolute; opacity: 0; cursor: pointer; width: 100%; height: 100%; left: 0; top: 0; z-index: 1; }
        .option-label { position: relative; z-index: 0; display: block; width: 100%; padding: 10px 15px; border-radius: 8px; transition: all 0.3s ease; }
        .answer-option input[type="radio"]:checked + .option-label { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; }

        .quiz-navigation { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; padding-top: 30px; border-top: 2px solid #e0e0e0; }
        .nav-buttons { display: flex; gap: 15px; }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-primary-quiz { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; }
        .btn-submit { background: linear-gradient(135deg, var(--success) 0%, #38a169 100%); color: white; padding: 15px 40px; font-size: 1.1rem; }

        /* Quiz Result */
        .result-header { padding: 40px; text-align: center; border-radius: 12px 12px 0 0; }
        .result-header.passed { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
        .result-header.failed { background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); }
        .result-icon { font-size: 4rem; margin-bottom: 20px; }
        .result-title { font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
        .score-display { margin: 30px 0; }
        .score-circle { width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2); }
        .score-number { font-size: 3rem; font-weight: 800; color: #333; }

        .result-body { padding: 40px; background: white; color: #333; border-radius: 0 0 12px 12px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 15px; text-align: center; border: 2px solid #e0e0e0; }
        .stat-icon { font-size: 2rem; color: #667eea; margin-bottom: 10px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin-bottom: 5px; }
        .stat-label { font-size: 0.9rem; color: #6c757d; }

        .action-buttons { display: flex; gap: 15px; justify-content: center; margin-top: 40px; }

        /* Sidebar */
        .sidebar { width: 380px; background: #1e293b; border-left: 1px solid rgba(255, 255, 255, 0.1); display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-content { flex: 1; overflow-y: auto; padding: 20px; }

        /* Modules & Lessons */
        .module-accordion { margin-bottom: 15px; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; overflow: hidden; }
        .module-header { padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.3s ease; }
        .module-header:hover { background: rgba(255, 255, 255, 0.05); }
        .module-title { font-size: 1rem; font-weight: 700; }
        .module-icon { transition: transform 0.3s ease; }
        .module-accordion.active .module-icon { transform: rotate(180deg); }
        .lesson-list { display: none; background: rgba(0, 0, 0, 0.3); }
        .module-accordion.active .lesson-list { display: block; }

        .lesson-item, .quiz-item { padding: 15px 20px 15px 40px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: all 0.3s ease; border-left: 3px solid transparent; text-decoration: none; color: rgba(255, 255, 255, 0.8); }
        .lesson-item:hover, .quiz-item:hover { background: rgba(255, 255, 255, 0.05); }
        .lesson-item.active, .quiz-item.active { background: rgba(102, 126, 234, 0.2); border-left-color: var(--primary); }
        .lesson-item.locked, .quiz-item.locked { opacity: 0.5; cursor: not-allowed; }
        .lesson-info, .quiz-info-sidebar { flex: 1; }
        .lesson-name, .quiz-name { font-size: 0.9rem; font-weight: 500; }
        .lesson-duration, .quiz-duration { font-size: 0.75rem; color: rgba(255, 255, 255, 0.5); }

        .empty-state { text-align: center; padding: 60px 20px; color: rgba(255, 255, 255, 0.5); }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; opacity: 0.3; }

        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-warning { background: rgba(237, 137, 54, 0.1); border: 2px solid var(--warning); color: var(--warning); }
        .alert-info { background: rgba(66, 153, 225, 0.1); border: 2px solid var(--info); color: var(--info); }

        .locked-message { background: rgba(245, 101, 101, 0.1); border: 2px solid var(--danger); padding: 20px; border-radius: 12px; text-align: center; }

        @media (max-width: 968px) {
            .learning-layout { flex-direction: column; }
            .sidebar { width: 100%; max-height: 40vh; }
        }
    </style>
</head>
<body>
    <div class="learning-layout">
        <div class="main-area">
            <!-- Top Bar -->
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

            <!-- Content Display -->
            <div class="content-wrapper">
                <div class="content-display">
                    @if(session('success'))
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> {{ session('info') }}
                    </div>
                    @endif

                    @if($ongoingQuiz)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        You have an ongoing quiz: <strong>{{ $ongoingQuiz->quiz->title }}</strong>. 
                        Complete it before accessing other content.
                        <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'quiz' => $ongoingQuiz->quiz_id]) }}" style="color: var(--warning); text-decoration: underline;">
                            Continue Quiz
                        </a>
                    </div>
                    @endif

                    <!-- LESSON VIEW -->
                    @if($currentView === 'lesson' && $currentLesson)
                        @include('edutech.student.partials.lesson-display')
                    
                    <!-- QUIZ VIEW -->
                    @elseif($currentView === 'quiz' && $currentQuiz)
                        @if(request()->has('result'))
                            @include('edutech.student.partials.quiz-result')
                        @elseif($currentAttempt)
                            @include('edutech.student.partials.quiz-taking')
                        @else
                            @include('edutech.student.partials.quiz-preview')
                        @endif
                    
                    <!-- PRE-TEST GATE -->
                    @elseif(!$canAccessMaterials)
                        <div class="locked-message">
                            <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 20px;"></i>
                            <h2>Pre-Test Required</h2>
                            <p style="margin: 15px 0;">You must pass the pre-test before accessing course materials.</p>
                            @if($preTest)
                                <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'quiz' => $preTest->id]) }}" class="btn btn-next">
                                    <i class="fas fa-play-circle"></i> Take Pre-Test
                                </a>
                            @endif
                        </div>

                    @else
                        <div class="empty-state">
                            <i class="fas fa-book-open"></i>
                            <h3>Select a Lesson or Quiz</h3>
                            <p>Choose from the sidebar to start learning</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                @include('edutech.student.partials.learning-sidebar')
            </div>
        </div>
    </div>

    <script>
        function toggleModule(element) {
            const module = element.parentElement;
            module.classList.toggle('active');
        }

        function markAsComplete(lessonId) {
            fetch(`/edutech/learning/lesson/${lessonId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Quiz functions
        let currentQuestion = 0;
        let answeredQuestions = new Set();

        function startTimer(duration) {
            let timeRemaining = duration * 60;
            const timerInterval = setInterval(() => {
                timeRemaining--;
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                const timerElement = document.getElementById('timer');
                if (timerElement) {
                    timerElement.textContent = `${minutes}:${seconds.toString().padStart(2,'0')}`;
                    if (timeRemaining <= 300) timerElement.classList.add('warning');
                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        document.getElementById('quizForm').submit();
                    }
                }
            }, 1000);
        }

        function changeQuestion(direction) {
            const questions = document.querySelectorAll('.question-card');
            questions[currentQuestion].style.display = 'none';
            
            const navQuestions = document.querySelectorAll('.nav-question');
            navQuestions[currentQuestion].classList.remove('current');
            
            currentQuestion += direction;
            questions[currentQuestion].style.display = 'block';
            navQuestions[currentQuestion].classList.add('current');
            
            updateNavigationButtons();
            updateProgressBar();
        }

        function goToQuestion(index) {
            const questions = document.querySelectorAll('.question-card');
            const navQuestions = document.querySelectorAll('.nav-question');
            
            questions[currentQuestion].style.display = 'none';
            navQuestions[currentQuestion].classList.remove('current');
            
            currentQuestion = index;
            questions[currentQuestion].style.display = 'block';
            navQuestions[currentQuestion].classList.add('current');
            
            updateNavigationButtons();
            updateProgressBar();
        }

        function updateNavigationButtons() {
            const totalQuestions = document.querySelectorAll('.question-card').length;
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            if (prevBtn) prevBtn.style.display = currentQuestion === 0 ? 'none' : 'block';
            if (nextBtn) nextBtn.style.display = currentQuestion === totalQuestions - 1 ? 'none' : 'block';
            if (submitBtn) submitBtn.style.display = currentQuestion === totalQuestions - 1 ? 'block' : 'none';
        }

        function markAnswered(questionId) {
            answeredQuestions.add(questionId);
            const navQuestion = document.getElementById(`nav-${questionId}`);
            if (navQuestion) navQuestion.classList.add('answered');
            updateProgressBar();
        }

        function updateProgressBar() {
            const totalQuestions = document.querySelectorAll('.question-card').length;
            const progress = ((currentQuestion + 1) / totalQuestions) * 100;
            const progressBar = document.getElementById('progressBar');
            if (progressBar) progressBar.style.width = `${progress}%`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const quizForm = document.getElementById('quizForm');
            if (quizForm) {
                const durationElement = document.getElementById('quizDuration');
                if (durationElement) {
                    startTimer(parseInt(durationElement.value));
                }
                
                updateNavigationButtons();
                updateProgressBar();
                
                const firstNavQuestion = document.querySelector('.nav-question');
                if (firstNavQuestion) firstNavQuestion.classList.add('current');
                
                quizForm.addEventListener('submit', e => {
                    e.preventDefault();
                    const totalQuestions = document.querySelectorAll('.question-card').length;
                    const unanswered = totalQuestions - answeredQuestions.size;
                    const msg = unanswered > 0
                        ? `You have ${unanswered} unanswered question(s). Submit anyway?`
                        : 'Submit your quiz?';
                    if (confirm(msg)) e.target.submit();
                });
            }
        });

        window.addEventListener('beforeunload', e => {
            const quizForm = document.getElementById('quizForm');
            if (quizForm) {
                e.preventDefault();
                e.returnValue = 'Your quiz progress may be lost.';
            }
        });
    </script>
</body>
</html>