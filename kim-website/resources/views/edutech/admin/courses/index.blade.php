@extends('layouts.admin')

@section('title', 'Courses Management - Admin Panel')

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

    .admin-container {
        display: flex;
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

    .stat-icon.gray {
        background: linear-gradient(135deg, #718096, #4a5568);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
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
    }

    .btn-reset:hover {
        background: #4a5568;
    }

    /* Courses Grid */
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        padding: 30px;
    }

    .course-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--primary);
    }

    .course-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .course-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.95);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .course-body {
        padding: 20px;
    }

    .course-category {
        background: #e6f2ff;
        color: #2c5282;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 12px;
    }

    .course-title {
        font-size: 1.2rem;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .course-instructor {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .course-stats {
        display: flex;
        gap: 20px;
        padding: 15px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 15px;
    }

    .course-stat {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .course-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
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

    .btn-edit {
        background: #c6f6d5;
        color: #22543d;
    }

    .btn-edit:hover {
        background: #9ae6b4;
    }

    .btn-delete {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-delete:hover {
        background: #fc8181;
    }

    .btn-publish {
        background: #feebc8;
        color: #7c2d12;
    }

    .btn-publish:hover {
        background: #fbd38d;
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

    .badge.published {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge.draft {
        background: #fed7d7;
        color: #742a2a;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--gray);
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--gray);
    }

    /* Pagination */
    .pagination {
        padding: 20px 30px;
        display: flex;
        justify-content: center;
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

        .courses-grid {
            grid-template-columns: 1fr;
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
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
                <p>Total Courses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['published_courses'] ?? 0 }}</h3>
                <p>Published Courses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon gray">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['draft_courses'] ?? 0 }}</h3>
                <p>Draft Courses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_enrollments'] ?? 0 }}</h3>
                <p>Total Enrollments</p>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h2>
                <i class="fas fa-book"></i>
                All Courses
            </h2>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.courses') }}">
            <div class="filter-section">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="filter-input" placeholder="Search courses..." value="{{ request('search') }}">
                </div>
                
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Language" {{ request('category') == 'Language' ? 'selected' : '' }}>Language</option>
                        <option value="Teknologi Informasi" {{ request('category') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        <option value="Desain" {{ request('category') == 'Desain' ? 'selected' : '' }}>Desain</option>
                        <option value="Manajemen dan Teknik Industri" {{ request('category') == 'Manajemen dan Teknik Industri' ? 'selected' : '' }}>Manajemen dan Teknik Industri</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Instructor</label>
                    <select name="instructor" class="filter-select">
                        <option value="">All Instructors</option>
                        @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ request('instructor') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group" style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.courses') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
            <div class="course-card">
                <div style="position: relative;">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumbnail">
                    @else
                        <div class="course-thumbnail"></div>
                    @endif
                    <span class="course-badge badge {{ $course->is_published ? 'published' : 'draft' }}">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <div class="course-body">
                    <span class="course-category">{{ $course->category }}</span>
                    
                    <h3 class="course-title">{{ $course->title }}</h3>
                    
                    <div class="course-instructor">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ $course->instructor->name }}</span>
                    </div>

                    <div class="course-stats">
                        <div class="course-stat">
                            <i class="fas fa-users" style="color: var(--primary);"></i>
                            <span>{{ $course->enrollments_count }} enrolled</span>
                        </div>
                        <div class="course-stat">
                            <i class="fas fa-layer-group" style="color: var(--success);"></i>
                            <span>{{ $course->modules_count }} modules</span>
                        </div>
                    </div>

                    <div class="course-actions">
                        <a href="{{ route('edutech.admin.courses.show', $course->id) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('edutech.admin.courses.edit', $course->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('edutech.admin.courses.toggle', $course->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn-action {{ $course->is_published ? 'btn-delete' : 'btn-publish' }}" style="width: 100%;">
                                <i class="fas fa-{{ $course->is_published ? 'ban' : 'check' }}"></i>
                                {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-links">
                {{ $courses->links() }}
            </div>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>No courses found</h3>
            <p>Try adjusting your filters or search criteria</p>
        </div>
        @endif
    </div>
</main>
@endsection