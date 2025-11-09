<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - KIM Edutech</title>
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
            --danger: #e53e3e;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --gray: #718096;
            --light: #f7fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
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

        /* Main Content */
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #e53e3e, #c53030);
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #48bb78, #38a169);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #4299e1, #3182ce);
        }

        .stat-icon.gray {
            background: linear-gradient(135deg, #718096, #4a5568);
        }

        .stat-content h3 {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-content p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            font-size: 1.3rem;
            color: var(--dark);
        }

        .card-body {
            padding: 25px 30px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            color: var(--gray);
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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

        .badge.success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge.warning {
            background: #feebc8;
            color: #7c2d12;
        }

        .badge.danger {
            background: #fed7d7;
            color: #742a2a;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
        }

        /* Responsive */
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

        @yield('styles')
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>
                    <i class="fas fa-shield-alt"></i>
                    Admin Panel
                </h2>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('edutech.admin.dashboard') }}" class="menu-item {{ request()->routeIs('edutech.admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="{{ route('edutech.admin.users') }}" class="menu-item {{ request()->routeIs('edutech.admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Users Management
                </a>
                <a href="{{ route('edutech.admin.courses') }}" class="menu-item {{ request()->routeIs('edutech.admin.courses*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    Courses Management
                </a>
                <a href="{{ route('edutech.admin.enrollments') }}" class="menu-item {{ request()->routeIs('edutech.admin.enrollments*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>
                    Enrollments
                </a>
                <a href="{{ route('edutech.admin.certificates') }}" class="menu-item {{ request()->routeIs('edutech.admin.certificates*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>
                    Certificates
                </a>
                
                <div class="menu-divider"></div>
                
                <a href="{{ route('edutech.admin.settings') }}" class="menu-item {{ request()->routeIs('edutech.admin.settings*') ? 'active' : '' }}">
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
                <h1>@yield('page-title', 'Admin Dashboard')</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(session('edutech_user_name', 'A'), 0, 1)) }}
                    </div>
                    <span style="font-weight: 600; color: var(--dark);">{{ session('edutech_user_name', 'Admin') }}</span>
                    <form action="{{ route('edutech.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>