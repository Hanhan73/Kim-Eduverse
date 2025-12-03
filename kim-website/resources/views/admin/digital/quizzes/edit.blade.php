@extends('layouts.admin-digital')

@section('title', 'Edit Quiz: ' . $quiz->title)

@push('styles')
<style>
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
        background: #f8f9fa;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-header {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        font-size: 2rem;
        color: var(--dark);
    }

    .quiz-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .quiz-badge.pre_test {
        background: #feebc8;
        color: #7c2d12;
    }

    .quiz-badge.post_test {
        background: #c6f6d5;
        color: #22543d;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: var(--light);
        color: var(--dark);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    .btn-edit {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-edit:hover {
        background: #90cdf4;
    }

    .btn-delete {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-delete:hover {
        background: #fc8181;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border: 1px solid #fc8181;
    }

    .grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .card h2 {
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control:disabled {
        background: #f7fafc;
        color: #a0aec0;
        cursor: not-allowed;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-hint {
        font-size: 0.85rem;
        color: var(--gray);
        margin-top: 5px;
    }

    .question-list {
        margin-top: 20px;
    }

    .question-item {
        background: var(--light);
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .question-item:hover {
        border-color: var(--primary);
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .question-number {
        background: var(--primary);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .question-type {
        padding: 4px 10px;
        background: var(--info);
        color: white;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .question-text {
        font-size: 1.05rem;
        color: var(--dark);
        margin: 15px 0;
        line-height: 1.6;
    }

    .question-options {
        margin: 10px 0 10px 20px;
    }

    .question-options li {
        margin: 5px 0;
        color: var(--gray);
    }

    .question-options li.correct {
        color: var(--success);
        font-weight: 600;
    }

    .question-points {
        color: var(--warning);
        font-weight: 600;
        margin-top: 10px;
    }

    .question-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 40px;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .modal-header h3 {
        font-size: 1.5rem;
        color: var(--dark);
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray);
    }

    .btn-close:hover {
        color: var(--dark);
    }

    .option-input {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }

    .option-input input[type="text"] {
        flex: 1;
    }

    .option-input input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .info-note {
        background: #fffbeb;
        padding: 12px 15px;
        border-radius: 8px;
        border-left: 4px solid var(--warning);
        margin-bottom: 15px;
    }

    .info-note i {
        color: var(--warning);
        margin-right: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-card {
        background: var(--light);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--gray);
        font-size: 0.9rem;
    }

    @media (max-width: 968px) {
        .grid-2col {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1>Edit Quiz</h1>
            <div style="margin-top: 10px;">
                <span class="quiz-badge {{ $quiz->type ?? 'quiz' }}">
                    @if($quiz->type === 'pre_test')
                    PRE-TEST
                    @elseif($quiz->type === 'post_test')
                    POST-TEST
                    @else
                    QUIZ
                    @endif
                </span>
                <span style="color: var(--gray); margin-left: 15px;">
                    <i class="fas fa-book"></i> {{ $quiz->title }}
                </span>
            </div>
        </div>
        <a href="{{ request()->header('referer') ?? route('admin.digital.seminars.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <strong>Error:</strong>
        <ul style="margin-top: 10px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid-2col">
        <!-- Edit Quiz Details -->
        <div class="card">
            <h2>Quiz Details</h2>
            <form action="{{ route('admin.digital.quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    Quiz type cannot be changed after creation
                </div>

                <div class="form-group">
                    <label>Quiz Type</label>
                    <input type="text" class="form-control"
                        value="{{ ucwords(str_replace('_', ' ', $quiz->type ?? 'quiz')) }}" disabled>
                </div>

                <div class="form-group">
                    <label>Title <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}"
                        required>
                    <div class="form-hint">Give your quiz a clear, descriptive title</div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"
                        placeholder="Describe what this quiz will assess...">{{ old('description', $quiz->description) }}</textarea>
                    <div class="form-hint">Optional: Provide instructions or context for students</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Passing Score (%) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="passing_score" class="form-control"
                            value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                        <div class="form-hint">Minimum score to pass (0-100)</div>
                    </div>
                    <div class="form-group">
                        <label>Duration (Minutes) <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control"
                            value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" required>
                        <div class="form-hint">Time limit for this quiz</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Maximum Attempts <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="max_attempts" class="form-control"
                        value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" max="10" required>
                    <div class="form-hint">How many times can students retake this quiz? (1-10)</div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Update Quiz Details
                </button>
            </form>
        </div>

        <!-- Quiz Stats -->
        <div class="card">
            <h2>Quiz Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--primary);">{{ $quiz->questions->count() }}</div>
                    <div class="stat-label">Total Questions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--success);">{{ $quiz->questions->sum('points') }}</div>
                    <div class="stat-label">Total Points</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: var(--warning);">{{ $quiz->passing_score }}%</div>
                    <div class="stat-label">Passing Score</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Section -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; border: none; padding: 0;">Questions ({{ $quiz->questions->count() }})</h2>

            <button onclick="openModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>

        @if($quiz->questions->count() > 0)
        <div class="question-list">
            @foreach($quiz->questions as $index => $question)
            <div class="question-item">
                <div class="question-header">
                    <span class="question-number">{{ $index + 1 }}</span>
                    <span class="question-type">MULTIPLE CHOICE</span>
                </div>

                <div class="question-text">{{ $question->question_text }}</div>

                <ul class="question-options">
                    <li class="{{ $question->correct_answer === 'a' ? 'correct' : '' }}">
                        A. {{ $question->option_a }}
                        @if($question->correct_answer === 'a')
                        <i class="fas fa-check-circle"></i>
                        @endif
                    </li>
                    <li class="{{ $question->correct_answer === 'b' ? 'correct' : '' }}">
                        B. {{ $question->option_b }}
                        @if($question->correct_answer === 'b')
                        <i class="fas fa-check-circle"></i>
                        @endif
                    </li>
                    <li class="{{ $question->correct_answer === 'c' ? 'correct' : '' }}">
                        C. {{ $question->option_c }}
                        @if($question->correct_answer === 'c')
                        <i class="fas fa-check-circle"></i>
                        @endif
                    </li>
                    <li class="{{ $question->correct_answer === 'd' ? 'correct' : '' }}">
                        D. {{ $question->option_d }}
                        @if($question->correct_answer === 'd')
                        <i class="fas fa-check-circle"></i>
                        @endif
                    </li>
                    @if($question->option_e)
                    <li class="{{ $question->correct_answer === 'e' ? 'correct' : '' }}">
                        E. {{ $question->option_e }}
                        @if($question->correct_answer === 'e')
                        <i class="fas fa-check-circle"></i>
                        @endif
                    </li>
                    @endif
                </ul>

                <div class="question-points">
                    <i class="fas fa-star"></i> {{ $question->points }} points
                </div>

                <div class="question-actions">
                    <button type="button" class="btn btn-sm btn-edit"
                        onclick="editQuestion({{ $question->id }}, '{{ $question->question_text }}', '{{ $question->option_a }}', '{{ $question->option_b }}', '{{ $question->option_c }}', '{{ $question->option_d }}', '{{ $question->option_e ?? '' }}', '{{ $question->correct_answer }}', {{ $question->points }})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="{{ route('admin.digital.quizzes.destroy-question', [$quiz->id, $question->id]) }}"
                        method="POST" style="display:inline;"
                        onsubmit="return confirm('Delete this question? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-question"></i>
            <h3>No Questions Yet</h3>
            <p>Click "Add Question" to create your first question</p>
        </div>
        @endif
    </div>
</div>

<!-- Question Modal (for both Add & Edit) -->
<div id="questionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Question</h3>
            <button class="btn-close" onclick="closeModal()">&times;</button>
        </div>

        <form id="questionForm" action="{{ route('admin.digital.quizzes.store-question', $quiz->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Question <span style="color: var(--danger);">*</span></label>
                <textarea id="question_text" name="question_text" class="form-control"
                    placeholder="Enter your question here..." required></textarea>
            </div>

            <!-- Multiple Choice Options -->
            <div id="mcOptions">
                <label style="display: block; font-weight: 600; margin-bottom: 10px;">
                    Answer Options <span style="color: var(--danger);">*</span>
                </label>
                <div class="form-hint" style="margin-bottom: 15px;">
                    <i class="fas fa-info-circle"></i> Click the radio button to mark the correct answer
                </div>
                <div class="option-input">
                    <input type="radio" name="correct_answer" value="a" required>
                    <input type="text" name="option_a" class="form-control" placeholder="Option A" required>
                </div>
                <div class="option-input">
                    <input type="radio" name="correct_answer" value="b" required>
                    <input type="text" name="option_b" class="form-control" placeholder="Option B" required>
                </div>
                <div class="option-input">
                    <input type="radio" name="correct_answer" value="c" required>
                    <input type="text" name="option_c" class="form-control" placeholder="Option C" required>
                </div>
                <div class="option-input">
                    <input type="radio" name="correct_answer" value="d" required>
                    <input type="text" name="option_d" class="form-control" placeholder="Option D" required>
                </div>
                <div class="option-input">
                    <input type="radio" name="correct_answer" value="e">
                    <input type="text" name="option_e" class="form-control" placeholder="Option E (Optional)">
                </div>
            </div>

            <div class="form-group">
                <label>Points <span style="color: var(--danger);">*</span></label>
                <input type="number" id="question_points" name="points" class="form-control" value="1" min="1" required>
                <div class="form-hint">Point value for this question</div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <span id="submitButtonText">Save Question</span>
                </button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal() {
        document.getElementById('questionModal').classList.add('active');
        document.getElementById('modalTitle').innerText = 'Add Question';
        document.getElementById('submitButtonText').innerText = 'Save Question';
        document.getElementById('questionForm').reset();
        document.getElementById('questionForm').action = "{{ route('admin.digital.quizzes.store-question', $quiz->id) }}";
        // Reset form method to POST for adding new question
        document.getElementById('questionForm').method = 'POST';
        // Remove any existing PUT method input
        const methodInput = document.querySelector('#questionForm input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
    }

    function closeModal() {
        document.getElementById('questionModal').classList.remove('active');
    }

    function editQuestion(id, text, optA, optB, optC, optD, optE, correct, points) {
        const modal = document.getElementById('questionModal');
        const form = document.getElementById('questionForm');

        // Populate form fields
        document.getElementById('question_text').value = text;
        document.querySelector('input[name="option_a"]').value = optA;
        document.querySelector('input[name="option_b"]').value = optB;
        document.querySelector('input[name="option_c"]').value = optC;
        document.querySelector('input[name="option_d"]').value = optD;
        document.querySelector('input[name="option_e"]').value = optE;
        document.getElementById('question_points').value = points;

        // Set the correct answer radio button
        document.querySelectorAll('input[name="correct_answer"]').forEach(radio => {
            radio.checked = (radio.value === correct);
        });

        // Change modal title and button text
        document.getElementById('modalTitle').innerText = 'Edit Question';
        document.getElementById('submitButtonText').innerText = 'Update Question';

        // Change form action and method for editing
        form.action = `{{ route('admin.digital.quizzes.update-question', [$quiz->id, 'QUESTION_ID']) }}`.replace(
            'QUESTION_ID', id);
        form.method = 'POST';

        // Add or update the _method input for PUT
        let methodInput = document.querySelector('#questionForm input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        // Show the modal
        modal.classList.add('active');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('questionModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endpush