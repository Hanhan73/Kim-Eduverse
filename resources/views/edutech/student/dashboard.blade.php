@extends('layouts.student')

@section('title', 'Dashboard Student')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>👋 Selamat Datang, {{ session('edutech_user_name') }}!</h1>
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

    @if(session('success'))
    <div style="background: #c6f6d5; color: #22543d; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active_courses'] ?? 0 }}</h3>
                <p>Active Courses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['completed_courses'] ?? 0 }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['certificates'] ?? 0 }}</h3>
                <p>Certificates</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($stats['avg_progress'] ?? 0, 0) }}%</h3>
                <p>Avg Progress</p>
            </div>
        </div>
    </div>

    <!-- Active Courses -->
    <div class="content-section">
        <div class="section-header">
            <h2>📚 Continue Learning</h2>
            <a href="{{ route('edutech.student.my-courses') }}" class="btn-primary">View All Courses</a>
        </div>

        @if(isset($activeCourses) && $activeCourses->count() > 0)
        <div class="courses-grid">
            @foreach($activeCourses as $enrollment)
            <div class="course-card">
                @if($enrollment->course->thumbnail)
                <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}"
                    alt="{{ $enrollment->course->title }}" class="course-thumbnail">
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

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $enrollment->progress_percentage }}%"></div>
                    </div>
                    <p class="progress-text">Progress: {{ $enrollment->progress_percentage }}%</p>

                    <a href="{{ route('edutech.courses.learn', $enrollment->course->slug) }}" class="btn-continue">
                        Continue Learning
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-book"></i>
            <h3>Belum Ada Course Aktif</h3>
            <p>Mulai perjalanan belajar Anda dengan mendaftar ke course yang tersedia</p>
            <a href="{{ route('edutech.courses.index') }}" class="btn-primary">
                <i class="fas fa-search"></i> Jelajahi Courses
            </a>
        </div>
        @endif
    </div>

    <!-- Recommended Courses -->
    @if(isset($recommendedCourses) && $recommendedCourses->count() > 0)
    <div class="content-section">
        <div class="section-header">
            <h2>✨ Recommended for You</h2>
            <a href="{{ route('edutech.courses.index') }}" class="btn-primary">Browse All</a>
        </div>

        <div class="courses-grid">
            @foreach($recommendedCourses as $course)
            <div class="course-card">
                @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                    class="course-thumbnail">
                @else
                <div class="course-thumbnail"></div>
                @endif

                <div class="course-body">
                    <span class="course-category">{{ $course->category }}</span>
                    <h3 class="course-title">{{ $course->title }}</h3>
                    <p style="font-size: 0.85rem; color: var(--gray); margin-bottom: 15px;">
                        {{ Str::limit($course->description, 80) }}
                    </p>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        @if($course->price > 0)
                        <span style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">
                            Rp {{ number_format($course->price, 0, ',', '.') }}
                        </span>
                        @else
                        <span style="font-size: 1.2rem; font-weight: 700; color: var(--success);">
                            GRATIS
                        </span>
                        @endif
                    </div>

                    <a href="{{ route('edutech.courses.detail', $course->slug) }}" class="btn-continue">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</main>
@endsection