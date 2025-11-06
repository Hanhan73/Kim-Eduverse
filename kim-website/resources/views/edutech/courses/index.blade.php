@extends('layouts.app')

@section('title', 'Katalog Kursus - KIM Edutech')

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
                        <input type="text" 
                               name="search" 
                               class="form-control-filter" 
                               placeholder="Ketik judul kursus..."
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
                                       {{ request('price') == 'free' ? 'checked' : '' }}
                                       onchange="this.form.submit()">
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
                                       {{ request('price') == 'under_1m' ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span>Di bawah Rp 1.000.000</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="price" value="above_1m"
                                       {{ request('price') == 'above_1m' ? 'checked' : '' }}
                                       onchange="this.form.submit()">
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
                        @if(request('search') || request('category') != 'all' || request('level') != 'all' || request('price') != 'all')
                        <span class="active-filters">
                            Filter aktif: 
                            @if(request('search'))
                                <span class="filter-tag">"{{ request('search') }}"</span>
                            @endif
                            @if(request('category') && request('category') != 'all')
                                <span class="filter-tag">{{ request('category') }}</span>
                            @endif
                            @if(request('level') && request('level') != 'all')
                                <span class="filter-tag">{{ ucfirst(request('level')) }}</span>
                            @endif
                        </span>
                        @endif
                    </div>
                    <div class="toolbar-right">
                        <label>Urutkan:</label>
                        <select name="sort" class="sort-select" onchange="location.href=this.value">
                            <option value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}"
                                    {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                Terbaru
                            </option>
                            <option value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                                    {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                Terpopuler
                            </option>
                            <option value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}"
                                    {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                Harga Terendah
                            </option>
                            <option value="{{ route('edutech.courses.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}"
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
                            <a href="{{ route('edutech.courses.show', $course->slug) }}">
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
                                <a href="{{ route('edutech.courses.show', $course->slug) }}">
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
                                <span><i class="fas fa-users"></i> {{ $course->enrollments_count }}</span>
                                <span><i class="fas fa-star"></i> 4.8</span>
                            </div>

                            <div class="course-card-footer">
                                @if($course->price == 0)
                                    <span class="price-label free">GRATIS</span>
                                @else
                                    <span class="price-label">{{ $course->formatted_price }}</span>
                                @endif
                                <a href="{{ route('edutech.courses.show', $course->slug) }}" class="btn-detail">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $courses->links() }}
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
/* Page Header */
.page-header-courses {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 80px 0 60px;
    color: white;
}

.header-content {
    text-align: center;
}

.header-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
}

.header-content p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 0.95rem;
}

.breadcrumb a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    opacity: 0.9;
}

.breadcrumb a:hover {
    opacity: 1;
    text-decoration: underline;
}

/* Courses Layout */
.courses-content {
    padding: 60px 0 100px;
    background: #f8f9fa;
}

.courses-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 40px;
}

/* Filter Sidebar */
.filter-sidebar {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    height: fit-content;
    sticky: top-20px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
}

.filter-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-reset {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
}

.btn-reset:hover {
    text-decoration: underline;
}

.filter-group {
    margin-bottom: 30px;
}

.filter-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 12px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-control-filter {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control-filter:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 10px;
    border-radius: 10px;
    transition: background 0.2s ease;
}

.radio-label:hover {
    background: #f7fafc;
}

.radio-label input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.radio-label span {
    color: #4a5568;
    font-size: 0.9rem;
}

.btn-apply-filter {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-apply-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Courses Main */
.courses-main {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.courses-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 20px;
}

.toolbar-left h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.active-filters {
    font-size: 0.9rem;
    color: #718096;
}

.filter-tag {
    display: inline-block;
    padding: 4px 12px;
    background: #f0f4ff;
    color: #667eea;
    border-radius: 8px;
    font-weight: 600;
    margin-left: 5px;
}

.toolbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toolbar-right label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.95rem;
}

.sort-select {
    padding: 10px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.9rem;
    cursor: pointer;
    min-width: 180px;
}

/* Courses Grid */
.courses-grid-catalog {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.course-card-catalog {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.course-card-catalog:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.course-image-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.course-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.course-card-catalog:hover .course-image-wrapper img {
    transform: scale(1.1);
}

.level-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 5px 14px;
    background: white;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.level-badge.beginner { color: #48bb78; }
.level-badge.intermediate { color: #ed8936; }
.level-badge.advanced { color: #e53e3e; }

.free-label {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 5px 14px;
    background: #48bb78;
    color: white;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.course-card-body {
    padding: 20px;
}

.category-label {
    display: inline-block;
    padding: 4px 10px;
    background: #f0f4ff;
    color: #667eea;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.course-card-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 12px;
    line-height: 1.4;
}

.course-card-title a {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.3s ease;
}

.course-card-title a:hover {
    color: #667eea;
}

.instructor-small {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.instructor-small img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.instructor-small span {
    font-size: 0.85rem;
    color: #718096;
    font-weight: 500;
}

.course-excerpt {
    font-size: 0.9rem;
    color: #718096;
    line-height: 1.6;
    margin-bottom: 15px;
}

.course-meta-info {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.course-meta-info span {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: #718096;
}

.course-meta-info i {
    color: #667eea;
}

.course-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.price-label {
    font-size: 1.4rem;
    font-weight: 700;
    color: #667eea;
}

.price-label.free {
    color: #48bb78;
}

.btn-detail {
    padding: 8px 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 80px 20px;
}

.no-results i {
    font-size: 5rem;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.no-results h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
}

.no-results p {
    color: #718096;
    font-size: 1.05rem;
    margin-bottom: 30px;
}

.btn-reset-search {
    display: inline-block;
    padding: 12px 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* Responsive */
@media (max-width: 1024px) {
    .courses-layout {
        grid-template-columns: 1fr;
    }

    .filter-sidebar {
        position: relative;
    }
}

@media (max-width: 768px) {
    .header-content h1 {
        font-size: 2.2rem;
    }

    .courses-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }

    .courses-grid-catalog {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush