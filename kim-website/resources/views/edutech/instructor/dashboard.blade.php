<!DOCTYPE html>
<html lang="id">
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
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
            --gray: #718096;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            display: flex;
            min-height: 100vh;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #ed8936, #dd6b20);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: block;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            color: white;
        }

        .menu-item i {
            width: 25px;
            margin-right: 12px;
        }

        .menu-divider {
            margin: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            width: calc(100% - 260px);
        }

        .top-bar {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
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
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .btn-logout {
            background: var(--danger);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #e53e3e;
            transform: translateY(-2px);
        }

        /* === STATS CARDS === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
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
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
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
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(237, 137, 54, 0.3);
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
            
            <div class="menu-divider"></div>
            
            <a href="{{ route('edutech.courses') }}" class="menu-item">
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

        <!-- Stats Grid -->
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

        <!-- My Courses -->
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
                            <th>Category</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>
                                <strong style="color: var(--dark);">{{ $course->title }}</strong>
                            </td>
                            <td>{{ $course->category }}</td>
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
                                @if($course->price > 0)
                                    <strong style="color: var(--dark);">Rp {{ number_format($course->price, 0, ',', '.') }}</strong>
                                @else
                                    <strong style="color: var(--success);">GRATIS</strong>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('edutech.instructor.courses.edit', $course->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('edutech.course.detail', $course->slug) }}" class="btn-action btn-edit">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-book"></i>
                <h3>Belum Ada Course</h3>
                <p>Mulai berbagi ilmu Anda dengan membuat course pertama</p>
                <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create Your First Course
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Students -->
        @if(isset($recentStudents) && $recentStudents->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2>👥 Recent Students</h2>
                <a href="{{ route('edutech.instructor.students') }}" class="btn-primary">View All</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Enrolled Date</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStudents as $enrollment)
                        <tr>
                            <td>
                                <strong style="color: var(--dark);">{{ $enrollment->student->name }}</strong>
                            </td>
                            <td>{{ $enrollment->course->title }}</td>
                            <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                        <div style="width: {{ $enrollment->progress_percentage }}%; height: 100%; background: linear-gradient(90deg, #ed8936, #dd6b20);"></div>
                                    </div>
                                    <span style="font-weight: 600; color: var(--dark);">{{ $enrollment->progress_percentage }}%</span>
                                </div>
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