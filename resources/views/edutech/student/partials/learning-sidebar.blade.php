<div class="sidebar">
    <div class="sidebar-content">
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-weight: 600;">Course Progress</span>
                <span style="font-weight: 700; color: var(--success);">{{ $enrollment->progress_percentage }}%</span>
            </div>
            <div style="width: 100%; height: 8px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; overflow: hidden;">
                <div style="width: {{ $enrollment->progress_percentage }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
            </div>
        </div>

        <!-- PRE-TEST -->
        @if($preTest)
        <div style="background: rgba(254, 235, 200, 0.1); border: 2px solid #feebc8; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span style="font-weight: 700; color: #feebc8;">
                    <i class="fas fa-clipboard-list"></i> Pre-Test
                </span>
                @if($preTestAttempt)
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                @endif
            </div>
            <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'quiz' => $preTest->id]) }}" 
               class="quiz-item {{ $currentView === 'quiz' && $currentQuiz && $currentQuiz->id === $preTest->id ? 'active' : '' }}"
               style="padding: 10px; border-radius: 8px;">
                <i class="fas fa-clipboard-list"></i>
                <div class="quiz-info-sidebar">
                    <div class="quiz-name">{{ $preTest->title }}</div>
                    <div class="quiz-duration">{{ $preTest->questions->count() }} questions · {{ $preTest->duration_minutes }} min</div>
                </div>
                @if(in_array($preTest->id, $passedQuizzes))
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                @endif
            </a>
        </div>
        @endif

        <!-- MODULES & LESSONS & QUIZZES -->
        @foreach($course->modules as $module)
            @php
            $canAccessThisModule = $enrollment->canAccessModule($module->id);
            $moduleQuiz = $module->quiz; // Relasi ke quiz
            @endphp
            
            <div class="module-accordion {{ $loop->first ? 'active' : '' }} {{ !$canAccessThisModule ? 'locked' : '' }}">
                <div class="module-header" onclick="toggleModule(this)">
                    <div>
                        <div class="module-title">
                            @if(!$canAccessThisModule)
                            <i class="fas fa-lock" style="color: var(--danger);"></i>
                            @endif
                            {{ $module->title }}
                        </div>
                        <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-top: 5px;">
                            <i class="fas fa-play-circle"></i> {{ $module->lessons->count() }} lessons
                            @if($moduleQuiz)
                            · <i class="fas fa-clipboard-list"></i> 1 quiz
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-chevron-down module-icon"></i>
                </div>

                <div class="lesson-list">
                    @foreach($module->lessons as $lesson)
                        <a href="{{ $canAccessThisModule ? route('edutech.courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) : '#' }}" 
                           class="lesson-item {{ $currentView === 'lesson' && $currentLesson && $currentLesson->id === $lesson->id ? 'active' : '' }} {{ !$canAccessThisModule ? 'locked' : '' }}">
                            <i class="fas fa-{{ $lesson->type == 'video' ? 'play' : ($lesson->type == 'pdf' ? 'file-pdf' : 'file-alt') }}-circle"></i>
                            <div class="lesson-info">
                                <div class="lesson-name">{{ $lesson->title }}</div>
                                <div class="lesson-duration">{{ $lesson->duration_minutes }} min</div>
                            </div>
                            @if(in_array($lesson->id, $completedLessons))
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            @endif
                        </a>
                    @endforeach

                    <!-- MODULE QUIZ -->
                    @if($moduleQuiz)
                        @php
                        $allLessonsCompleted = true;
                        foreach($module->lessons as $l) {
                            if(!in_array($l->id, $completedLessons)) {
                                $allLessonsCompleted = false;
                                break;
                            }
                        }
                        $canAccessModuleQuiz = $canAccessThisModule && $allLessonsCompleted;
                        @endphp
                        
                        <a href="{{ $canAccessModuleQuiz ? route('edutech.courses.learn', ['slug' => $course->slug, 'quiz' => $moduleQuiz->id]) : '#' }}" 
                           class="quiz-item {{ $currentView === 'quiz' && $currentQuiz && $currentQuiz->id === $moduleQuiz->id ? 'active' : '' }} {{ !$canAccessModuleQuiz ? 'locked' : '' }}"
                           style="background: rgba(230, 255, 250, 0.05); border-top: 1px solid rgba(255, 255, 255, 0.1);">
                            @if(!$canAccessModuleQuiz)
                            <i class="fas fa-lock" style="color: var(--warning);"></i>
                            @else
                            <i class="fas fa-clipboard-list" style="color: var(--info);"></i>
                            @endif
                            <div class="quiz-info-sidebar">
                                <div class="quiz-name">{{ $moduleQuiz->title }}</div>
                                <div class="quiz-duration">{{ $moduleQuiz->questions->count() }} questions · {{ $moduleQuiz->duration_minutes }} min</div>
                            </div>
                            @if(in_array($moduleQuiz->id, $passedQuizzes))
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- POST-TEST -->
        @if($postTest)
        <div style="background: rgba(198, 246, 213, 0.1); border: 2px solid #c6f6d5; border-radius: 10px; padding: 15px; margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span style="font-weight: 700; color: #c6f6d5;">
                    <i class="fas fa-clipboard-check"></i> Post-Test
                </span>
                @if($postTestAttempt && $postTestAttempt->is_passed)
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                @endif
            </div>
            
            @if($canAccessPostTest)
            <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'quiz' => $postTest->id]) }}" 
               class="quiz-item {{ $currentView === 'quiz' && $currentQuiz && $currentQuiz->id === $postTest->id ? 'active' : '' }}"
               style="padding: 10px; border-radius: 8px;">
                <i class="fas fa-clipboard-check"></i>
                <div class="quiz-info-sidebar">
                    <div class="quiz-name">{{ $postTest->title }}</div>
                    <div class="quiz-duration">{{ $postTest->questions->count() }} questions · {{ $postTest->duration_minutes }} min</div>
                </div>
                @if(in_array($postTest->id, $passedQuizzes))
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                @endif
            </a>
            @else
            <div style="padding: 10px; color: rgba(255, 255, 255, 0.5); font-size: 0.85rem;">
                <i class="fas fa-lock"></i> Complete all modules to unlock
            </div>
            @endif
        </div>
        @endif

        @if($course->modules->count() === 0)
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No modules available yet</p>
        </div>
        @endif
    </div>
</div>