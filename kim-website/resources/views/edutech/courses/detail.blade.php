<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - KIM EduTech</title>
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
            --info: #4299e1;
            --dark: #2d3748;
            --light: #f7fafc;
            --gray: #718096;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: var(--light);
        }

        /* === NAVBAR === */
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

        /* === COURSE HEADER === */
        .course-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 60px 20px;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .course-header-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .course-info h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .course-meta {
            display: flex;
            gap: 25px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .instructor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
        }

        .instructor-details h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .instructor-details p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        /* === ENROLLMENT CARD === */
        .enrollment-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 90px;
        }

        .course-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .course-price {
            text-align: center;
            margin-bottom: 20px;
        }

        .price-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .price-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .price-amount.free {
            color: var(--success);
        }

        .btn-enroll {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-enroll:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-enrolled {
            background: var(--success);
        }

        .btn-continue {
            background: var(--info);
        }

        .course-includes {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid var(--light);
        }

        .course-includes h4 {
            font-size: 1rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .includes-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .include-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .include-item i {
            color: var(--success);
            font-size: 1rem;
        }

        /* === MAIN CONTENT === */
        .main-content {
            padding: 60px 20px;
        }

        .content-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .content-section {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .description {
            color: var(--white);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* === MODULES/CURRICULUM === */
        .module-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .module-item {
            border: 2px solid var(--light);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .module-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .module-header {
            padding: 20px;
            background: var(--light);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .module-header h4 {
            font-size: 1.1rem;
            color: var(--dark);
        }

        .module-meta {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .lesson-list {
            padding: 15px;
        }

        .lesson-item {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--gray);
            font-size: 0.95rem;
            border-bottom: 1px solid var(--light);
        }

        .lesson-item:last-child {
            border-bottom: none;
        }

        .lesson-item i {
            color: var(--primary);
        }

        /* === STATS === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-box {
            background: var(--light);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-box i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-box h4 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-box p {
            font-size: 0.85rem;
            color: var(--gray);
        }

        /* === RELATED COURSES === */
        .related-courses {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .related-card {
            background: white;
            border: 2px solid var(--light);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .related-thumbnail {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .related-body {
            padding: 20px;
        }

        .related-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .related-instructor {
            font-size: 0.85rem;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .related-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* === ALERT === */
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
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
        }

        /* === RESPONSIVE === */
        @media (max-width: 968px) {
            .navbar-edutech .nav-links {
                display: none;
            }

            .course-header-content,
            .content-layout {
                grid-template-columns: 1fr;
            }

            .enrollment-card {
                position: relative;
                top: 0;
            }

            .course-info h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <!-- Course Header -->
    <section class="course-header">
        <div class="container">
            <div class="course-header-content">
                <div class="course-info">
                    <h1>{{ $course->title }}</h1>
                    
                    <div class="course-meta">
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>{{ $course->category }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-signal"></i>
                            <span>{{ ucfirst($course->level) }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $course->enrollments_count }} siswa</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $course->duration_hours }} jam</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span>4.8 (120 reviews)</span>
                        </div>
                    </div>

                    <p class="description" style="opacity: 0.95; font-size: 1.1rem;">
                        {{ $course->description }}
                    </p>

                    <div class="instructor-info">
                        <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}" 
                             alt="{{ $course->instructor->name }}" 
                             class="instructor-avatar">
                        <div class="instructor-details">
                            <h3>{{ $course->instructor->name }}</h3>
                            <p>{{ $course->instructor->bio ?? 'Professional Instructor' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Enrollment Card (Desktop) -->
                <div class="enrollment-card">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumbnail">
                    @else
                        <div class="course-thumbnail"></div>
                    @endif

                    <div class="course-price">
                        <p class="price-label">Harga Course</p>
                        @if($course->price > 0)
                            <h2 class="price-amount">Rp {{ number_format($course->price, 0, ',', '.') }}</h2>
                        @else
                            <h2 class="price-amount free">GRATIS</h2>
                        @endif
                    </div>

                    @if(isset($isInstructor) && $isInstructor)
                        <a href="{{ route('edutech.courses.learn', $course->slug) }}" class="btn-enroll">
                            <i class="fas fa-eye"></i> Preview Course (Instructor Mode)
                        </a>
                    @elseif($isEnrolled)
                        <a href="{{ route('edutech.courses.learn', $course->slug) }}" class="btn-enroll">
                            <i class="fas fa-play"></i> Continue Learning
                        </a>
                    @else
                        <form action="{{ route('edutech.courses.enroll', $course->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-enroll">
                                {{ $course->price > 0 ? 'Enroll - Rp ' . number_format($course->price, 0, ',', '.') : 'Enroll Free' }}
                            </button>
                        </form>
                    @endif

                    <div class="course-includes">
                        <h4>Course ini termasuk:</h4>
                        <div class="includes-list">
                            <div class="include-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $course->duration_hours }} jam video pembelajaran</span>
                            </div>
                            <div class="include-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $course->modules->count() }} modul terstruktur</span>
                            </div>
                            <div class="include-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Akses selamanya</span>
                            </div>
                            <div class="include-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Sertifikat digital</span>
                            </div>
                            <div class="include-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Quiz & Evaluasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="main-content">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
            </div>
            @endif

            <div class="content-layout">
                <div>
                    <!-- Description -->
                    <div class="content-section">
                        <h2 class="section-title">Tentang Course Ini</h2>
                        <div class="description">
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>

                    <!-- Curriculum -->
                    <div class="content-section">
                        <h2 class="section-title">📚 Kurikulum Course</h2>
                        
                        @if($course->modules->count() > 0)
                        <div class="module-list">
                            @foreach($course->modules as $index => $module)
                            <div class="module-item">
                                <div class="module-header">
                                    <div>
                                        <h4>Modul {{ $index + 1 }}: {{ $module->title }}</h4>
                                    </div>
                                    <div class="module-meta">
                                        <span><i class="fas fa-play-circle"></i> {{ $module->lessons->count() }} lessons</span>
                                        <span><i class="fas fa-clock"></i> {{ $module->duration_minutes ?? 60 }}m</span>
                                    </div>
                                </div>
                                
                                @if($module->lessons->count() > 0)
                                <div class="lesson-list">
                                    @foreach($module->lessons as $lesson)
                                    <div class="lesson-item">
                                        <i class="fas fa-play-circle"></i>
                                        <span>{{ $lesson->title }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p style="color: var(--gray); text-align: center; padding: 40px 20px;">
                            <i class="fas fa-info-circle"></i> Kurikulum course sedang dalam persiapan
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- Stats -->
                    <div class="content-section">
                        <h3 class="section-title" style="font-size: 1.3rem;">📊 Statistik</h3>
                        <div class="stats-grid">
                            <div class="stat-box">
                                <i class="fas fa-users"></i>
                                <h4>{{ $course->enrollments_count }}</h4>
                                <p>Students</p>
                            </div>
                            <div class="stat-box">
                                <i class="fas fa-book"></i>
                                <h4>{{ $course->modules->count() }}</h4>
                                <p>Modules</p>
                            </div>
                            <div class="stat-box">
                                <i class="fas fa-clock"></i>
                                <h4>{{ $course->duration_hours }}h</h4>
                                <p>Duration</p>
                            </div>
                            <div class="stat-box">
                                <i class="fas fa-certificate"></i>
                                <h4>{{ $course->passing_score }}%</h4>
                                <p>Passing Score</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Courses -->
                    @if($relatedCourses->count() > 0)
                    <div class="content-section">
                        <h3 class="section-title" style="font-size: 1.3rem;">🔥 Course Terkait</h3>
                        <div class="related-courses">
                            @foreach($relatedCourses as $related)
                            <a href="{{ route('edutech.courses.detail', $related->slug) }}" class="related-card">
                                @if($related->thumbnail)
                                    <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="related-thumbnail">
                                @else
                                    <div class="related-thumbnail"></div>
                                @endif
                                
                                <div class="related-body">
                                    <h4 class="related-title">{{ $related->title }}</h4>
                                    <p class="related-instructor">
                                        <i class="fas fa-user-circle"></i> {{ $related->instructor->name }}
                                    </p>
                                    @if($related->price > 0)
                                        <p class="related-price">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="related-price" style="color: var(--success);">GRATIS</p>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</body>
</html>