<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bendahara Digital')</title>
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

    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #1a202c 0%, #2d3748 100%);
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
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .sidebar-header .role-badge {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 8px;
        display: inline-block;
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
        background: rgba(237, 137, 54, 0.15);
        border-left-color: #ed8936;
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

    .main-content {
        margin-left: 260px;
        padding: 30px 40px;
        min-height: 100vh;
        width: calc(100% - 260px);
    }

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
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
    }

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

    .stat-icon.warning {
        background: linear-gradient(135deg, #ecc94b, #d69e2e);
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #fc8181, #f56565);
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

    .stat-content small {
        display: block;
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 3px;
    }

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

    .badge.info {
        background: #bee3f8;
        color: #2c5282;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #48bb78;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #f56565;
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
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    @stack('styles')
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-coins"></i>
                Bendahara Digital
            </h2>
            <span class="role-badge">FINANCE OFFICER</span>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('digital.bendahara.dashboard') }}"
                class="menu-item {{ request()->routeIs('digital.bendahara.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>

            <a href="{{ route('digital.bendahara.withdrawals.index') }}"
                class="menu-item {{ request()->routeIs('digital.bendahara.withdrawals.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-usd"></i>
                Penarikan Dana
            </a>

            <a href="{{ route('digital.bendahara.revenues.index') }}"
                class="menu-item {{ request()->routeIs('digital.bendahara.revenues.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                Revenue Report
            </a>

            <a href="{{ route('digital.bendahara.collaborators.index') }}"
                class="menu-item {{ request()->routeIs('digital.bendahara.collaborators.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Collaborator List
            </a>

            <div class="menu-divider"></div>

            <a href="#" class="menu-item">
                <i class="fas fa-user-circle"></i>
                Profile
            </a>

            <form action="{{ route('digital.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="menu-item"
                    style="width: 100%; background: none; border: none; text-align: left; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>