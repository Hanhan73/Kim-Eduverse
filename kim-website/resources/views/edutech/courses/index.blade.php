@extends('layouts.edutech')

@section('title', 'Course - KIM Edutech')

@section('content')

<!-- Page Header -->
<section class="page-header-courses">
    <div class="container">
        <div class="header-content">
            <h1>Katalog Kursus</h1>
            <p>Temukan kursus yang sesuai dengan minat dan tujuan karir Anda</p>
            <div class="breadcrumb">
                <a href="{{ route('edutech.landing') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <span>Kursus</span>
            </div>
        </div>
    </div>
</section>

<!-- Courses Content -->
<section class="courses-content">
    <div class="container">
        <div class="courses-layout">
            <!-- Sidebar Filter -->
            <aside class="filter-sidebar">
                <div class="filter-header">
                    <h3><i class="fas fa-filter"></i> Filter Kursus</h3>
                    <a href="{{ route('edutech.courses.index') }}" class="btn-reset">Reset</a>
                </div>

                <form id="filterForm" method="GET" action="{{ route('edutech.courses.index') }}">
                    <!-- Search -->
                    <div class="filter-group">
                        <label><i class="fas fa-search"></i> Cari Kursus</label>
                        <input type="text" name="search" class="form-control-filter" placeholder="Ketik judul kursus..."
                            value="{{ request('search') }}">
                    </div>

                    <!-- Category -->
                    <div class="filter-group">
                        <label><i class="fas fa-th-large"></i> Kategori</label>
                        <select name="category" class="form-control-filter" onchange="this.form.submit()">
                            <option value="all">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <label><i class="fas fa-tag"></i> Harga</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="price" value="all"
                                    {{ request('price', 'all') == 'all' ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span>Semua Harga</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="price" value="free"
                                    {{ request('price') == 'free' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Gratis</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="price" value="under_500k"
                                    {{ request('price') == 'under_500k' ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <span>Di bawah Rp 500.000</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="price" value="under_1m"
                                    {{ request('price') == 'under_1m' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Di bawah Rp 1.000.000</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="price" value="above_1m"
                                    {{ request('price') == 'above_1m' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>Di atas Rp 1.000.000</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-apply-filter">
                        <i class="fas fa-check"></i> Terapkan Filter
                    </button>
                </form>
            </aside>

            <!-- Main Content -->
            <main class="courses-main">
                <!-- Toolbar -->
                <div class="courses-toolbar">
                    <div class="toolbar-left">
                        <h4>Menampilkan {{ $courses->total() }} Kursus</h4>
                        @if(request('search') || request('category') != 'all' || request('price') != 'all')
                        <span class="active-filters">
                            Filter aktif:
                            @if(request('search'))
                            <span class="filter-tag">"{{ request('search') }}"</span>
                            @endif
                            @if(request('category') && request('category') != 'all')
                            <span class="filter-tag">{{ request('category') }}</span>
                            @endif
                        </span>
                        @endif
                    </div>
                    <div class="toolbar-right">
                        <label>Urutkan:</label>
                        <select name="sort" class="sort-select" onchange="location.href=this.value">
                            <option
                                value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                                {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                Terbaru
                            </option>
                            <option
                                value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                                {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                Terpopuler
                            </option>
                            <option
                                value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                                {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                Harga Terendah
                            </option>
                            <option
                                value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
                                {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                Harga Tertinggi
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Courses Grid -->
                @if($courses->count() > 0)
                <div class="courses-grid-catalog">
                    @foreach($courses as $course)
                    <div class="course-card-catalog">
                        <div class="course-image-wrapper">
                            <a href="{{ route('edutech.courses.detail', $course->slug) }}">
                                <img src="{{ $course->thumbnail ?? 'https://via.placeholder.com/400x250/667eea/ffffff?text=' . urlencode($course->title) }}"
                                    alt="{{ $course->title }}">
                            </a>
                            <span class="level-badge {{ $course->level }}">
                                {{ ucfirst($course->level) }}
                            </span>
                            @if($course->price == 0)
                            <span class="free-label">GRATIS</span>
                            @endif
                        </div>

                        <div class="course-card-body">
                            <div class="category-label">{{ $course->category }}</div>

                            <h3 class="course-card-title">
                                <a href="{{ route('edutech.courses.detail', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>

                            <div class="instructor-small">
                                <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}"
                                    alt="{{ $course->instructor->name }}">
                                <span>{{ $course->instructor->name }}</span>
                            </div>

                            <p class="course-excerpt">
                                {{ Str::limit($course->description, 100) }}
                            </p>

                            <div class="course-meta-info">
                                <span><i class="fas fa-clock"></i> {{ $course->duration_hours }}h</span>
                                <span><i class="fas fa-users"></i> {{ $course->enrollments_count ?? 0 }}</span>
                                <span><i class="fas fa-star"></i> 4.8</span>
                            </div>

                            <div class="course-card-footer">
                                @if($course->price == 0)
                                <span class="price-label free">GRATIS</span>
                                @else
                                <span class="price-label">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                @endif
                                <a href="{{ route('edutech.courses.detail', $course->slug) }}" class="btn-detail">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $courses->links('vendor.pagination.default') }}
                </div>
                @else
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada kursus ditemukan</h3>
                    <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                    <a href="{{ route('edutech.courses.index') }}" class="btn-reset-search">Reset Filter</a>
                </div>
                @endif
            </main>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    .pagination i {
        font-size: 16px;
        color: #5b21b6;
        transition: color 0.2s ease;
    }

    .pagination a:hover i {
        color: white;
    }

    .pagination {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 6px;
        padding: 0;
    }

    .pagination li {
        display: inline-flex;
    }

    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        /* Kurangi dari 12px ke 6px untuk sangat kecil */
        height: 32px;
        border-radius: 50%;
        background: #f5f3ff;
        color: #5b21b6;
        font-size: 1.0rem;
        /* Kurangi font-size teks juga */
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pagination a:hover {
        background: #6d28d9;
        color: white;
    }

    .pagination .active span {
        background: #6d28d9;
        color: white;
        font-weight: 600;
    }

    .pagination .disabled span {
        background: #ede9fe;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
@endpush


<!-- @section('hideFooter', false) -->