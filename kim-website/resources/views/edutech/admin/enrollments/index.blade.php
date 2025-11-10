@extends('layouts.admin')

@section('title', 'Enrollments Management')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: 5px;">
            <i class="fas fa-graduation-cap" style="color: var(--primary);"></i> Enrollments Management
        </h1>
        <p style="color: var(--gray);">Track and manage all student enrollments</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_enrollments'] ?? 0 }}</h3>
                <p>Total Enrollments</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
                <p>In Progress</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['completed'] ?? 0 }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending'] ?? 0 }}</h3>
                <p>Pending Approval</p>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h3>All Enrollments</h3>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.enrollments') }}">
            <div style="padding: 20px 30px; background: #f7fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Search Student</label>
                    <input type="text" name="search" placeholder="Student name..." value="{{ request('search') }}"
                        style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Course</label>
                    <select name="course" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.enrollments') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Enrolled Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($enrollment->student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 style="color: var(--dark); font-weight: 600; margin-bottom: 3px;">{{ $enrollment->student->name }}</h4>
                                    <p style="font-size: 0.85rem; color: var(--gray);">{{ $enrollment->student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h4 style="color: var(--dark); font-weight: 600; margin-bottom: 5px;">
                                {{ $enrollment->course->title }}
                            </h4>
                            <p style="font-size: 0.85rem; color: var(--gray);">
                                by {{ $enrollment->course->instructor->name }}
                            </p>
                        </td>
                        <td>
                            <div style="width: 150px;">
                                <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 5px;">
                                    <div style="height: 100%; width: {{ $enrollment->progress_percentage }}%; background: linear-gradient(90deg, var(--success), #38a169); border-radius: 10px;"></div>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--gray); font-weight: 600;">{{ $enrollment->progress_percentage }}% completed</div>
                            </div>
                        </td>
                        <td>
                            @if($enrollment->progress_percentage === 100)
                                <span class="badge success">Completed</span>
                            @elseif($enrollment->progress_percentage > 0)
                                <span class="badge warning">In Progress</span>
                            @else
                                <span class="badge danger">Not Started</span>
                            @endif
                        </td>
                        <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('edutech.admin.enrollments.show', $enrollment->id) }}" 
                                style="background: #bee3f8; color: #2c5282; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-block; margin-bottom: 5px;">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i class="fas fa-user-graduate" style="font-size: 3rem; color: var(--gray); opacity: 0.3; margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray);">No enrollments found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0;">
            <div style="color: var(--gray);">
                Showing {{ $enrollments->firstItem() ?? 0 }} to {{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} enrollments
            </div>
            <div>
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection