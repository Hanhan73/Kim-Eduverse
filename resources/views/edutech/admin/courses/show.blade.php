@extends('layouts.admin')

@section('title', 'Course Detail')

@section('page-title', '📚 Course Detail')

@section('content')
<div class="content-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>{{ $course->title }}</h3>
        <span class="badge {{ $course->is_published ? 'success' : 'warning' }}">
            {{ $course->is_published ? 'Published' : 'Draft' }}
        </span>
    </div>
    <div class="card-body">
        @if($course->thumbnail)
        <div style="margin-bottom: 20px;">
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" 
                style="width: 100%; max-width: 600px; height: 300px; object-fit: cover; border-radius: 12px;">
        </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Category</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $course->category }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Instructor</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $course->instructor->name }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Price</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">
                    {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Free' }}
                </p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Created</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $course->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 10px;">Description</label>
            <div style="color: var(--dark); line-height: 1.6;">
                {{ $course->description }}
            </div>
        </div>

        <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('edutech.admin.courses.edit', $course->id) }}" style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-edit"></i> Edit Course
            </a>
            <form action="{{ route('edutech.admin.courses.toggle', $course->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: {{ $course->is_published ? 'var(--warning)' : 'var(--success)' }}; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    <i class="fas fa-{{ $course->is_published ? 'ban' : 'check' }}"></i> 
                    {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                </button>
            </form>
            <a href="{{ route('edutech.admin.courses') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

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
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['completed'] ?? 0 }}</h3>
            <p>Completed</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-spinner"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['in_progress'] ?? 0 }}</h3>
            <p>In Progress</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3>{{ number_format($stats['average_progress'] ?? 0, 1) }}%</h3>
            <p>Average Progress</p>
        </div>
    </div>
</div>

@if($course->modules->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h3>Course Modules</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Module Title</th>
                    <th>Order</th>
                    <th>Lessons</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->modules as $module)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $module->title }}</td>
                    <td>{{ $module->order }}</td>
                    <td>{{ $module->lessons->count() }} lessons</td>
                    <td>{{ $module->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($course->enrollments->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h3>Recent Enrollments</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Enrolled Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->enrollments->take(10) as $enrollment)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $enrollment->student->name }}</td>
                    <td>
                        <div style="width: 100px;">
                            <div style="width: 100%; height: 8px; background: var(--light); border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $enrollment->progress }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                            </div>
                            <span style="font-size: 0.8rem; color: var(--gray);">{{ $enrollment->progress_percentage }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($enrollment->progress_percentage == 100)
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