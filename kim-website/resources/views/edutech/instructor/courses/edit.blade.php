<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Course - {{ $course->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #667eea; --secondary: #764ba2; --success: #48bb78;
            --danger: #f56565; --warning: #ed8936; --dark: #2d3748;
            --gray: #718096; --light: #f7fafc;
        }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: var(--light); color: var(--dark); }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        .header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 2rem; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-secondary { background: white; color: var(--dark); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .card { background: white; border-radius: 12px; padding: 30px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card h2 { margin-bottom: 20px; color: var(--dark); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary); }
        textarea { min-height: 120px; resize: vertical; }
        .modules-list { display: flex; flex-direction: column; gap: 20px; }
        .module-card { border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .module-header { background: var(--light); padding: 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        .module-header:hover { background: #edf2f7; }
        .module-title { font-size: 1.2rem; font-weight: 700; color: var(--dark); }
        .module-actions { display: flex; gap: 10px; }
        .lessons-list { padding: 20px; }
        .lesson-item { padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; }
        .lesson-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .lesson-info { flex: 1; }
        .lesson-title { font-weight: 600; margin-bottom: 5px; }
        .lesson-meta { font-size: 0.85rem; color: var(--gray); }
        .lesson-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--gray); }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; opacity: 0.3; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 12px; padding: 30px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { font-size: 1.5rem; }
        .btn-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray); }
        .info-box { background: #e6fffa; border: 2px solid #81e6d9; border-radius: 8px; padding: 15px; margin-bottom: 20px; font-size: 0.9rem; }
        .info-box i { color: #2c7a7b; margin-right: 8px; }
        .quiz-section { background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .quiz-section h3 { color: #1e40af; margin-bottom: 15px; }
        .quiz-card { background: white; border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 10px; }
        .quiz-header { display: flex; justify-content: space-between; align-items: center; }
        .quiz-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .quiz-badge.pre_test { background: #feebc8; color: #7c2d12; }
        .quiz-badge.post_test { background: #c6f6d5; color: #22543d; }
        .quiz-badge.module_quiz { background: #e6fffa; color: #234e52; }
        .module-quiz-section { background: #d1fae5; border: 2px solid var(--success); border-radius: 8px; padding: 15px; margin-top: 15px; }
        .module-quiz-section.no-quiz { background: #f0f9ff; border-color: #3b82f6; }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="header">
            <div>
                <h1>{{ $course->title }}</h1>
                <p style="opacity: 0.9; margin-top: 10px;">
                    <span class="badge">{{ $course->is_published ? '✅ Published' : '📝 Draft' }}</span>
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <form action="{{ route('edutech.instructor.courses.publish', $course->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn {{ $course->is_published ? 'btn-secondary' : 'btn-success' }}">
                        <i class="fas fa-{{ $course->is_published ? 'eye-slash' : 'check' }}"></i>
                        {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
                <a href="{{ route('edutech.instructor.courses') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Course Info -->
        <div class="card">
            <h2><i class="fas fa-info-circle"></i> Informasi Course</h2>
            <form action="{{ route('edutech.instructor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Judul Course</label>
                    <input type="text" name="title" value="{{ $course->title }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" required>{{ $course->description }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" required>
                            @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ $course->category == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" required>
                            <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" value="{{ $course->price }}" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Durasi (jam)</label>
                        <input type="number" name="duration_hours" value="{{ $course->duration_hours }}" min="1" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Course
                </button>
            </form>
        </div>

        <!-- PRE-TEST & POST-TEST -->
        <div class="card">
            <h2><i class="fas fa-clipboard-check"></i> Pre-Test & Post-Test</h2>
            
            <div class="quiz-section">
                <h3><i class="fas fa-clipboard-list"></i> Pre-Test</h3>
                @php
                    $preTest = $course->quizzes->where('type', 'pre_test')->first();
                @endphp
                
                @if($preTest)
                    <div class="quiz-card">
                        <div class="quiz-header">
                            <div>
                                <strong>{{ $preTest->title }}</strong>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-top: 5px;">
                                    {{ $preTest->questions->count() }} soal · {{ $preTest->duration_minutes }} menit · {{ $preTest->passing_score }}% passing
                                    @if($preTest->randomize_questions)
                                    · <i class="fas fa-random"></i> Random
                                    @endif
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('edutech.instructor.quiz.edit', $preTest->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('edutech.instructor.quiz.destroy', $preTest->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus Pre-Test?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button onclick="openQuizModal('pre_test')" class="btn btn-warning">
                        <i class="fas fa-plus"></i> Tambah Pre-Test
                    </button>
                @endif
            </div>

            <div class="quiz-section" style="margin-top: 20px;">
                <h3><i class="fas fa-clipboard-check"></i> Post-Test</h3>
                @php
                    $postTest = $course->quizzes->where('type', 'post_test')->first();
                @endphp
                
                @if($postTest)
                    <div class="quiz-card">
                        <div class="quiz-header">
                            <div>
                                <strong>{{ $postTest->title }}</strong>
                                <p style="color: var(--gray); font-size: 0.9rem; margin-top: 5px;">
                                    {{ $postTest->questions->count() }} soal · {{ $postTest->duration_minutes }} menit · {{ $postTest->passing_score }}% passing
                                    @if($postTest->randomize_questions)
                                    · <i class="fas fa-random"></i> Random
                                    @endif
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('edutech.instructor.quiz.edit', $postTest->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('edutech.instructor.quiz.destroy', $postTest->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus Post-Test?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button onclick="openQuizModal('post_test')" class="btn btn-warning">
                        <i class="fas fa-plus"></i> Tambah Post-Test
                    </button>
                @endif
            </div>
        </div>

        <!-- Modules & Lessons -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2><i class="fas fa-book"></i> Modules & Lessons</h2>
                <button onclick="openModuleModal()" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Module
                </button>
            </div>

            @if($course->modules->count() > 0)
            <div class="modules-list">
                @foreach($course->modules as $module)
                <div class="module-card">
                    <div class="module-header">
                        <div>
                            <div class="module-title">📚 {{ $module->title }}</div>
                            <div style="font-size: 0.9rem; color: var(--gray); margin-top: 5px;">
                                {{ $module->lessons->count() }} lessons · {{ $module->duration_minutes }} menit
                            </div>
                        </div>
                        <div class="module-actions">
                            <button onclick="openLessonModal({{ $module->id }})" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Add Lesson
                            </button>
                            <form action="{{ route('edutech.instructor.modules.destroy', [$course->id, $module->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus module ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- MODULE QUIZ -->
                    <div class="module-quiz-section {{ $module->quiz ? '' : 'no-quiz' }}">
                        @php
                            $moduleQuiz = $course->quizzes->where('type', 'module_quiz')->where('module_id', $module->id)->first();
                        @endphp
                        
                        @if($moduleQuiz)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong><i class="fas fa-tasks"></i> Module Quiz: {{ $moduleQuiz->title }}</strong>
                                    <p style="color: var(--gray); font-size: 0.85rem; margin-top: 5px;">
                                        {{ $moduleQuiz->questions->count() }} soal · {{ $moduleQuiz->duration_minutes }} menit · {{ $moduleQuiz->passing_score }}% passing
                                    </p>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('edutech.instructor.quiz.edit', $moduleQuiz->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('edutech.instructor.quiz.destroy', $moduleQuiz->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus quiz module?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--gray);">
                                    <i class="fas fa-clipboard-question"></i> Belum ada quiz untuk module ini
                                </span>
                                <button onclick="openQuizModal('module_quiz', {{ $module->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-plus"></i> Tambah Quiz Module
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($module->lessons->count() > 0)
                    <div class="lessons-list">
                        @foreach($module->lessons as $lesson)
                        <div class="lesson-item">
                            <div class="lesson-header">
                                <div class="lesson-info">
                                    <div class="lesson-title">
                                        <i class="fas fa-play-circle"></i> {{ $lesson->title }}
                                    </div>
                                    <div class="lesson-meta">
                                        {{ ucfirst($lesson->type) }} · {{ $lesson->duration_minutes }} menit
                                        @if($lesson->video_id)
                                        · Video ID: {{ $lesson->video_id }}
                                        @endif
                                    </div>
                                </div>
                                <div class="lesson-actions">
                                    <form action="{{ route('edutech.instructor.lessons.destroy', [$course->id, $module->id, $lesson->id]) }}" method="POST" onsubmit="return confirm('Hapus lesson ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state" style="padding: 30px;">
                        <p>Belum ada lessons. Klik "Add Lesson" untuk menambahkan.</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>Belum Ada Module</h3>
                <p>Klik tombol "Add Module" untuk menambahkan module pertama</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Add Module Modal -->
    <div id="moduleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Module</h3>
                <button class="btn-close" onclick="closeModal('moduleModal')">&times;</button>
            </div>
            <form action="{{ route('edutech.instructor.modules.store', $course->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Module Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" name="duration_minutes" value="60" min="0">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Module
                </button>
            </form>
        </div>
    </div>

    <!-- Add Lesson Modal -->
    <div id="lessonModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Lesson</h3>
                <button class="btn-close" onclick="closeModal('lessonModal')">&times;</button>
            </div>
            <form id="lessonForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Lesson Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" required onchange="toggleLessonFields(this.value)">
                        <option value="video">Video (YouTube)</option>
                        <option value="pdf">PDF</option>
                        <option value="text">Text Content</option>
                    </select>
                </div>
                <div class="form-group" id="videoUrlField">
                    <label>YouTube URL</label>
                    <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=xxxxx">
                    <small style="color: var(--gray);">Paste full YouTube URL - Video ID akan otomatis diambil</small>
                </div>
                <div class="form-group" id="pdfField" style="display: none;">
                    <label>Google Drive PDF Link</label>
                    <input type="url" name="file_path" placeholder="https://drive.google.com/file/d/xxxxx/view">
                    <small style="color: var(--gray);">Paste Google Drive shareable link</small>
                </div>
                <div class="form-group" id="textField" style="display: none;">
                    <label>Content</label>
                    <textarea name="content"></textarea>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" name="duration_minutes" value="30" min="0">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Lesson
                </button>
            </form>
        </div>
    </div>

    <!-- Add Quiz Modal -->
    <div id="quizModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="quizModalTitle">Add Quiz</h3>
                <button class="btn-close" onclick="closeModal('quizModal')">&times;</button>
            </div>

            <form id="quizForm" action="{{ route('edutech.instructor.quiz.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="type" id="quizType">
                <input type="hidden" name="module_id" id="quizModuleId">

                <div class="form-group">
                    <label>Quiz Title</label>
                    <input type="text" name="title" id="quizTitle" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Passing Score (%)</label>
                        <input type="number" name="passing_score" value="70" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" value="30" min="1" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maximum Attempts</label>
                    <input type="number" name="max_attempts" value="3" min="1" required>
                </div>

                <div class="form-group" id="randomizeField" style="display: none;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="randomize_questions" value="1" style="width: auto;">
                        <span>Randomize Questions (Acak urutan soal setiap attempt)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Create Quiz & Add Questions
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModuleModal() {
            document.getElementById('moduleModal').classList.add('active');
        }

        function openLessonModal(moduleId) {
            const form = document.getElementById('lessonForm');
            form.action = `/edutech/instructor/courses/{{ $course->id }}/modules/${moduleId}/lessons`;
            document.getElementById('lessonModal').classList.add('active');
        }

        function openQuizModal(type, moduleId = null) {
            const modal = document.getElementById('quizModal');
            const titleField = document.getElementById('quizTitle');
            const typeField = document.getElementById('quizType');
            const moduleField = document.getElementById('quizModuleId');
            const randomizeField = document.getElementById('randomizeField');
            const modalTitle = document.getElementById('quizModalTitle');

            typeField.value = type;
            moduleField.value = moduleId || '';

            if (type === 'pre_test') {
                titleField.value = 'Pre-Test';
                modalTitle.textContent = 'Tambah Pre-Test';
                randomizeField.style.display = 'block';
            } else if (type === 'post_test') {
                titleField.value = 'Post-Test';
                modalTitle.textContent = 'Tambah Post-Test';
                randomizeField.style.display = 'block';
            } else if (type === 'module_quiz') {
                titleField.value = 'Module Quiz';
                modalTitle.textContent = 'Tambah Module Quiz';
                randomizeField.style.display = 'none';
            }

            modal.classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function toggleLessonFields(type) {
            document.getElementById('videoUrlField').style.display = type === 'video' ? 'block' : 'none';
            document.getElementById('pdfField').style.display = type === 'pdf' ? 'block' : 'none';
            document.getElementById('textField').style.display = type === 'text' ? 'block' : 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>
</html>