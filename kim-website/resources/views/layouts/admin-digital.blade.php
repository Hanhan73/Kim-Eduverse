<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Digital') - KIM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --info: #4299e1;
        --dark: #2d3748;
        --gray: #718096;
        --light: #f7fafc;
        --sidebar-width: 260px;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: #f8fafc;
        color: var(--dark);
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 25px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
        color: white;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-header h2 i {
        color: var(--primary);
    }

    .sidebar-header p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .sidebar-menu {
        padding: 15px 0;
    }

    .menu-section {
        padding: 10px 20px 5px;
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.95rem;
        border-left: 3px solid transparent;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .menu-item.active {
        background: rgba(102, 126, 234, 0.2);
        color: white;
        border-left-color: var(--primary);
    }

    .menu-item i {
        width: 20px;
        margin-right: 12px;
        font-size: 1rem;
    }

    .menu-item .badge {
        margin-left: auto;
        background: var(--danger);
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 10px;
    }

    /* Main Content */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
    }

    /* Top Header */
    .top-header {
        background: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 15px;
        background: var(--light);
        border-radius: 10px;
    }

    .admin-avatar {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .admin-name {
        font-weight: 600;
        color: var(--dark);
    }

    /* Content Area */
    .content-area {
        padding: 30px;
    }

    /* Cards */
    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .card-body {
        padding: 25px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #38a169, #48bb78);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #dd6b20, #ed8936);
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #c53030, #f56565);
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #2b6cb0, #4299e1);
    }

    .stat-content h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-content p {
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #edf2f7;
        color: var(--dark);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-success {
        background: linear-gradient(135deg, #38a169, #48bb78);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #c53030, #f56565);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #dd6b20, #ed8936);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .btn-icon {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    /* Forms */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    select.form-control {
        cursor: pointer;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    /* Tables */
    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #edf2f7;
    }

    th {
        background: #f7fafc;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        color: var(--dark);
    }

    tr:hover {
        background: #f7fafc;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge-warning {
        background: #feebc8;
        color: #744210;
    }

    .badge-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .badge-info {
        background: #bee3f8;
        color: #2a4365;
    }

    .badge-primary {
        background: #e9d8fd;
        color: #44337a;
    }

    .badge-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    /* Alerts */
    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
        border: 1px solid #feb2b2;
    }

    .alert-warning {
        background: #feebc8;
        color: #744210;
        border: 1px solid #fbd38d;
    }

    .alert-info {
        background: #bee3f8;
        color: #2a4365;
        border: 1px solid #90cdf4;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 25px;
        padding: 0;
        list-style: none;
    }

    .pagination li a,
    .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--dark);
        background: white;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        transition: all 0.2s;
    }

    .pagination li.active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination li a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination li.disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: var(--dark);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 20px;
    }

    /* Filter Section */
    .filter-section {
        background: #f7fafc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .filter-group .form-control {
        padding: 10px 12px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        margin: 20px;
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.2rem;
        color: var(--dark);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray);
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }
    }

    @yield('styles')
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-store"></i> KIM Digital</h2>
            <p>Admin Panel</p>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-section">Menu Utama</div>

            <a href="{{ route('admin.digital.dashboard') }}"
                class="menu-item {{ request()->routeIs('admin.digital.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <div class="menu-section">Produk</div>

            <a href="{{ route('admin.digital.categories.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.categories.*') ? 'active' : '' }}">
                <i class="fas fa-folder"></i> Kategori
            </a>

            <a href="{{ route('admin.digital.products.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Produk
            </a>

            <a href="{{ route('admin.digital.landing-pages.index') }}" class="menu-item">
                <i class="fas fa-bullhorn"></i> Landing Pages
            </a>

            <div class="menu-section">Angket</div>

            <a href="{{ route('admin.digital.questionnaires.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.questionnaires.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i> Daftar Angket
            </a>

            <a href="{{ route('admin.digital.dimensions.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.dimensions.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Daftar Dimensi
            </a>

            <a href="{{ route('admin.digital.questions.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.questions.*') ? 'active' : '' }}">
                <i class="fas fa-circle-question"></i> Daftar Pertanyaan
            </a>

            <a href="{{ route('admin.digital.responses.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.responses.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Daftar Responden
            </a>


            <div class="menu-section">Transaksi</div>

            <a href="{{ route('admin.digital.orders.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>

            <div class="menu-section">Seminar On-Demand</div>

            <a href="{{ route('admin.digital.seminars.index') }}"
                class="menu-item {{ request()->routeIs('admin.digital.seminars.*') ? 'active' : '' }}">
                <i class="fas fa-white-board"></i> Seminar
            </a>

            <div class="menu-section">Navigasi</div>

            <a href="{{ route('admin.articles.index') }}" class="menu-item">
                <i class="fas fa-arrow-left"></i> Kembali ke Blog Admin
            </a>

            <form action="{{ route('admin.digital.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="menu-item"
                    style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="header-actions">
                <div class="admin-info">
                    <div class="admin-avatar">
                        {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                    </div>
                    <span class="admin-name">{{ session('admin_name', 'Admin') }}</span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 5px 0 0 20px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Confirm delete
    function confirmDelete(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus item ini?');
    }
    </script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>