@extends('layouts.instructor')

@section('title', 'My Courses- KIM EDUVERSE')

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --success: #48bb78;
    --warning: #ed8936;
    --danger: #f56565;
    --info: #4299e1;
    --dark: #2d3748;
    --gray: #718096;
    --light: #f7fafc;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f8f9fa;
}

/* Sidebar - sama seperti dashboard */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, var(--primary), var(--secondary));
    padding: 25px 0;
    color: white;
    overflow-y: auto;
}

.sidebar-header {
    padding: 0 25px 30px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar-header h2 {
    font-size: 1.5rem;
    font-weight: 800;
}

.sidebar-menu {
    padding: 20px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 25px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.menu-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.menu-item.active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border-left-color: white;
}

.menu-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
    margin: 20px 25px;
}

.main-content {
    margin-left: 280px;
    padding: 30px;
}

.top-bar {
    background: white;
    padding: 25px 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar h1 {
    font-size: 1.8rem;
    color: var(--dark);
}

.btn-primary {
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(237, 137, 54, 0.3);
}

/* Course Grid */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
}

.course-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.course-thumbnail {
    width: 100%;
    height: 180px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.course-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-published {
    background: #c6f6d5;
    color: #22543d;
}

.badge-draft {
    background: #fed7d7;
    color: #742a2a;
}

.course-body {
    padding: 20px;
}

.course-category {
    display: inline-block;
    padding: 4px 12px;
    background: #f0f4ff;
    color: var(--primary);
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.course-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 10px;
    line-height: 1.4;
}

.course-stats {
    display: flex;
    gap: 20px;
    margin: 15px 0;
    padding: 15px 0;
    border-top: 1px solid var(--light);
    border-bottom: 1px solid var(--light);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray);
    font-size: 0.85rem;
}

.stat-item i {
    color: var(--info);
}

.course-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #bee3f8;
    color: #2c5282;
}

.btn-edit:hover {
    background: #90cdf4;
}

.btn-view {
    background: var(--light);
    color: var(--dark);
}

.btn-view:hover {
    background: #e2e8f0;
}

.empty-state {
    background: white;
    padding: 80px 40px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.empty-state i {
    font-size: 5rem;
    color: var(--light);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: var(--dark);
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 30px;
}

@media (max-width: 968px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .main-content {
        margin-left: 0;
    }
}
</style>

@section('content')
<!-- Main Content -->
<main class="main-content">
    <div class="top-bar">
        <h1>📚 My Courses</h1>
        <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
            <i class="fas fa-plus-circle"></i> Create New Course
        </a>
    </div>

    @if(session('success'))
    <div style="background: #c6f6d5; color: #22543d; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(isset($courses) && $courses->count() > 0)
    <div class="courses-grid">
        @foreach($courses as $course)
        <div class="course-card">
            <div class="course-thumbnail">
                @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                @else
                <i class="fas fa-book" style="font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
                @endif

                <span class="course-status-badge {{ $course->is_published ? 'badge-published' : 'badge-draft' }}">
                    {{ $course->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>

            <div class="course-body">
                <span class="course-category">{{ $course->category }}</span>
                <h3 class="course-title">{{ $course->title }}</h3>

                <div class="course-stats">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span>{{ $course->enrollments_count ?? 0 }} students</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ $course->modules_count ?? 0 }} modules</span>
                    </div>
                </div>

                <div class="course-actions">
                    <a href="{{ route('edutech.instructor.courses.edit', $course->id) }}" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('edutech.courses.detail', $course->slug) }}" class="btn btn-view">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-book-open"></i>
        <h3>Belum Ada Course</h3>
        <p>Mulai berbagi ilmu dengan membuat course pertama Anda</p>
        <a href="{{ route('edutech.instructor.courses.create') }}" class="btn-primary">
            <i class="fas fa-plus-circle"></i> Create Your First Course
        </a>
    </div>
    @endif
</main>
@endsection