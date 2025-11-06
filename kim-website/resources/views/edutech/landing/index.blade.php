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
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
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

        @media (max-width: 768px) {
            .navbar-edutech .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
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
                <li><a href="{{ route('edutech.courses') }}">Courses</a></li>
                <li><a href="#categories">Kategori</a></li>
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
                <a href="{{ route('edutech.courses') }}" class="btn btn-primary">Jelajahi Courses</a>
                <a href="{{ route('edutech.register') }}" class="btn btn-primary">Daftar Gratis</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <i class="fas fa-book"></i>
                <h3>{{ $stats['total_courses'] }}+</h3>
                <p>Courses Tersedia</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <h3>{{ $stats['total_students'] }}+</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>{{ $stats['total_instructors'] }}+</h3>
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
                @forelse($featuredCourses as $course)
                <a href="{{ route('edutech.course.detail', $course->slug) }}" class="course-card">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumbnail">
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
                                {{ $course->enrollments_count }} siswa
                            </span>
                        </div>
                    </div>
                </a>
                @empty
                <p>Belum ada course tersedia</p>
                @endforelse
            </div>
        </div>
    </section>
</body>
</html>