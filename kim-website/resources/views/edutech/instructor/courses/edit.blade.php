@extends('layouts.instructor')

@section('title', 'Edit Course - KIM EDUVERSE')

<style>
    /* --- Base Variables & Reset --- */
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --dark: #2d3748;
        --gray: #718096;
        --light: #f7fafc;
        --border: #e2e8f0;
        --bg: #f8f9fa;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: var(--bg);
        color: var(--dark);
    }

    /* --- Layout --- */
    .page-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .content-area {
        flex: 1;
        padding: 2rem;
        margin-left: 280px;
        width: calc(100% - 280px);
    }

    @media (max-width: 1024px) {
        .content-area {
            margin-left: 0;
            width: 100%;
            padding: 1rem;
        }
    }

    /* --- Cards & Containers --- */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .card-header h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* --- Forms --- */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .form-group .form-hint {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* --- Toggle Switch Custom Style --- */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e0;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: var(--primary);
    }

    input:focus+.slider {
        box-shadow: 0 0 1px var(--primary);
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }

    /* --- Buttons --- */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #38a169;
    }

    .btn-danger {
        background: #fff;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .btn-danger:hover {
        background: #fff5f5;
    }

    .btn-secondary {
        background: var(--light);
        color: var(--dark);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-warning {
        background: var(--warning);
        color: white;
    }

    .btn-warning:hover {
        background: #dd6b20;
    }

    /* --- Curriculum Builder (Modules & Lessons) --- */
    .module-item {
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 1rem;
        background: #fff;
    }

    .module-header {
        padding: 1rem 1.5rem;
        background: #fcfcfc;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .module-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .module-icon {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .module-title {
        font-weight: 700;
        font-size: 1rem;
    }

    .module-meta {
        font-size: 0.8rem;
        color: var(--gray);
        margin-left: 10px;
    }

    .module-content {
        padding: 1.5rem;
        background: white;
        display: none;
    }

    .module-content.active {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Lessons List */
    .lessons-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .lesson-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: var(--light);
        border-radius: 6px;
        border-left: 3px solid var(--gray);
    }

    .lesson-item.type-video {
        border-left-color: var(--danger);
    }

    .lesson-item.type-pdf {
        border-left-color: var(--warning);
    }

    .lesson-item.type-text {
        border-left-color: var(--primary);
    }

    .lesson-info h4 {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .lesson-info span {
        font-size: 0.8rem;
        color: var(--gray);
    }

    /* Quiz Specifics */
    .quiz-card {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quiz-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        font-weight: 800;
        margin-right: 8px;
    }

    .badge-pre {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-post {
        background: #dcfce7;
        color: #166534;
    }

    .badge-module {
        background: #e0f2fe;
        color: #075985;
    }

    /* --- Modals --- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background: white;
        width: 100%;
        max-width: 600px;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: scale(0.95);
        transition: transform 0.3s;
    }

    .modal-overlay.active .modal-container {
        transform: scale(1);
    }

    /* Image Preview */
    .img-preview-box {
        width: 100%;
        height: 200px;
        background: var(--light);
        border: 2px dashed var(--border);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
    }

    .img-preview-box:hover {
        background: #edf2f7;
        border-color: var(--primary);
    }

    .img-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .img-placeholder {
        color: var(--gray);
        text-align: center;
        pointer-events: none;
    }
</style>

@section('content')
<div class="content-area">

    <!-- Header & Actions -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">
                Edit Course
            </h1>
            <p style="color: var(--gray);">
                Last updated: {{ $course->updated_at->diffForHumans() }} &bull;
                Status: <strong style="color: {{ $course->is_published ? 'var(--success)' : 'var(--warning)' }}">
                    {{ $course->is_published ? 'Published' : 'Draft' }}
                </strong>
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('edutech.instructor.courses.publish', $course->id) }}" method="POST"
                style="display:inline;">
                @csrf
                <button type="submit" class="btn {{ $course->is_published ? 'btn-secondary' : 'btn-success' }}">
                    <i class="fas fa-{{ $course->is_published ? 'eye-slash' : 'globe' }}"></i>
                    {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                </button>
            </form>
            <a href="{{ route('edutech.instructor.courses') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div
        style="background: #c6f6d5; color: #22543d; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- TAB 1: BASIC INFORMATION -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-info-circle" style="color: var(--primary);"></i> Basic Information</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('edutech.instructor.courses.update', $course->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Course Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ $course->category == $key ? 'selected' : '' }}>{{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Level</label>
                        <select name="level" class="form-control" required>
                            <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Beginner
                            </option>
                            <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>
                                Intermediate</option>
                            <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Advanced
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price (IDR)</label>
                        <input type="number" name="price" class="form-control"
                            value="{{ old('price', $course->price) }}" min="0">
                    </div>

                    <div class="form-group">
                        <label>Duration (Hours)</label>
                        <input type="number" name="duration_hours" class="form-control"
                            value="{{ old('duration_hours', $course->duration_hours) }}" min="1">
                    </div>

                    <!-- FITUR BARU: DEGREE / GELAR -->
                    <div class="form-group"
                        style="grid-column: span 2; background: #fff; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="margin: 0;">Apakah Course ini memberikan Gelar / Sertifikat Resmi?</label>
                            <label class="switch">
                                <input type="checkbox" name="has_degree" id="hasDegreeToggle"
                                    {{ $course->has_degree ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div id="degreeTitleGroup"
                            style="{{ $course->has_degree ? '' : 'display: none;' }} transition: all 0.3s;">
                            <label>Judul Gelar / Sertifikat <span style="color:red">*</span></label>
                            <input type="text" name="degree_title" class="form-control"
                                value="{{ old('degree_title', $course->degree_title ?? '') }}"
                                placeholder="Contoh: Sertifikat Kompetensi Dasar Web Design">
                            <div class="form-hint">Masukkan judul gelar yang akan tertera di sertifikat peserta.</div>
                        </div>
                    </div>
                    <!-- END FITUR BARU -->

                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"
                        required>{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Thumbnail</label>
                    <div class="img-preview-box" onclick="document.getElementById('thumbnailInput').click()">
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" id="previewImg">
                        @else
                        <div class="img-placeholder">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                            Click to upload thumbnail
                        </div>
                        @endif
                    </div>
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" style="display: none;"
                        onchange="previewThumbnail(event)">
                </div>

                <div style="text-align: right; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: QUIZZES (PRE/POST) -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-clipboard-check" style="color: var(--warning);"></i> Course Assessments</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Pre-Test -->
                <div style="border: 1px solid var(--border); padding: 1.5rem; border-radius: 8px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.1rem; font-weight: 700;">
                            <i class="fas fa-sign-in-alt"></i> Pre-Test
                        </h3>
                        <button class="btn btn-sm btn-warning" onclick="openQuizModal('pre_test')">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>

                    @php $preTest = $course->quizzes->where('type', 'pre_test')->first(); @endphp
                    @if($preTest)
                    <div class="quiz-card" style="background: #fff; border-color: #e2e8f0;">
                        <div>
                            <span class="quiz-badge badge-pre">PRE</span>
                            <strong>{{ $preTest->title }}</strong>
                            <div style="font-size: 0.8rem; color: var(--gray); margin-top: 4px;">
                                {{ $preTest->questions->count() }} Questions &bull; {{ $preTest->duration_minutes }}
                                mins
                            </div>
                        </div>
                        <a href="{{ route('edutech.instructor.quiz.edit', $preTest->id) }}"
                            class="btn btn-sm btn-secondary">Edit</a>
                    </div>
                    @else
                    <p style="color: var(--gray); font-size: 0.9rem; font-style: italic;">No Pre-Test configured.</p>
                    @endif
                </div>

                <!-- Post-Test -->
                <div style="border: 1px solid var(--border); padding: 1.5rem; border-radius: 8px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.1rem; font-weight: 700;">
                            <i class="fas fa-sign-out-alt"></i> Post-Test
                        </h3>
                        <button class="btn btn-sm btn-warning" onclick="openQuizModal('post_test')">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>

                    @php $postTest = $course->quizzes->where('type', 'post_test')->first(); @endphp
                    @if($postTest)
                    <div class="quiz-card" style="background: #fff; border-color: #e2e8f0;">
                        <div>
                            <span class="quiz-badge badge-post">POST</span>
                            <strong>{{ $postTest->title }}</strong>
                            <div style="font-size: 0.8rem; color: var(--gray); margin-top: 4px;">
                                {{ $postTest->questions->count() }} Questions &bull; {{ $postTest->duration_minutes }}
                                mins
                            </div>
                        </div>
                        <a href="{{ route('edutech.instructor.quiz.edit', $postTest->id) }}"
                            class="btn btn-sm btn-secondary">Edit</a>
                    </div>
                    @else
                    <p style="color: var(--gray); font-size: 0.9rem; font-style: italic;">No Post-Test configured.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: CURRICULUM (MODULES & LESSONS) -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-layer-group" style="color: var(--secondary);"></i> Curriculum Content</h2>
            <button class="btn btn-primary" onclick="document.getElementById('modalModule').classList.add('active')">
                <i class="fas fa-plus"></i> Add New Module
            </button>
        </div>
        <div class="card-body">
            @if($course->modules->count() > 0)
            <div id="modulesContainer">
                @foreach($course->modules as $module)
                <div class="module-item" id="module-{{ $module->id }}">
                    <div class="module-header" onclick="toggleModule({{ $module->id }})">
                        <div class="module-title-group">
                            <i class="fas fa-chevron-down module-icon" id="icon-{{ $module->id }}"></i>
                            <div>
                                <span class="module-title">{{ $module->title }}</span>
                                <span class="module-meta">{{ $module->lessons->count() }} Lessons</span>
                            </div>
                        </div>
                        <div>
                            <!-- Module Actions -->
                            <form action="{{ route('edutech.instructor.modules.destroy', [$course->id, $module->id]) }}"
                                method="POST" style="display:inline;"
                                onsubmit="return confirm('Delete this module and all lessons?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Module"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>

                    <!-- Module Body (Accordion) -->
                    <div class="module-content" id="content-{{ $module->id }}">

                        <!-- Module Quiz Section -->
                        <div
                            style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--light);">
                            @php $modQuiz = $course->quizzes->where('type', 'module_quiz')->where('module_id',
                            $module->id)->first(); @endphp

                            @if($modQuiz)
                            <div class="quiz-card">
                                <div>
                                    <span class="quiz-badge badge-module">QUIZ</span>
                                    <strong>{{ $modQuiz->title }}</strong>
                                </div>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('edutech.instructor.quiz.edit', $modQuiz->id) }}"
                                        class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('edutech.instructor.quiz.destroy', $modQuiz->id) }}"
                                        method="POST" onsubmit="return confirm('Delete quiz?')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <button class="btn btn-sm btn-warning"
                                onclick="openQuizModal('module_quiz', {{ $module->id }})"
                                style="width: 100%; border-style: dashed;">
                                <i class="fas fa-plus-circle"></i> Add Module Quiz
                            </button>
                            @endif
                        </div>

                        <!-- Lessons List -->
                        <div class="lessons-list">
                            @foreach($module->lessons as $lesson)
                            <div class="lesson-item type-{{ $lesson->type }}">
                                <div class="lesson-info">
                                    <h4><i
                                            class="fas fa-{{ $lesson->type == 'video' ? 'play-circle' : ($lesson->type == 'pdf' ? 'file-pdf' : 'file-alt') }}"></i>
                                        {{ $lesson->title }}
                                    </h4>
                                    <span>{{ $lesson->type }} &bull; {{ $lesson->duration_minutes }} min</span>
                                </div>
                                <form
                                    action="{{ route('edutech.instructor.lessons.destroy', [$course->id, $module->id, $lesson->id]) }}"
                                    method="POST" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-secondary"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            @endforeach
                        </div>

                        <button class="btn btn-secondary" style="width: 100%; margin-top: 1rem; border-style: dashed;"
                            onclick="openLessonModal({{ $module->id }})">
                            <i class="fas fa-plus"></i> Add Lesson Content
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div
                style="text-align: center; padding: 3rem; background: var(--light); border-radius: 8px; color: var(--gray);">
                <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>No modules created yet.</p>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- MODAL: ADD MODULE -->
<div class="modal-overlay" id="modalModule">
    <div class="modal-container">
        <h3 style="margin-bottom: 1.5rem;">Add New Module</h3>
        <form action="{{ route('edutech.instructor.modules.store', $course->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Module Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Introduction to Laravel"
                    required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Duration (mins)</label>
                <input type="number" name="duration_minutes" class="form-control" value="60">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('modalModule').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Module</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ADD LESSON -->
<div class="modal-overlay" id="modalLesson">
    <div class="modal-container">
        <h3 style="margin-bottom: 1.5rem;">Add Lesson Content</h3>
        <form id="lessonForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Lesson Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Installing Composer" required>
            </div>

            <div class="form-group">
                <label>Content Type</label>
                <select name="type" class="form-control" onchange="toggleLessonFields(this.value)">
                    <option value="video">Video</option>
                    <option value="pdf">PDF Document</option>
                    <option value="text">Text Article</option>
                </select>
            </div>

            <!-- Dynamic Fields based on Type -->
            <div id="field-video" class="form-group">
                <label>YouTube URL</label>
                <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
            </div>
            <div id="field-pdf" class="form-group" style="display:none;">
                <label>Google Drive PDF Link</label>
                <input type="url" name="file_path" class="form-control"
                    placeholder="https://drive.google.com/file/d/...">
            </div>
            <div id="field-text" class="form-group" style="display:none;">
                <label>Text Content</label>
                <textarea name="content" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Duration (mins)</label>
                <input type="number" name="duration_minutes" class="form-control" value="10">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('modalLesson').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-success">Add Lesson</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ADD QUIZ -->
<div class="modal-overlay" id="modalQuiz">
    <div class="modal-container">
        <h3 id="quizModalTitle" style="margin-bottom: 1.5rem;">Add Quiz</h3>
        <form action="{{ route('edutech.instructor.quiz.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="type" id="quizType">
            <input type="hidden" name="module_id" id="quizModuleId">

            <div class="form-group">
                <label>Quiz Title</label>
                <input type="text" name="title" id="quizInputTitle" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Duration (min)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="30" required>
                </div>
                <div class="form-group">
                    <label>Passing Score (%)</label>
                    <input type="number" name="passing_score" class="form-control" value="70" required>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('modalQuiz').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-warning">Create Quiz</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image Preview Logic
    function previewThumbnail(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('previewImg');
            if (!output) {
                const div = document.querySelector('.img-preview-box');
                div.innerHTML =
                    `<img src="${reader.result}" id="previewImg" style="width:100%; height:100%; object-fit:cover;">`;
            } else {
                output.src = reader.result;
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Degree Toggle Logic (FITUR BARU)
    const degreeToggle = document.getElementById('hasDegreeToggle');
    const degreeTitleGroup = document.getElementById('degreeTitleGroup');
    const degreeInput = document.querySelector('input[name="degree_title"]');

    if (degreeToggle) {
        degreeToggle.addEventListener('change', function() {
            if (this.checked) {
                degreeTitleGroup.style.display = 'block';
                degreeInput.setAttribute('required', 'required');
            } else {
                degreeTitleGroup.style.display = 'none';
                degreeInput.removeAttribute('required');
                degreeInput.value = ''; // Optional: Clear value when unchecked
            }
        });
    }

    // Module Accordion Toggle
    function toggleModule(id) {
        const content = document.getElementById(`content-${id}`);
        const icon = document.getElementById(`icon-${id}`);

        if (content.classList.contains('active')) {
            content.classList.remove('active');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            content.classList.add('active');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }

    // Open Lesson Modal & Set Dynamic Route
    function openLessonModal(moduleId) {
        const form = document.getElementById('lessonForm');
        form.action = `/edutech/instructor/courses/{{ $course->id }}/modules/${moduleId}/lessons`;
        document.getElementById('modalLesson').classList.add('active');
    }

    // Toggle Lesson Input Fields
    function toggleLessonFields(type) {
        document.getElementById('field-video').style.display = (type === 'video') ? 'block' : 'none';
        document.getElementById('field-pdf').style.display = (type === 'pdf') ? 'block' : 'none';
        document.getElementById('field-text').style.display = (type === 'text') ? 'block' : 'none';
    }

    // Open Quiz Modal Logic
    function openQuizModal(type, moduleId = null) {
        const modalTitle = document.getElementById('quizModalTitle');
        const typeField = document.getElementById('quizType');
        const moduleField = document.getElementById('quizModuleId');
        const titleInput = document.getElementById('quizInputTitle');

        typeField.value = type;
        moduleField.value = moduleId || '';

        if (type === 'pre_test') {
            modalTitle.textContent = 'Add Course Pre-Test';
            titleInput.value = 'Course Pre-Test';
        } else if (type === 'post_test') {
            modalTitle.textContent = 'Add Course Post-Test';
            titleInput.value = 'Course Post-Test';
        } else {
            modalTitle.textContent = 'Add Module Quiz';
            titleInput.value = 'Module Quiz';
        }

        document.getElementById('modalQuiz').classList.add('active');
    }

    // Close modals on outside click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
</script>
@endsection