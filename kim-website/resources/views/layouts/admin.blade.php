<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - PT KIM Eduverse</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
    :root {
        --primary-purple: #6F42C1;
        --primary-gold: #FFD700;
        --dark-purple: #563d7c;
        --sidebar-width: 260px;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--primary-purple), var(--dark-purple));
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .sidebar-brand {
        padding: 25px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-brand h4 {
        color: white;
        margin: 0;
        font-weight: 700;
        font-size: 20px;
    }

    .sidebar-brand p {
        color: rgba(255, 255, 255, 0.7);
        margin: 5px 0 0;
        font-size: 12px;
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .menu-title {
        color: rgba(255, 255, 255, 0.5);
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 10px 20px;
        letter-spacing: 1px;
    }

    .menu-item {
        padding: 12px 20px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .menu-item i {
        width: 30px;
        font-size: 16px;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .menu-item.active {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border-left-color: var(--primary-gold);
    }

    /* Main Content */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
    }

    /* Top Navbar */
    .top-navbar {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 15px 30px;
        position: sticky;
        top: 0;
        z-index: 999;
    }

    .page-header {
        padding: 30px;
        background: white;
        margin-bottom: 30px;
        border-bottom: 1px solid #e0e0e0;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .page-header .breadcrumb {
        margin: 10px 0 0;
        background: none;
        padding: 0;
    }

    /* Content Area */
    .content-area {
        padding: 30px;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 20px;
        font-weight: 600;
        color: #333;
    }

    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary {
        border-left-color: var(--primary-purple);
    }

    .stat-card.success {
        border-left-color: #28a745;
    }

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
    }

    .stat-card.danger {
        border-left-color: #dc3545;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    .btn-primary:hover {
        background: var(--dark-purple);
        border-color: var(--dark-purple);
    }

    /* Tables */
    .table {
        background: white;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid var(--primary-purple);
        color: #333;
        font-weight: 600;
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }

    /* User Dropdown */
    .user-dropdown {
        cursor: pointer;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-purple);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap fa-2x text-white mb-2"></i>
            <h4>KIM Eduverse</h4>
            <p>{{ auth()->user()->role->display_name ?? 'Admin' }}</p>
        </div>

        <!-- In your main layout file, e.g., resources/views/layouts/admin.blade.php -->

        <div class="sidebar-menu">
            <!-- Dashboard (Visible to all logged-in admins) -->
            <a href="{{ route('admin.super-admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.super-admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            {{-- SUPER ADMIN TOOLS --}}
            @if(auth()->user()->hasRole('super_admin'))
            <div class="menu-section">
                <div class="menu-title">Super Admin Tools</div>
                <a href="{{ route('admin.super-admin.users') }}"
                    class="menu-item {{ request()->routeIs('admin.super-admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.super-admin.instructors') }}" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i><span>Instructors Management</span>
                </a>
                <a href="{{ route('admin.super-admin.settings') }}"
                    class="menu-item {{ request()->routeIs('admin.super-admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span>Global Settings</span>
                </a>
            </div>
            @endif

            {{-- EDUTECH SYSTEM --}}
            {{-- CORRECTED: Using hasAnyRole for an array --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'edutech_admin']))
            <div class="menu-section">
                <div class="menu-title">Edutech System</div>
                <a href="{{ route('edutech.admin.dashboard') }}"
                    class="menu-item {{ request()->routeIs('edutech.admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Edutech Dashboard</span>
                </a>
                <a href="{{ route('edutech.admin.users') }}"
                    class="menu-item {{ request()->routeIs('edutech.admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('edutech.admin.courses') }}"
                    class="menu-item {{ request()->routeIs('edutech.admin.courses*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
                <a href="{{ route('edutech.admin.enrollments') }}"
                    class="menu-item {{ request()->routeIs('edutech.admin.enrollments*') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i>
                    <span>Enrollments</span>
                </a>
                <a href="{{ route('edutech.admin.certificates') }}"
                    class="menu-item {{ request()->routeIs('edutech.admin.certificates*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>
                    <span>Certificates</span>
                </a>
            </div>
            @endif

            {{-- KIM DIGITAL SYSTEM --}}
            {{-- CORRECTED: Using hasAnyRole for an array --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'digital_admin']))
            <div class="menu-section">
                <div class="menu-title">Kim Digital System</div>
                <a href="{{ route('admin.digital.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.digital.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Digital Dashboard</span>
                </a>
                <a href="{{ route('admin.digital.products.index') }}"
                    class="menu-item {{ request()->routeIs('admin.digital.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.digital.categories.index') }}"
                    class="menu-item {{ request()->routeIs('admin.digital.categories*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.digital.orders.index') }}"
                    class="menu-item {{ request()->routeIs('admin.digital.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ route('admin.digital.questionnaires.index') }}"
                    class="menu-item {{ request()->routeIs('admin.digital.questionnaires*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Questionnaires</span>
                </a>
            </div>
            @endif

            {{-- BLOG SYSTEM --}}
            {{-- CORRECTED: Using hasAnyRole for an array --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'admin_blog']))
            <div class="menu-section">
                <div class="menu-title">Blog System</div>
                <a href="{{ route('admin.articles.index') }}"
                    class="menu-item {{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Manage Articles</span>
                </a>
                <a href="{{ route('blog.index') }}" class="menu-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Blog</span>
                </a>
            </div>
            @endif

            {{-- FINANCE (BENDAHARA) --}}
            {{-- CORRECTED: Using hasAnyRole for an array --}}
            @if(auth()->user()->hasAnyRole(['bendahara']))
            <div class="menu-section">
                <div class="menu-title">Finance</div>
                <a href="{{ route('admin.bendahara.dashboard') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.bendahara.revenue') }}" class="menu-item active">
                    <i class="fas fa-chart-line"></i><span>Revenue</span>
                </a>
                <a href="{{ route('admin.bendahara.instructor-earnings') }}" class="menu-item">
                    <i class="fas fa-users"></i><span>Instructor Earnings</span>
                </a>
                <a href="{{ route('admin.bendahara.withdrawals') }}" class="menu-item">
                    <i class="fas fa-money-bill-wave"></i><span>Withdrawals</span>
                </a>
                <a href="{{ route('admin.bendahara.reports') }}" class="menu-item">
                    <i class="fas fa-file-alt"></i><span>Reports</span>
                </a>
            </div>
            @elseif(auth()->user()->hasAnyRole(['super_admin']))
            <div class="menu-section">
                <div class="menu-title">Finance</div>
                <a href="{{ route('admin.super-admin.revenue') }}"
                    class="menu-item {{ request()->routeIs('admin.super-admin.revenue') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Revenue</span>
                </a>
                <a href="{{ route('admin.super-admin.withdrawals') }}"
                    class="menu-item {{ request()->routeIs('admin.super-admin.withdrawals*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Withdrawals</span>
                </a>
            </div>
            @endif

            {{-- INSTRUCTOR --}}
            {{-- CORRECTED: Using hasAnyRole for an array --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'instructor']))
            <div class="menu-section">
                <div class="menu-title">Instructor Panel</div>
                <a href="{{ route('instructor.dashboard') }}"
                    class="menu-item {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>My Dashboard</span>
                </a>
                <a href="{{ route('instructor.courses') }}"
                    class="menu-item {{ request()->routeIs('instructor.courses') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>My Courses</span>
                </a>
                <a href="{{ route('instructor.earnings') }}"
                    class="menu-item {{ request()->routeIs('instructor.earnings') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i>
                    <span>My Earnings</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted">
                        <i class="far fa-calendar-alt me-2"></i>
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>

                <div class="dropdown user-dropdown">
                    <div class="d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="me-2">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
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
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <h1>@yield('page-title')</h1>
            @yield('breadcrumb')
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
    // Initialize DataTables
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    });
    </script>

    @stack('scripts')
</body>

</html>