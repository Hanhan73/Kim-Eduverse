@extends('layouts.admin')

@section('title', 'Certificates Management')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: 5px;">
            <i class="fas fa-certificate" style="color: var(--primary);"></i> Certificates Management
        </h1>
        <p style="color: var(--gray);">Track and manage all issued certificates</p>
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
            <div class="stat-icon purple">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_certificates'] ?? 0 }}</h3>
                <p>Total Certificates</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['this_month'] ?? 0 }}</h3>
                <p>This Month</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['this_year'] ?? 0 }}</h3>
                <p>This Year</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                <p>Certified Students</p>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h3>All Certificates</h3>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.certificates') }}">
            <div style="padding: 20px 30px; background: #f7fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Search Student</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Student name..." 
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
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                        style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                        style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.certificates') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">
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
                        <th>Certificate Number</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Issued Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">{{ $certificate->certificate_number }}</td>
                        <td>
                            <div>
                                <h4 style="font-weight: 600; color: var(--dark); margin-bottom: 3px;">{{ $certificate->student->name }}</h4>
                                <p style="font-size: 0.85rem; color: var(--gray);">{{ $certificate->student->email }}</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <h4 style="font-weight: 600; color: var(--dark); margin-bottom: 3px;">{{ $certificate->course->title }}</h4>
                                <p style="font-size: 0.85rem; color: var(--gray);">by {{ $certificate->course->instructor->name }}</p>
                            </div>
                        </td>
                        <td>{{ $certificate->certificate_issued_at->format('d M Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('edutech.admin.certificates.show', $certificate->id) }}" 
                                    style="background: #bee3f8; color: #2c5282; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('edutech.admin.certificates.revoke', $certificate->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Revoke this certificate?')"
                                        style="background: #fed7d7; color: #742a2a; padding: 6px 12px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                                        <i class="fas fa-ban"></i> Revoke
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            <i class="fas fa-certificate" style="font-size: 3rem; color: var(--gray); opacity: 0.3; margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray);">No certificates found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0;">
            <div style="color: var(--gray);">
                Showing {{ $certificates->firstItem() ?? 0 }} to {{ $certificates->lastItem() ?? 0 }} of {{ $certificates->total() }} certificates
            </div>
            <div>
                {{ $certificates->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
</div>
@endsection