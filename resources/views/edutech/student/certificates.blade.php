<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates - KIM Edutech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #4F46E5;
        --secondary: #7C3AED;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --dark: #1F2937;
        --gray: #6B7280;
        --light: #F3F4F6;
        --white: #FFFFFF;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: #1e293b;
        padding: 20px;
        color: white;
        z-index: 1000;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        margin-bottom: 30px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header i {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .sidebar-header h2 {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .nav-menu {
        list-style: none;
    }

    .nav-item {
        margin-bottom: 8px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .nav-link:hover,
    .nav-link.active {
        background: rgba(79, 70, 229, 0.2);
        color: white;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        padding: 30px;
        min-height: 100vh;
    }

    /* Header */
    .page-header {
        background: white;
        padding: 25px 30px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title i {
        font-size: 2rem;
        color: var(--primary);
    }

    .page-title h1 {
        font-size: 1.8rem;
        color: var(--dark);
    }

    .user-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .btn-logout {
        background: var(--danger);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-logout:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 15px;
    }

    .stat-icon.earned {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .stat-icon.verified {
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* Certificates Grid */
    .certificates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .certificate-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .certificate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .certificate-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 20px;
        color: white;
    }

    .certificate-badge {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .certificate-badge i {
        font-size: 1.8rem;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(16, 185, 129, 0.2);
        color: var(--success);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .certificate-number {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-top: 10px;
    }

    .certificate-body {
        padding: 20px;
    }

    .certificate-course {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 15px;
    }

    .certificate-meta {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
        padding: 15px;
        background: var(--light);
        border-radius: 8px;
    }

    .certificate-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        color: var(--gray);
    }

    .certificate-info i {
        color: var(--primary);
        width: 20px;
    }

    .certificate-actions {
        display: flex;
        gap: 10px;
    }

    .btn-download {
        flex: 1;
        background: var(--success);
        color: white;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        text-align: center;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-download:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .btn-share {
        background: var(--primary);
        color: white;
        padding: 12px 15px;
        border-radius: 8px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .btn-share:hover {
        background: #4338ca;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 15px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        color: var(--gray);
        opacity: 0.3;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 25px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #4338ca;
        transform: translateY(-2px);
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap"></i>
            <h2>Student Panel</h2>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('edutech.student.dashboard') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('edutech.profile.index') }}" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profile Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('edutech.student.my-courses') }}" class="nav-link">
                    <i class="fas fa-book"></i>
                    <span>My Courses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('edutech.student.certificates') }}" class="nav-link active">
                    <i class="fas fa-certificate"></i>
                    <span>Certificates</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-search"></i>
                    <span>Browse Courses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('edutech.landing') }}" class="nav-link">
                    <i class="fas fa-globe"></i>
                    <span>Home</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-certificate"></i>
                <h1>My Certificates</h1>
            </div>
            <div class="user-section">
                <div class="user-avatar">S</div>

                <a href="#" class="btn-logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('edutech.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        @if($certificates->count() > 0)
        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon earned">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-number">{{ $certificates->count() }}</div>
                <div class="stat-label">Certificates Earned</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon verified">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $certificates->count() }}</div>
                <div class="stat-label">Verified</div>
            </div>
        </div>

        <!-- Certificates Grid -->
        <div class="certificates-grid">
            @foreach($certificates as $certificate)
            <div class="certificate-card">
                <div class="certificate-header">
                    <div class="certificate-badge">
                        <i class="fas fa-award"></i>
                    </div>
                    <div style="text-align: center;">
                        <div class="verified-badge">
                            <i class="fas fa-check-circle"></i> Verified
                        </div>
                        <div class="certificate-number">
                            Certificate No: {{ $certificate->certificate_number }}
                        </div>
                    </div>
                </div>

                <div class="certificate-body">
                    <h3 class="certificate-course">{{ $certificate->course->title }}</h3>

                    <div class="certificate-meta">
                        <div class="certificate-info">
                            <i class="fas fa-calendar-check"></i>
                            <span>Issued: {{ $certificate->certificate_issued_at->format('d F Y') }}</span>
                        </div>
                        <div class="certificate-info">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <span>Instructor: {{ $certificate->course->instructor->name }}</span>
                        </div>
                        <div class="certificate-info">
                            <i class="fas fa-tag"></i>
                            <span>Category: {{ $certificate->course->category }}</span>
                        </div>
                    </div>

                    <div class="certificate-actions">
                        <a href="{{ route('edutech.student.certificate.download', $certificate->id) }}"
                            class="btn-download">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                        <a href="#" class="btn-share" title="Share Certificate">
                            <i class="fas fa-share-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-certificate"></i>
            <h3>Belum Ada Sertifikat</h3>
            <p>Selesaikan course dan dapatkan sertifikat profesional yang diakui industri!</p>
            <a href="{{ route('edutech.student.my-enrollments') }}" class="btn-primary">
                <i class="fas fa-book"></i> Lihat My Courses
            </a>
        </div>
        @endif
    </main>
</body>

</html>