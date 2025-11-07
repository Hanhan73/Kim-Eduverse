<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management - Instructor</title>
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
            background: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            padding: 25px 0;
            color: white;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 20px 25px;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .quiz-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .quiz-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
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

        .quiz-badge.active {
            background: #c6f6d5;
            color: #22543d;
        }

        .quiz-badge.inactive {
            background: #fed7d7;
            color: #742a2a;
        }

        .quiz-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .quiz-course {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .quiz-stats {
            display: flex;
            gap: 20px;
            padding: 15px 0;
            border-top: 1px solid var(--light);
            border-bottom: 1px solid var(--light);
            margin: 15px 0;
        }

        .quiz-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .quiz-stat i {
            color: var(--info);
        }

        .quiz-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit {
            background: #bee3f8;
            color: #2c5282;
            flex: 1;
        }

        .btn-edit:hover {
            background: #90cdf4;
        }

        .btn-toggle {
            background: #feebc8;
            color: #7c2d12;
        }

        .btn-toggle:hover {
            background: #fbd38d;
        }

        .btn-delete {
            background: #fed7d7;
            color: #742a2a;
        }

        .btn-delete:hover {
            background: #fc8181;
        }

        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--light);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .empty-state p {
            color: var(--gray);
            margin-bottom: 30px;
        }

        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .quiz-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-chalkboard-teacher"></i>
                Instructor Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.instructor.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('edutech.instructor.courses') }}" class="menu-item">
                <i class="fas fa-book"></i>
                My Courses
            </a>
            <a href="{{ route('edutech.instructor.courses.create') }}" class="menu-item">
                <i class="fas fa-plus-circle"></i>
                Create Course
            </a>
            <a href="{{ route('edutech.instructor.quiz.index') }}" class="menu-item active">
                <i class="fas fa-clipboard-list"></i>
                Quiz Management
            </a>
            <a href="{{ route('edutech.instructor.students') }}" class="menu-item">
                <i class="fas fa-users"></i>
                Students
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="{{ route('edutech.courses.index') }}" class="menu-item">
                <i class="fas fa-globe"></i>
                Browse Courses
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-home"></i>
                Home
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1>📝 Quiz Management</h1>
            <a href="{{ route('edutech.instructor.quiz.create') }}" class="btn-primary">
                <i class="fas fa-plus-circle"></i> Create Quiz
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($quizzes->count() > 0)
        <div class="quiz-grid">
            @foreach($quizzes as $quiz)
            <div class="quiz-card">
                <div class="quiz-header">
                    <span class="quiz-badge {{ $quiz->type }}">
                        {{ $quiz->type === 'pre_test' ? '📋 PRE-TEST' : '✅ POST-TEST' }}
                    </span>
                    <span class="quiz-badge {{ $quiz->is_active ? 'active' : 'inactive' }}">
                        {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="quiz-title">{{ $quiz->title }}</div>
                <div class="quiz-course">
                    <i class="fas fa-book"></i> {{ $quiz->course->title }}
                </div>

                <div class="quiz-stats">
                    <div class="quiz-stat">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ $quiz->questions_count }} Questions</span>
                    </div>
                    <div class="quiz-stat">
                        <i class="fas fa-clock"></i>
                        <span>{{ $quiz->duration_minutes }} min</span>
                    </div>
                    <div class="quiz-stat">
                        <i class="fas fa-trophy"></i>
                        <span>{{ $quiz->passing_score }}%</span>
                    </div>
                </div>

                <div style="color: var(--gray); font-size: 0.85rem; margin-bottom: 15px;">
                    <i class="fas fa-redo"></i> Max Attempts: {{ $quiz->max_attempts }}
                </div>

                <div class="quiz-actions">
                    <a href="{{ route('edutech.instructor.quiz.edit', $quiz->id) }}" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit & Questions
                    </a>

                    <form action="{{ route('edutech.instructor.quiz.toggle', $quiz->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-toggle" title="{{ $quiz->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas fa-{{ $quiz->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                    </form>

                    <form action="{{ route('edutech.instructor.quiz.destroy', $quiz->id) }}" method="POST" 
                          onsubmit="return confirm('Delete this quiz? This cannot be undone!')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <h3>No Quizzes Yet</h3>
            <p>Create your first pre-test or post-test to assess student knowledge</p>
            <a href="{{ route('edutech.instructor.quiz.create') }}" class="btn-primary">
                <i class="fas fa-plus-circle"></i> Create Your First Quiz
            </a>
        </div>
        @endif
    </main>
</body>
</html>