<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Learning</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: white;
        }

        /* === LAYOUT === */
        .learning-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* === TOP BAR === */
        .top-bar {
            background: #1e293b;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .course-info h2 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .course-info p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
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
            border: none;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* === CONTENT TABS === */
        .content-tabs {
            background: #1e293b;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            display: flex;
        }

        .tab-button {
            padding: 15px 30px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-button:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .tab-button.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* === CONTENT AREA === */
        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            display: flex;
        }

        .content-display {
            flex: 1;
            padding: 30px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* === VIDEO PLAYER === */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 */
            height: 0;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* === PDF VIEWER === */
        .pdf-viewer {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            height: 80vh;
        }

        .pdf-viewer iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* === LESSON INFO === */
        .lesson-header {
            margin-bottom: 25px;
        }

        .lesson-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .lesson-meta {
            display: flex;
            gap: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .lesson-description {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* === LESSON ACTIONS === */
        .lesson-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-complete {
            background: linear-gradient(135deg, var(--success), #38a169);
            color: white;
        }

        .btn-complete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
        }

        .btn-complete.completed {
            background: #gray;
            cursor: not-allowed;
        }

        .btn-next {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* === QUIZ SECTION === */
        .quiz-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
        }

        .quiz-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .quiz-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .quiz-badge.pre-test {
            background: #feebc8;
            color: #7c2d12;
        }

        .quiz-badge.post-test {
            background: #c6f6d5;
            color: #22543d;
        }

        .quiz-info {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quiz-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        .quiz-info-item i {
            color: var(--info);
        }

        .quiz-attempt-result {
            background: rgba(72, 187, 120, 0.1);
            border: 2px solid var(--success);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .quiz-attempt-result.failed {
            background: rgba(245, 101, 101, 0.1);
            border-color: var(--danger);
        }

        .attempt-score {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--success);
        }

        .attempt-score.failed {
            color: var(--danger);
        }

        .btn-start-quiz {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-start-quiz:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-start-quiz:disabled {
            background: gray;
            cursor: not-allowed;
        }

        /* === LIVE SESSIONS === */
        .session-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .session-card:hover {
            border-color: var(--primary);
            background: rgba(102, 126, 234, 0.1);
        }

        .session-card.upcoming {
            border-color: var(--success);
        }

        .session-time {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 15px;
        }

        .session-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .session-description {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 15px;
        }

        .btn-join-meet {
            background: linear-gradient(135deg, #4285F4, #34A853);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-join-meet:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(66, 133, 244, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 380px;
            background: #1e293b;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-tabs {
            display: flex;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-tab {
            flex: 1;
            padding: 15px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .sidebar-tab:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-tab.active {
            color: white;
            border-bottom-color: var(--primary);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .sidebar-pane {
            display: none;
        }

        .sidebar-pane.active {
            display: block;
        }

        /* === MODULES & LESSONS === */
        .module-accordion {
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .module-header {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s ease;
        }

        .module-header:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .module-title {
            font-size: 1rem;
            font-weight: 700;
        }

        .module-icon {
            transition: transform 0.3s ease;
        }

        .module-accordion.active .module-icon {
            transform: rotate(180deg);
        }

        .lesson-list {
            display: none;
            background: rgba(0, 0, 0, 0.3);
        }

        .module-accordion.active .lesson-list {
            display: block;
        }

        .lesson-item {
            padding: 15px 20px 15px 40px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
        }

        .lesson-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .lesson-item.active {
            background: rgba(102, 126, 234, 0.2);
            border-left-color: var(--primary);
        }

        .lesson-item i {
            font-size: 1rem;
        }

        .lesson-info {
            flex: 1;
        }

        .lesson-name {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .lesson-duration {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 968px) {
            .learning-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                max-height: 40vh;
            }
        }
    </style>
</head>

<body>
    <div class="learning-layout">
        <!-- Main Area -->
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

            <!-- Content Tabs -->
            <div class="content-tabs">
                <button class="tab-button active" onclick="showTab('lessons')">
                    <i class="fas fa-play-circle"></i> Lessons
                </button>
                <button class="tab-button" onclick="showTab('quizzes')">
                    <i class="fas fa-clipboard-list"></i> Quizzes
                </button>
                <button class="tab-button" onclick="showTab('live')">
                    <i class="fas fa-video"></i> Live Sessions
                </button>
            </div>

            <!-- Content Display -->
            <div class="content-wrapper">
                <div class="content-display">
                    <!-- LESSONS TAB -->
                    <div id="lessons-tab" class="tab-content active">
                        @if($currentLesson)
                        <div class="lesson-header">
                            <h1>{{ $currentLesson->title }}</h1>
                            <div class="lesson-meta">
                                <span><i class="fas fa-clock"></i> {{ $currentLesson->duration_minutes }} minutes</span>
                                <span><i
                                        class="fas fa-{{ $currentLesson->type == 'video' ? 'play' : 'file' }}-circle"></i>
                                    {{ ucfirst($currentLesson->type) }}</span>
                            </div>
                        </div>

                        @if($currentLesson->type === 'video' && $currentLesson->video_id)
                        <!-- YouTube Video -->
                        <div class="video-container">
                            <iframe src="https://www.youtube.com/embed/{{ $currentLesson->video_id }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        @elseif($currentLesson->type === 'pdf' && $currentLesson->file_path)
                        <!-- PDF Viewer -->
                        <div class="pdf-viewer">
                            @php
                            // Extract Google Drive file ID from URL
                            preg_match('/\/d\/(.*?)\//', $currentLesson->file_path, $matches);
                            $fileId = $matches[1] ?? null;
                            @endphp
                            @if($fileId)
                            <iframe src="https://drive.google.com/file/d/{{ $fileId }}/preview"></iframe>
                            @else
                            <div class="empty-state">
                                <i class="fas fa-file-pdf"></i>
                                <h3>PDF tidak dapat ditampilkan</h3>
                                <p>Pastikan link Google Drive sudah benar</p>
                                <a href="{{ $currentLesson->file_path }}" target="_blank" class="btn btn-next">
                                    <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                </a>
                            </div>
                            @endif
                        </div>
                        @elseif($currentLesson->type === 'text')
                        <!-- Text Content -->
                        <div class="lesson-description">
                            {!! nl2br(e($currentLesson->content)) !!}
                        </div>
                        @endif

                        @if($currentLesson->description)
                        <div class="lesson-description">
                            <strong>Description:</strong><br>
                            {!! nl2br(e($currentLesson->description)) !!}
                        </div>
                        @endif

                        <!-- Lesson Actions -->
                        <div class="lesson-actions">
                            <button
                                class="btn btn-complete {{ in_array($currentLesson->id, $completedLessons) ? 'completed' : '' }}"
                                onclick="markAsComplete({{ $currentLesson->id }})"
                                {{ in_array($currentLesson->id, $completedLessons) ? 'disabled' : '' }}>
                                <i class="fas fa-check-circle"></i>
                                <span>{{ in_array($currentLesson->id, $completedLessons) ? 'Completed' : 'Mark as Complete' }}</span>
                            </button>

                            <a href="{{ route('edutech.learning.next', $currentLesson->id) }}" class="btn btn-next">
                                <span>Next Lesson</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-book-open"></i>
                            <h3>Select a Lesson</h3>
                            <p>Choose a lesson from the sidebar to start learning</p>
                        </div>
                        @endif
                    </div>

                    <!-- This continues in part 2... -->
                    <!-- QUIZZES TAB -->
                    <div id="quizzes-tab" class="tab-content">
                        <h2 style="margin-bottom: 30px;">📝 Course Quizzes</h2>

                        <!-- Pre-Test -->
                        @if($preTest)
                        <div class="quiz-section">
                            <div class="quiz-header">
                                <div>
                                    <div class="quiz-title">{{ $preTest->title }}</div>
                                    <p style="color: rgba(255, 255, 255, 0.6);">{{ $preTest->description }}</p>
                                </div>
                                <span class="quiz-badge pre-test">PRE-TEST</span>
                            </div>

                            <div class="quiz-info">
                                <div class="quiz-info-item">
                                    <i class="fas fa-question-circle"></i>
                                    <span>{{ $preTest->questions_count ?? 10 }} Questions</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $preTest->duration_minutes }} Minutes</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-trophy"></i>
                                    <span>Passing Score: {{ $preTest->passing_score }}%</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-redo"></i>
                                    <span>Max Attempts: {{ $preTest->max_attempts }}</span>
                                </div>
                            </div>

                            @if($preTestAttempt)
                            <!-- Show Attempt Result -->
                            <div class="quiz-attempt-result {{ $preTestAttempt->is_passed ? '' : 'failed' }}">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h3>{{ $preTestAttempt->is_passed ? '🎉 Congratulations!' : '😔 Try Again' }}
                                        </h3>
                                        <p>Your Score:</p>
                                        <div class="attempt-score {{ $preTestAttempt->is_passed ? '' : 'failed' }}">
                                            {{ number_format($preTestAttempt->score, 0) }}%
                                        </div>
                                        <p style="margin-top: 10px; color: rgba(255, 255, 255, 0.7);">
                                            {{ $preTestAttempt->is_passed ? 'You passed the pre-test!' : 'You need ' . $preTest->passing_score . '% to pass.' }}
                                        </p>
                                        <small style="color: rgba(255, 255, 255, 0.5);">
                                            Submitted:
                                            {{ $preTestAttempt->submitted_at ? $preTestAttempt->submitted_at->diffForHumans() : 'N/A' }}
                                        </small>
                                    </div>
                                    @if(!$preTestAttempt->is_passed &&
                                    $preTest->canUserAttempt(session('edutech_user_id')))
                                    <a href="{{ route('edutech.student.quiz.start', $preTest->id) }}"
                                        class="btn-start-quiz">
                                        <i class="fas fa-redo"></i> Retake Quiz
                                    </a>
                                    @endif
                                </div>
                            </div>

                            @else
                            <!-- Start Quiz Button -->
                            <div style="text-align: center; padding: 20px;">
                                <a href="{{ route('edutech.student.quiz.start', $preTest->id) }}"
                                    class="btn-start-quiz">
                                    <i class="fas fa-play-circle"></i> Start Pre-Test
                                </a>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="quiz-section">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h3>No Pre-Test Available</h3>
                                <p>The instructor hasn't created a pre-test for this course yet.</p>
                            </div>
                        </div>
                        @endif

                        <!-- Post-Test -->
                        @if($postTest)
                        <div class="quiz-section">
                            <div class="quiz-header">
                                <div>
                                    <div class="quiz-title">{{ $postTest->title }}</div>
                                    <p style="color: rgba(255, 255, 255, 0.6);">{{ $postTest->description }}</p>
                                </div>
                                <span class="quiz-badge post-test">POST-TEST</span>
                            </div>

                            <div class="quiz-info">
                                <div class="quiz-info-item">
                                    <i class="fas fa-question-circle"></i>
                                    <span>{{ $postTest->questions_count ?? 10 }} Questions</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $postTest->duration_minutes }} Minutes</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-trophy"></i>
                                    <span>Passing Score: {{ $postTest->passing_score }}%</span>
                                </div>
                                <div class="quiz-info-item">
                                    <i class="fas fa-redo"></i>
                                    <span>Max Attempts: {{ $postTest->max_attempts }}</span>
                                </div>
                            </div>

                            @if($postTestAttempt)
                            <!-- Show Attempt Result -->
                            <div class="quiz-attempt-result {{ $postTestAttempt->is_passed ? '' : 'failed' }}">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h3>{{ $postTestAttempt->is_passed ? '🎉 Congratulations!' : '😔 Try Again' }}
                                        </h3>
                                        <p>Your Score:</p>
                                        <div class="attempt-score {{ $postTestAttempt->is_passed ? '' : 'failed' }}">
                                            {{ number_format($postTestAttempt->score, 0) }}%
                                        </div>
                                        <p style="margin-top: 10px; color: rgba(255, 255, 255, 0.7);">
                                            {{ $postTestAttempt->is_passed ? 'You passed the post-test!' : 'You need ' . $postTest->passing_score . '% to pass.' }}
                                        </p>
                                        <small style="color: rgba(255, 255, 255, 0.5);">
                                            Submitted:
                                            {{ $postTestAttempt->submitted_at ? $postTestAttempt->submitted_at->diffForHumans() : 'N/A' }}
                                        </small>
                                    </div>
                                    @if(!$postTestAttempt->is_passed &&
                                    $postTest->canUserAttempt(session('edutech_user_id')))
                                    <a href="{{ route('edutech.student.quiz.start', $postTest->id) }}"
                                        class="btn-start-quiz">
                                        <i class="fas fa-redo"></i> Retake Quiz
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @else
                            <!-- Check Progress Requirement -->
                            @if($enrollment->progress_percentage >= 80)
                            <div style="text-align: center; padding: 20px;">
                                <a href="{{ route('edutech.student.quiz.start', $postTest->id) }}"
                                    class="btn-start-quiz">
                                    <i class="fas fa-play-circle"></i> Start Post-Test
                                </a>
                            </div>
                            @else
                            <div
                                style="background: rgba(237, 137, 54, 0.1); border: 2px solid var(--warning); padding: 20px; border-radius: 12px; text-align: center;">
                                <i class="fas fa-lock"
                                    style="font-size: 2rem; color: var(--warning); margin-bottom: 15px;"></i>
                                <h3>Post-Test Locked</h3>
                                <p style="color: rgba(255, 255, 255, 0.7);">Complete at least 80% of the course to
                                    unlock the post-test.</p>
                                <p style="color: var(--warning); font-weight: 600; margin-top: 10px;">
                                    Current Progress: {{ $enrollment->progress_percentage }}%
                                </p>
                            </div>
                            @endif
                            @endif
                        </div>
                        @else
                        <div class="quiz-section">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h3>No Post-Test Available</h3>
                                <p>The instructor hasn't created a post-test for this course yet.</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- LIVE SESSIONS TAB -->
                    <div id="live-tab" class="tab-content">
                        <h2 style="margin-bottom: 30px;">📹 Live Sessions with Instructor</h2>

                        @if($liveSessions->count() > 0)
                        <h3 style="color: var(--success); margin-bottom: 20px;">Upcoming Sessions</h3>
                        @foreach($liveSessions as $session)
                        <div class="session-card upcoming">
                            <div class="session-time">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $session->scheduled_at->format('l, d F Y') }}</span>
                                <span>•</span>
                                <i class="fas fa-clock"></i>
                                <span>{{ $session->scheduled_at->format('H:i') }} WIB</span>
                                <span>•</span>
                                <i class="fas fa-hourglass-half"></i>
                                <span>{{ $session->duration_minutes }} minutes</span>
                            </div>

                            <div class="session-title">{{ $session->title }}</div>

                            @if($session->description)
                            <div class="session-description">{{ $session->description }}</div>
                            @endif

                            <!-- Check if session is starting soon (within 15 minutes) -->
                            @php
                            $isStartingSoon = $session->scheduled_at->diffInMinutes(now(), false) <= 15 && $session->
                                scheduled_at->diffInMinutes(now(), false) >= -15;
                                @endphp

                                @if($isStartingSoon)
                                <a href="{{ $session->meeting_url }}" target="_blank" class="btn-join-meet">
                                    <i class="fab fa-google"></i> Join Google Meet
                                </a>
                                @else
                                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-top: 10px;">
                                    <i class="fas fa-info-circle"></i>
                                    Join button will be available 15 minutes before session starts
                                </div>
                                @endif
                        </div>
                        @endforeach
                        @else
                        <div class="empty-state" style="margin-bottom: 40px;">
                            <i class="fas fa-calendar-times"></i>
                            <h3>No Upcoming Sessions</h3>
                            <p>There are no scheduled live sessions at the moment.</p>
                        </div>
                        @endif

                        @if($pastSessions->count() > 0)
                        <h3 style="color: rgba(255, 255, 255, 0.6); margin-top: 40px; margin-bottom: 20px;">Past
                            Sessions</h3>
                        @foreach($pastSessions as $session)
                        <div class="session-card">
                            <div class="session-time">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $session->scheduled_at->format('d F Y') }}</span>
                                <span>•</span>
                                <i class="fas fa-clock"></i>
                                <span>{{ $session->scheduled_at->format('H:i') }} WIB</span>
                            </div>

                            <div class="session-title">{{ $session->title }}</div>

                            @if($session->description)
                            <div class="session-description">{{ $session->description }}</div>
                            @endif

                            <div style="color: rgba(255, 255, 255, 0.5); font-size: 0.85rem; margin-top: 10px;">
                                <i class="fas fa-check-circle"></i> Session Completed
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <div class="sidebar-tabs">
                        <button class="sidebar-tab active" onclick="showSidebar('lessons-sidebar')">
                            <i class="fas fa-list"></i> Lessons
                        </button>
                    </div>

                    <div class="sidebar-content">
                        <!-- Lessons Sidebar -->
                        <div id="lessons-sidebar" class="sidebar-pane active">
                            <div style="margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span style="font-weight: 600;">Course Progress</span>
                                    <span
                                        style="font-weight: 700; color: var(--success);">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                                <div
                                    style="width: 100%; height: 8px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; overflow: hidden;">
                                    <div
                                        style="width: {{ $enrollment->progress_percentage }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);">
                                    </div>
                                </div>
                            </div>

                            @foreach($course->modules as $module)
                            <div class="module-accordion {{ $loop->first ? 'active' : '' }}">
                                <div class="module-header" onclick="toggleModule(this)">
                                    <div>
                                        <div class="module-title">{{ $module->title }}</div>
                                        <div
                                            style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin-top: 5px;">
                                            <i class="fas fa-play-circle"></i> {{ $module->lessons->count() }} lessons
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down module-icon"></i>
                                </div>

                                <div class="lesson-list">
                                    @foreach($module->lessons as $lesson)
                                    <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}"
                                        class="lesson-item {{ $currentLesson && $currentLesson->id === $lesson->id ? 'active' : '' }}">
                                        <i
                                            class="fas fa-{{ $lesson->type == 'video' ? 'play' : ($lesson->type == 'pdf' ? 'file-pdf' : 'file-alt') }}-circle"></i>
                                        <div class="lesson-info">
                                            <div class="lesson-name">{{ $lesson->title }}</div>
                                            <div class="lesson-duration">{{ $lesson->duration_minutes }} min</div>
                                        </div>
                                        @if(in_array($lesson->id, $completedLessons))
                                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                        @endif
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            @if($course->modules->count() === 0)
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <p>No modules available yet</p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching - Main Tabs
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }

        // Sidebar switching
        function showSidebar(sidebarName) {
            document.querySelectorAll('.sidebar-pane').forEach(pane => {
                pane.classList.remove('active');
            });

            document.querySelectorAll('.sidebar-tab').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(sidebarName).classList.add('active');
            event.target.classList.add('active');
        }

        // Toggle module accordion
        function toggleModule(element) {
            const module = element.parentElement;
            module.classList.toggle('active');
        }

        // Mark lesson as complete
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
                        // Reload page to update UI
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Initialize first module as open
        document.addEventListener('DOMContentLoaded', function() {
            const firstModule = document.querySelector('.module-accordion');
            if (firstModule) {
                firstModule.classList.add('active');
            }
        });
    </script>
</body>

</html>