<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - KIM EduTech</title>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8f9fa;
            color: var(--dark);
        }

        /* === LAYOUT === */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            padding: 25px 0;
            color: white;
            z-index: 1000;
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
            min-height: 100vh;
        }

        /* === TOP BAR === */
        .top-bar {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .btn-logout {
            padding: 10px 20px;
            background: #fee;
            color: var(--danger);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* === STATS GRID === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 65px;
            height: 65px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success), #38a169);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, var(--info), #3182ce);
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .stat-icon.pink {
            background: linear-gradient(135deg, #ed64a6, #d53f8c);
        }

        .stat-icon.teal {
            background: linear-gradient(135deg, #38b2ac, #319795);
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, #ecc94b, #d69e2e);
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #fc8181, #f56565);
        }

        .stat-content h3 {
            font-size: 2rem;
            color: var(--dark);
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-content p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .stat-content small {
            display: block;
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 3px;
        }

        /* === CONTENT SECTIONS === */
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: var(--dark);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(237, 137, 54, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* === TABLE === */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--light);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--light);
            color: var(--gray);
        }

        tr:hover {
            background: #fafafa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge.published {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.draft {
            background: #fed7d7;
            color: #742a2a;
        }

        .badge.success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.warning {
            background: #feebc8;
            color: #7c2d12;
        }

        .badge.info {
            background: #bee3f8;
            color: #2c5282;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 5px;
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

        /* === QUIZ SECTION === */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .quiz-card {
            background: white;
            border: 2px solid var(--light);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .quiz-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .quiz-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .quiz-meta {
            display: flex;
            gap: 15px;
            color: var(--gray);
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .quiz-stats {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid var(--light);
        }

        .quiz-stat-item {
            text-align: center;
        }

        .quiz-stat-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
        }

        .quiz-stat-label {
            font-size: 0.75rem;
            color: var(--gray);
        }

        /* === EMPTY STATE === */
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

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .empty-state p {
            margin-bottom: 20px;
        }

        /* === RESPONSIVE === */
        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
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
            <a href="{{ route('edutech.instructor.dashboard') }}" class="menu-item active">
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
            <a href="{{ route('edutech.instructor.students') }}" class="menu-item">
                <i class="fas fa-users"></i>
                Students
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>👨‍🏫 Selamat Datang, {{ session('edutech_user_name') }}!</h1>
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('edutech_user_name'), 0, 1)) }}
                </div>
                <form action="{{ route('edutech.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #c6f6d5; color: #22543d; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Main Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
                    <p>Total Courses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                    <p>Total Students</p>
                    <small>{{ $stats['active_students_month'] ?? 0 }} active this month</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['certificates_issued'] ?? 0 }}</h3>
                    <p>Certificates Issued</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['avg_rating'] ?? 0, 1) }}</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>

        <!-- Content Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pink">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_modules'] ?? 0 }}</h3>
                    <p>Total Modules</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon teal">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['video_lessons'] ?? 0 }}</h3>
                    <p>Video Lessons</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pdf_lessons'] ?? 0 }}</h3>
                    <p>PDF Materials</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_quizzes'] ?? 0 }}</h3>
                    <p>Total Quizzes</p>
                </div>
            </div>
        </div>

        <!-- Quiz Performance -->
        <div class="content-section">
            <div class="section-header">
                <h2>🎯 Quiz Performance</h2>
            </div>

            <div class="stats-grid" style="margin-bottom: 0;">
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $quizStats['total_attempts'] ?? 0 }}</h3>
                        <p>Total Attempts</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $quizStats['passed_attempts'] ?? 0 }}</h3>
                        <p>Passed Attempts</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($quizStats['pass_rate'] ?? 0, 1) }}%</h3>
                        <p>Pass Rate</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($quizStats['avg_score'] ?? 0, 1) }}</h3>
                        <p>Average Score</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses with Quiz Status -->
        <div class="content-section">
            <div class="section-header">
                <h2>📚 My Courses</h2>
                <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create New Course
                </a>
            </div>

            @if(isset($courses) && $courses->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Content</th>
                            <th>Quiz Status</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>
                                <strong style="color: var(--dark);">{{ $course->title }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ $course->category }}</small>
                            </td>
                            <td>
                                <span style="color: var(--info);">
                                    <i class="fas fa-layer-group"></i> {{ $course->modules_count }} modules
                                </span><br>
                                <span style="color: var(--gray); font-size: 0.85rem;">
                                    <i class="fas fa-play-circle"></i> {{ $course->total_lessons }} lessons
                                </span>
                            </td>
                            <td>
                                @if($course->has_pretest)
                                    <span class="badge success">✓ Pre-test</span>
                                @else
                                    <span class="badge warning">⚠ No Pre-test</span>
                                @endif
                                <br>
                                @if($course->has_posttest)
                                    <span class="badge success">✓ Post-test</span>
                                @else
                                    <span class="badge warning">⚠ No Post-test</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-users" style="color: var(--info);"></i>
                                {{ $course->enrollments_count ?? 0 }}
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="badge published">Published</span>
                                @else
                                    <span class="badge draft">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('edutech.instructor.courses.edit', $course->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>Belum Ada Course</h3>
                <p>Mulai buat course pertama Anda sekarang!</p>
                <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create First Course
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Quiz Attempts -->
        @if(isset($recentQuizAttempts) && $recentQuizAttempts->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2>📊 Recent Quiz Attempts</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quiz</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Result</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentQuizAttempts as $attempt)
                        <tr>
                            <td>
                                <strong>{{ $attempt->user->name ?? 'Unknown' }}</strong>
                            </td>
                            <td>{{ $attempt->quiz->title ?? 'N/A' }}</td>
                            <td>
                                <small>{{ $attempt->quiz->course->title ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong style="color: var(--info);">{{ number_format($attempt->score, 0) }}</strong>
                            </td>
                            <td>
                                @if($attempt->is_passed)
                                    <span class="badge success">
                                        <i class="fas fa-check"></i> Passed
                                    </span>
                                @else
                                    <span class="badge warning">
                                        <i class="fas fa-times"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $attempt->submitted_at ? $attempt->submitted_at->diffForHumans() : 'N/A' }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Students -->
        @if(isset($recentStudents) && $recentStudents->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2>👥 Recent Students</h2>
                <a href="{{ route('edutech.instructor.students') }}" class="btn-secondary">
                    View All Students
                </a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStudents as $enrollment)
                        <tr>
                            <td>
                                <strong>{{ $enrollment->student->name ?? 'Unknown' }}</strong><br>
                                <small style="color: var(--gray);">{{ $enrollment->student->email ?? '' }}</small>
                            </td>
                            <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                        <div style="width: {{ $enrollment->progress_percentage ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                                    </div>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--dark);">
                                        {{ $enrollment->progress_percentage ?? 0 }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($enrollment->status === 'completed')
                                    <span class="badge success">Completed</span>
                                @elseif($enrollment->status === 'active')
                                    <span class="badge info">Active</span>
                                @else
                                    <span class="badge">{{ ucfirst($enrollment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : 'N/A' }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </main>
</body>
</html>