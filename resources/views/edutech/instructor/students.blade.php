@extends('layouts.instructor')

@section('title', 'My Students - KIM EDUVERSE')

@section('content')
<!-- Main Content -->
<main class="main-content">
    <div class="top-bar">
        <h1>👥 My Students</h1>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                <p>Total Students</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active_students'] ?? 0 }}</h3>
                <p>Active Students</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['completed'] ?? 0 }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($stats['avg_progress'] ?? 0, 0) }}%</h3>
                <p>Avg Progress</p>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="content-section">
        @if(isset($students) && $students->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Enrolled Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $enrollment)
                    <tr>
                        <td>
                            <strong
                                style="color: var(--dark);">{{ $enrollment->student->name ?? 'Unknown' }}</strong><br>
                            <small style="color: var(--gray);">{{ $enrollment->student->email ?? '' }}</small>
                        </td>
                        <td>
                            <strong style="color: var(--dark);">{{ $enrollment->course->title ?? 'N/A' }}</strong><br>
                            <small style="color: var(--gray);">{{ $enrollment->course->category ?? '' }}</small>
                        </td>
                        <td style="min-width: 150px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1;">
                                    <div class="progress-bar">
                                        <div class="progress-fill"
                                            style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                    </div>
                                </div>
                                <span
                                    style="font-size: 0.85rem; font-weight: 600; color: var(--dark); min-width: 40px;">
                                    {{ $enrollment->progress_percentage ?? 0 }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($enrollment->status === 'completed')
                            <span class="badge completed">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                            @elseif($enrollment->status === 'active')
                            <span class="badge active">
                                <i class="fas fa-play-circle"></i> Active
                            </span>
                            @else
                            <span class="badge">{{ ucfirst($enrollment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d M Y') : 'N/A' }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $students->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>Belum Ada Student</h3>
            <p>Student akan muncul di sini setelah mendaftar ke course Anda</p>
        </div>
        @endif
    </div>
</main>
@endsection