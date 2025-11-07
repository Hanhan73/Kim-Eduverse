<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - Instructor</title>
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

        /* Sidebar */
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

        .top-bar {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .top-bar h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }

        /* Stats Grid */
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

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success), #38a169);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, var(--info), #3182ce);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, var(--warning), #dd6b20);
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

        /* Table */
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

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

        .badge.active {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.completed {
            background: #bee3f8;
            color: #2c5282;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #38a169);
            transition: width 0.3s ease;
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

        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
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
            <a href="{{ route('edutech.instructor.students') }}" class="menu-item active">
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
        <div class="top-bar">
            <h1>👥 My Students</h1>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
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
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['active_students'] ?? 0 }}</h3>
                    <p>Active Students</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['completed'] ?? 0 }}</h3>
                    <p>Completed</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['avg_progress'] ?? 0, 0) }}%</h3>
                    <p>Avg Progress</p>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="content-section">
            @if(isset($students) && $students->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Enrolled Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $enrollment)
                        <tr>
                            <td>
                                <strong style="color: var(--dark);">{{ $enrollment->student->name ?? 'Unknown' }}</strong><br>
                                <small style="color: var(--gray);">{{ $enrollment->student->email ?? '' }}</small>
                            </td>
                            <td>
                                <strong style="color: var(--dark);">{{ $enrollment->course->title ?? 'N/A' }}</strong><br>
                                <small style="color: var(--gray);">{{ $enrollment->course->category ?? '' }}</small>
                            </td>
                            <td style="min-width: 150px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1;">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--dark); min-width: 40px;">
                                        {{ $enrollment->progress_percentage ?? 0 }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($enrollment->status === 'completed')
                                    <span class="badge completed">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @elseif($enrollment->status === 'active')
                                    <span class="badge active">
                                        <i class="fas fa-play-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge">{{ ucfirst($enrollment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d M Y') : 'N/A' }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $students->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>Belum Ada Student</h3>
                <p>Student akan muncul di sini setelah mendaftar ke course Anda</p>
            </div>
            @endif
        </div>
    </main>
</body>
</html>