@extends('layouts.admin')

@section('title', 'Enrollments Management - Admin Panel')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #e53e3e;
        --warning: #ed8936;
        --info: #4299e1;
        --dark: #2d3748;
        --gray: #718096;
        --light: #f7fafc;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        padding: 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 30px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
        color: white;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .menu-item.active {
        background: rgba(255, 255, 255, 0.1);
        border-left: 4px solid white;
        color: white;
    }

    .menu-item i {
        width: 25px;
        margin-right: 12px;
    }

    .menu-divider {
        margin: 20px 25px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Main Content */
    .main-content {
        margin-left: 260px;
        flex: 1;
        padding: 30px;
        width: calc(100% - 260px);
    }

    .top-bar {
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-bar h1 {
        font-size: 1.8rem;
        color: var(--dark);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e53e3e, #c53030);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .btn-logout {
        background: var(--danger);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-logout:hover {
        background: #c53030;
        transform: translateY(-2px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #4299e1, #3182ce);
    }

    .stat-content h3 {
        font-size: 2rem;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-content p {
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 25px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 1.5rem;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Filter Section */
    .filter-section {
        padding: 20px 30px;
        background: #f7fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .btn-reset {
        background: var(--gray);
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background: #4a5568;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f7fafc;
    }

    th {
        text-align: left;
        padding: 15px 20px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        color: var(--gray);
    }

    tbody tr {
        transition: background 0.3s ease;
    }

    tbody tr:hover {
        background: #f7fafc;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .user-info-cell h4 {
        color: var(--dark);
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-info-cell p {
        font-size: 0.85rem;
        color: var(--gray);
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.completed {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge.in-progress {
        background: #bee3f8;
        color: #2c5282;
    }

    .badge.not-started {
        background: #fed7d7;
        color: #742a2a;
    }

    .badge.pending {
        background: #feebc8;
        color: #7c2d12;
    }

    .badge.approved {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge.rejected {
        background: #fed7d7;
        color: #742a2a;
    }

    /* Progress Bar */
    .progress-container {
        width: 100%;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success), #38a169);
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.85rem;
        color: var(--gray);
        font-weight: 600;
    }

    /* Action Buttons */
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        margin-right: 5px;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-view:hover {
        background: #90cdf4;
    }

    .btn-approve {
        background: #c6f6d5;
        color: #22543d;
    }

    .btn-approve:hover {
        background: #9ae6b4;
    }

    .btn-reject {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-reject:hover {
        background: #fc8181;
    }

    /* Pagination */
    .pagination {
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: var(--gray);
    }

    .pagination-links {
        display: flex;
        gap: 8px;
    }

    .pagination-links a,
    .pagination-links span {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: var(--gray);
        transition: all 0.3s ease;
    }

    .pagination-links a:hover {
        background: #e2e8f0;
    }

    .pagination-links .active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    /* Alert */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .filter-section {
            flex-direction: column;
        }

        .table-container {
            overflow-x: auto;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<!-- Main Content -->
<main class="main-content">

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
            <div class="stat-icon info">
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
            <div class="stat-icon warning">
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
            <h2>
                <i class="fas fa-list"></i>
                All Enrollments
            </h2>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.enrollments') }}">
            <div class="filter-section">
                <div class="filter-group">
                    <label>Search Student</label>
                    <input type="text" name="search" class="filter-input" placeholder="Search by student name..." value="{{ request('search') }}">
                </div>
                
                <div class="filter-group">
                    <label>Course</label>
                    <select name="course" class="filter-select">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="filter-group" style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.enrollments') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-container">
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
                            <div class="user-cell">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($enrollment->student->name, 0, 1)) }}
                                </div>
                                <div class="user-info-cell">
                                    <h4>{{ $enrollment->student->name }}</h4>
                                    <p>{{ $enrollment->student->email }}</p>
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
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <div class="progress-text">{{ $enrollment->progress_percentage}}% completed</div>
                            </div>
                        </td>
                        <td>
                            @if($enrollment->progress_percentage === 100)
                                <span class="badge completed">Completed</span>
                            @elseif($enrollment->progress_percentage > 0)
                                <span class="badge in-progress">In Progress</span>
                            @else
                                <span class="badge not-started">Not Started</span>
                            @endif
                        </td>
                        <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('edutech.admin.enrollments.show', $enrollment->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($enrollment->status === 'pending')
                            <form action="{{ route('edutech.admin.enrollments.approve', $enrollment->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this enrollment?')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('edutech.admin.enrollments.reject', $enrollment->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-reject" onclick="return confirm('Reject this enrollment?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i class="fas fa-user-graduate" style="font-size: 3rem; color: var(--gray); margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray);">No enrollments found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info">
                Showing {{ $enrollments->firstItem() ?? 0 }} to {{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} enrollments
            </div>
            <div class="pagination-links">
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
</main>
@endsection