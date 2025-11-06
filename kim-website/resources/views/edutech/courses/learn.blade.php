<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Learning</title>
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
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
            --gray: #718096;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            color: white;
            overflow-x: hidden;
        }

        /* === TOP BAR === */
        .top-bar {
            background: rgba(0, 0, 0, 0.95);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .course-title-bar {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-back {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .course-title-bar h1 {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .progress-indicator {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: conic-gradient(var(--primary) {{ $enrollment->progress_percentage * 3.6 }}deg, rgba(255,255,255,0.1) 0deg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .btn-toggle-sidebar {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .btn-toggle-sidebar:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* === LAYOUT === */
        .learning-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            height: calc(100vh - 71px);
        }

        .video-section {
            background: #000;
            display: flex;
            flex-direction: column;
        }

        .video-player {
            flex: 1;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .video-placeholder {
            text-align: center;
            padding: 60px 20px;
        }

        .video-placeholder i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        .video-placeholder h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .video-placeholder p {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Real Video Element */
        video {
            width: 100%;
            height: 100%;
            max-height: calc(100vh - 271px);
            background: #000;
        }

        /* === LESSON CONTENT === */
        .lesson-content {
            padding: 30px;
            background: #1a1a1a;
        }

        .lesson-header h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .lesson-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .lesson-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .lesson-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .lesson-actions {
            display: flex;
            gap: 15px;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-complete {
            background: var(--success);
            color: white;
        }

        .btn-complete:hover {
            background: #38a169;
            transform: translateY(-2px);
        }

        .btn-next {
            background: var(--primary);
            color: white;
        }

        .btn-next:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        /* === SIDEBAR === */
        .sidebar {
            background: #1a1a1a;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar.hidden {
            transform: translateX(100%);
        }

        .sidebar-header {
            padding: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .sidebar-tabs {
            display: flex;
            gap: 10px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
        }

        .sidebar-content {
            padding: 0;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* === MODULE LIST === */
        .module-accordion {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .module-header {
            padding: 20px 25px;
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

        .module-stats {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
        }

        .module-icon {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.6);
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
            padding: 15px 25px 15px 45px;
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

        .lesson-item.completed {
            color: var(--success);
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
            margin-bottom: 3px;
        }

        .lesson-duration {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* === QUIZ TAB === */
        .quiz-list {
            padding: 20px;
        }

        .quiz-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            display: block;
        }

        .quiz-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .quiz-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .quiz-meta {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .quiz-badge {
            display: inline-block;
            padding: 4px 10px;
            background: var(--success);
            color: white;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 10px;
        }

        /* === NOTES TAB === */
        .notes-section {
            padding: 20px;
        }

        .note-form {
            margin-bottom: 20px;
        }

        .note-form textarea {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            resize: vertical;
            min-height: 100px;
        }

        .note-form textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn-save-note {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-save-note:hover {
            background: var(--secondary);
        }

        .notes-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .note-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
        }

        .note-time {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 8px;
        }

        .note-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* === RESPONSIVE === */
        @media (max-width: 968px) {
            .learning-layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                right: 0;
                top: 71px;
                height: calc(100vh - 71px);
                width: 100%;
                max-width: 400px;
                z-index: 999;
            }

            .top-bar {
                flex-wrap: wrap;
            }

            .course-title-bar h1 {
                font-size: 1rem;
            }
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="course-title-bar">
            <a href="{{ route('edutech.student.my-courses') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <h1>{{ $course->title }}</h1>
        </div>

        <div class="top-bar-actions">
            <div class="progress-indicator">
                <div class="progress-circle">
                    {{ $enrollment->progress_percentage }}%
                </div>
                <div>
                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Progress</div>
                    <div style="font-weight: 700;">{{ $enrollment->progress_percentage }}% Complete</div>
                </div>
            </div>
            <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Learning Layout -->
    <div class="learning-layout">
        <!-- Video & Content Section -->
        <div class="video-section">
            <!-- Video Player -->
            <div class="video-player">
                @if($currentLesson && $currentLesson->video_url)
                    <video controls controlsList="nodownload" id="videoPlayer">
                        <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="video-placeholder">
                        <i class="fas fa-video"></i>
                        <h3>{{ $currentLesson ? $currentLesson->title : 'Pilih Lesson' }}</h3>
                        <p>{{ $currentLesson ? 'Video untuk lesson ini sedang dalam proses upload' : 'Pilih lesson dari sidebar untuk memulai belajar' }}</p>
                    </div>
                @endif
            </div>

            <!-- Lesson Content -->
            <div class="lesson-content">
                @if($currentLesson)
                <div class="lesson-header">
                    <h2>{{ $currentLesson->title }}</h2>
                    <div class="lesson-meta">
                        <span><i class="fas fa-clock"></i> {{ $currentLesson->duration_minutes ?? 15 }} menit</span>
                        <span><i class="fas fa-eye"></i> Lesson {{ $currentLesson->order }}</span>
                    </div>
                </div>

                <div class="lesson-description">
                    <p>{{ $currentLesson->content ?? 'Deskripsi lesson akan segera ditambahkan.' }}</p>
                </div>

                <div class="lesson-actions">
                    <button class="btn-action btn-complete" onclick="markAsComplete()">
                        <i class="fas fa-check"></i>
                        Tandai Selesai
                    </button>
                    <button class="btn-action btn-next" onclick="nextLesson()">
                        Lesson Berikutnya
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                @else
                <div style="text-align: center; padding: 40px 20px; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <h3>Belum ada lesson dipilih</h3>
                    <p>Pilih lesson dari sidebar untuk memulai pembelajaran</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Konten Course</h3>
                <div class="sidebar-tabs">
                    <button class="tab-btn active" onclick="switchTab('curriculum')">
                        <i class="fas fa-list"></i> Curriculum
                    </button>
                    <button class="tab-btn" onclick="switchTab('quiz')">
                        <i class="fas fa-question-circle"></i> Quiz
                    </button>
                    <button class="tab-btn" onclick="switchTab('notes')">
                        <i class="fas fa-sticky-note"></i> Notes
                    </button>
                </div>
            </div>

            <div class="sidebar-content">
                <!-- Curriculum Tab -->
                <div id="curriculum-tab" class="tab-pane active">
                    @foreach($course->modules as $moduleIndex => $module)
                    <div class="module-accordion {{ $moduleIndex === 0 ? 'active' : '' }}">
                        <div class="module-header" onclick="toggleModule(this)">
                            <div>
                                <div class="module-title">{{ $module->title }}</div>
                                <div class="module-stats">
                                    <i class="fas fa-play-circle"></i> {{ $module->lessons->count() }} lessons
                                </div>
                            </div>
                            <i class="fas fa-chevron-down module-icon"></i>
                        </div>

                        <div class="lesson-list">
                            @foreach($module->lessons as $lesson)
                            <a href="{{ route('edutech.course.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}" 
                               class="lesson-item {{ $currentLesson && $currentLesson->id === $lesson->id ? 'active' : '' }}">
                                <i class="fas fa-play-circle"></i>
                                <div class="lesson-info">
                                    <div class="lesson-name">{{ $lesson->title }}</div>
                                    <div class="lesson-duration">{{ $lesson->duration_minutes ?? 15 }} menit</div>
                                </div>
                                @if($lesson->is_completed ?? false)
                                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    @if($course->modules->count() === 0)
                    <div style="padding: 40px 20px; text-align: center; color: rgba(255,255,255,0.5);">
                        <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p>Belum ada modul dalam course ini</p>
                    </div>
                    @endif
                </div>

                <!-- Quiz Tab -->
                <div id="quiz-tab" class="tab-pane">
                    <div class="quiz-list">
                        @foreach($course->modules as $module)
                            @foreach($module->quizzes as $quiz)
                            <a href="#" class="quiz-item">
                                <div class="quiz-title">{{ $quiz->title }}</div>
                                <div class="quiz-meta">
                                    <span><i class="fas fa-question"></i> {{ $quiz->questions_count ?? 10 }} pertanyaan</span>
                                    <span><i class="fas fa-clock"></i> {{ $quiz->duration_minutes ?? 30 }} menit</span>
                                </div>
                                <span class="quiz-badge">Belum Dikerjakan</span>
                            </a>
                            @endforeach
                        @endforeach

                        @if($course->modules->sum(function($m) { return $m->quizzes->count(); }) === 0)
                        <div style="text-align: center; padding: 40px 20px; color: rgba(255,255,255,0.5);">
                            <i class="fas fa-clipboard-question" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p>Belum ada quiz tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes Tab -->
                <div id="notes-tab" class="tab-pane">
                    <div class="notes-section">
                        <div class="note-form">
                            <textarea placeholder="Tulis catatan Anda di sini..." id="noteInput"></textarea>
                            <button class="btn-save-note" onclick="saveNote()">
                                <i class="fas fa-save"></i> Simpan Catatan
                            </button>
                        </div>

                        <div class="notes-list" id="notesList">
                            <!-- Notes will be loaded here -->
                            <div style="text-align: center; padding: 20px; color: rgba(255,255,255,0.5);">
                                <i class="fas fa-sticky-note" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.3;"></i>
                                <p>Belum ada catatan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Switch Tabs
        function switchTab(tab) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked tab
            event.target.closest('.tab-btn').classList.add('active');
            document.getElementById(tab + '-tab').classList.add('active');
        }

        // Toggle Module Accordion
        function toggleModule(element) {
            const moduleAccordion = element.closest('.module-accordion');
            moduleAccordion.classList.toggle('active');
        }

        // Mark Lesson as Complete
        function markAsComplete() {
            // TODO: Send AJAX request to mark lesson as complete
            alert('Lesson marked as complete! (This will be implemented with backend)');
        }

        // Next Lesson
        function nextLesson() {
            const currentLesson = document.querySelector('.lesson-item.active');
            if (currentLesson) {
                const nextLesson = currentLesson.nextElementSibling;
                if (nextLesson && nextLesson.classList.contains('lesson-item')) {
                    nextLesson.click();
                } else {
                    alert('Anda sudah menyelesaikan modul ini!');
                }
            }
        }

        // Save Note
        function saveNote() {
            const noteInput = document.getElementById('noteInput');
            const noteText = noteInput.value.trim();

            if (!noteText) {
                alert('Please write something first!');
                return;
            }

            // TODO: Send AJAX request to save note
            const notesList = document.getElementById('notesList');
            
            // Remove "no notes" message if exists
            const noNotesMsg = notesList.querySelector('div[style*="text-align: center"]');
            if (noNotesMsg) {
                noNotesMsg.remove();
            }

            // Add new note
            const noteItem = document.createElement('div');
            noteItem.className = 'note-item';
            noteItem.innerHTML = `
                <div class="note-time">${new Date().toLocaleString('id-ID')}</div>
                <div class="note-text">${noteText}</div>
            `;
            notesList.prepend(noteItem);

            // Clear input
            noteInput.value = '';
            
            alert('Note saved! (This will be saved to database in backend)');
        }

        // Video Progress Tracking
        const video = document.getElementById('videoPlayer');
        if (video) {
            video.addEventListener('timeupdate', function() {
                // TODO: Track video progress and send to backend
                const progress = (video.currentTime / video.duration) * 100;
                console.log('Video progress:', progress.toFixed(2) + '%');
            });

            video.addEventListener('ended', function() {
                // TODO: Auto mark lesson as complete when video ends
                console.log('Video completed!');
            });
        }

        // Auto-open first module on load
        document.addEventListener('DOMContentLoaded', function() {
            const firstModule = document.querySelector('.module-accordion');
            if (firstModule) {
                firstModule.classList.add('active');
            }
        });
    </script>
</body>
</html>