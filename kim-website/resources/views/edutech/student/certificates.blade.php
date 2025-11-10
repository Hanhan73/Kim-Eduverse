@extends('layouts.student')

@section('title', 'Certificates Student')

@section('content')
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
        --gold: #f59e0b;
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
        background: linear-gradient(180deg, var(--primary), var(--secondary));
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
        width: calc(100%);
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

    /* === STATS BANNER === */
    .stats-banner {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        padding: 40px;
        border-radius: 16px;
        color: white;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(251, 191, 36, 0.3);
    }

    .stats-banner i {
        font-size: 4rem;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .stats-banner h2 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .stats-banner p {
        font-size: 1.2rem;
        opacity: 0.95;
    }

    /* === CERTIFICATES GRID === */
    .certificates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    .certificate-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
    }

    .certificate-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
    }

    .certificate-header {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        padding: 30px;
        text-align: center;
        position: relative;
    }

    .certificate-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        opacity: 0.2;
    }

    .certificate-icon {
        font-size: 3.5rem;
        color: white;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .certificate-number {
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .certificate-body {
        padding: 25px;
    }

    .certificate-course {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .certificate-meta {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--light);
    }

    .certificate-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .certificate-info i {
        color: var(--gold);
        width: 20px;
    }

    .certificate-actions {
        display: flex;
        gap: 10px;
    }

    .btn-download {
        flex: 1;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-share {
        padding: 12px 20px;
        background: var(--light);
        color: var(--gray);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-share:hover {
        background: var(--info);
        color: white;
    }

    .verified-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        color: var(--success);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    /* === EMPTY STATE === */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 80px 40px;
        text-align: center;
        color: var(--gray);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 25px;
        opacity: 0.3;
        color: var(--gold);
    }

    .empty-state h3 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: var(--dark);
    }

    .empty-state p {
        margin-bottom: 30px;
        font-size: 1.1rem;
    }

    .btn-primary {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 14px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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

        .certificates-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>🏆 My Certificates</h1>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(session('edutech_user_name'), 0, 1)) }}
            </div>
            <form action="{{ route('edutech.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    @if($certificates->count() > 0)
    <!-- Stats Banner -->
    <div class="stats-banner">
        <i class="fas fa-trophy"></i>
        <h2>{{ $certificates->count() }}</h2>
        <p>Certificates Earned</p>
    </div>

    <!-- Certificates Grid -->
    <div class="certificates-grid">
        @foreach($certificates as $certificate)
        <div class="certificate-card">
            <span class="verified-badge">
                <i class="fas fa-check-circle"></i>
                Verified
            </span>

            <div class="certificate-header">
                <div class="certificate-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="certificate-number">
                    Certificate No: {{ $certificate->certificate_number }}
                </div>
            </div>

            <div class="certificate-body">
                <h3 class="certificate-course">{{ $certificate->course->title }}</h3>

                <div class="certificate-meta">
                    <div class="certificate-info">
                        <i class="fas fa-calendar-check"></i>
                        <span>Issued: {{ $certificate->issued_at->format('d F Y') }}</span>
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
        <a href="{{ route('edutech.student.my-courses') }}" class="btn-primary">
            <i class="fas fa-book"></i> Lihat My Courses
        </a>
    </div>
    @endif
</main>
@endsection