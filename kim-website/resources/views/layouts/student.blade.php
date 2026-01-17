<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Edutech')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .admin-pagination {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .page-btn {
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #edf2f7;
        color: var(--dark);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .page-btn:hover {
        background: var(--primary);
        color: white;
    }

    .page-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .page-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .page-dots {
        padding: 0 6px;
        color: var(--gray);
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
        min-height: 100vh;
    }

    /* === SIDEBAR === */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
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

    /* === MAIN CONTENT - DENGAN SPACING YANG LEBIH BAIK === */
    .main-content {
        margin-left: 280px;
        /* Disesuaikan dengan lebar sidebar */
        padding: 40px 0;
        /* Tambahkan padding vertikal untuk jarak */
        min-height: 100vh;
        width: calc(100% - 280px);
        /* Disesuaikan dengan lebar sidebar */
    }

    /* Container untuk membuat spacing yang konsisten */
    .container {
        max-width: 1200px;
        /* Sedikit lebih kecil untuk keterbacaan */
        margin: 0 auto;
        padding: 0 40px;
        /* Padding kiri-kanan yang cukup */
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
        background: linear-gradient(135deg, var(--primary), var(--secondary));
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

    .stat-icon.primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .stat-icon.success {
        background: linear-gradient(135deg, var(--success), #38a169);
    }

    .stat-icon.info {
        background: linear-gradient(135deg, var(--info), #3182ce);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, var(--warning), #dd6b20);
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
        background: linear-gradient(135deg, var(--primary), var(--secondary));
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
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    /* === COURSE CARDS === */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .course-card {
        background: white;
        border: 2px solid var(--light);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .course-thumbnail {
        width: 100%;
        height: 150px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .course-body {
        padding: 20px;
    }

    .course-category {
        display: inline-block;
        padding: 4px 10px;
        background: #f0f4ff;
        color: var(--primary);
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .course-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        color: var(--gray);
        font-size: 0.85rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--light);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.85rem;
        color: var(--gray);
        margin-bottom: 15px;
    }

    .btn-continue {
        width: 100%;
        background: var(--primary);
        color: white;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        display: block;
        transition: all 0.3s ease;
    }

    .btn-continue:hover {
        background: var(--secondary);
        transform: translateY(-2px);
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
    @media (max-width: 1024px) {
        .container {
            padding: 0 30px;
        }

        .courses-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .container {
            padding: 0 20px;
        }

        .courses-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-user-graduate"></i>
                Student Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.student.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                Profile Saya
            </a>
            <a href="{{ route('edutech.student.my-enrollments') }}"
                class="menu-item {{ request()->routeIs('edutech.student.my-enrollments') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                My Enrollments
            </a>
            <a href="{{ route('edutech.student.certificates') }}"
                class="menu-item {{ request()->routeIs('edutech.student.certificates') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i>
                Certificates
            </a>

            <div class="menu-divider"></div>

            <a href="{{ route('edutech.courses.index') }}" class="menu-item">
                <i class="fas fa-search"></i>
                Browse Courses
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-globe"></i>
                Home
            </a>
        </nav>
    </aside>

    @yield('content')

    @stack('scripts')
</body>

</html>