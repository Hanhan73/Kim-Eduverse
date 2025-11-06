<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KIM EduTech</title>
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
            --admin: #e53e3e;
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
            background: linear-gradient(180deg, #e53e3e, #c53030);
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
            background: linear-gradient(135deg, #e53e3e, #c53030);
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
            background: #c53030;
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

        .stat-icon.red {
            background: linear-gradient(135deg, #e53e3e, #c53030);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success), #38a169);
        }

        .stat-icon.warning {
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
            background: linear-gradient(135deg, #e53e3e, #c53030);
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
            box-shadow: 0 8px 20px rgba(229, 62, 62, 0.3);
        }

        /* === CHARTS === */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .chart-card h3 {
            font-size: 1.2rem;
            color: var(--dark);
            margin-bottom: 20px;
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

        .badge.active {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.inactive {
            background: #fed7d7;
            color: #742a2a;
        }

        .badge.admin {
            background: #fed7d7;
            color: #742a2a;
        }

        .badge.instructor {
            background: #feebc8;
            color: #7c2d12;
        }

        .badge.student {
            background: #e6f2ff;
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
            border: none;
            cursor: pointer;
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

        /* === RESPONSIVE === */
        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid,
            .charts-grid {
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
                <i class="fas fa-shield-alt"></i>
                Admin Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.admin.dashboard') }}" class="menu-item active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('edutech.admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i>
                Users Management
            </a>
            <a href="{{ route('edutech.admin.courses') }}" class="menu-item">
                <i class="fas fa-book"></i>
                Courses Management
            </a>
            <a href="{{ route('edutech.admin.enrollments') }}" class="menu-item">
                <i class="fas fa-user-graduate"></i>
                Enrollments
            </a>
            <a href="{{ route('edutech.admin.certificates') }}" class="menu-item">
                <i class="fas fa-certificate"></i>
                Certificates
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="{{ route('edutech.admin.settings') }}" class="menu-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-globe"></i>
                View Website
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>🛡️ Admin Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('edutech_user_name'), 0, 1)) }}
                </div>
                <span style="font-weight: 600; color: var(--dark);">{{ session('edutech_user_name') }}</span>
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
                <div class="stat-icon red">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
                    <p>Total Courses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_enrollments'] ?? 0 }}</h3>
                    <p>Total Enrollments</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Role Distribution -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3>📊 User Distribution by Role</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--dark); font-weight: 600;">Students</span>
                            <span style="color: var(--info); font-weight: 600;">{{ $roleStats['students'] ?? 0 }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $roleStats['students_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--info), #3182ce);"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--dark); font-weight: 600;">Instructors</span>
                            <span style="color: var(--warning); font-weight: 600;">{{ $roleStats['instructors'] ?? 0 }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $roleStats['instructors_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--warning), #dd6b20);"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--dark); font-weight: 600;">Admins</span>
                            <span style="color: var(--danger); font-weight: 600;">{{ $roleStats['admins'] ?? 0 }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $roleStats['admins_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--danger), #c53030);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <h3>📈 Course Statistics</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--dark); font-weight: 600;">Published</span>
                            <span style="color: var(--success); font-weight: 600;">{{ $courseStats['published'] ?? 0 }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $courseStats['published_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--dark); font-weight: 600;">Draft</span>
                            <span style="color: var(--gray); font-weight: 600;">{{ $courseStats['draft'] ?? 0 }}</span>
                        </div>
                        <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $courseStats['draft_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--gray), #4a5568);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="content-section">
            <div class="section-header">
                <h2>👥 Recent Users</h2>
                <a href="{{ route('edutech.admin.users') }}" class="btn-primary">View All Users</a>
            </div>

            @if(isset($recentUsers) && $recentUsers->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td><strong style="color: var(--dark);">{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge active">Active</span>
                                @else
                                    <span class="badge inactive">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('edutech.admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Recent Enrollments -->
        <div class="content-section">
            <div class="section-header">
                <h2>📝 Recent Enrollments</h2>
                <a href="{{ route('edutech.admin.enrollments') }}" class="btn-primary">View All</a>
            </div>

            @if(isset($recentEnrollments) && $recentEnrollments->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Date</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentEnrollments as $enrollment)
                        <tr>
                            <td><strong style="color: var(--dark);">{{ $enrollment->student->name }}</strong></td>
                            <td>{{ $enrollment->course->title }}</td>
                            <td>{{ $enrollment->course->instructor->name }}</td>
                            <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                        <div style="width: {{ $enrollment->progress_percentage }}%; height: 100%; background: linear-gradient(90deg, #e53e3e, #c53030);"></div>
                                    </div>
                                    <span style="font-weight: 600; color: var(--dark); min-width: 45px;">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </main>
</body>
</html>