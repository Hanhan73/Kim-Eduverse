<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz - Instructor</title>
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
            --dark: #2d3748;
            --gray: #718096;
            --light: #f7fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .page-header p {
            color: var(--gray);
        }

        .form-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group label span {
            color: var(--danger);
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        .form-hint {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid var(--light);
        }

        .btn {
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
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

        .info-box {
            background: #e6fffa;
            border: 2px solid #81e6d9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-box h3 {
            color: #234e52;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box ul {
            margin-left: 30px;
            color: #2c7a7b;
        }

        .info-box ul li {
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>📝 Create Quiz</h1>
            <p>Create a pre-test or post-test for your course</p>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Quiz Types:</h3>
            <ul>
                <li><strong>Pre-Test:</strong> Assess student knowledge BEFORE starting the course</li>
                <li><strong>Post-Test:</strong> Evaluate learning outcomes AFTER completing the course (80% progress required)</li>
                <li>Each course can have ONE pre-test and ONE post-test</li>
            </ul>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <strong>Oops!</strong> There were some errors:
            <ul style="margin-top: 10px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('edutech.instructor.quiz.store') }}" method="POST">
            @csrf
            
            <div class="form-card">
                <h3 style="margin-bottom: 25px; color: var(--dark);">Quiz Details</h3>

                <!-- Course Selection -->
                <div class="form-group">
                    <label>Select Course <span>*</span></label>
                    <select name="course_id" class="form-control" required>
                        <option value="">Choose a course...</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                        @endforeach
                    </select>
                    @if($courses->count() === 0)
                    <div class="form-hint" style="color: var(--warning);">
                        <i class="fas fa-exclamation-triangle"></i> You need to create and publish a course first
                    </div>
                    @endif
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label>Quiz Title <span>*</span></label>
                    <input type="text" name="title" class="form-control" 
                           placeholder="e.g., Web Development Pre-Test Assessment" 
                           value="{{ old('title') }}" required>
                    <div class="form-hint">Give your quiz a clear, descriptive title</div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" 
                              placeholder="Describe what this quiz will assess...">{{ old('description') }}</textarea>
                    <div class="form-hint">Optional: Provide instructions or context for students</div>
                </div>

                <!-- Passing Score & Duration -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Passing Score (%) <span>*</span></label>
                        <input type="number" name="passing_score" class="form-control" 
                               placeholder="70" value="{{ old('passing_score', 70) }}" 
                               min="0" max="100" required>
                        <div class="form-hint">Minimum score to pass (0-100)</div>
                    </div>

                    <div class="form-group">
                        <label>Duration (Minutes) <span>*</span></label>
                        <input type="number" name="duration_minutes" class="form-control" 
                               placeholder="60" value="{{ old('duration_minutes', 60) }}" 
                               min="1" required>
                        <div class="form-hint">Time limit for this quiz</div>
                    </div>
                </div>

                <!-- Max Attempts -->
                <div class="form-group">
                    <label>Maximum Attempts <span>*</span></label>
                    <input type="number" name="max_attempts" class="form-control" 
                           placeholder="3" value="{{ old('max_attempts', 3) }}" 
                           min="1" max="10" required>
                    <div class="form-hint">How many times can students retake this quiz? (1-10)</div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" {{ $courses->count() === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-save"></i>
                        Create Quiz & Add Questions
                    </button>
                    <a href="{{ route('edutech.instructor.quiz.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>