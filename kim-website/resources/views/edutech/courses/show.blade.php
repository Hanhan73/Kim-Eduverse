@extends('layouts.edutech')

@section('title', $course->title . ' - KIM Edutech')

@section('content')
<!-- Course Hero -->
<section class="course-hero-detail">
    <div class="container">
        <div class="course-hero-grid">
            <div class="hero-info">
                <div class="breadcrumb-detail">
                    <a href="{{ route('edutech.landing') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('edutech.courses.index') }}">Kursus</a>
                    <span>/</span>
                    <a href="{{ route('edutech.courses.index', ['category' => $course->category]) }}">
                        {{ $course->category }}
                    </a>
                </div>

                <span class="category-badge-large">{{ $course->category }}</span>

                <h1 class="course-title-detail">{{ $course->title }}</h1>

                <p class="course-description-short">{{ $course->description }}</p>

                <div class="course-stats-row">
                    <div class="stat-item-detail">
                        <i class="fas fa-star"></i>
                        <span><strong>4.8</strong> (250 ulasan)</span>
                    </div>
                    <div class="stat-item-detail">
                        <i class="fas fa-users"></i>
                        <span><strong>{{ $course->enrollments_count }}</strong> peserta</span>
                    </div>
                    <div class="stat-item-detail">
                        <i class="fas fa-clock"></i>
                        <span><strong>{{ $course->duration_hours }} jam</strong> materi</span>
                    </div>
                    <div class="stat-item-detail">
                        <i class="fas fa-signal"></i>
                        <span><strong>{{ ucfirst($course->level) }}</strong></span>
                    </div>
                </div>

                <div class="instructor-info-large">
                    <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) }}"
                        alt="{{ $course->instructor->name }}">
                    <div>
                        <div class="instructor-label">Instruktur</div>
                        <div class="instructor-name-large">{{ $course->instructor->name }}</div>
                        @if($course->instructor->bio)
                        <div class="instructor-bio">{{ Str::limit($course->instructor->bio, 100) }}</div>
                        @endif
                    </div>
                </div>

                <div class="updated-info">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Terakhir diperbarui: {{ $course->updated_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="hero-card">
                <div class="course-preview-card">
                    <div class="preview-image">
                        <img src="{{ $course->thumbnail ?? 'https://via.placeholder.com/500x300/667eea/ffffff?text=' . urlencode($course->title) }}"
                            alt="{{ $course->title }}">
                        <div class="play-overlay">
                            <i class="fas fa-play-circle"></i>
                            <span>Preview Course</span>
                        </div>
                    </div>

                    <div class="preview-content">
                        <div class="price-section">
                            @if($course->price == 0)
                            <div class="price-free-large">GRATIS</div>
                            @else
                            <div class="price-large">{{ $course->formatted_price }}</div>
                            <div class="price-note">Akses selamanya</div>
                            @endif
                        </div>

                        @auth
                        @if(isset($isInstructor) && $isInstructor)
                            <a href="{{ route('edutech.courses.learn', $course->slug) }}" class="btn-enroll">
                                <i class="fas fa-eye"></i> Preview Course (Instructor Mode)
                            </a>
                        @elseif($isEnrolled)
                            <a href="{{ route('edutech.courses.learn', $course->slug) }}" class="btn-enroll">
                                <i class="fas fa-play"></i> Continue Learning
                            </a>
                        @else
                            <form action="{{ route('edutech.courses.enroll', $course->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-enroll">
                                    {{ $course->price > 0 ? 'Enroll - Rp ' . number_format($course->price, 0, ',', '.') : 'Enroll Free' }}
                                </button>
                            </form>
                        @endif
                        @if($enrollment)
                        <div class="progress-info">
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar-fill" style="width: {{ $enrollment->progress_percentage }}%">
                                </div>
                            </div>
                            <span>Progress: {{ $enrollment->progress_percentage }}%</span>
                        </div>
                        @endif
                        @else
                        <a href="{{ route('edutech.login') }}" class="btn-enroll-large">
                            <i class="fas fa-sign-in-alt"></i>
                            Login untuk Mendaftar
                        </a>
                        @endauth

                        <div class="course-includes">
                            <h4>Kursus ini termasuk:</h4>
                            <ul>
                                <li><i class="fas fa-video"></i> {{ $course->materials->count() }} materi pembelajaran
                                </li>
                                <li><i class="fas fa-file-pdf"></i> Materi PDF yang dapat didownload</li>
                                <li><i class="fas fa-infinity"></i> Akses selamanya</li>
                                <li><i class="fas fa-mobile-alt"></i> Akses di mobile & desktop</li>
                                <li><i class="fas fa-certificate"></i> Sertifikat digital</li>
                                @if($course->liveSessions->count() > 0)
                                <li><i class="fas fa-video"></i> {{ $course->liveSessions->count() }} sesi live</li>
                                @endif
                            </ul>
                        </div>

                        <div class="share-section">
                            <button class="btn-share"><i class="fas fa-share-alt"></i> Bagikan</button>
                            <button class="btn-wishlist"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Course Content Tabs -->
<section class="course-content-section">
    <div class="container">
        <div class="content-layout">
            <div class="content-main">
                <!-- Tabs -->
                <div class="tabs-navigation">
                    <button class="tab-btn active" data-tab="overview">
                        <i class="fas fa-info-circle"></i> Overview
                    </button>
                    <button class="tab-btn" data-tab="curriculum">
                        <i class="fas fa-list"></i> Kurikulum
                    </button>
                    <button class="tab-btn" data-tab="instructor">
                        <i class="fas fa-chalkboard-teacher"></i> Instruktur
                    </button>
                    <button class="tab-btn" data-tab="reviews">
                        <i class="fas fa-star"></i> Ulasan
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="tabs-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane active" id="overview">
                        <h2>Tentang Kursus Ini</h2>
                        <div class="course-description-full">
                            {{ $course->description }}
                        </div>

                        <h3>Yang Akan Anda Pelajari</h3>
                        <div class="learning-objectives">
                            <div class="objective-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Menguasai fundamental {{ $course->category }}</span>
                            </div>
                            <div class="objective-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Membangun project nyata untuk portfolio</span>
                            </div>
                            <div class="objective-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Siap berkarir sebagai profesional</span>
                            </div>
                            <div class="objective-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Mendapatkan sertifikat resmi</span>
                            </div>
                        </div>

                        <h3>Persyaratan</h3>
                        <ul class="requirements-list">
                            <li>Komputer/laptop dengan koneksi internet</li>
                            <li>Tidak diperlukan pengalaman sebelumnya</li>
                            <li>Motivasi untuk belajar dan berkembang</li>
                        </ul>

                        <h3>Untuk Siapa Kursus Ini?</h3>
                        <ul class="target-audience">
                            <li><i class="fas fa-user-graduate"></i> Pemula yang ingin memulai karir di
                                {{ $course->category }}
                            </li>
                            <li><i class="fas fa-user-tie"></i> Profesional yang ingin upgrade skill</li>
                            <li><i class="fas fa-users"></i> Siapa saja yang tertarik dengan {{ $course->category }}
                            </li>
                        </ul>
                    </div>

                    <!-- Curriculum Tab -->
                    <div class="tab-pane" id="curriculum">
                        <h2>Kurikulum Lengkap</h2>
                        <div class="curriculum-info">
                            <span><i class="fas fa-book"></i> {{ $course->materials->count() }} Materi</span>
                            <span><i class="fas fa-clock"></i> {{ $course->duration_hours }} Jam Total</span>
                        </div>

                        <div class="curriculum-accordion">
                            @foreach($course->materials->groupBy(function($item) { return floor(($item->order - 1) / 5)
                            + 1; }) as $section => $materials)
                            <div class="accordion-item">
                                <button class="accordion-header">
                                    <div class="header-left">
                                        <i class="fas fa-folder"></i>
                                        <span>Section {{ $section }}</span>
                                    </div>
                                    <div class="header-right">
                                        <span>{{ $materials->count() }} materi</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </button>
                                <div class="accordion-content">
                                    @foreach($materials as $material)
                                    <div class="material-item">
                                        <div class="material-info">
                                            @switch($material->type)
                                            @case('video')
                                            <i class="fas fa-play-circle"></i>
                                            @break
                                            @case('pdf')
                                            <i class="fas fa-file-pdf"></i>
                                            @break
                                            @default
                                            <i class="fas fa-file"></i>
                                            @endswitch
                                            <span>{{ $material->title }}</span>
                                        </div>
                                        <div class="material-meta">
                                            @if($material->is_preview)
                                            <span class="preview-label">Preview</span>
                                            @endif
                                            <span class="duration">{{ $material->duration_minutes }} menit</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>


                    </div>

                    <!-- Instructor Tab -->
                    <div class="tab-pane" id="instructor">
                        <div class="instructor-profile">
                            <div class="instructor-header">
                                <img src="{{ $course->instructor->avatar ?? 'https://ui-avatars.com/api/?size=120&name=' . urlencode($course->instructor->name) }}"
                                    alt="{{ $course->instructor->name }}">
                                <div>
                                    <h2>{{ $course->instructor->name }}</h2>
                                    <p>Instruktur Profesional</p>
                                </div>
                            </div>

                            <div class="instructor-stats">
                                <div class="stat-box-instructor">
                                    <i class="fas fa-star"></i>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Rating</div>
                                    </div>
                                </div>
                                <div class="stat-box-instructor">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <div class="stat-num">
                                            {{ $course->instructor->coursesAsInstructor->sum(function($c) { return $c->enrollments->count(); }) }}
                                        </div>
                                        <div class="stat-label">Total Peserta</div>
                                    </div>
                                </div>
                                <div class="stat-box-instructor">
                                    <i class="fas fa-book"></i>
                                    <div>
                                        <div class="stat-num">{{ $course->instructor->coursesAsInstructor->count() }}
                                        </div>
                                        <div class="stat-label">Kursus</div>
                                    </div>
                                </div>
                            </div>

                            @if($course->instructor->bio)
                            <div class="instructor-bio-full">
                                <h3>Tentang Instruktur</h3>
                                <p>{{ $course->instructor->bio }}</p>
                            </div>
                            @endif

                            <div class="instructor-courses">
                                <h3>Kursus Lainnya</h3>
                                <div class="courses-list-small">
                                    @foreach($course->instructor->coursesAsInstructor()->published()->where('id', '!=',
                                    $course->id)->take(3)->get() as $otherCourse)
                                    <a href="{{ route('edutech.courses.detail', $otherCourse->slug) }}"
                                        class="course-item-small">
                                        <img src="{{ $otherCourse->thumbnail ?? 'https://via.placeholder.com/100x60' }}"
                                            alt="{{ $otherCourse->title }}">
                                        <div>
                                            <h4>{{ $otherCourse->title }}</h4>
                                            <p>{{ $otherCourse->enrollments_count }} peserta</p>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane" id="reviews">
                        <div class="reviews-header">
                            <div class="rating-summary">
                                <div class="big-rating">4.8</div>
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <p>250 ulasan</p>
                            </div>

                            <div class="rating-breakdown">
                                <div class="rating-row">
                                    <span>5 ★</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" style="width: 85%"></div>
                                    </div>
                                    <span>85%</span>
                                </div>
                                <div class="rating-row">
                                    <span>4 ★</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" style="width: 10%"></div>
                                    </div>
                                    <span>10%</span>
                                </div>
                                <div class="rating-row">
                                    <span>3 ★</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" style="width: 3%"></div>
                                    </div>
                                    <span>3%</span>
                                </div>
                                <div class="rating-row">
                                    <span>2 ★</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" style="width: 1%"></div>
                                    </div>
                                    <span>1%</span>
                                </div>
                                <div class="rating-row">
                                    <span>1 ★</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" style="width: 1%"></div>
                                    </div>
                                    <span>1%</span>
                                </div>
                            </div>
                        </div>

                        <div class="reviews-list">
                            <!-- Sample reviews -->
                            <div class="review-item">
                                <div class="review-header">
                                    <img src="https://ui-avatars.com/api/?name=Ahmad+Rizki" alt="Ahmad Rizki">
                                    <div>
                                        <h4>Ahmad Rizki</h4>
                                        <div class="review-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="review-date">2 minggu lalu</span>
                                </div>
                                <p class="review-text">
                                    Kursus yang sangat bagus! Materi dijelaskan dengan detail dan mudah dipahami.
                                    Instruktur sangat responsif dalam menjawab pertanyaan. Highly recommended!
                                </p>
                            </div>

                            <div class="review-item">
                                <div class="review-header">
                                    <img src="https://ui-avatars.com/api/?name=Siti+Nurhaliza" alt="Siti Nurhaliza">
                                    <div>
                                        <h4>Siti Nurhaliza</h4>
                                        <div class="review-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="review-date">1 bulan lalu</span>
                                </div>
                                <p class="review-text">
                                    Worth it banget! Setelah menyelesaikan kursus ini, saya langsung dapat project.
                                    Materi sangat up-to-date dan sesuai dengan kebutuhan industri.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="content-sidebar">
                @if($course->liveSessions->count() > 0)
                <div class="sidebar-card">
                    <h3><i class="fas fa-video"></i> Live Sessions</h3>
                    @foreach($course->liveSessions->take(3) as $session)
                    <div class="session-item">
                        <div class="session-date">
                            <div class="date-num">{{ $session->scheduled_at->format('d') }}</div>
                            <div class="date-month">{{ $session->scheduled_at->format('M') }}</div>
                        </div>
                        <div class="session-info">
                            <h4>{{ $session->title }}</h4>
                            <p>{{ $session->scheduled_at->format('H:i') }} WIB • {{ $session->duration_minutes }} menit
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="sidebar-card">
                    <h3><i class="fas fa-share-alt"></i> Bagikan Kursus</h3>
                    <div class="social-share">
                        <a href="#" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Related Courses -->
@if($relatedCourses->count() > 0)
<section class="related-courses">
    <div class="container">
        <h2>Kursus Terkait</h2>
        <div class="related-grid">
            @foreach($relatedCourses as $related)
            <div class="course-card-related">
                <a href="{{ route('edutech.courses.detail', $related->slug) }}">
                    <img src="{{ $related->thumbnail ?? 'https://via.placeholder.com/300x180' }}"
                        alt="{{ $related->title }}">
                </a>
                <div class="card-body-related">
                    <h3><a href="{{ route('edutech.courses.detail', $related->slug) }}">{{ $related->title }}</a></h3>
                    <p class="instructor-name">{{ $related->instructor->name }}</p>
                    <div class="card-footer-related">
                        <span class="rating"><i class="fas fa-star"></i> 4.8</span>
                        <span class="price">{{ $related->price == 0 ? 'GRATIS' : $related->formatted_price }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    /* Course Hero Detail */
    .course-hero-detail {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 60px 0 80px;
        color: white;
    }

    .course-hero-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 60px;
    }

    .breadcrumb-detail {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .breadcrumb-detail a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
    }

    .breadcrumb-detail a:hover {
        text-decoration: underline;
    }

    .category-badge-large {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .course-title-detail {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .course-description-short {
        font-size: 1.15rem;
        opacity: 0.95;
        line-height: 1.7;
        margin-bottom: 30px;
    }

    .course-stats-row {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .stat-item-detail {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
    }

    .stat-item-detail i {
        font-size: 1.2rem;
        color: #fbbf24;
    }

    .instructor-info-large {
        display: flex;
        align-items: center;
        gap: 20px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .instructor-info-large img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid white;
    }

    .instructor-label {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .instructor-name-large {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .instructor-bio {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .updated-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Preview Card */
    .course-preview-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: sticky;
        top: 20px;
    }

    .preview-image {
        position: relative;
        height: 250px;
        overflow: hidden;
        cursor: pointer;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(102, 126, 234, 0.9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
    }

    .preview-image:hover .play-overlay {
        opacity: 1;
    }

    .play-overlay i {
        font-size: 4rem;
        margin-bottom: 15px;
    }

    .play-overlay span {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .preview-content {
        padding: 30px;
    }

    .price-section {
        margin-bottom: 25px;
    }

    .price-large {
        font-size: 2.5rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 5px;
    }

    .price-free-large {
        font-size: 2rem;
        font-weight: 800;
        color: #48bb78;
    }

    .price-note {
        color: #718096;
        font-size: 0.9rem;
    }

    .btn-enroll-large {
        width: 100%;
        padding: 18px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-enroll-large:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-enroll-large.enrolled {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }

    .progress-info {
        margin-bottom: 20px;
    }

    .progress-bar-wrapper {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-info span {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
    }

    .course-includes {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .course-includes h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .course-includes ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .course-includes li {
        padding: 10px 0;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
    }

    .course-includes i {
        color: #667eea;
        font-size: 1.1rem;
    }

    .share-section {
        display: flex;
        gap: 10px;
    }

    .btn-share,
    .btn-wishlist {
        flex: 1;
        padding: 12px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-share:hover,
    .btn-wishlist:hover {
        border-color: #667eea;
        color: #667eea;
    }

    /* Course Content Section */
    .course-content-section {
        padding: 60px 0 100px;
        background: #f8f9fa;
    }

    .content-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 40px;
    }

    .content-main {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    /* Tabs */
    .tabs-navigation {
        display: flex;
        gap: 15px;
        margin-bottom: 40px;
        border-bottom: 2px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 15px 25px;
        background: transparent;
        border: none;
        color: #718096;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .tab-btn:hover {
        color: #667eea;
    }

    .tab-btn.active {
        color: #667eea;
    }

    .tab-btn.active::after {
        transform: scaleX(1);
    }

    .tabs-content {
        color: #2d3748;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-pane h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #2d3748;
    }

    .tab-pane h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 40px;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .course-description-full {
        line-height: 1.8;
        color: #4a5568;
        font-size: 1.05rem;
        margin-bottom: 40px;
    }

    .learning-objectives {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .objective-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
        background: #f0f4ff;
        border-radius: 12px;
    }

    .objective-item i {
        color: #48bb78;
        font-size: 1.3rem;
        margin-top: 2px;
    }

    .objective-item span {
        color: #2d3748;
        font-weight: 500;
    }

    .requirements-list,
    .target-audience {
        list-style: none;
        padding: 0;
    }

    .requirements-list li,
    .target-audience li {
        padding: 12px 0;
        color: #4a5568;
        padding-left: 30px;
        position: relative;
    }

    .requirements-list li::before {
        content: '•';
        position: absolute;
        left: 10px;
        color: #667eea;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .target-audience li {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .target-audience i {
        color: #667eea;
        font-size: 1.2rem;
    }

    /* Curriculum */
    .curriculum-info {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .curriculum-info span {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #4a5568;
    }

    .curriculum-info i {
        color: #667eea;
    }

    .curriculum-accordion {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .accordion-item {
        border-bottom: 1px solid #e2e8f0;
    }

    .accordion-item:last-child {
        border-bottom: none;
    }

    .accordion-header {
        width: 100%;
        padding: 20px 25px;
        background: white;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .accordion-header:hover {
        background: #f8f9fa;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 700;
        color: #2d3748;
        font-size: 1.05rem;
    }

    .header-left i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #718096;
        font-size: 0.9rem;
    }

    .accordion-content {
        display: none;
        background: #f8f9fa;
        padding: 20px 25px;
    }

    .accordion-item.active .accordion-content {
        display: block;
    }

    .accordion-item.active .accordion-header .fa-chevron-down {
        transform: rotate(180deg);
    }

    .material-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .material-item:last-child {
        margin-bottom: 0;
    }

    .material-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #2d3748;
        font-weight: 500;
    }

    .material-info i {
        color: #667eea;
        font-size: 1.1rem;
    }

    .material-meta {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .preview-label {
        padding: 4px 12px;
        background: #48bb78;
        color: white;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .duration {
        color: #718096;
        font-size: 0.85rem;
    }

    .quizzes-section {
        margin-top: 40px;
        padding: 30px;
        background: #f0f4ff;
        border-radius: 16px;
    }

    .quizzes-section h3 {
        margin-top: 0;
    }

    .quiz-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .quiz-item i {
        color: #667eea;
        font-size: 2rem;
    }

    .quiz-info h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .quiz-info p {
        color: #718096;
        font-size: 0.9rem;
    }

    /* Instructor Profile */
    .instructor-profile {
        color: #2d3748;
    }

    .instructor-header {
        display: flex;
        align-items: center;
        gap: 25px;
        margin-bottom: 30px;
    }

    .instructor-header img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #e2e8f0;
    }

    .instructor-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .instructor-header p {
        color: #718096;
        font-size: 1.05rem;
    }

    .instructor-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-box-instructor {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .stat-box-instructor i {
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

    .stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #718096;
    }

    .instructor-bio-full {
        margin-bottom: 40px;
    }

    .instructor-bio-full p {
        line-height: 1.8;
        color: #4a5568;
        font-size: 1.05rem;
    }

    .courses-list-small {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .course-item-small {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .course-item-small:hover {
        background: white;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transform: translateX(5px);
    }

    .course-item-small img {
        width: 100px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .course-item-small h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .course-item-small p {
        font-size: 0.85rem;
        color: #718096;
    }

    /* Reviews */
    .reviews-header {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 40px;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
    }

    .rating-summary {
        text-align: center;
    }

    .big-rating {
        font-size: 4rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .rating-stars i {
        margin: 0 2px;
    }

    .rating-breakdown {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .rating-row {
        display: grid;
        grid-template-columns: 40px 1fr 50px;
        align-items: center;
        gap: 15px;
    }

    .rating-row span:first-child {
        font-weight: 600;
        color: #718096;
    }

    .rating-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .rating-fill {
        height: 100%;
        background: linear-gradient(90deg, #fbbf24, #f59e0b);
        border-radius: 10px;
    }

    .rating-row span:last-child {
        text-align: right;
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .review-item {
        padding: 25px;
        background: #f8f9fa;
        border-radius: 16px;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .review-header img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .review-header h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .review-stars {
        color: #fbbf24;
        font-size: 0.9rem;
    }

    .review-date {
        margin-left: auto;
        font-size: 0.85rem;
        color: #718096;
    }

    .review-text {
        color: #4a5568;
        line-height: 1.7;
    }

    /* Sidebar */
    .content-sidebar {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .sidebar-card {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .sidebar-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-card h3 i {
        color: #667eea;
    }

    .session-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .session-item:last-child {
        margin-bottom: 0;
    }

    .session-date {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .date-num {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
    }

    .date-month {
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .session-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .session-info p {
        font-size: 0.85rem;
        color: #718096;
    }

    .social-share {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .share-btn {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: white;
        text-decoration: none;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .share-btn.facebook {
        background: #1877f2;
    }

    .share-btn.twitter {
        background: #1da1f2;
    }

    .share-btn.whatsapp {
        background: #25d366;
    }

    .share-btn.linkedin {
        background: #0077b5;
    }

    /* Related Courses */
    .related-courses {
        padding: 80px 0;
        background: white;
    }

    .related-courses h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 40px;
        text-align: center;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .course-card-related {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .course-card-related:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .course-card-related img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .card-body-related {
        padding: 20px;
    }

    .card-body-related h3 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .card-body-related h3 a {
        color: #2d3748;
        text-decoration: none;
    }

    .card-body-related h3 a:hover {
        color: #667eea;
    }

    .instructor-name {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 15px;
    }

    .card-footer-related {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #718096;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .rating i {
        color: #fbbf24;
    }

    .price {
        font-weight: 700;
        color: #667eea;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .course-hero-grid {
            grid-template-columns: 1fr;
        }

        .hero-card {
            order: -1;
        }

        .course-preview-card {
            position: static;
        }

        .content-layout {
            grid-template-columns: 1fr;
        }

        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .course-title-detail {
            font-size: 2rem;
        }

        .course-stats-row {
            flex-direction: column;
            gap: 15px;
        }

        .learning-objectives {
            grid-template-columns: 1fr;
        }

        .instructor-stats {
            grid-template-columns: 1fr;
        }

        .reviews-header {
            grid-template-columns: 1fr;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab Navigation
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            // Add active class to clicked tab
            btn.classList.add('active');
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Accordion
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const item = header.parentElement;
            item.classList.toggle('active');
        });
    });
</script>
@endpush