@extends('layouts.admin-digital')

@section('title', 'Edit Quiz - ' . $quiz->title)
@section('page-title', 'Edit Quiz')

@section('styles')
<style>
:root {
    --primary: #667eea;
    --primary-dark: #5a67d8;
    --secondary: #764ba2;
    --success: #48bb78;
    --warning: #ed8936;
    --danger: #f56565;
    --info: #4299e1;
    --dark: #2d3748;
    --gray: #718096;
    --light: #f7fafc;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark);
}

.page-header .breadcrumb {
    display: flex;
    gap: 8px;
    align-items: center;
    color: var(--gray);
    font-size: 0.9rem;
}

.page-header .breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

/* Stats Cards */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

.stat-icon.success {
    background: linear-gradient(135deg, #38a169, #48bb78);
}

.stat-icon.warning {
    background: linear-gradient(135deg, #dd6b20, #ed8936);
}

.stat-icon.info {
    background: linear-gradient(135deg, #2b6cb0, #4299e1);
}

.stat-content h4 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.stat-content p {
    color: var(--gray);
    font-size: 0.9rem;
    margin: 0;
}

/* Main Grid */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

/* Card */
.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #edf2f7;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

/* Form */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--dark);
    font-size: 0.9rem;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-check input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #edf2f7;
    color: var(--dark);
}

.btn-success {
    background: linear-gradient(135deg, #38a169, #48bb78);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #dd6b20, #ed8936);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #c53030, #f56565);
    color: white;
}

.btn-info {
    background: linear-gradient(135deg, #2b6cb0, #4299e1);
    color: white;
}

.btn-sm {
    padding: 8px 14px;
    font-size: 0.85rem;
}

.btn-block {
    width: 100%;
    justify-content: center;
}

/* Questions List */
.questions-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.question-item {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.question-item:hover {
    border-color: var(--primary);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.question-number {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.question-text {
    flex: 1;
    font-weight: 600;
    color: var(--dark);
    line-height: 1.5;
}

.question-meta {
    display: flex;
    gap: 15px;
    font-size: 0.85rem;
    color: var(--gray);
    margin-top: 10px;
}

.question-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.question-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

.option-item {
    padding: 10px 15px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.option-item.correct {
    background: #c6f6d5;
    border-color: #48bb78;
    color: #22543d;
}

.option-label {
    font-weight: 700;
    color: var(--primary);
}

.question-actions {
    display: flex;
    gap: 8px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: var(--dark);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 20px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal.show {
    display: flex !important;
}

.modal-content {
    background: white;
    border-radius: 15px;
    width: 100%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #edf2f7;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header h3 {
    font-size: 1.2rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #edf2f7;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: #f8fafc;
    border-radius: 0 0 15px 15px;
}

/* Options Grid in Modal */
.options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.option-group {
    position: relative;
}

.option-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.option-group .option-marker {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
}

/* Radio Group for Correct Answer */
.correct-answer-group {
    background: #f0fff4;
    border: 2px solid #68d391;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}

.correct-answer-group h4 {
    color: #22543d;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.radio-options {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.radio-option:hover {
    border-color: var(--success);
}

.radio-option input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.radio-option input[type="radio"]:checked+span {
    color: var(--success);
    font-weight: 700;
}

/* Badge */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: #c6f6d5;
    color: #22543d;
}

.badge-warning {
    background: #feebc8;
    color: #744210;
}

.badge-danger {
    background: #fed7d7;
    color: #742a2a;
}

.badge-info {
    background: #bee3f8;
    color: #2a4365;
}

/* Info Box */
.info-box {
    background: #ebf8ff;
    border: 2px solid #90cdf4;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.info-box i {
    color: #2b6cb0;
    font-size: 1.2rem;
    margin-top: 2px;
}

.info-box p {
    color: #2c5282;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 1024px) {
    .main-grid {
        grid-template-columns: 1fr;
    }

    .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .options-grid {
        grid-template-columns: 1fr;
    }

    .question-options {
        grid-template-columns: 1fr;
    }
}

.sync-info-box {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
    border: 2px solid #0284c7;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.sync-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-sync {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    flex: 1;
}

.btn-sync:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(14, 165, 233, 0.4);
}

.sync-warning {
    background: #fef3c7;
    border: 2px solid #fbbf24;
    border-radius: 8px;
    padding: 12px;
    margin-top: 10px;
    font-size: 0.85rem;
    color: #92400e;
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.digital.seminars.index') }}">Seminars</a>
            <i class="fas fa-chevron-right"></i>
            <span>Edit Quiz</span>
        </div>
        <h1>{{ $quiz->title }}</h1>
    </div>
    <a href="{{ route('admin.digital.seminars.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $quiz->questions->count() }}</h4>
            <p>Total Soal</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $quiz->questions->sum('points') }}</h4>
            <p>Total Poin</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $quiz->passing_score }}%</h4>
            <p>Passing Score</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $quiz->duration_minutes }}</h4>
            <p>Menit</p>
        </div>
    </div>
</div>

<div class="main-grid">
    <!-- Questions Section -->
    <div>
        <!-- Sync Feature Box -->
        @php
        // Find related quiz (pre-test or post-test)
        $relatedQuiz = null;
        $syncType = '';

        // Get seminar that uses this quiz
        $seminar = \App\Models\Seminar::where('pre_test_id', $quiz->id)
        ->orWhere('post_test_id', $quiz->id)
        ->first();

        if ($seminar) {
        if ($seminar->pre_test_id === $quiz->id && $seminar->post_test_id) {
        $relatedQuiz = $seminar->postTest;
        $syncType = 'Post-Test';
        } elseif ($seminar->post_test_id === $quiz->id && $seminar->pre_test_id) {
        $relatedQuiz = $seminar->preTest;
        $syncType = 'Pre-Test';
        }
        }
        @endphp

        @if($relatedQuiz)
        <div class="sync-info-box">
            <h4 style="margin: 0 0 10px 0; color: #0c4a6e; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sync-alt"></i> Sinkronisasi Pertanyaan
            </h4>
            <p style="color: #0c4a6e; margin: 0 0 15px 0; line-height: 1.6;">
                Quiz ini terhubung dengan <strong>{{ $relatedQuiz->title }}</strong> dalam seminar
                <strong>{{ $seminar->title }}</strong>. Anda dapat menyinkronkan pertanyaan antara kedua quiz.
            </p>

            <div style="background: white; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 5px;">Quiz Ini</div>
                        <div style="font-weight: 600; color: #0c4a6e;">{{ $quiz->questions->count() }} Pertanyaan</div>
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 5px;">{{ $syncType }}</div>
                        <div style="font-weight: 600; color: #0c4a6e;">{{ $relatedQuiz->questions->count() }} Pertanyaan
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.digital.quizzes.sync', [$quiz->id, $relatedQuiz->id]) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menyinkronkan pertanyaan? Ini akan menghapus semua pertanyaan di {{ $syncType }} dan menggantinya dengan pertanyaan dari quiz ini.')">
                @csrf
                <button type="submit" class="btn btn-sync btn-block">
                    <i class="fas fa-sync-alt"></i>
                    Sync ke {{ $syncType }} ({{ $relatedQuiz->title }})
                </button>
            </form>

            <div class="sync-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Peringatan:</strong> Sinkronisasi akan menghapus semua pertanyaan yang ada di {{ $syncType }}
                dan menggantinya dengan pertanyaan dari quiz ini. Pastikan Anda sudah yakin!
            </div>
        </div>
        @endif
        <div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list-ol"></i> Daftar Pertanyaan ({{ $quiz->questions->count() }})</h3>
                <button type="button" class="btn btn-primary" onclick="openAddQuestionModal()">
                    <i class="fas fa-plus"></i> Tambah Pertanyaan
                </button>
            </div>
            <div class="card-body">
                @if($quiz->questions->count() > 0)
                <div class="questions-list">
                    @foreach($quiz->questions->sortBy('order') as $index => $question)
                    <div class="question-item" data-id="{{ $question->id }}">
                        <div class="question-header">
                            <div style="display: flex; gap: 15px; align-items: flex-start; flex: 1;">
                                <div class="question-number">{{ $index + 1 }}</div>
                                <div>
                                    <div class="question-text">{{ $question->question }}</div>
                                    <div class="question-meta">
                                        <span><i class="fas fa-star"></i> {{ $question->points }} poin</span>
                                        <span><i class="fas fa-check-circle"></i> Jawaban:
                                            {{ $question->correct_answer }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="question-actions">
                                <button type="button" class="btn btn-sm btn-info"
                                    onclick="openEditQuestionModal({{ $question->id }}, {{ json_encode($question) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form
                                    action="{{ route('admin.digital.quizzes.destroy-question', [$quiz->id, $question->id]) }}"
                                    method="POST" style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($question->options)
                        <div class="question-options">
                            @foreach($question->options as $key => $option)
                            @if($option)
                            <div class="option-item {{ $key == $question->correct_answer ? 'correct' : '' }}">
                                <span class="option-label">{{ $key }}.</span>
                                <span>{{ $option }}</span>
                                @if($key == $question->correct_answer)
                                <i class="fas fa-check" style="margin-left: auto; color: #22543d;"></i>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h3>Belum Ada Pertanyaan</h3>
                    <p>Klik tombol "Tambah Pertanyaan" untuk menambahkan soal ke quiz ini</p>
                    <button type="button" class="btn btn-primary" onclick="openAddQuestionModal()">
                        <i class="fas fa-plus"></i> Tambah Pertanyaan Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar - Quiz Settings -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-cog"></i> Pengaturan Quiz</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.digital.quizzes.update', $quiz) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Judul Quiz</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description', $quiz->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Durasi (menit)</label>
                        <input type="number" name="duration_minutes" class="form-control"
                            value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Passing Score (%)</label>
                        <input type="number" name="passing_score" class="form-control"
                            value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label>Maksimal Percobaan</label>
                        <input type="number" name="max_attempts" class="form-control"
                            value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ $quiz->is_active ? 'checked' : '' }}>
                            <span>Quiz Aktif</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>
                        <strong>Tips:</strong><br>
                        • Pastikan setiap pertanyaan memiliki minimal 2 opsi jawaban<br>
                        • Tentukan jawaban yang benar dengan tepat<br>
                        • Sesuaikan poin berdasarkan tingkat kesulitan
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Question Modal -->
<div id="questionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-question-circle"></i> <span id="modalTitle">Tambah Pertanyaan</span></h3>
            <button type="button" class="modal-close" onclick="closeQuestionModal()">&times;</button>
        </div>

        <form id="questionForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label>Pertanyaan <span style="color: red;">*</span></label>
                    <textarea name="question_text" id="questionText" class="form-control" rows="3" required
                        placeholder="Tulis pertanyaan di sini..."></textarea>
                </div>

                <div class="options-grid">
                    <div class="option-group">
                        <label>
                            <span class="option-marker">A</span>
                            Opsi A <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="option_a" id="optionA" class="form-control" required
                            placeholder="Jawaban opsi A">
                    </div>

                    <div class="option-group">
                        <label>
                            <span class="option-marker">B</span>
                            Opsi B <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="option_b" id="optionB" class="form-control" required
                            placeholder="Jawaban opsi B">
                    </div>

                    <div class="option-group">
                        <label>
                            <span class="option-marker">C</span>
                            Opsi C
                        </label>
                        <input type="text" name="option_c" id="optionC" class="form-control"
                            placeholder="Jawaban opsi C (opsional)">
                    </div>

                    <div class="option-group">
                        <label>
                            <span class="option-marker">D</span>
                            Opsi D
                        </label>
                        <input type="text" name="option_d" id="optionD" class="form-control"
                            placeholder="Jawaban opsi D (opsional)">
                    </div>

                    <div class="option-group" style="grid-column: span 2;">
                        <label>
                            <span class="option-marker">E</span>
                            Opsi E
                        </label>
                        <input type="text" name="option_e" id="optionE" class="form-control"
                            placeholder="Jawaban opsi E (opsional)">
                    </div>
                </div>

                <div class="correct-answer-group">
                    <h4><i class="fas fa-check-circle"></i> Jawaban Benar <span style="color: red;">*</span></h4>
                    <div class="radio-options">
                        <label class="radio-option">
                            <input type="radio" name="correct_answer" value="A" required>
                            <span>Opsi A</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="correct_answer" value="B">
                            <span>Opsi B</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="correct_answer" value="C">
                            <span>Opsi C</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="correct_answer" value="D">
                            <span>Opsi D</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="correct_answer" value="E">
                            <span>Opsi E</span>
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label>Poin <span style="color: red;">*</span></label>
                    <input type="number" name="points" id="questionPoints" class="form-control" value="10" min="1"
                        required>
                    <small style="color: var(--gray);">Poin yang didapat jika menjawab benar</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeQuestionModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <span id="submitBtnText">Simpan Pertanyaan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Pindahkan semua fungsi ke global scope
window.openAddQuestionModal = function() {
    console.log('Opening add question modal...');
    document.getElementById('modalTitle').textContent = 'Tambah Pertanyaan';
    document.getElementById('submitBtnText').textContent = 'Simpan Pertanyaan';
    document.getElementById('questionForm').action =
        "{{ route('admin.digital.quizzes.store-question', $quiz->id) }}";
    document.getElementById('formMethod').value = 'POST';

    // Reset form
    document.getElementById('questionForm').reset();
    document.getElementById('questionPoints').value = 10;

    // Show modal
    document.getElementById('questionModal').classList.add('show');
    document.body.style.overflow = 'hidden';
};

window.openEditQuestionModal = function(questionId, questionData) {
    console.log('Opening edit modal for question:', questionId);

    try {
        // Parse questionData jika string
        if (typeof questionData === 'string') {
            questionData = JSON.parse(questionData);
        }

        document.getElementById('modalTitle').textContent = 'Edit Pertanyaan';
        document.getElementById('submitBtnText').textContent = 'Update Pertanyaan';
        document.getElementById('questionForm').action =
            `/admin/digital/quizzes/{{ $quiz->id }}/questions/${questionId}`;
        document.getElementById('formMethod').value = 'PUT';

        // Fill form with question data
        document.getElementById('questionText').value = questionData.question || '';
        document.getElementById('questionPoints').value = questionData.points || 10;

        // Fill options
        if (questionData.options) {
            const options = typeof questionData.options === 'string' ?
                JSON.parse(questionData.options) : questionData.options;

            document.getElementById('optionA').value = options.A || '';
            document.getElementById('optionB').value = options.B || '';
            document.getElementById('optionC').value = options.C || '';
            document.getElementById('optionD').value = options.D || '';
            document.getElementById('optionE').value = options.E || '';
        }

        // Select correct answer
        const correctAnswer = questionData.correct_answer;
        if (correctAnswer) {
            const radio = document.querySelector(`input[name="correct_answer"][value="${correctAnswer}"]`);
            if (radio) radio.checked = true;
        }

        // Show modal
        document.getElementById('questionModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Terjadi kesalahan saat membuka modal edit');
    }
};

window.closeQuestionModal = function() {
    document.getElementById('questionModal').classList.remove('show');
    document.body.style.overflow = 'auto';
};

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing modal functions...');

    // Close modal when clicking outside
    const modal = document.getElementById('questionModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeQuestionModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeQuestionModal();
        }
    });

    // Test: expose function to global scope
    console.log('Functions available:', {
        openAddQuestionModal: typeof window.openAddQuestionModal,
        closeQuestionModal: typeof window.closeQuestionModal
    });
});
</script>
@endsection