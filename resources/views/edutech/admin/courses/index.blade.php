@extends('layouts.admin')

@section('title', 'Courses Management')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: 5px;">
            <i class="fas fa-book" style="color: var(--primary);"></i> Courses Management
        </h1>
        <p style="color: var(--gray);">Manage all courses, categories, and content</p>
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
            <div class="stat-icon warning">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['draft_courses'] ?? 0 }}</h3>
                <p>Draft Courses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
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
            <h3>All Courses</h3>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.courses') }}">
            <div style="padding: 20px 30px; background: #f7fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Search</label>
                    <input type="text" name="search" placeholder="Search courses..." value="{{ request('search') }}"
                        style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Category</label>
                    <select name="category" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Categories</option>
                        <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                        <option value="Language" {{ request('category') == 'Language' ? 'selected' : '' }}>Language</option>
                        <option value="Teknologi Informasi" {{ request('category') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                        <option value="Desain" {{ request('category') == 'Desain' ? 'selected' : '' }}>Desain</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Instructor</label>
                    <select name="instructor" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Instructors</option>
                        @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ request('instructor') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.courses') }}" style="background: var(--gray); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; padding: 30px;">
            @foreach($courses as $course)
            <div style="background: white; border: 2px solid var(--light); border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                <div style="position: relative;">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" 
                            style="width: 100%; height: 180px; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #667eea, #764ba2);"></div>
                    @endif
                    <span class="badge {{ $course->is_published ? 'success' : 'danger' }}" style="position: absolute; top: 15px; right: 15px;">
                        {{ $course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <div style="padding: 20px;">
                    <span style="background: #e6f2ff; color: #2c5282; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                        {{ $course->category }}
                    </span>
                    
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--dark); margin: 12px 0; line-height: 1.4;">
                        {{ $course->title }}
                    </h3>
                    
                    <div style="display: flex; align-items: center; gap: 8px; color: var(--gray); font-size: 0.85rem; margin-bottom: 15px;">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ $course->instructor->name }}</span>
                    </div>

                    <div style="display: flex; gap: 20px; padding: 15px 0; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--gray);">
                            <i class="fas fa-users" style="color: var(--primary);"></i>
                            <span>{{ $course->enrollments_count }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--gray);">
                            <i class="fas fa-layer-group" style="color: var(--success);"></i>
                            <span>{{ $course->modules_count }} modules</span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;">
                        <a href="{{ route('edutech.admin.courses.show', $course->id) }}" 
                            style="background: #bee3f8; color: #2c5282; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 500; text-align: center;">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('edutech.admin.courses.edit', $course->id) }}" 
                            style="background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 500; text-align: center;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('edutech.admin.courses.toggle', $course->id) }}" method="POST" style="grid-column: 1 / -1;">
                            @csrf
                            <button type="submit" 
                                style="width: 100%; background: {{ $course->is_published ? '#fed7d7' : '#feebc8' }}; color: {{ $course->is_published ? '#742a2a' : '#7c2d12' }}; padding: 10px; border-radius: 8px; border: none; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
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
        <div style="padding: 20px 30px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div style="color: var(--gray);">
                Showing {{ $courses->firstItem() ?? 0 }} to {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} courses
            </div>
            <div>
                {{ $courses->links() }}
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-book-open" style="font-size: 4rem; color: var(--gray); opacity: 0.3; margin-bottom: 20px;"></i>
            <h3 style="font-size: 1.5rem; color: var(--dark); margin-bottom: 10px;">No courses found</h3>
            <p style="color: var(--gray);">Try adjusting your filters or search criteria</p>
        </div>
        @endif
    </div>
</div>
@endsection