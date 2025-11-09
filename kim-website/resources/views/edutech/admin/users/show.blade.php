@extends('layouts.admin')

@section('title', 'User Detail')

@section('page-title', '👤 User Detail')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h3>User Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Name</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $user->name }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Email</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $user->email }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Role</label>
                <span class="badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Status</label>
                <span class="badge {{ $user->is_active ? 'success' : 'danger' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Joined Date</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('edutech.admin.users.edit', $user->id) }}" style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="{{ route('edutech.admin.users') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
            <p>Total Courses</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_enrollments'] ?? 0 }}</h3>
            <p>Total Enrollments</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['completed_courses'] ?? 0 }}</h3>
            <p>Completed Courses</p>
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
</div>

@if($user->role === 'instructor' && $user->instructorCourses->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h3>Courses as Instructor</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Enrollments</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->instructorCourses as $course)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $course->title }}</td>
                    <td>{{ $course->category }}</td>
                    <td>
                        <span class="badge {{ $course->is_published ? 'success' : 'warning' }}">
                            {{ $course->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>{{ $course->enrollments->count() }}</td>
                    <td>{{ $course->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($user->enrollments->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h3>Enrollments</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Enrolled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->enrollments as $enrollment)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $enrollment->course->title }}</td>
                    <td>{{ $enrollment->course->instructor->name }}</td>
                    <td>
                        <div style="width: 100px;">
                            <div style="width: 100%; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $enrollment->progress }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                            </div>
                            <span style="font-size: 0.8rem; color: var(--gray);">{{ $enrollment->progress }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($enrollment->completed_at)
                            <span class="badge success">Completed</span>
                        @else
                            <span class="badge warning">In Progress</span>
                        @endif
                    </td>
                    <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection