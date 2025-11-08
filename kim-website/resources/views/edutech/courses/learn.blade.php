@extends('edutech.student.layouts.app')

@section('content')
<style>
    /* Enhanced styles for NEW QUIZ SYSTEM */
    .learning-container { display: flex; gap: 20px; padding: 20px; max-width: 1400px; margin: 0 auto; }
    
    /* Progress Section with NEW CALCULATION */
    .progress-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .progress-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 15px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    /* PRE-TEST GATE - Material Locked State */
    .materials-locked {
        background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
        border: 3px solid #fdcb6e;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        margin: 30px 0;
        box-shadow: 0 10px 30px rgba(253, 203, 110, 0.3);
    }
    
    .materials-locked .lock-icon {
        font-size: 4rem;
        color: #e17055;
        margin-bottom: 20px;
        animation: shake 0.5s ease-in-out infinite;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .materials-locked h2 {
        color: #2d3436;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }
    
    .materials-locked p {
        color: #636e72;
        font-size: 1.1rem;
        margin-bottom: 25px;
    }
    
    /* Quiz Sections */
    .quiz-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .quiz-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .quiz-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .quiz-badge {
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .quiz-badge.pre-test {
        background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
        color: #d63031;
    }
    
    .quiz-badge.post-test {
        background: linear-gradient(135deg, #a29bfe, #6c5ce7);
        color: white;
    }
    
    .quiz-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }
    
    .quiz-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #718096;
    }
    
    .quiz-info-item i {
        color: #667eea;
    }
    
    /* Quiz Attempt Result */
    .quiz-attempt-result {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 2px solid #28a745;
        padding: 25px;
        border-radius: 12px;
        margin-top: 20px;
    }
    
    .quiz-attempt-result.failed {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border: 2px solid #dc3545;
    }
    
    .attempt-score {
        font-size: 3rem;
        font-weight: 700;
        color: #28a745;
        margin: 10px 0;
    }
    
    .attempt-score.failed {
        color: #dc3545;
    }
    
    /* POST-TEST Locked State */
    .posttest-locked {
        background: linear-gradient(135deg, #ffeaa7, #fab1a0);
        border: 3px solid #fdcb6e;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
    }
    
    .posttest-locked .lock-icon {
        font-size: 3rem;
        color: #e17055;
        margin-bottom: 15px;
    }
    
    /* Buttons */
    .btn-start-quiz {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 35px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .btn-start-quiz:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    /* Module & Lesson List */
    .modules-list {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    
    .module-item {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .module-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .module-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .lessons-list {
        padding: 15px;
    }
    
    .lesson-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .lesson-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .lesson-item.completed {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
    }
    
    .lesson-item.locked {
        opacity: 0.5;
        pointer-events: none;
        background: #f1f3f5;
    }
    
    .lesson-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
    }
    
    .lesson-icon i {
        color: #667eea;
    }
    
    .lesson-icon.completed i {
        color: #28a745;
    }
</style>

<div class="learning-container">
    <div style="flex: 1;">
        <!-- Progress Header with NEW CALCULATION -->
        <div class="progress-header">
            <h2 style="margin-bottom: 10px;">{{ $course->title }}</h2>
            <div class="progress-bar" style="background: rgba(255,255,255,0.3); height: 12px; border-radius: 25px; overflow: hidden; margin: 15px 0;">
                <div style="background: linear-gradient(90deg, #48bb78, #38a169); height: 100%; width: {{ $enrollment->progress_percentage }}%; transition: width 0.5s ease;"></div>
            </div>
            
            <div class="progress-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $enrollment->progress_percentage }}%</div>
                    <div class="stat-label">Overall Progress</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $course->modules->count() }}</div>
                    <div class="stat-label">Total Modules</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        @php
                            $totalLessons = $course->modules->sum(function($m) { return $m->lessons->count(); });
                        @endphp
                        {{ count($completedLessons) }}/{{ $totalLessons }}
                    </div>
                    <div class="stat-label">Lessons Completed</div>
                </div>
            </div>
        </div>

        <!-- ===== PRE-TEST SECTION ===== -->
        @if($preTest)
        <div class="quiz-section">
            <div class="quiz-header">
                <div>
                    <div class="quiz-title">{{ $preTest->title }}</div>
                    <p style="color: #718096; margin-top: 10px;">{{ $preTest->description }}</p>
                </div>
                <span class="quiz-badge pre-test">PRE-TEST</span>
            </div>

            <div class="quiz-info">
                <div class="quiz-info-item">
                    <i class="fas fa-question-circle"></i>
                    <span>{{ $preTest->questions_count ?? $preTest->questions->count() }} Questions</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $preTest->duration_minutes }} Minutes</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-trophy"></i>
                    <span>Passing: {{ $preTest->passing_score }}%</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-redo"></i>
                    <span>Max Attempts: {{ $preTest->max_attempts }}</span>
                </div>
            </div>

            @if($preTestAttempt && $preTestAttempt->is_passed)
                <!-- Pre-test PASSED -->
                <div class="quiz-attempt-result">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3>🎉 Pre-Test Completed!</h3>
                            <div class="attempt-score">{{ number_format($preTestAttempt->score, 0) }}%</div>
                            <p style="margin-top: 10px; color: #155724;">
                                You passed the pre-test! You can now access all course materials.
                            </p>
                            <small style="color: #6c757d;">
                                Completed: {{ $preTestAttempt->submitted_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @else
                <!-- Pre-test NOT PASSED or NOT TAKEN -->
                <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 12px; margin-top: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #856404;"></i>
                        <div>
                            <h4 style="color: #856404; margin: 0;">Pre-Test Required</h4>
                            <p style="color: #856404; margin: 5px 0 0 0;">You must pass the pre-test before accessing course materials.</p>
                        </div>
                    </div>
                    <div style="text-align: center; padding: 15px 0;">
                        <a href="{{ route('edutech.student.quiz.start', $preTest->id) }}" class="btn-start-quiz">
                            <i class="fas fa-play-circle"></i> Start Pre-Test Now
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- ===== COURSE MATERIALS (Locked if pre-test not passed) ===== -->
        @if(!$canAccessMaterials)
            <div class="materials-locked">
                <div class="lock-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Course Materials Locked</h2>
                <p>Complete the pre-test above to unlock all course materials and start learning!</p>
            </div>
        @else
            <!-- Show Modules & Lessons -->
            <div class="modules-list">
                <h3 style="margin-bottom: 20px; color: #2d3748;">📚 Course Content</h3>
                
                @foreach($course->modules as $module)
                <div class="module-item">
                    <div class="module-header">
                        <div>
                            <div class="module-title">{{ $module->order }}. {{ $module->title }}</div>
                            <p style="color: #718096; margin: 5px 0 0 0;">{{ $module->lessons->count() }} lessons</p>
                        </div>
                        <div>
                            @php
                                $moduleCompletedCount = $module->lessons->filter(function($l) use ($completedLessons) {
                                    return in_array($l->id, $completedLessons);
                                })->count();
                            @endphp
                            <span style="font-weight: 600; color: #667eea;">
                                {{ $moduleCompletedCount }}/{{ $module->lessons->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="lessons-list">
                        @foreach($module->lessons as $lesson)
                        <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" 
                           class="lesson-item {{ in_array($lesson->id, $completedLessons) ? 'completed' : '' }}" 
                           style="text-decoration: none; color: inherit;">
                            <div class="lesson-icon {{ in_array($lesson->id, $completedLessons) ? 'completed' : '' }}">
                                @if(in_array($lesson->id, $completedLessons))
                                    <i class="fas fa-check"></i>
                                @else
                                    @if($lesson->type == 'video')
                                        <i class="fas fa-play-circle"></i>
                                    @elseif($lesson->type == 'pdf')
                                        <i class="fas fa-file-pdf"></i>
                                    @else
                                        <i class="fas fa-file-alt"></i>
                                    @endif
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $lesson->order }}. {{ $lesson->title }}</div>
                                <small style="color: #718096;">
                                    {{ ucfirst($lesson->type) }} • {{ $lesson->duration }} min
                                </small>
                            </div>
                            @if(in_array($lesson->id, $completedLessons))
                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem;"></i>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- ===== POST-TEST SECTION ===== -->
        @if($postTest)
        <div class="quiz-section">
            <div class="quiz-header">
                <div>
                    <div class="quiz-title">{{ $postTest->title }}</div>
                    <p style="color: #718096; margin-top: 10px;">{{ $postTest->description }}</p>
                </div>
                <span class="quiz-badge post-test">POST-TEST</span>
            </div>

            <div class="quiz-info">
                <div class="quiz-info-item">
                    <i class="fas fa-question-circle"></i>
                    <span>{{ $postTest->questions_count ?? $postTest->questions->count() }} Questions</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $postTest->duration_minutes }} Minutes</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-trophy"></i>
                    <span>Passing: {{ $postTest->passing_score }}%</span>
                </div>
                <div class="quiz-info-item">
                    <i class="fas fa-redo"></i>
                    <span>Max Attempts: {{ $postTest->max_attempts }}</span>
                </div>
            </div>

            @if($postTestAttempt && $postTestAttempt->is_passed)
                <!-- Post-test PASSED -->
                <div class="quiz-attempt-result">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3>🎉 Congratulations! Course Completed!</h3>
                            <div class="attempt-score">{{ number_format($postTestAttempt->score, 0) }}%</div>
                            <p style="margin-top: 10px; color: #155724;">
                                You've successfully completed the post-test and finished the course!
                            </p>
                            <small style="color: #6c757d;">
                                Completed: {{ $postTestAttempt->submitted_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @elseif($canAccessPostTest)
                <!-- Post-test AVAILABLE -->
                <div style="background: #d4edda; border: 2px solid #28a745; padding: 20px; border-radius: 12px; margin-top: 15px; text-align: center;">
                    <h4 style="color: #155724; margin-bottom: 15px;">✅ All Modules Completed!</h4>
                    <p style="color: #155724; margin-bottom: 20px;">You can now take the post-test to complete the course.</p>
                    <a href="{{ route('edutech.student.quiz.start', $postTest->id) }}" class="btn-start-quiz">
                        <i class="fas fa-play-circle"></i> Start Post-Test
                    </a>
                </div>
            @else
                <!-- Post-test LOCKED -->
                <div class="posttest-locked">
                    <div class="lock-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 style="color: #2d3436; margin-bottom: 10px;">Post-Test Locked</h3>
                    <p style="color: #636e72;">
                        Complete <strong>ALL course modules</strong> to unlock the post-test.
                    </p>
                    <p style="color: #e17055; font-weight: 600; margin-top: 15px;">
                        @php
                            $totalModuleLessons = $course->modules->sum(function($m) { return $m->lessons->count(); });
                            $completedCount = count($completedLessons);
                        @endphp
                        {{ $completedCount }}/{{ $totalModuleLessons }} Lessons Completed
                    </p>
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Sidebar (if currentLesson exists) -->
    @if($currentLesson)
    <div style="width: 400px;">
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);">
            <h3 style="margin-bottom: 20px;">Current Lesson</h3>
            <div style="padding: 20px; background: #f7fafc; border-radius: 10px;">
                <h4>{{ $currentLesson->title }}</h4>
                <p style="color: #718096; margin: 10px 0;">{{ $currentLesson->module->title }}</p>
                
                @if($currentLesson->type == 'video')
                    <video controls style="width: 100%; border-radius: 10px; margin: 15px 0;">
                        <source src="{{ asset('storage/' . $currentLesson->video_url) }}" type="video/mp4">
                    </video>
                @elseif($currentLesson->type == 'pdf')
                    <a href="{{ asset('storage/' . $currentLesson->pdf_url) }}" target="_blank" class="btn-start-quiz" style="width: 100%; text-align: center; margin-top: 15px;">
                        <i class="fas fa-file-pdf"></i> Open PDF
                    </a>
                @else
                    <div style="padding: 15px; background: white; border-radius: 8px; margin: 15px 0;">
                        {!! nl2br(e($currentLesson->content)) !!}
                    </div>
                @endif
                
                @if(!in_array($currentLesson->id, $completedLessons))
                <button onclick="completeLesson({{ $currentLesson->id }})" class="btn-start-quiz" style="width: 100%; margin-top: 15px;">
                    <i class="fas fa-check"></i> Mark as Complete
                </button>
                @else
                <div style="background: #d4edda; padding: 15px; border-radius: 8px; text-align: center; margin-top: 15px; color: #155724;">
                    <i class="fas fa-check-circle"></i> Completed
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function completeLesson(lessonId) {
    fetch(`/edutech/learning/lesson/${lessonId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to complete lesson');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endsection