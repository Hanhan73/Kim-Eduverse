<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learning - {{ $course->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
            --dark: #2d3748;
            --gray: #718096;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #1a1a1a;
            color: white;
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .course-title-bar {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-back {
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.2);
        }

        .course-title-bar h1 {
            font-size: 1.3rem;
        }

        .progress-indicator {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* Layout */
        .learning-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            height: calc(100vh - 71px);
        }

        /* Video Section */
        .video-section {
            background: #000;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            background: #000;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #1a1a1a;
        }

        .video-placeholder i {
            font-size: 4rem;
            color: rgba(255,255,255,0.3);
            margin-bottom: 20px;
        }

        /* Lesson Content */
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
            color: rgba(255,255,255,0.7);
        }

        .lesson-description {
            line-height: 1.8;
            color: rgba(255,255,255,0.8);
            margin-bottom: 25px;
        }

        .lesson-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
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
        }

        .btn-complete.completed {
            background: rgba(72, 187, 120, 0.3);
            cursor: default;
        }

        .btn-next {
            background: var(--primary);
            color: white;
        }

        .btn-next:hover {
            background: var(--secondary);
        }

        /* PDF Section */
        .pdf-section {
            background: #f7fafc;
            padding: 30px;
            margin-top: 20px;
            border-radius: 12px;
        }

        .pdf-section h3 {
            color: var(--dark);
            margin-bottom: 15px;
        }

        .pdf-download {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .pdf-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Sidebar */
        .sidebar {
            background: #2d3748;
            overflow-y: auto;
            border-left: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .course-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }

        .progress-bar-mini {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.1);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-fill-mini {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        /* Modules & Lessons */
        .modules-list {
            padding: 15px;
        }

        .module-item {
            margin-bottom: 10px;
        }

        .module-header {
            background: rgba(0,0,0,0.3);
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .module-header:hover {
            background: rgba(0,0,0,0.5);
        }

        .module-header.active {
            background: rgba(102, 126, 234, 0.2);
        }

        .module-title {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .module-icon {
            transition: transform 0.3s;
        }

        .module-item.active .module-icon {
            transform: rotate(180deg);
        }

        .lessons-list {
            display: none;
            padding: 10px 0;
        }

        .module-item.active .lessons-list {
            display: block;
        }

        .lesson-item {
            padding: 12px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 5px;
        }

        .lesson-item:hover {
            background: rgba(255,255,255,0.05);
        }

        .lesson-item.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .lesson-item.completed {
            color: var(--success);
        }

        .lesson-item i {
            font-size: 1.1rem;
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
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .learning-layout {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="course-title-bar">
            <a href="{{ route('edutech.student.my-courses') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1>{{ $course->title }}</h1>
        </div>

        <div class="progress-indicator">
            <div class="progress-circle" id="progressCircle">
                {{ $enrollment->progress_percentage }}%
            </div>
            <div>
                <div style="font-size: 0.85rem; opacity: 0.9;">Progress</div>
                <div style="font-weight: 700;">{{ $enrollment->progress_percentage }}% Complete</div>
            </div>
        </div>
    </div>

    <!-- Learning Layout -->
    <div class="learning-layout">
        <!-- Video & Content Section -->
        <div class="video-section">
            @if($currentLesson)
                <!-- Video Player -->
                <div class="video-container">
                    @if($currentLesson->video_url)
                        <iframe
                            id="youtubePlayer"
                            src="https://www.youtube.com/embed/{{ $currentLesson->video_id }}?enablejsapi=1&rel=0"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    @else
                        <div class="video-placeholder">
                            <i class="fas fa-video"></i>
                            <h3>Video Belum Tersedia</h3>
                            <p>Video untuk lesson ini sedang dalam proses upload</p>
                        </div>
                    @endif
                </div>

                <!-- Lesson Content -->
                <div class="lesson-content">
                    <div class="lesson-header">
                        <h2>{{ $currentLesson->title }}</h2>
                        <div class="lesson-meta">
                            <span><i class="fas fa-clock"></i> {{ $currentLesson->duration_minutes ?? 15 }} menit</span>
                            <span><i class="fas fa-play-circle"></i> Lesson {{ $currentLesson->order }}</span>
                        </div>
                    </div>

                    <div class="lesson-description">
                        {!! nl2br(e($currentLesson->description ?? 'Selamat belajar! Tonton video dengan seksama dan jangan lupa catat poin-poin penting.')) !!}
                    </div>

                    <!-- Lesson Actions -->
                    <div class="lesson-actions">
                        <button 
                            id="btn-complete" 
                            class="btn btn-complete {{ in_array($currentLesson->id, $completedLessons) ? 'completed' : '' }}"
                            onclick="markAsComplete({{ $currentLesson->id }})"
                            {{ in_array($currentLesson->id, $completedLessons) ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle"></i>
                            <span>{{ in_array($currentLesson->id, $completedLessons) ? 'Sudah Selesai' : 'Tandai Selesai' }}</span>
                        </button>

                        <button id="btn-next" class="btn btn-next" onclick="goToNextLesson({{ $currentLesson->id }})">
                            <span>Next Lesson</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <!-- PDF Materials -->
                    @if($currentLesson->file_path)
                    <div class="pdf-section">
                        <h3><i class="fas fa-file-pdf"></i> Materi Pendukung</h3>
                        <a href="{{ $currentLesson->file_path }}" target="_blank" class="pdf-download">
                            <i class="fas fa-download"></i>
                            Download Materi PDF
                        </a>
                    </div>
                    @endif
                </div>
            @else
                <div class="video-container">
                    <div class="video-placeholder">
                        <i class="fas fa-book-open"></i>
                        <h3>Pilih Lesson</h3>
                        <p>Pilih lesson dari sidebar untuk memulai belajar</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Course Content</h3>
                <div class="course-progress">
                    <span>{{ $enrollment->progress_percentage }}% Selesai</span>
                </div>
                <div class="progress-bar-mini">
                    <div class="progress-fill-mini" style="width: {{ $enrollment->progress_percentage }}%"></div>
                </div>
            </div>

            <div class="modules-list">
                @foreach($course->modules as $index => $module)
                <div class="module-item {{ $loop->first ? 'active' : '' }}">
                    <div class="module-header" onclick="toggleModule(this)">
                        <div class="module-title">{{ $module->title }}</div>
                        <i class="fas fa-chevron-down module-icon"></i>
                    </div>

                    <div class="lessons-list">
                        @foreach($module->lessons as $lesson)
                        <a href="{{ route('edutech.courses.learn', ['slug' => $course->slug, 'lesson' => $lesson->id]) }}"
                           class="lesson-item {{ $currentLesson && $currentLesson->id === $lesson->id ? 'active' : '' }} {{ in_array($lesson->id, $completedLessons) ? 'completed' : '' }}">
                            <i class="fas {{ in_array($lesson->id, $completedLessons) ? 'fa-check-circle' : 'fa-play-circle' }}"></i>
                            <div class="lesson-info">
                                <div class="lesson-name">{{ $lesson->title }}</div>
                                <div class="lesson-duration">{{ $lesson->duration_minutes ?? 15 }} menit</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($course->modules->count() === 0)
                <div style="padding: 40px 20px; text-align: center; color: rgba(255,255,255,0.5);">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Belum ada modul</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toggle Module
        function toggleModule(element) {
            const moduleItem = element.closest('.module-item');
            moduleItem.classList.toggle('active');
        }

        // Mark Lesson as Complete
        function markAsComplete(lessonId) {
            const btn = document.getElementById('btn-complete');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            fetch(`/edutech/learning/lesson/${lessonId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    watch_duration: 0 // Will be tracked with YouTube API later
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.classList.add('completed');
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> <span>Sudah Selesai</span>';
                    
                    // Update progress
                    document.getElementById('progressCircle').textContent = data.progress + '%';
                    
                    // Update sidebar progress
                    document.querySelector('.progress-fill-mini').style.width = data.progress + '%';
                    document.querySelector('.course-progress span').textContent = data.progress + '% Selesai';
                    
                    // Mark lesson in sidebar
                    const lessonItem = document.querySelector(`a[href*="lesson=${lessonId}"]`);
                    if (lessonItem) {
                        lessonItem.classList.add('completed');
                        lessonItem.querySelector('i').className = 'fas fa-check-circle';
                    }
                    
                    // Show success message
                    alert('✅ Lesson berhasil diselesaikan!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span>Tandai Selesai</span>';
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }

        // Go to Next Lesson
        function goToNextLesson(lessonId) {
            fetch(`/edutech/learning/lesson/${lessonId}/next`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.next_lesson) {
                        window.location.href = data.next_lesson.url;
                    } else {
                        alert('🎉 Anda sudah menyelesaikan modul ini!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Auto-open module with current lesson
        document.addEventListener('DOMContentLoaded', function() {
            const activeLesson = document.querySelector('.lesson-item.active');
            if (activeLesson) {
                const moduleItem = activeLesson.closest('.module-item');
                if (moduleItem) {
                    moduleItem.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>