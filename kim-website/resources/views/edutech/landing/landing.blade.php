@extends('layouts.app')

@section('title', 'KIM Edutech - Platform Pembelajaran Terlengkap')

@section('content')
<!-- Hero Section -->
<section class="edutech-hero-main">
    <div class="container">
        <div class="hero-wrapper">
            <div class="hero-content-main">
                <div class="hero-badge-animated">
                    <span class="badge-dot"></span>
                    <span>🚀 Platform LMS Terpercaya</span>
                </div>
                
                <h1 class="hero-title-main">
                    Belajar Tanpa Batas,<br>
                    Raih <span class="gradient-text-animated">Masa Depan</span> Cemerlang
                </h1>
                
                <p class="hero-desc-main">
                    Platform pembelajaran online terlengkap dengan ribuan kursus berkualitas. 
                    Tingkatkan skill Anda bersama instruktur berpengalaman dan dapatkan sertifikat resmi.
                </p>

                <div class="hero-cta">
                    <a href="{{ route('edutech.courses.index') }}" class="btn-hero-primary">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Jelajahi Kursus</span>
                    </a>
                    <a href="{{ route('edutech.register') }}" class="btn-hero-secondary">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Gratis</span>
                    </a>
                </div>

                <!-- Live Stats -->
                <div class="live-stats">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <div class="stat-num">{{ number_format($stats['students']) }}+</div>
                            <div class="stat-label">Peserta Aktif</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-book"></i></div>
                        <div class="stat-info">
                            <div class="stat-num">{{ $stats['courses'] }}+</div>
                            <div class="stat-label">Kursus Tersedia</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                        <div class="stat-info">
                            <div class="stat-num">{{ number_format($stats['certificates']) }}+</div>
                            <div class="stat-label">Sertifikat Dikeluarkan</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="visual-card card-1">
                    <div class="card-icon"><i class="fas fa-laptop-code"></i></div>
                    <div class="card-text">
                        <div class="card-title">Belajar Fleksibel</div>
                        <div class="card-desc">Akses 24/7</div>
                    </div>
                </div>
                <div class="visual-card card-2">
                    <div class="card-icon"><i class="fas fa-trophy"></i></div>
                    <div class="card-text">
                        <div class="card-title">Sertifikat Resmi</div>
                        <div class="card-desc">Diakui Industri</div>
                    </div>
                </div>
                <div class="visual-card card-3">
                    <div class="card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="card-text">
                        <div class="card-title">Instruktur Ahli</div>
                        <div class="card-desc">Berpengalaman</div>
                    </div>
                </div>
                <div class="hero-illustration">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Section -->
<section class="category-showcase">
    <div class="container">
        <div class="section-header-center">
            <h2>Jelajahi Kategori Kursus</h2>
            <p>Temukan kursus sesuai minat dan karir Anda</p>
        </div>

        <div class="category-grid">
            @foreach($categories as $category)
            <a href="{{ route('edutech.courses.index', ['category' => $category]) }}" class="category-card-modern">
                <div class="category-icon-wrapper">
                    @switch($category)
                        @case('Web Development')
                            <i class="fas fa-code"></i>
                            @break
                        @case('Data Science')
                            <i class="fas fa-chart-line"></i>
                            @break
                        @case('UI/UX Design')
                            <i class="fas fa-paint-brush"></i>
                            @break
                        @case('Mobile Development')
                            <i class="fas fa-mobile-alt"></i>
                            @break
                        @case('Digital Marketing')
                            <i class="fas fa-bullhorn"></i>
                            @break
                        @default
                            <i class="fas fa-book"></i>
                    @endswitch
                </div>
                <h3>{{ $category }}</h3>
                <p>{{ \App\Models\Course::where('category', $category)->published()->count() }} Kursus</p>
                <span class="card-arrow"><i class="fas fa-arrow-right"></i></span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="featured-courses-section">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <h2>Kursus Populer</h2>
                <p>Kursus pilihan dengan rating tertinggi</p>
            </div>
            <a href="{{ route('edutech.courses.index') }}" class="btn-view-all">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="featured-grid">
            @foreach($featuredCourses as $course)
            <div class="course-card-modern">
                <div class="course-thumbnail-wrapper">
                    <img src="{{ $course->thumbnail ?? 'https://via.placeholder.com/400x250/667eea/ffffff?text=' . urlencode($course->title) }}" 
                         alt="{{ $course->title }}">
                    <div class="course-overlay">
                        <a href="{{ route('edutech.courses.show', $course->slug) }}" class="btn-preview">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                    <span class="course-level-badge {{ $course->level }}">
                        {{ ucfirst($course->level) }}
                    </span>
                    @if($course->price == 0)
                    <span class="free-badge">GRATIS</span>
                    @endif
                </div>

                <div class="course-body">
                    <div class="course-category-tag">{{ $course->category }}</div>
                    
                    <h3 class="course-title-modern">
                        <a href="{{ route('edutech.courses.show', $course->slug) }}">
                            {{ $course->title }}
                        </a>
                    </h3>

                    <p class="course-desc-short">
                        {{ Str::limit($course->description, 100) }}
                    </p>

                    <div class="course-instructor-info">
                        <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}" 
                             alt="{{ $course->instructor->name }}"
                             class="instructor-avatar-small">
                        <span>{{ $course->instructor->name }}</span>
                    </div>

                    <div class="course-meta-row">
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $course->duration_hours }} Jam</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-users"></i>
                            <span>{{ $course->enrollments_count }} Peserta</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span>4.8</span>
                        </div>
                    </div>

                    <div class="course-footer-modern">
                        <div class="course-price-modern">
                            @if($course->price == 0)
                                <span class="price-free">GRATIS</span>
                            @else
                                <span class="price-amount">{{ $course->formatted_price }}</span>
                            @endif
                        </div>
                        <a href="{{ route('edutech.courses.show', $course->slug) }}" class="btn-enroll-modern">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Modern -->
<section class="features-modern-section">
    <div class="container">
        <div class="section-header-center">
            <h2>Keunggulan Platform Kami</h2>
            <p>Pembelajaran online dengan standar profesional</p>
        </div>

        <div class="features-modern-grid">
            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3>Materi Terstruktur</h3>
                <p>Konten pembelajaran disusun secara sistematis dari basic hingga advanced</p>
            </div>

            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                    <i class="fas fa-video"></i>
                </div>
                <h3>Video HD Quality</h3>
                <p>Materi video berkualitas tinggi dengan penjelasan yang mudah dipahami</p>
            </div>

            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #f56565, #e53e3e);">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Pre & Post Test</h3>
                <p>Evaluasi pemahaman dengan quiz yang terstruktur dan terukur</p>
            </div>

            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #3182ce, #2c5aa0);">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Sertifikat Digital</h3>
                <p>Dapatkan sertifikat resmi yang diakui industri setelah lulus</p>
            </div>

            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #805ad5, #6b46c1);">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Support 24/7</h3>
                <p>Tim support siap membantu kendala teknis kapan saja</p>
            </div>

            <div class="feature-modern-card">
                <div class="feature-icon-circle" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                    <i class="fas fa-infinity"></i>
                </div>
                <h3>Lifetime Access</h3>
                <p>Akses selamanya untuk semua materi yang sudah dibeli</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-modern">
    <div class="container">
        <div class="cta-content-modern">
            <div class="cta-icon-large">
                <i class="fas fa-rocket"></i>
            </div>
            <h2>Siap Memulai Perjalanan Belajar?</h2>
            <p>Bergabunglah dengan ribuan peserta lainnya dan tingkatkan skill Anda hari ini</p>
            <div class="cta-buttons-modern">
                <a href="{{ route('edutech.courses.index') }}" class="btn-cta-primary">
                    <i class="fas fa-graduation-cap"></i>
                    Lihat Semua Kursus
                </a>
                <a href="{{ route('edutech.register') }}" class="btn-cta-secondary">
                    <i class="fas fa-user-plus"></i>
                    Daftar Gratis
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Modern Hero */
.edutech-hero-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 120px 0 100px;
    position: relative;
    overflow: hidden;
}

.edutech-hero-main::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 80%;
    height: 150%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(5deg); }
}

.hero-wrapper {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.hero-badge-animated {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 10px 24px;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-dot {
    width: 8px;
    height: 8px;
    background: #48bb78;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
}

.hero-title-main {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.1;
    color: white;
    margin-bottom: 30px;
}

.gradient-text-animated {
    background: linear-gradient(90deg, #fff, #f0f0f0, #fff);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    to { background-position: 200% center; }
}

.hero-desc-main {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.95);
    line-height: 1.8;
    margin-bottom: 40px;
    max-width: 600px;
}

.hero-cta {
    display: flex;
    gap: 20px;
    margin-bottom: 60px;
    flex-wrap: wrap;
}

.btn-hero-primary,
.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 36px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.05rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-hero-primary {
    background: white;
    color: #667eea;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-hero-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.btn-hero-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 2px solid white;
    backdrop-filter: blur(10px);
}

.btn-hero-secondary:hover {
    background: white;
    color: #667eea;
}

.live-stats {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 20px 30px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: white;
    color: #667eea;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-info {
    color: white;
}

.stat-num {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 0.85rem;
    opacity: 0.9;
}

/* Hero Visual Cards */
.hero-visual {
    position: relative;
    height: 500px;
}

.visual-card {
    position: absolute;
    background: white;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 15px;
    animation: cardFloat 3s infinite ease-in-out;
}

.card-1 {
    top: 20px;
    left: 0;
    animation-delay: 0s;
}

.card-2 {
    top: 150px;
    right: 50px;
    animation-delay: 1s;
}

.card-3 {
    bottom: 100px;
    left: 30px;
    animation-delay: 2s;
}

@keyframes cardFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

.card-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.card-title {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
}

.card-desc {
    font-size: 0.85rem;
    color: #718096;
}

.hero-illustration {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 15rem;
    color: rgba(255, 255, 255, 0.1);
    z-index: -1;
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Category Showcase */
.category-showcase {
    padding: 100px 0;
    background: #f8f9fa;
}

.section-header-center {
    text-align: center;
    margin-bottom: 60px;
}

.section-header-center h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.section-header-center p {
    font-size: 1.1rem;
    color: #718096;
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
}

.category-card-modern {
    background: white;
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.category-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.category-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.category-card-modern:hover::before {
    transform: scaleX(1);
}

.category-icon-wrapper {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.category-card-modern:hover .category-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.category-card-modern h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.category-card-modern p {
    color: #718096;
    font-size: 0.9rem;
}

.card-arrow {
    display: inline-block;
    margin-top: 15px;
    color: #667eea;
    font-size: 1.2rem;
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.category-card-modern:hover .card-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* Featured Courses */
.featured-courses-section {
    padding: 100px 0;
    background: white;
}

.section-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 50px;
}

.section-header-flex h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.section-header-flex p {
    color: #718096;
    font-size: 1.05rem;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 35px;
}

.course-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.course-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.course-thumbnail-wrapper {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.course-thumbnail-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.course-card-modern:hover .course-thumbnail-wrapper img {
    transform: scale(1.1);
}

.course-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.course-card-modern:hover .course-overlay {
    opacity: 1;
}

.btn-preview {
    padding: 12px 28px;
    background: white;
    color: #667eea;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.course-level-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 16px;
    background: white;
    color: #667eea;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.course-level-badge.beginner { color: #48bb78; }
.course-level-badge.intermediate { color: #ed8936; }
.course-level-badge.advanced { color: #e53e3e; }

.free-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 16px;
    background: #48bb78;
    color: white;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}

.course-body {
    padding: 25px;
}

.course-category-tag {
    display: inline-block;
    padding: 5px 12px;
    background: #f0f4ff;
    color: #667eea;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.course-title-modern {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.course-title-modern a {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.3s ease;
}

.course-title-modern a:hover {
    color: #667eea;
}

.course-desc-short {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.course-instructor-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.instructor-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.course-instructor-info span {
    color: #4a5568;
    font-weight: 500;
    font-size: 0.9rem;
}

.course-meta-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #718096;
    font-size: 0.85rem;
}

.meta-item i {
    color: #667eea;
}

.course-footer-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.course-price-modern .price-amount {
    font-size: 1.6rem;
    font-weight: 700;
    color: #667eea;
}

.course-price-modern .price-free {
    font-size: 1.3rem;
    font-weight: 700;
    color: #48bb78;
}

.btn-enroll-modern {
    padding: 10px 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-enroll-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

/* Features Modern */
.features-modern-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.features-modern-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 35px;
}

.feature-modern-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.feature-modern-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.feature-icon-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.2rem;
    margin: 0 auto 25px;
    transition: all 0.3s ease;
}

.feature-modern-card:hover .feature-icon-circle {
    transform: rotate(360deg) scale(1.1);
}

.feature-modern-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.feature-modern-card p {
    color: #718096;
    line-height: 1.7;
    font-size: 0.95rem;
}

/* CTA Modern */
.cta-modern {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.cta-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -20%;
    width: 100%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
}

.cta-content-modern {
    text-align: center;
    position: relative;
    z-index: 2;
    color: white;
}

.cta-icon-large {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 30px;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.cta-content-modern h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-content-modern p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 40px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons-modern {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary,
.btn-cta-secondary {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.05rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-cta-primary {
    background: white;
    color: #667eea;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-cta-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.btn-cta-secondary {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border: 2px solid white;
    backdrop-filter: blur(10px);
}

.btn-cta-secondary:hover {
    background: white;
    color: #667eea;
}

/* Responsive */
@media (max-width: 1024px) {
    .hero-wrapper {
        grid-template-columns: 1fr;
        gap: 60px;
    }

    .hero-title-main {
        font-size: 3rem;
    }

    .hero-visual {
        height: 400px;
    }
}

@media (max-width: 768px) {
    .hero-title-main {
        font-size: 2.5rem;
    }

    .hero-desc-main {
        font-size: 1.05rem;
    }

    .live-stats {
        justify-content: center;
    }

    .section-header-flex {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }

    .featured-grid {
        grid-template-columns: 1fr;
    }

    .cta-content-modern h2 {
        font-size: 2.2rem;
    }

    .cta-buttons-modern {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush