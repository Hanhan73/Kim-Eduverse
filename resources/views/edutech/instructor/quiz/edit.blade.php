<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz - Instructor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #667eea; --secondary: #764ba2; --success: #48bb78;
            --warning: #ed8936; --danger: #f56565; --info: #4299e1;
            --dark: #2d3748; --gray: #718096; --light: #f7fafc;
        }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .page-header { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 2rem; color: var(--dark); }
        .quiz-badge { padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .quiz-badge.pre_test { background: #feebc8; color: #7c2d12; }
        .quiz-badge.post_test { background: #c6f6d5; color: #22543d; }
        .quiz-badge.module_quiz { background: #e6fffa; color: #234e52; }
        .btn { padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); }
        .btn-secondary { background: var(--light); color: var(--dark); }
        .btn-secondary:hover { background: #e2e8f0; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-error { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .card h2 { font-size: 1.5rem; color: var(--dark); margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid var(--light); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--dark); margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
        .form-control:disabled { background: #f7fafc; color: #a0aec0; cursor: not-allowed; }
        textarea.form-control { min-height: 100px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-hint { font-size: 0.85rem; color: var(--gray); margin-top: 5px; }
        .question-list { margin-top: 20px; }
        .question-item { background: var(--light); border: 2px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 15px; transition: all 0.3s ease; }
        .question-item:hover { border-color: var(--primary); }
        .question-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .question-number { background: var(--primary); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .question-type { padding: 4px 10px; background: var(--info); color: white; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .question-text { font-size: 1.05rem; color: var(--dark); margin: 15px 0; line-height: 1.6; }
        .question-options { margin: 10px 0 10px 20px; }
        .question-options li { margin: 5px 0; color: var(--gray); }
        .question-options li.correct { color: var(--success); font-weight: 600; }
        .question-points { color: var(--warning); font-weight: 600; margin-top: 10px; }
        .question-actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn-sm { padding: 8px 16px; font-size: 0.85rem; }
        .btn-edit { background: #bee3f8; color: #2c5282; }
        .btn-edit:hover { background: #90cdf4; }
        .btn-delete { background: #fed7d7; color: #742a2a; }
        .btn-delete:hover { background: #fc8181; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 40px; border-radius: 12px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h3 { font-size: 1.5rem; color: var(--dark); }
        .btn-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray); }
        .btn-close:hover { color: var(--dark); }
        .option-input { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
        .option-input input[type="text"] { flex: 1; }
        .option-input input[type="radio"] { width: 20px; height: 20px; cursor: pointer; }
        .btn-add-option { background: var(--light); color: var(--dark); padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; cursor: pointer; border: 2px dashed #e2e8f0; width: 100%; margin-top: 10px; }
        .btn-add-option:hover { border-color: var(--primary); background: #f0f4ff; }
        .btn-remove-option { background: var(--danger); color: white; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; cursor: pointer; border: none; }
        .btn-remove-option:hover { background: #c53030; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--gray); }
        .empty-state i { font-size: 4rem; margin-bottom: 20px; opacity: 0.3; }
        .info-note { background: #fffbeb; padding: 12px 15px; border-radius: 8px; border-left: 4px solid var(--warning); margin-bottom: 15px; }
        .info-note i { color: var(--warning); margin-right: 8px; }
        @media (max-width: 968px) {
            .grid-2col { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <div>
                <h1>Edit Quiz</h1>
                <div style="margin-top: 10px;">
                    <span class="quiz-badge {{ $quiz->type }}">
                        @if($quiz->type === 'pre_test')
                            PRE-TEST
                        @elseif($quiz->type === 'post_test')
                            POST-TEST
                        @else
                            MODULE QUIZ
                        @endif
                    </span>
                    <span style="color: var(--gray); margin-left: 15px;">
                        <i class="fas fa-book"></i> {{ $quiz->course->title }}
                    </span>
                </div>
            </div>
            <a href="{{ route('edutech.instructor.courses.edit', $quiz->course->id) }}" class="btn btn-secondary">
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
                <form action="{{ route('edutech.instructor.quiz.update', $quiz->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="info-note">
                        <i class="fas fa-info-circle"></i>
                        Quiz type and course cannot be changed after creation
                    </div>

                    <div class="form-group">
                        <label>Quiz Type</label>
                        <input type="text" class="form-control" value="{{ ucwords(str_replace('_', ' ', $quiz->type)) }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Course</label>
                        <input type="text" class="form-control" value="{{ $quiz->course->title }}" disabled>
                    </div>

                    @if($quiz->type === 'module_quiz' && $quiz->module)
                    <div class="form-group">
                        <label>Module</label>
                        <input type="text" class="form-control" value="{{ $quiz->module->title }}" disabled>
                    </div>
                    @endif

                    <div class="form-group">
                        <label>Title <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}" required>
                        <div class="form-hint">Give your quiz a clear, descriptive title</div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Describe what this quiz will assess...">{{ old('description', $quiz->description) }}</textarea>
                        <div class="form-hint">Optional: Provide instructions or context for students</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Passing Score (%) <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="passing_score" class="form-control" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                            <div class="form-hint">Minimum score to pass (0-100)</div>
                        </div>
                        <div class="form-group">
                            <label>Duration (Minutes) <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1" required>
                            <div class="form-hint">Time limit for this quiz</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Maximum Attempts <span style="color: var(--danger);">*</span></label>
                        <input type="number" name="max_attempts" class="form-control" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" max="10" required>
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
                <div style="display: grid; gap: 20px;">
                    <div style="background: var(--light); padding: 20px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary);">{{ $quiz->questions->count() }}</div>
                        <div style="color: var(--gray); margin-top: 5px;">Total Questions</div>
                    </div>
                    <div style="background: var(--light); padding: 20px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: var(--success);">{{ $quiz->questions->sum('points') }}</div>
                        <div style="color: var(--gray); margin-top: 5px;">Total Points</div>
                    </div>
                    <div style="background: var(--light); padding: 20px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: var(--warning);">{{ $quiz->passing_score }}%</div>
                        <div style="color: var(--gray); margin-top: 5px;">Passing Score</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0; border: none; padding: 0;">Questions ({{ $quiz->questions->count() }})</h2>
                
                <div style="display: flex; gap: 10px;">
                    @if($quiz->type === 'pre_test')
                        <form action="{{ route('edutech.instructor.quiz.sync', [$quiz->id, 'post_test']) }}" method="POST" onsubmit="return confirm('Sync questions to Post-Test? This will copy all questions.')">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Sync to Post-Test
                            </button>
                        </form>
                    @elseif($quiz->type === 'post_test')
                        <form action="{{ route('edutech.instructor.quiz.sync', [$quiz->id, 'pre_test']) }}" method="POST" onsubmit="return confirm('Sync questions to Pre-Test? This will copy all questions.')">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Sync to Pre-Test
                            </button>
                        </form>
                    @endif

                    <button onclick="openModal()" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Question
                    </button>
                </div>
            </div>

            @if($quiz->questions->count() > 0)
            <div class="question-list">
                @foreach($quiz->questions as $index => $question)
                <div class="question-item">
                    <div class="question-header">
                        <span class="question-number">{{ $index + 1 }}</span>
                        <span class="question-type">{{ strtoupper(str_replace('_', ' ', $question->type)) }}</span>
                    </div>

                    <div class="question-text">{{ $question->question }}</div>

                    @if($question->type === 'multiple_choice' && $question->options)
                    <ul class="question-options">
                        @foreach($question->options as $option)
                        <li class="{{ $option === $question->correct_answer ? 'correct' : '' }}">
                            {{ $option }}
                            @if($option === $question->correct_answer)
                            <i class="fas fa-check-circle"></i>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @elseif($question->type === 'true_false')
                    <div class="question-options">
                        <div class="{{ $question->correct_answer === 'True' ? 'correct' : '' }}" style="margin: 5px 0;">
                            ✓ True @if($question->correct_answer === 'True')<i class="fas fa-check-circle"></i>@endif
                        </div>
                        <div class="{{ $question->correct_answer === 'False' ? 'correct' : '' }}" style="margin: 5px 0;">
                            ✗ False @if($question->correct_answer === 'False')<i class="fas fa-check-circle"></i>@endif
                        </div>
                    </div>
                    @elseif($question->type === 'essay')
                    <div class="info-note" style="margin-top: 10px;">
                        <i class="fas fa-pen"></i> Essay question - requires manual grading
                    </div>
                    @endif

                    <div class="question-points">
                        <i class="fas fa-star"></i> {{ $question->points }} points
                    </div>

                    <div class="question-actions">
                        <form action="{{ route('edutech.instructor.quiz.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this question? This action cannot be undone.')">
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

    <!-- Add Question Modal -->
    <div id="questionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Question</h3>
                <button class="btn-close" onclick="closeModal()">&times;</button>
            </div>

            <form action="{{ route('edutech.instructor.quiz.questions.store', $quiz->id) }}" method="POST" id="questionForm">
                @csrf
                
                <div class="form-group">
                    <label>Question Type <span style="color: var(--danger);">*</span></label>
                    <select name="type" id="questionType" class="form-control" onchange="toggleQuestionFields()" required>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Question <span style="color: var(--danger);">*</span></label>
                    <textarea name="question" class="form-control" placeholder="Enter your question here..." required></textarea>
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
                        <input type="radio" name="correct_answer" value="" required>
                        <input type="text" name="options[]" class="form-control" placeholder="Option 1" required oninput="updateRadioValue(this)">
                        <button type="button" class="btn-remove-option" onclick="removeOption(this)">✖</button>
                    </div>
                    <div class="option-input">
                        <input type="radio" name="correct_answer" value="" required>
                        <input type="text" name="options[]" class="form-control" placeholder="Option 2" required oninput="updateRadioValue(this)">
                        <button type="button" class="btn-remove-option" onclick="removeOption(this)">✖</button>
                    </div>
                    <button type="button" class="btn-add-option" onclick="addOption()">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                </div>
                    
                <!-- True/False Options -->
                <div id="tfOptions" class="form-group" style="display: none;">
                    <label>Correct Answer <span style="color: var(--danger);">*</span></label>
                    <select name="correct_answer_tf" class="form-control">
                        <option value="True">True</option>
                        <option value="False">False</option>
                    </select>
                </div>

                <!-- Essay Note -->
                <div id="essayNote" style="display: none;">
                    <div class="info-note">
                        <i class="fas fa-info-circle"></i> Essay questions require manual grading by the instructor
                    </div>
                </div>

                <div class="form-group">
                    <label>Points <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="points" class="form-control" value="10" min="1" required>
                    <div class="form-hint">Point value for this question</div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Save Question
                    </button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let optionCount = 2;

        function openModal() {
            document.getElementById('questionModal').classList.add('active');
            document.getElementById('questionForm').reset();
            toggleQuestionFields();
            resetOptions();
        }

        function closeModal() {
            document.getElementById('questionModal').classList.remove('active');
        }

        function resetOptions() {
            const mcOptions = document.getElementById('mcOptions');
            const optionInputs = mcOptions.querySelectorAll('.option-input');
            
            // Remove all options except first 2
            optionInputs.forEach((opt, index) => {
                if (index > 1) {
                    mcOptions.removeChild(opt);
                }
            });
            
            // Reset first 2 options
            optionInputs.forEach((opt, index) => {
                if (index < 2) {
                    const textInput = opt.querySelector('input[type="text"]');
                    const radioInput = opt.querySelector('input[type="radio"]');
                    textInput.value = '';
                    textInput.placeholder = `Option ${index + 1}`;
                    radioInput.value = '';
                    radioInput.checked = false;
                }
            });
            
            optionCount = 2;
        }

        function updateRadioValue(input) {
            const radio = input.parentElement.querySelector('input[type="radio"]');
            radio.value = input.value;
            if (!input.value.trim()) {
                radio.checked = false;
            }
        }

        function toggleQuestionFields() {
            const type = document.getElementById('questionType').value;
            const mcOptions = document.getElementById('mcOptions');
            const tfOptions = document.getElementById('tfOptions');
            const essayNote = document.getElementById('essayNote');

            // Hide all first
            mcOptions.style.display = 'none';
            tfOptions.style.display = 'none';
            essayNote.style.display = 'none';

            // Show relevant section
            if (type === 'multiple_choice') {
                mcOptions.style.display = 'block';
                mcOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = true);
                tfOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = false);
            } else if (type === 'true_false') {
                tfOptions.style.display = 'block';
                tfOptions.querySelector('select').setAttribute('name', 'correct_answer');
                tfOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = true);
                mcOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = false);
            } else if (type === 'essay') {
                essayNote.style.display = 'block';
                mcOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = false);
                tfOptions.querySelectorAll('input, textarea, select').forEach(el => el.required = false);
                
                // Add hidden input for essay
                let hiddenInput = document.querySelector('input[name="correct_answer"][type="hidden"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'correct_answer';
                    hiddenInput.value = 'N/A';
                    document.getElementById('questionForm').appendChild(hiddenInput);
                }
            }
        }

        function addOption() {
            optionCount++;
            const mcOptions = document.getElementById('mcOptions');
            const addButton = mcOptions.querySelector('.btn-add-option');
            const newOption = document.createElement('div');
            newOption.className = 'option-input';
            newOption.innerHTML = `
                <input type="radio" name="correct_answer" value="" required>
                <input type="text" name="options[]" class="form-control" placeholder="Option ${optionCount}" required oninput="updateRadioValue(this)">
                <button type="button" class="btn-remove-option" onclick="removeOption(this)">✖</button>
            `;
            mcOptions.insertBefore(newOption, addButton);
        }

        function removeOption(button) {
            const mcOptions = document.getElementById('mcOptions');
            const optionDiv = button.parentElement;
            const optionInputs = mcOptions.querySelectorAll('.option-input');

            if (optionInputs.length <= 2) {
                alert("A question must have at least 2 options!");
                return;
            }

            mcOptions.removeChild(optionDiv);
            updateOptionPlaceholders();
        }

        function updateOptionPlaceholders() {
            const optionInputs = document.querySelectorAll('#mcOptions .option-input');
            optionInputs.forEach((opt, i) => {
                const textInput = opt.querySelector('input[type="text"]');
                textInput.placeholder = `Option ${i + 1}`;
            });
            optionCount = optionInputs.length;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('questionModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleQuestionFields();
        });
    </script>
</body>
</html>