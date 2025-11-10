<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
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
            background: #f8fafc;
            color: var(--dark);
        }

        /* Sidebar - DARK GRADIENT */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
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
            margin: 0;
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
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

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

        /* Main Content - FULL WIDTH & RESPONSIVE */
        .main-content {
            margin-left: 260px;
            padding: 30px 40px;
            min-height: 100vh;
        }

        /* Content wrapper untuk max-width */
        .content-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-icon.red { background: linear-gradient(135deg, #fc8181, #f56565); color: white; }
        .stat-icon.purple { background: linear-gradient(135deg, #b794f4, #9f7aea); color: white; }
        .stat-icon.success { background: linear-gradient(135deg, #68d391, #48bb78); color: white; }
        .stat-icon.warning { background: linear-gradient(135deg, #f6ad55, #ed8936); color: white; }

        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
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
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
        }

        .card-body {
            padding: 25px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px 15px;
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

        .badge.admin { background: #fed7d7; color: #742a2a; }
        .badge.instructor { background: #feebc8; color: #7c2d12; }
        .badge.student { background: #e6f2ff; color: #2c5282; }
        .badge.success { background: #c6f6d5; color: #22543d; }
        .badge.warning { background: #feebc8; color: #7c2d12; }
        .badge.danger { background: #fed7d7; color: #742a2a; }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { background: #c6f6d5; color: #22543d; }
        .alert-error { background: #fed7d7; color: #742a2a; }
        .alert-info { background: #bee3f8; color: #2c5282; }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                padding: 25px 30px;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .logout-btn {
    background-color: #ef4444;
    color: white;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
    border-radius: 0.5rem;
}

.logout-btn:hover {
    background-color: #dc2626;
    transform: scale(1.02);
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
                <a href="{{ route('edutech.profile.index') }}" class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    Profile Saya
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
                    <i class="fas fa-graduation-cap"></i>
                    Enrollments
                </a>
                <a href="{{ route('edutech.admin.certificates') }}" class="menu-item {{ request()->routeIs('edutech.admin.certificates*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>
                    Certificates
                </a>

                <div class="menu-divider"></div>

                <a href="{{ route('edutech.admin.settings') }}" class="menu-item {{ request()->routeIs('edutech.admin.settings') ? 'active' : '' }}">
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
            <div class="content-wrapper">

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>