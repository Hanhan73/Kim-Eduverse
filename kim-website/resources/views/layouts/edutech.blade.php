<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kursus - KIM Edutech</title>
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
        --dark: #2d3748;
        --light: #f7fafc;
        --gray: #718096;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--dark);
        background: #f8f9fa;
    }

    /* === NAVBAR EDUTECH (BERBEDA!) === */
    .navbar-edutech {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar-edutech .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .navbar-edutech .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        text-decoration: none;
    }

    .navbar-edutech .logo i {
        font-size: 2rem;
    }

    .navbar-edutech .nav-links {
        display: flex;
        align-items: center;
        gap: 30px;
        list-style: none;
    }

    .navbar-edutech .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .navbar-edutech .nav-links a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-login {
        background: linear-gradient(135deg, #ffffff33, #ffffff22);
        color: #fff;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-login:hover {
        background: #fff;
        color: #4b2bbf;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
    }

    /* Page Header */
    .page-header-courses {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 60px;
        color: white;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-content {
        text-align: center;
    }

    .header-content h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .header-content p {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 25px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.95rem;
    }

    .breadcrumb a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        opacity: 0.9;
    }

    .breadcrumb a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    /* Courses Layout */
    .courses-content {
        padding: 60px 0 100px;
        background: #f8f9fa;
    }

    .courses-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
    }

    /* Filter Sidebar */
    .filter-sidebar {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 90px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    .filter-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-reset {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .btn-reset:hover {
        text-decoration: underline;
    }

    .filter-group {
        margin-bottom: 30px;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 12px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control-filter {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control-filter:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 10px;
        border-radius: 10px;
        transition: background 0.2s ease;
    }

    .radio-label:hover {
        background: #f7fafc;
    }

    .radio-label input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .radio-label span {
        color: #4a5568;
        font-size: 0.9rem;
    }

    .btn-apply-filter {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-apply-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    /* Courses Main */
    .courses-main {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .courses-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 20px;
    }

    .toolbar-left h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .active-filters {
        font-size: 0.9rem;
        color: #718096;
    }

    .filter-tag {
        display: inline-block;
        padding: 4px 12px;
        background: #f0f4ff;
        color: #667eea;
        border-radius: 8px;
        font-weight: 600;
        margin-left: 5px;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toolbar-right label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.95rem;
    }

    .sort-select {
        padding: 10px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        cursor: pointer;
        min-width: 180px;
    }

    /* Courses Grid */
    .courses-grid-catalog {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .course-card-catalog {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .course-card-catalog:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .course-image-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .course-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .course-card-catalog:hover .course-image-wrapper img {
        transform: scale(1.1);
    }

    .level-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 5px 14px;
        background: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .level-badge.beginner {
        color: #48bb78;
    }

    .level-badge.intermediate {
        color: #ed8936;
    }

    .level-badge.advanced {
        color: #e53e3e;
    }

    .free-label {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 5px 14px;
        background: #48bb78;
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .course-card-body {
        padding: 20px;
    }

    .category-label {
        display: inline-block;
        padding: 4px 10px;
        background: #f0f4ff;
        color: #667eea;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .course-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .course-card-title a {
        color: #2d3748;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .course-card-title a:hover {
        color: #667eea;
    }

    .instructor-small {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .instructor-small img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .instructor-small span {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 500;
    }

    .course-excerpt {
        font-size: 0.9rem;
        color: #718096;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .course-meta-info {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .course-meta-info span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #718096;
    }

    .course-meta-info i {
        color: #667eea;
    }

    .course-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .price-label {
        font-size: 1.4rem;
        font-weight: 700;
        color: #667eea;
    }

    .price-label.free {
        color: #48bb78;
    }

    .btn-detail {
        padding: 8px 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 80px 20px;
    }

    .no-results i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .no-results h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
    }

    .no-results p {
        color: #718096;
        font-size: 1.05rem;
        margin-bottom: 30px;
    }

    .btn-reset-search {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
    }

    /* === PAGINATION FIXED === */


    /* Responsive */
    @media (max-width: 1024px) {
        .courses-layout {
            grid-template-columns: 1fr;
        }

        .filter-sidebar {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        .navbar-edutech .nav-links {
            display: none;
        }

        .header-content h1 {
            font-size: 2.2rem;
        }

        .courses-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .courses-grid-catalog {
            grid-template-columns: 1fr;
        }
    }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar Edutech (BERBEDA!) -->
    <nav class="navbar-edutech">
        <div class="container">
            <a href="{{ route('edutech.landing') }}" class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>KIM EduTech</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('edutech.landing') }}">Beranda</a></li>
                <li><a href="{{ route('edutech.courses.index') }}">Kursus</a></li>
                @if(session()->has('edutech_user_id'))
                @if(session('edutech_user_role') === 'admin')
                <li><a href="{{ route('edutech.admin.dashboard') }}">Dashboard</a></li>
                @elseif(session('edutech_user_role') === 'instructor')
                <li><a href="{{ route('edutech.instructor.dashboard') }}">Dashboard</a></li>
                @else
                <li><a href="{{ route('edutech.student.dashboard') }}">Dashboard</a></li>
                @endif
                @else
                <li><a href="{{ route('edutech.login') }}" class="btn-login">Masuk</a></li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @if (!View::hasSection('hideFooter'))
    <!-- Footer - Disesuaikan untuk Edutech -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="footer-logo-text">KIM Edutech</span>
                    </div>
                    <p class="footer-description">
                        Platform pembelajaran online terpercaya untuk meningkatkan skill Anda di era digital.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Kursus</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('edutech.courses.index') }}" class="footer-link">Semua Kursus</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Akun</h3>
                    <ul class="footer-links">
                        @if(session()->has('edutech_user_id'))
                        <li><a href="{{ route('edutech.' . session('edutech_user_role') . '.dashboard') }}"
                                class="footer-link">Dashboard</a></li>
                        <li><a href="{{ route('edutech.logout') }}" class="footer-link">Logout</a></li>
                        @else
                        <li><a href="{{ route('edutech.login') }}" class="footer-link">Masuk</a></li>
                        <li><a href="{{ route('edutech.register') }}" class="footer-link">Daftar</a></li>
                        @endif
                        <li><a href="{{ route('contact.index') }}" class="footer-link">Kontak Kami</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Kontak</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><i class="fas fa-envelope"></i> info@kim-edutech.com</li>
                        <li class="footer-link"><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                        <li class="footer-link"><i class="fas fa-map-marker-alt"></i> Bandung, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} KIM Edutech. All rights reserved. Powered by PT KIM.</p>
            </div>
        </div>
    </footer>
    @endif
    <!-- Scripts -->
    <script>
    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        const icon = menuToggle.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    });

    // Mobile Dropdown
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            if (window.innerWidth <= 968) {
                e.preventDefault();
                dropdown.classList.toggle('active');
            }
        });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Active link highlighting
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
    </script>

    @stack('scripts')
</body>

</html>