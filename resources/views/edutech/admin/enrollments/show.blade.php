@extends('layouts.admin')

@section('title', 'Enrollment Detail')

@section('page-title', '🎓 Enrollment Detail')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h3>Enrollment Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Student</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->student->name }}</p>
                <p style="font-size: 0.9rem; color: var(--gray);">{{ $enrollment->student->email }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Course</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->course->title }}</p>
                <p style="font-size: 0.9rem; color: var(--gray);">by {{ $enrollment->course->instructor->name }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Progress</label>
                <div style="margin-bottom: 8px;">
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $enrollment->progress }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                    </div>
                </div>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->progress }}% completed</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Status</label>
                @if($enrollment->completed_at)
                    <span class="badge success">Completed</span>
                @elseif($enrollment->progress > 0)
                    <span class="badge warning">In Progress</span>
                @else
                    <span class="badge danger">Not Started</span>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Enrolled Date</label>
                <p style="font-size: 1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($enrollment->completed_at)
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Completed Date</label>
                <p style="font-size: 1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->completed_at->format('d M Y H:i') }}</p>
            </div>
            @endif
            @if($enrollment->certificate_issued_at)
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Certificate Issued</label>
                <p style="font-size: 1rem; font-weight: 600; color: var(--dark);">{{ $enrollment->certificate_issued_at->format('d M Y') }}</p>
                <p style="font-size: 0.85rem; color: var(--gray);">{{ $enrollment->certificate_number }}</p>
            </div>
            @endif
        </div>

        <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            @if($enrollment->status === 'pending')
            <form action="{{ route('edutech.admin.enrollments.approve', $enrollment->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: var(--success); color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form action="{{ route('edutech.admin.enrollments.reject', $enrollment->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: var(--danger); color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 500; cursor: pointer;">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
            @endif
            <a href="{{ route('edutech.admin.enrollments') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressDetails['total_lessons'] ?? 0 }}</h3>
            <p>Total Lessons</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressDetails['completed_lessons'] ?? 0 }}</h3>
            <p>Completed Lessons</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressDetails['remaining_lessons'] ?? 0 }}</h3>
            <p>Remaining Lessons</p>
        </div>
    </div>
</div>

@if($enrollment->course->modules->count() > 0)
<div class="content-card">
    <div class="card-header">
        <h3>Course Progress by Module</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Total Lessons</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollment->course->modules as $module)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $module->title }}</td>
                    <td>{{ $module->lessons->count() }} lessons</td>
                    <td>
                        <span class="badge warning">In Progress</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection