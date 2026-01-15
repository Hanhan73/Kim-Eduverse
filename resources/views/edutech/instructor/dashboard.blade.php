@extends('layouts.instructor')

@section('title', 'Instructor Dashboard - KIM EduTech')

@section('content')
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>👨‍🏫 Selamat Datang, {{ session('edutech_user_name') }}!</h1>
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

        <!-- Main Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
                    <p>Total Courses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                    <p>Total Students</p>
                    <small>{{ $stats['active_students_month'] ?? 0 }} active this month</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['certificates_issued'] ?? 0 }}</h3>
                    <p>Certificates Issued</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['avg_rating'] ?? 0, 1) }}</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>

        <!-- Content Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pink">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_modules'] ?? 0 }}</h3>
                    <p>Total Modules</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon teal">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['video_lessons'] ?? 0 }}</h3>
                    <p>Video Lessons</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pdf_lessons'] ?? 0 }}</h3>
                    <p>PDF Materials</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total_quizzes'] ?? 0 }}</h3>
                    <p>Total Quizzes</p>
                </div>
            </div>
        </div>

        <!-- Quiz Performance -->
        <div class="content-section">
            <div class="section-header">
                <h2>🎯 Quiz Performance</h2>
            </div>

            <div class="stats-grid" style="margin-bottom: 0;">
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $quizStats['total_attempts'] ?? 0 }}</h3>
                        <p>Total Attempts</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $quizStats['passed_attempts'] ?? 0 }}</h3>
                        <p>Passed Attempts</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($quizStats['pass_rate'] ?? 0, 1) }}%</h3>
                        <p>Pass Rate</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ number_format($quizStats['avg_score'] ?? 0, 1) }}</h3>
                        <p>Average Score</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses with Quiz Status -->
        <div class="content-section">
            <div class="section-header">
                <h2>📚 My Courses</h2>
                <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create New Course
                </a>
            </div>

            @if(isset($courses) && $courses->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Content</th>
                            <th>Quiz Status</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>
                                <strong style="color: var(--dark);">{{ $course->title }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ $course->category }}</small>
                            </td>
                            <td>
                                <span style="color: var(--info);">
                                    <i class="fas fa-layer-group"></i> {{ $course->modules_count }} modules
                                </span><br>
                                <span style="color: var(--gray); font-size: 0.85rem;">
                                    <i class="fas fa-play-circle"></i> {{ $course->total_lessons }} lessons
                                </span>
                            </td>
                            <td>
                                @if($course->has_pretest)
                                    <span class="badge success">✓ Pre-test</span>
                                @else
                                    <span class="badge warning">⚠ No Pre-test</span>
                                @endif
                                <br>
                                @if($course->has_posttest)
                                    <span class="badge success">✓ Post-test</span>
                                @else
                                    <span class="badge warning">⚠ No Post-test</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-users" style="color: var(--info);"></i>
                                {{ $course->enrollments_count ?? 0 }}
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="badge published">Published</span>
                                @else
                                    <span class="badge draft">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('edutech.instructor.courses.edit', $course->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>Belum Ada Course</h3>
                <p>Mulai buat course pertama Anda sekarang!</p>
                <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i> Create First Course
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Quiz Attempts -->
        @if(isset($recentQuizAttempts) && $recentQuizAttempts->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2>📊 Recent Quiz Attempts</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quiz</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Result</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentQuizAttempts as $attempt)
                        <tr>
                            <td>
                                <strong>{{ $attempt->user->name ?? 'Unknown' }}</strong>
                            </td>
                            <td>{{ $attempt->quiz->title ?? 'N/A' }}</td>
                            <td>
                                <small>{{ $attempt->quiz->course->title ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong style="color: var(--info);">{{ number_format($attempt->score, 0) }}</strong>
                            </td>
                            <td>
                                @if($attempt->is_passed)
                                    <span class="badge success">
                                        <i class="fas fa-check"></i> Passed
                                    </span>
                                @else
                                    <span class="badge warning">
                                        <i class="fas fa-times"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $attempt->submitted_at ? $attempt->submitted_at->diffForHumans() : 'N/A' }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Students -->
        @if(isset($recentStudents) && $recentStudents->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2>👥 Recent Students</h2>
                <a href="{{ route('edutech.instructor.students') }}" class="btn-secondary">
                    View All Students
                </a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStudents as $enrollment)
                        <tr>
                            <td>
                                <strong>{{ $enrollment->student->name ?? 'Unknown' }}</strong><br>
                                <small style="color: var(--gray);">{{ $enrollment->student->email ?? '' }}</small>
                            </td>
                            <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                        <div style="width: {{ $enrollment->progress_percentage ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                                    </div>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--dark);">
                                        {{ $enrollment->progress_percentage ?? 0 }}%
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($enrollment->status === 'completed')
                                    <span class="badge success">Completed</span>
                                @elseif($enrollment->status === 'active')
                                    <span class="badge info">Active</span>
                                @else
                                    <span class="badge">{{ ucfirst($enrollment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : 'N/A' }}</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </main>
@endsection