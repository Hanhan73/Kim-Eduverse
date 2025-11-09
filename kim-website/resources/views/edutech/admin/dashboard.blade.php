@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', '🛡️ Admin Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
            <p>Total Users</p>
        </div>
    </div>

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
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3>Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- User Roles Chart -->
    <div class="content-card">
        <div class="card-header">
            <h3>👥 User Roles Distribution</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--dark); font-weight: 600;">Students</span>
                        <span style="color: var(--primary); font-weight: 600;">{{ $roleStats['students'] ?? 0 }}</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $roleStats['students_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary));"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--dark); font-weight: 600;">Instructors</span>
                        <span style="color: var(--warning); font-weight: 600;">{{ $roleStats['instructors'] ?? 0 }}</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $roleStats['instructors_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--warning), #dd6b20);"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--dark); font-weight: 600;">Admins</span>
                        <span style="color: var(--danger); font-weight: 600;">{{ $roleStats['admins'] ?? 0 }}</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $roleStats['admins_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--danger), #c53030);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Stats Chart -->
    <div class="content-card">
        <div class="card-header">
            <h3>📈 Course Statistics</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--dark); font-weight: 600;">Published</span>
                        <span style="color: var(--success); font-weight: 600;">{{ $courseStats['published'] ?? 0 }}</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $courseStats['published_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--success), #38a169);"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--dark); font-weight: 600;">Draft</span>
                        <span style="color: var(--gray); font-weight: 600;">{{ $courseStats['draft'] ?? 0 }}</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: var(--light); border-radius: 10px; overflow: hidden;">
                        <div style="width: {{ $courseStats['draft_percentage'] ?? 0 }}%; height: 100%; background: linear-gradient(90deg, var(--gray), #4a5568);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div class="content-card">
    <div class="card-header">
        <h3>📋 Recent Users</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px;">
                        <i class="fas fa-users" style="font-size: 2rem; color: var(--gray); display: block; margin-bottom: 10px;"></i>
                        No users yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Enrollments Table -->
<div class="content-card">
    <div class="card-header">
        <h3>📚 Recent Enrollments</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEnrollments as $enrollment)
                <tr>
                    <td style="font-weight: 600; color: var(--dark);">{{ $enrollment->student->name }}</td>
                    <td>{{ $enrollment->course->title }}</td>
                    <td>{{ $enrollment->course->instructor->name }}</td>
                    <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px;">
                        <i class="fas fa-book" style="font-size: 2rem; color: var(--gray); display: block; margin-bottom: 10px;"></i>
                        No enrollments yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection