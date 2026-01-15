<!-- Quiz Preview/Start Screen -->
<div class="quiz-display">
    <div class="quiz-header-display">
        <div>
            <h1 class="quiz-title">{{ $currentQuiz->title }}</h1>
            <span class="quiz-badge {{ str_replace('_', '-', $currentQuiz->type) }}">
                {{ ucwords(str_replace('_', ' ', $currentQuiz->type)) }}
            </span>
        </div>
    </div>

    @if($currentQuiz->description)
    <div style="background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 10px; margin-bottom: 25px; line-height: 1.6;">
        {!! nl2br(e($currentQuiz->description)) !!}
    </div>
    @endif

    <div class="quiz-info">
        <div class="quiz-info-item">
            <i class="fas fa-question-circle"></i>
            <span>{{ $currentQuiz->questions->count() }} Questions</span>
        </div>
        <div class="quiz-info-item">
            <i class="fas fa-clock"></i>
            <span>{{ $currentQuiz->duration_minutes }} Minutes</span>
        </div>
        <div class="quiz-info-item">
            <i class="fas fa-trophy"></i>
            <span>Passing Score: {{ $currentQuiz->passing_score }}%</span>
        </div>
        <div class="quiz-info-item">
            <i class="fas fa-redo"></i>
            <span>Max Attempts: {{ $currentQuiz->max_attempts }}</span>
        </div>
    </div>

    <!-- Previous Attempts -->
    @php
        $attempts = \App\Models\QuizAttempt::where('quiz_id', $currentQuiz->id)
            ->where('user_id', session('edutech_user_id'))
            ->where('is_passed', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $attemptsUsed = $attempts->count();
        $attemptsRemaining = $currentQuiz->max_attempts - $attemptsUsed;
        $bestScore = $attempts->max('score') ?? 0;
        $hasPassed = $attempts->where('is_passed', true)->isNotEmpty();
    @endphp

    @if($attemptsUsed > 0)
    <div style="background: rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 10px; margin-bottom: 25px;">
        <h3 style="margin-bottom: 15px; font-size: 1.2rem;">
            <i class="fas fa-history"></i> Your Attempts
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div style="background: rgba(66, 153, 225, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-bottom: 5px;">Attempts Used</div>
                <div style="font-size: 1.8rem; font-weight: 700; color: var(--info);">{{ $attemptsUsed }}/{{ $currentQuiz->max_attempts }}</div>
            </div>
            
            <div style="background: rgba(237, 137, 54, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-bottom: 5px;">Best Score</div>
                <div style="font-size: 1.8rem; font-weight: 700; color: var(--warning);">{{ number_format($bestScore, 0) }}%</div>
            </div>
            
            <div style="background: rgba(72, 187, 120, 0.1); padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-bottom: 5px;">Status</div>
                <div style="font-size: 1.8rem; font-weight: 700; color: {{ $hasPassed ? 'var(--success)' : 'var(--danger)' }};">
                    @if($hasPassed)
                    <i class="fas fa-check-circle"></i> Passed
                    @else
                    <i class="fas fa-times-circle"></i> Not Passed
                    @endif
                </div>
            </div>
        </div>

        <!-- Attempt History -->
        <div style="max-height: 200px; overflow-y: auto;">
            @foreach($attempts as $attempt)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(0, 0, 0, 0.2); border-radius: 8px; margin-bottom: 10px;">
                <div>
                    <div style="font-weight: 600;">Attempt #{{ $loop->iteration }}</div>
                    <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">
                        {{ $attempt->created_at->format('M d, Y - H:i') }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.3rem; font-weight: 700; color: {{ $attempt->is_passed ? 'var(--success)' : 'var(--danger)' }};">
                        {{ number_format($attempt->score, 0) }}%
                    </div>
                    <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">
                        {{ $attempt->correct_answers }}/{{ $attempt->total_questions }} correct
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Instructions -->
    <div style="background: rgba(66, 153, 225, 0.1); border-left: 4px solid var(--info); padding: 20px; border-radius: 8px; margin-bottom: 25px;">
        <h3 style="margin-bottom: 15px; font-size: 1.1rem;">
            <i class="fas fa-info-circle"></i> Instructions
        </h3>
        <ul style="margin-left: 20px; line-height: 1.8;">
            <li>You have <strong>{{ $currentQuiz->duration_minutes }} minutes</strong> to complete this quiz</li>
            <li>Answer all <strong>{{ $currentQuiz->questions->count() }} questions</strong> before time runs out</li>
            <li>You need at least <strong>{{ $currentQuiz->passing_score }}%</strong> to pass</li>
            <li>You can attempt this quiz up to <strong>{{ $currentQuiz->max_attempts }} times</strong></li>
            @if($currentQuiz->randomize_questions && ($currentQuiz->type === 'pre_test' || $currentQuiz->type === 'post_test'))
            <li>Questions will be <strong>randomized</strong> for each attempt</li>
            @endif
            <li>Once started, the timer <strong>cannot be paused</strong></li>
            <li>Make sure you have a <strong>stable internet connection</strong></li>
        </ul>
    </div>

    <!-- Start Button -->
    @if($attemptsRemaining > 0)
        @if(!$hasPassed || ($hasPassed && $attemptsRemaining > 0))
        <form action="{{ route('edutech.student.quiz.start', $currentQuiz->id) }}" method="POST">
            @csrf
            <div style="text-align: center;">
                @if($hasPassed)
                <div style="background: rgba(72, 187, 120, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    <p style="margin-top: 10px; color: var(--success);">
                        You've already passed this quiz! You have {{ $attemptsRemaining }} attempt(s) remaining if you want to improve your score.
                    </p>
                </div>
                @endif

                <button type="submit" class="btn btn-next" style="padding: 18px 50px; font-size: 1.2rem;">
                    <i class="fas fa-play-circle"></i>
                    {{ $attemptsUsed > 0 ? 'Retake Quiz' : 'Start Quiz' }}
                </button>
                
                @if($attemptsRemaining > 0)
                <p style="margin-top: 15px; color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
                    {{ $attemptsRemaining }} attempt(s) remaining
                </p>
                @endif
            </div>
        </form>
        @endif
    @else
    <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid var(--danger); padding: 20px; border-radius: 12px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--danger); margin-bottom: 15px;"></i>
        <h3 style="color: var(--danger); margin-bottom: 10px;">No Attempts Remaining</h3>
        <p style="color: rgba(255, 255, 255, 0.7);">
            You have used all {{ $currentQuiz->max_attempts }} attempts for this quiz.
            @if(!$hasPassed)
            Please contact your instructor for assistance.
            @endif
        </p>
    </div>
    @endif

    <!-- Back Button -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('edutech.courses.learn', $course->slug) }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Course
        </a>
    </div>
</div>

<style>
.quiz-display { background: rgba(255, 255, 255, 0.05); padding: 30px; border-radius: 12px; margin-bottom: 20px; }
.quiz-header-display { display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; }
.quiz-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 10px; }
.quiz-badge { padding: 8px 18px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; display: inline-block; }
.quiz-badge.pre-test { background: #feebc8; color: #7c2d12; }
.quiz-badge.post-test { background: #c6f6d5; color: #22543d; }
.quiz-badge.module-quiz { background: #e6fffa; color: #234e52; }
.quiz-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.quiz-info-item { display: flex; align-items: center; gap: 10px; color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; }
.quiz-info-item i { color: var(--info); font-size: 1.2rem; }
</style>