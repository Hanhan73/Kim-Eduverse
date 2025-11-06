<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Student Dashboard</title>
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
            width: calc(100% - 260px);
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

        /* === TABS === */
        .tabs-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .tabs-nav {
            display: flex;
            border-bottom: 2px solid var(--light);
        }

        .tab-btn {
            flex: 1;
            padding: 20px;
            background: transparent;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-btn:hover {
            background: var(--light);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .tab-badge {
            display: inline-block;
            padding: 2px 8px;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            font-size: 0.75rem;
            margin-left: 8px;
        }

        .tab-content {
            display: none;
            padding: 30px;
        }

        .tab-content.active {
            display: block;
        }

        /* === COURSE GRID === */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
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
            height: 180px;
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

        .progress-section {
            margin-bottom: 15px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .progress-percentage {
            font-weight: 700;
            color: var(--primary);
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .course-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--light);
        }

        .course-stat {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: var(--gray);
        }

        .course-stat i {
            color: var(--info);
        }

        .btn-continue {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 12px;
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

        .btn-completed {
            background: var(--success);
        }

        .btn-completed:hover {
            background: #38a169;
        }

        .certificate-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* === EMPTY STATE === */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .empty-state p {
            margin-bottom: 25px;
            font-size: 1.05rem;
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

            .courses-grid {
                grid-template-columns: 1fr;
            }

            .tabs-nav {
                flex-direction: column;
            }
        }
    </style>
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
            <a href="{{ route('edutech.student.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('edutech.student.my-courses') }}" class="menu-item active">
                <i class="fas fa-book"></i>
                My Courses
            </a>
            <a href="{{ route('edutech.student.certificates') }}" class="menu-item">
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>📚 My Courses</h1>
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

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs-nav">
                <button class="tab-btn active" onclick="switchTab('active')">
                    <i class="fas fa-play-circle"></i> Active Courses
                    <span class="tab-badge">{{ $activeCourses->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('completed')">
                    <i class="fas fa-check-circle"></i> Completed Courses
                    <span class="tab-badge" style="background: var(--success);">{{ $completedCourses->count() }}</span>
                </button>
            </div>

            <!-- Active Courses Tab -->
            <div id="active-tab" class="tab-content active">
                @if($activeCourses->count() > 0)
                <div class="courses-grid">
                    @foreach($activeCourses as $enrollment)
                    <div class="course-card">
                        @if($enrollment->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="course-thumbnail">
                        @else
                            <div class="course-thumbnail"></div>
                        @endif
                        
                        <div class="course-body">
                            <span class="course-category">{{ $enrollment->course->category }}</span>
                            
                            <h3 class="course-title">{{ $enrollment->course->title }}</h3>
                            
                            <div class="course-instructor">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $enrollment->course->instructor->name }}</span>
                            </div>

                            <div class="progress-section">
                                <div class="progress-label">
                                    <span>Progress</span>
                                    <span class="progress-percentage">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                </div>
                            </div>

                            <div class="course-stats">
                                <div class="course-stat">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $enrollment->course->duration_hours }}h</span>
                                </div>
                                <div class="course-stat">
                                    <i class="fas fa-video"></i>
                                    <span>{{ $enrollment->course->modules_count ?? 0 }} Modules</span>
                                </div>
                                <div class="course-stat">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $enrollment->created_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('edutech.course.learn', $enrollment->course->slug) }}" class="btn-continue">
                                <i class="fas fa-play"></i> Continue Learning
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>Belum Ada Course Aktif</h3>
                    <p>Yuk mulai belajar dengan mendaftar ke course yang kamu minati!</p>
                    <a href="{{ route('edutech.courses.index') }}" class="btn-primary">
                        <i class="fas fa-search"></i> Jelajahi Courses
                    </a>
                </div>
                @endif
            </div>

            <!-- Completed Courses Tab -->
            <div id="completed-tab" class="tab-content">
                @if($completedCourses->count() > 0)
                <div class="courses-grid">
                    @foreach($completedCourses as $enrollment)
                    <div class="course-card">
                        @if($enrollment->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}" class="course-thumbnail">
                        @else
                            <div class="course-thumbnail"></div>
                        @endif
                        
                        <div class="course-body">
                            @if($enrollment->certificate_issued_at)
                            <span class="certificate-badge">
                                <i class="fas fa-certificate"></i>
                                Certified
                            </span>
                            @endif

                            <span class="course-category">{{ $enrollment->course->category }}</span>
                            
                            <h3 class="course-title">{{ $enrollment->course->title }}</h3>
                            
                            <div class="course-instructor">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $enrollment->course->instructor->name }}</span>
                            </div>

                            <div class="progress-section">
                                <div class="progress-label">
                                    <span>Completed</span>
                                    <span class="progress-percentage">100%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                                </div>
                            </div>

                            <div class="course-stats">
                                <div class="course-stat">
                                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                    <span>Completed {{ $enrollment->completed_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            @if($enrollment->certificate_issued_at)
                            <a href="{{ route('edutech.student.certificates') }}" class="btn-continue btn-completed">
                                <i class="fas fa-download"></i> Download Certificate
                            </a>
                            @else
                            <a href="{{ route('edutech.course.learn', $enrollment->course->slug) }}" class="btn-continue btn-completed">
                                <i class="fas fa-eye"></i> Review Course
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Belum Ada Course yang Selesai</h3>
                    <p>Selesaikan course yang kamu ikuti untuk mendapatkan sertifikat!</p>
                    <a href="{{ route('edutech.student.my-courses') }}" class="btn-primary">
                        <i class="fas fa-book"></i> Lihat Active Courses
                    </a>
                </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        function switchTab(tab) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active class to clicked tab
            if (tab === 'active') {
                document.querySelector('.tab-btn:first-child').classList.add('active');
                document.getElementById('active-tab').classList.add('active');
            } else {
                document.querySelector('.tab-btn:last-child').classList.add('active');
                document.getElementById('completed-tab').classList.add('active');
            }
        }
    </script>
</body>
</html>