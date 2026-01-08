<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Instructor Portal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary-color: #17a2b8;
            --primary-dark: #117a8b;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand i {
            font-size: 24px;
        }

        .menu-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            padding: 20px 20px 10px 20px;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
            gap: 12px;
            font-size: 14px;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-left: 3px solid #FFD700;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e0e0e0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-card.primary {
            border-left: 4px solid var(--primary-color);
        }

        .stat-card.success {
            border-left: 4px solid #28a745;
        }

        .stat-card.warning {
            border-left: 4px solid #ffc107;
        }

        .stat-card.info {
            border-left: 4px solid #17a2b8;
        }

        .stat-card.danger {
            border-left: 4px solid #dc3545;
        }

        /* Table */
        .table {
            background: white;
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 10px 0 0 0;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('instructor.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Instructor Portal</span>
            </a>
        </div>

        <div class="menu-title">Menu Utama</div>
        <a href="{{ route('instructor.dashboard') }}"
            class="menu-item {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('instructor.earnings') }}"
            class="menu-item {{ request()->routeIs('instructor.earnings') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i><span>My Earnings</span>
        </a>
        <a href="{{ route('instructor.withdrawals') }}"
            class="menu-item {{ request()->routeIs('instructor.withdrawals') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i><span>Withdrawals</span>
        </a>
        <a href="{{ route('instructor.bank-accounts') }}"
            class="menu-item {{ request()->routeIs('instructor.bank-accounts') ? 'active' : '' }}">
            <i class="fas fa-university"></i><span>Bank Accounts</span>
        </a>
        <a href="{{ route('instructor.courses') }}"
            class="menu-item {{ request()->routeIs('instructor.courses') ? 'active' : '' }}">
            <i class="fas fa-book"></i><span>My Courses</span>
        </a>
        <a href="{{ route('edutech.instructor.dashboard') }}"
            class="menu-item {{ request()->routeIs('edutech.instructor.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            Dashboard
        </a>
        <a href="{{ route('edutech.profile.index') }}"
            class="menu-item {{request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            Profile Saya
        </a>
        <a href="{{ route('edutech.instructor.courses') }}"
            class="menu-item {{ request()->routeIs('edutech.instructor.courses') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            My Courses
        </a>
        <a href="{{ route('edutech.instructor.live-meetings.index') }}"
            class="menu-item {{ request()->routeIs('edutech.instructor.live-meetings.index') ? 'active' : '' }}">
            <i class="fas fa-video"></i>
            Live Meeting
        </a>

        <a href="{{ route('edutech.instructor.students') }}"
            class="menu-item {{ request()->routeIs('edutech.instructor.students') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            Students
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">@yield('page-title')</h1>
                    @yield('breadcrumb')
                </div>
                <div class="dropdown">
                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">{{ auth()->user()->name }}</div>
                            <div style="font-size: 12px; color: #6c757d;">Instructor</div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 12px; color: #6c757d;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}"><i
                                    class="fas fa-user me-2"></i>Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // DataTables initialization - Manual per page
            // Tidak auto-init di sini, biar di masing-masing page
        });
    </script>

    @stack('scripts')
</body>

</html>