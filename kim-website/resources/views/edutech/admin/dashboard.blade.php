@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Top Bar with Logout -->
    <div style="background: white; padding: 20px 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: 5px;">
                <i class="fas fa-home" style="color: var(--primary);"></i> Dashboard
            </h1>
            <p style="color: var(--gray); margin: 0;">Welcome back, {{ session('edutech_user_name') }}! Here's what's happening today.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #e53e3e, #c53030); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.2rem;">
                {{ strtoupper(substr(session('edutech_user_name'), 0, 1)) }}
            </div>
            <form action="{{ route('edutech.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: var(--danger); color: white; padding: 10px 20px; border-radius: 8px; font-weight: 500; border: none; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

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
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_certificates'] ?? 0 }}</h3>
                <p>Certificates Issued</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 30px;">
        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-chart-line"></i> This Month</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--gray);">New Users</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $stats['new_users_this_month'] ?? 0 }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--gray);">New Enrollments</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--success);">{{ $stats['new_enrollments_this_month'] ?? 0 }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--gray);">Certificates Issued</span>
                        <span style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">{{ $stats['certificates_this_month'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-fire"></i> Popular Courses</h3>
            </div>
            <div class="card-body">
                @if(isset($popularCourses) && $popularCourses->count() > 0)
                <div style="display: grid; gap: 12px;">
                    @foreach($popularCourses->take(3) as $course)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: var(--light); border-radius: 8px;">
                        <div>
                            <h4 style="color: var(--dark); font-size: 0.9rem; font-weight: 600; margin-bottom: 3px;">{{ Str::limit($course->title, 30) }}</h4>
                            <p style="color: var(--gray); font-size: 0.8rem;">{{ $course->enrollments_count }} students</p>
                        </div>
                        <div style="background: var(--primary); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ $loop->iteration }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p style="color: var(--gray); text-align: center; padding: 20px;">No data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Recent Activity</h3>
        </div>
        <div class="card-body">
            @if(isset($recentActivities) && $recentActivities->count() > 0)
            <div style="display: grid; gap: 15px;">
                @foreach($recentActivities as $activity)
                <div style="display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid #e2e8f0;">
                    <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">
                        {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: var(--dark); font-weight: 600; margin-bottom: 3px;">{{ $activity->title }}</h4>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">{{ $activity->description }}</p>
                        <span style="color: var(--gray); font-size: 0.8rem;">
                            <i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 40px; color: var(--gray);">
                <i class="fas fa-history" style="font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"></i>
                <p>No recent activities</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection