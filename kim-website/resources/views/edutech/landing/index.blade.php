<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KIM EduTech - Platform Pembelajaran Online</title>
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
        }

        /* NAVBAR EDUTECH (BERBEDA DARI MAIN SITE!) */
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
            background: white;
            color: var(--primary);
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
        }

        .hero .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 35px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: var(--primary);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        /* STATS SECTION */
        .stats {
            background: white;
            padding: 60px 20px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .stats .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-item p {
            color: var(--gray);
            font-size: 1.1rem;
        }

        /* FEATURED COURSES */
        .featured-courses {
            padding: 80px 20px;
            background: var(--light);
        }

        .featured-courses .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--gray);
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
        }

        .course-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .course-content {
            padding: 25px;
        }

        .course-category {
            display: inline-block;
            padding: 6px 14px;
            background: #e6f2ff;
            color: #4299e1;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .course-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .course-instructor {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--gray);
            font-size: 0.95rem;
        }

        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        .course-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .course-price.free {
            color: var(--success);
        }

        .course-students {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* CATEGORIES */
        .categories {
            padding: 80px 20px;
            background: white;
        }

        .categories .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .category-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }

        .category-card i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .category-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .category-card p {
            opacity: 0.9;
        }

        /* FOOTER */
        .footer {
            background: var(--dark);
            color: white;
            padding: 60px 20px 30px;
        }

        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-section a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .navbar-edutech .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .stats .container {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
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
                <li><a href="{{ route('edutech.courses.index') }}">Courses</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>🎓 Belajar Tanpa Batas</h1>
            <p>Platform pembelajaran online terbaik dengan instruktur profesional dan materi berkualitas</p>
            <div class="cta-buttons">
                <a href="{{ route('edutech.courses.index') }}" class="btn btn-primary">Jelajahi Courses</a>
                <a href="{{ route('edutech.register') }}" class="btn btn-outline">Daftar Gratis</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <i class="fas fa-book"></i>
                <h3>{{ $stats['total_courses'] ?? 0 }}+</h3>
                <p>Courses Tersedia</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <h3>{{ $stats['total_students'] ?? 0 }}+</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>{{ $stats['total_instructors'] ?? 0 }}+</h3>
                <p>Instruktur Profesional</p>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="featured-courses">
        <div class="container">
            <div class="section-title">
                <h2>✨ Courses Unggulan</h2>
                <p>Pilihan terbaik untuk memulai perjalanan belajar Anda</p>
            </div>

            <div class="courses-grid">
                @forelse($featuredCourses ?? [] as $course)
                <a href="{{ route('edutech.courses.detail', $course->slug) }}" class="course-card">
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                        class="course-thumbnail">
                    @else
                    <div class="course-thumbnail"></div>
                    @endif
                    <div class="course-content">
                        <span class="course-category">{{ $course->category }}</span>
                        <h3 class="course-title">{{ $course->title }}</h3>
                        <div class="course-instructor">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $course->instructor->name }}</span>
                        </div>
                        <div class="course-footer">
                            @if($course->price > 0)
                            <span class="course-price">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            @else
                            <span class="course-price free">GRATIS</span>
                            @endif
                            <span class="course-students">
                                <i class="fas fa-user-graduate"></i>
                                {{ $course->enrollments_count ?? 0 }} siswa
                            </span>
                        </div>
                    </div>
                </a>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-book" style="font-size: 4rem; color: #cbd5e0; margin-bottom: 20px;"></i>
                    <p style="color: #718096; font-size: 1.1rem;">Belum ada course tersedia</p>
                </div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 50px;">
                <a href="{{ route('edutech.courses.index') }}" class="btn btn-primary">Lihat Semua Courses</a>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories" id="categories">
        <div class="container">
            <div class="section-title">
                <h2>📚 Kategori Populer</h2>
                <p>Temukan course sesuai minat Anda</p>
            </div>

            <div class="categories-grid">
                <a href="{{ route('edutech.courses.index', ['category' => 'Education']) }}" class="category-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Education</h3>
                    <p>CBTS, Teknik Alba, Media Pembelajaran, AI</p>
                </a>
                <a href="{{ route('edutech.courses.index', ['category' => 'Language']) }}" class="category-card">
                    <i class="fas fa-language"></i>
                    <h3>Language</h3>
                    <p>Bahasa Inggris, Bahasa Arab</p>
                </a>
                <a href="{{ route('edutech.courses.index', ['category' => 'Teknologi Informasi']) }}"
                    class="category-card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Teknologi Informasi</h3>
                    <p>Office Computer, Coding</p>
                </a>
                <a href="{{ route('edutech.courses.index', ['category' => 'Desain']) }}" class="category-card">
                    <i class="fas fa-palette"></i>
                    <h3>Desain</h3>
                    <p>Desain Interior, DKV</p>
                </a>
                <a href="{{ route('edutech.courses.index', ['category' => 'Manajemen dan Teknik Industri']) }}"
                    class="category-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Manajemen & Teknik Industri</h3>
                    <p>ISO 9001, 7 Tools, Quality Management</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>KIM EduTech</h3>
                    <p>Platform pembelajaran online terbaik untuk meningkatkan keterampilan Anda</p>
                </div>
                <div class="footer-section">
                    <h3>Tautan Cepat</h3>
                    <ul>
                        <li><a href="{{ route('edutech.courses.index') }}">Courses</a></li>
                        <li><a href="{{ route('edutech.register') }}">Daftar</a></li>
                        <li><a href="{{ route('edutech.login') }}">Masuk</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kategori</h3>
                    <ul>
                        <li><a href="{{ route('edutech.courses.index', ['category' => 'CBTS']) }}">Education</a></li>
                        <li><a
                                href="{{ route('edutech.courses.index', ['category' => 'Bahasa Inggris']) }}">Language</a>
                        </li>
                        <li><a href="{{ route('edutech.courses.index', ['category' => 'Coding']) }}">Teknologi</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> info@kimedutech.com</li>
                        <li><i class="fas fa-phone"></i> +62 XXX XXXX XXXX</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 KIM EduTech. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>