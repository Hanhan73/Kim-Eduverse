@extends('layouts.instructor')

@section('title', 'Students Management')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        padding: 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);
    }

    .page-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }

    .stat-icon.total { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
    .stat-icon.active { background: linear-gradient(135deg, #48bb78, #38a169); color: white; }
    .stat-icon.completed { background: linear-gradient(135deg, #4299e1, #3182ce); color: white; }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #718096;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .course-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .course-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 25px;
        border-bottom: 2px solid #e2e8f0;
    }

    .course-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .course-category {
        display: inline-block;
        padding: 5px 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .course-body {
        padding: 25px;
    }

    .course-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .stat-item-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
    }

    .stat-item-label {
        font-size: 0.85rem;
        color: #718096;
        margin-top: 5px;
    }

    .course-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
        color: white;
    }

    .btn-tertiary {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
    }

    .btn-tertiary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(66, 153, 225, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h2>
                <i class="fas fa-users"></i>
                Students Management
            </h2>
            <p style="margin: 0; opacity: 0.9;">Kelola siswa dan presensi untuk semua kursus Anda</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $activeEnrollments }}</div>
                <div class="stat-label">Enrollment Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-value">{{ $completedEnrollments }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
            <div class="courses-grid">
                @foreach($courses as $course)
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-title">{{ $course->title }}</div>
                        <span class="course-category">{{ $course->category }}</span>
                    </div>
                    <div class="course-body">
                        <div class="course-stats">
                            <div class="stat-item">
                                <div class="stat-item-value">{{ $course->enrollments_count }}</div>
                                <div class="stat-item-label">Siswa Terdaftar</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-item-value">{{ $course->level }}</div>
                                <div class="stat-item-label">Level</div>
                            </div>
                        </div>
                        <div class="course-actions">
                            <a href="{{ route('edutech.instructor.students.course', $course->id) }}" class="btn-action btn-primary">
                                <i class="fas fa-list"></i>
                                Lihat Siswa
                            </a>
                             <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="btn-action btn-secondary">
                                <i class="fas fa-clipboard-check"></i>
                                Presensi
                            </a>
                            @if(!empty($course->latest_batch_id))
                                <a href="{{ route('edutech.instructor.students.attendance.report', [$course->id, $course->latest_batch_id]) }}" class="btn-action btn-tertiary">
                                    <i class="fas fa-chart-bar"></i>
                                    Laporan
                                </a>
                            @else
                                <button type="button" class="btn-action btn-tertiary" disabled title="Belum ada batch untuk course ini">
                                    <i class="fas fa-chart-bar"></i>
                                    Laporan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Belum Ada Kursus</h3>
                <p>Buat kursus terlebih dahulu untuk mulai mengelola siswa</p>
            </div>
        @endif
    </div>
</div>
@endsection