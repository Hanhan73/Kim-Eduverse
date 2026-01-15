@extends('layouts.student')

@section('title', 'My Courses - KIM Edutech')

@section('content')

<!-- Page Header -->
<section class="page-header-my-courses">
    <div class="container">
        <div class="header-content">
            <h1><i class="fas fa-book-reader"></i> Kursus Saya</h1>
            <p>Kelola semua kursus yang Anda ikuti</p>
        </div>
    </div>
</section>

<!-- Main Content dengan spacing yang lebih baik -->
<section class="my-courses-content">
    <div class="container">

        <!-- Pending Payments Alert dengan margin bawah yang lebih konsisten -->
        @if($pendingPayments->count() > 0)
        <div class="pending-payments-alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Pembayaran Tertunda ({{ $pendingPayments->count() }})</h3>
            </div>
            <p>Anda memiliki {{ $pendingPayments->count() }} kursus yang menunggu pembayaran. Selesaikan pembayaran
                untuk mulai belajar.</p>

            <div class="pending-courses-grid">
                @foreach($pendingPayments as $pending)
                <div class="pending-course-card">
                    <div class="pending-course-image">
                        @if($pending->course->thumbnail)
                        <img src="{{ asset('storage/' . $pending->course->thumbnail) }}"
                            alt="{{ $pending->course->title }}">
                        @else
                        <div class="placeholder-image">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        @endif
                        <span class="pending-badge">Menunggu Pembayaran</span>
                    </div>
                    <div class="pending-course-info">
                        <h4>{{ $pending->course->title }}</h4>
                        <p class="instructor-name">
                            <i class="fas fa-user"></i> {{ $pending->course->instructor->name }}
                        </p>
                        <div class="pending-meta">
                            <span class="pending-date">
                                <i class="fas fa-clock"></i>
                                Terdaftar {{ $pending->created_at->diffForHumans() }}
                            </span>
                            <span class="pending-price">
                                Rp {{ number_format($pending->payment_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pending-actions">
                            <a href="{{ route('edutech.payment.show', $pending->id) }}" class="btn-pay-now">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </a>
                            <a href="{{ route('edutech.courses.detail', $pending->course->slug) }}"
                                class="btn-view-course">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tabs Container dengan margin atas yang konsisten -->
        <div class="tabs-container">
            <div class="tabs-nav">
                <button class="tab-btn active" onclick="switchTab('active')">
                    <i class="fas fa-play-circle"></i> Kursus Aktif
                    <span class="tab-badge">{{ $activeCourses->count() }}</span>
                </button>
                <button class="tab-btn" onclick="switchTab('completed')">
                    <i class="fas fa-check-circle"></i> Kursus Selesai
                    <span class="tab-badge badge-success">{{ $completedCourses->count() }}</span>
                </button>
            </div>

            <!-- Active Courses Tab -->
            <div id="active-tab" class="tab-content active">
                @if($activeCourses->count() > 0)
                <div class="active-courses-grid">
                    @foreach($activeCourses as $enrollment)
                    <div class="active-course-card">
                        <div class="course-thumbnail-wrapper">
                            @if($enrollment->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}"
                                alt="{{ $enrollment->course->title }}">
                            @else
                            <div class="placeholder-thumbnail">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            @endif

                            <!-- Progress Overlay -->
                            <div class="progress-overlay">
                                <div class="circular-progress">
                                    <svg class="progress-ring" width="60" height="60">
                                        <circle class="progress-ring-circle" stroke="#667eea" stroke-width="4"
                                            fill="transparent" r="26" cx="30" cy="30"
                                            style="stroke-dasharray: {{ 163.28 * $enrollment->progress_percentage / 100 }} 163.28" />
                                    </svg>
                                    <span class="progress-text">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="course-card-content">
                            <span class="course-category">{{ $enrollment->course->category }}</span>

                            <h3 class="course-title">{{ $enrollment->course->title }}</h3>

                            <p class="instructor-info">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $enrollment->course->instructor->name }}
                            </p>

                            <div class="course-stats">
                                <span><i class="fas fa-clock"></i> {{ $enrollment->course->duration_hours }}h</span>
                                <span><i class="fas fa-signal"></i> {{ ucfirst($enrollment->course->level) }}</span>
                            </div>

                            <div class="progress-bar-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $enrollment->progress_percentage }}%">
                                    </div>
                                </div>
                                <span class="progress-label">
                                    {{ $enrollment->progress_percentage }}% Selesai
                                </span>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('edutech.courses.learn', $enrollment->course->slug) }}"
                                    class="btn-continue">
                                    <i class="fas fa-play-circle"></i>
                                    {{ $enrollment->progress_percentage > 0 ? 'Lanjutkan' : 'Mulai' }} Belajar
                                </a>
                                <a href="{{ route('edutech.courses.detail', $enrollment->course->slug) }}"
                                    class="btn-view-outline">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            </div>

                            <div class="enrollment-date">
                                <i class="fas fa-calendar-check"></i>
                                Terdaftar: {{ $enrollment->enrolled_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>Belum Ada Kursus Aktif</h3>
                    <p>Mulai perjalanan belajar Anda dengan mendaftar kursus pertama</p>
                    <a href="{{ route('edutech.courses.index') }}" class="btn-browse-courses">
                        <i class="fas fa-search"></i> Jelajahi Kursus
                    </a>
                </div>
                @endif
            </div>

            <!-- Completed Courses Tab -->
            <div id="completed-tab" class="tab-content">
                @if($completedCourses->count() > 0)
                <div class="active-courses-grid">
                    @foreach($completedCourses as $enrollment)
                    <div class="active-course-card completed-card">
                        <div class="course-thumbnail-wrapper">
                            @if($enrollment->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}"
                                alt="{{ $enrollment->course->title }}">
                            @else
                            <div class="placeholder-thumbnail">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            @endif

                            <!-- Completed Badge -->
                            <div class="completed-overlay">
                                <div class="completed-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Selesai</span>
                                </div>
                            </div>
                        </div>

                        <div class="course-card-content">
                            @if($enrollment->certificate_issued_at)
                            <span class="certificate-badge">
                                <i class="fas fa-certificate"></i> Bersertifikat
                            </span>
                            @endif

                            <span class="course-category">{{ $enrollment->course->category }}</span>

                            <h3 class="course-title">{{ $enrollment->course->title }}</h3>

                            <p class="instructor-info">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $enrollment->course->instructor->name }}
                            </p>

                            <div class="course-stats">
                                <span><i class="fas fa-clock"></i> {{ $enrollment->course->duration_hours }}h</span>
                                <span><i class="fas fa-signal"></i> {{ ucfirst($enrollment->course->level) }}</span>
                            </div>

                            <div class="progress-bar-container">
                                <div class="progress-bar">
                                    <div class="progress-fill completed" style="width: 100%"></div>
                                </div>
                                <span class="progress-label">
                                    100% Selesai
                                </span>
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('edutech.courses.learn', $enrollment->course->slug) }}"
                                    class="btn-continue btn-completed">
                                    <i class="fas fa-eye"></i> Review Kursus
                                </a>
                                @if($enrollment->certificate_issued_at)
                                <a href="{{ route('edutech.student.certificates') }}"
                                    class="btn-view-outline btn-certificate">
                                    <i class="fas fa-download"></i> Sertifikat
                                </a>
                                @endif
                            </div>

                            <div class="enrollment-date">
                                <i class="fas fa-check-circle"></i>
                                Selesai:
                                {{ $enrollment->completed_at ? $enrollment->completed_at->format('d M Y') : '-' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Belum Ada Kursus yang Selesai</h3>
                    <p>Selesaikan kursus yang kamu ikuti untuk mendapatkan sertifikat!</p>
                    <a href="#" onclick="switchTab('active'); return false;" class="btn-browse-courses">
                        <i class="fas fa-book"></i> Lihat Kursus Aktif
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Page Header dengan padding yang lebih proporsional */
.page-header-my-courses {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px 0;
    /* Dikurangi sedikit untuk keseimbangan */
    color: white;
    margin-bottom: 40px;
    /* Tambahkan margin bawah */
}

.page-header-my-courses .header-content {
    text-align: center;
}

.page-header-my-courses h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.page-header-my-courses p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.my-courses-content {
    background: #f7fafc;
}

/* Pending Payments Alert dengan margin yang lebih baik */
.pending-payments-alert {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 40px;
    /* Jarak ke bawah yang konsisten */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #ff9800;
}

.pending-payments-alert .alert-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.pending-payments-alert .alert-header i {
    font-size: 2rem;
    color: #ff9800;
}

.pending-payments-alert .alert-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.5rem;
}

.pending-payments-alert p {
    color: #718096;
    margin-bottom: 25px;
    font-size: 1.1rem;
}

.pending-courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.pending-course-card {
    background: #fff8f0;
    border: 2px solid #ffeaa7;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.pending-course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.2);
}

.pending-course-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.pending-course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-image i {
    font-size: 4rem;
    color: white;
    opacity: 0.5;
}

.pending-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ff9800;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.pending-course-info {
    padding: 20px;
}

.pending-course-info h4 {
    color: #2d3748;
    margin-bottom: 10px;
    font-size: 1.2rem;
}

.instructor-name {
    color: #718096;
    margin-bottom: 15px;
}

.pending-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

.pending-date {
    color: #718096;
    font-size: 0.9rem;
}

.pending-price {
    color: #ff9800;
    font-weight: 700;
    font-size: 1.2rem;
}

.pending-actions {
    display: flex;
    gap: 10px;
}

.btn-pay-now {
    flex: 1;
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-pay-now:hover {
    background: linear-gradient(135deg, #f57c00, #e65100);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
}

.btn-view-course {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-view-course:hover {
    background: #667eea;
    color: white;
}

/* Tabs dengan shadow yang lebih halus */
.tabs-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    /* Shadow lebih halus */
}

.tabs-nav {
    display: flex;
    border-bottom: 2px solid #e2e8f0;
}

.tab-btn {
    flex: 1;
    padding: 20px;
    background: transparent;
    border: none;
    font-size: 1.1rem;
    font-weight: 600;
    color: #718096;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.tab-btn:hover {
    background: #f7fafc;
}

.tab-btn.active {
    color: #667eea;
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.tab-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #667eea;
    color: white;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

.tab-badge.badge-success {
    background: #48bb78;
}

.tab-content {
    display: none;
    padding: 30px;
}

.tab-content.active {
    display: block;
}

/* Active Courses Grid dengan gap yang sedikit lebih besar */
.active-courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
    /* Gap diperbesar untuk lebih bernapas */
}

.active-course-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.active-course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    /* Shadow lebih halus */
}

.course-thumbnail-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.course-thumbnail-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-thumbnail {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-thumbnail i {
    font-size: 4rem;
    color: white;
    opacity: 0.5;
}

.progress-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    padding: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.circular-progress {
    position: relative;
    width: 60px;
    height: 60px;
}

.progress-ring-circle {
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: 700;
    font-size: 0.9rem;
    color: #667eea;
}

/* Completed Badge */
.completed-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

.completed-badge {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
}

.certificate-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.course-card-content {
    padding: 20px;
}

.course-category {
    display: inline-block;
    padding: 4px 12px;
    background: #f0f4ff;
    color: #667eea;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.course-title {
    color: #2d3748;
    font-size: 1.2rem;
    margin-bottom: 10px;
    line-height: 1.4;
    font-weight: 700;
}

.instructor-info {
    color: #718096;
    margin-bottom: 15px;
    font-size: 0.95rem;
}

.course-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    color: #718096;
    font-size: 0.9rem;
}

.progress-bar-container {
    margin-bottom: 20px;
}

.progress-bar {
    height: 8px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-fill.completed {
    background: linear-gradient(90deg, #48bb78, #38a169);
}

.progress-label {
    font-size: 0.85rem;
    color: #718096;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-continue {
    flex: 1;
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-continue:hover {
    background: linear-gradient(135deg, #38a169, #2f855a);
    transform: translateY(-2px);
}

.btn-completed {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.btn-completed:hover {
    background: linear-gradient(135deg, #764ba2, #667eea);
}

.btn-view-outline {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-view-outline:hover {
    background: #667eea;
    color: white;
}

.btn-certificate {
    color: #fbbf24;
    border-color: #fbbf24;
}

.btn-certificate:hover {
    background: #fbbf24;
    color: white;
}

.enrollment-date {
    color: #718096;
    font-size: 0.85rem;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 5rem;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #2d3748;
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.empty-state p {
    color: #718096;
    margin-bottom: 25px;
}

.btn-browse-courses {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 15px 35px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-browse-courses:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

@media (max-width: 768px) {

    .pending-courses-grid,
    .active-courses-grid {
        grid-template-columns: 1fr;
    }

    .pending-actions {
        flex-direction: column;
    }

    .tabs-nav {
        flex-direction: column;
    }

    .tab-btn.active::after {
        height: 100%;
        width: 3px;
        bottom: 0;
        left: -2px;
        right: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
function switchTab(tab) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    // Add active class to clicked tab
    if (tab === 'active') {
        document.querySelector('.tab-btn:first-child').classList.add('active');
        document.getElementById('active-tab').classList.add('active');
    } else {
        document.querySelector('.tab-btn:last-child').classList.add('active');
        document.getElementById('completed-tab').classList.add('active');
    }
}
</script>
@endpush