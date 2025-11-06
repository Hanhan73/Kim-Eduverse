@extends('layouts.app')

@section('title', 'KIM Edutech - Platform Pembelajaran Online')

@section('content')
<!-- Hero Section -->
<section class="edutech-hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <span class="hero-badge">🎓 Platform Pembelajaran Digital</span>
                <h1 class="hero-title">
                    Tingkatkan Skill Anda dengan
                    <span class="gradient-text">KIM Edutech</span>
                </h1>
                <p class="hero-subtitle">
                    Platform Learning Management System terlengkap untuk pengembangan kompetensi 
                    dan skill profesional Anda. Belajar kapan saja, di mana saja.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('edutech.landing') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket"></i> Jelajahi Platform
                    </a>
                    <a href="{{ route('edutech.register') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-user-plus"></i> Daftar Gratis
                    </a>
                </div>
                
                <!-- Quick Stats -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Peserta Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Kursus Tersedia</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Tingkat Kepuasan</div>
                    </div>
                </div>
            </div>
            
            <div class="hero-image">
                <img src="https://via.placeholder.com/600x500/667eea/ffffff?text=Learning+Platform" alt="KIM Edutech">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Mengapa Memilih KIM Edutech?</h2>
            <p class="section-subtitle">Platform pembelajaran dengan fitur terlengkap</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3>Materi Berkualitas</h3>
                <p>Konten pembelajaran yang disusun oleh instruktur berpengalaman dan ahli di bidangnya</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Sertifikat Resmi</h3>
                <p>Dapatkan sertifikat digital yang diakui setelah menyelesaikan kursus</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #f56565, #e53e3e);">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Belajar Fleksibel</h3>
                <p>Akses materi 24/7 dan belajar sesuai dengan kecepatan Anda sendiri</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #3182ce, #2c5aa0);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3>Live Session</h3>
                <p>Interaksi langsung dengan instruktur melalui sesi live streaming</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #805ad5, #6b46c1);">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Pre & Post Test</h3>
                <p>Evaluasi pemahaman dengan quiz dan test yang terstruktur</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Progress Tracking</h3>
                <p>Monitor perkembangan belajar Anda dengan dashboard yang intuitif</p>
            </div>
        </div>
    </div>
</section>

<!-- Course Preview Section -->
<section class="course-preview-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Kursus Populer</h2>
            <p class="section-subtitle">Beberapa kursus terfavorit dari peserta kami</p>
        </div>

        <div class="courses-grid">
            <div class="course-card">
                <div class="course-thumbnail">
                    <img src="https://via.placeholder.com/400x250/667eea/ffffff?text=Web+Development" alt="Course">
                    <span class="course-badge">Beginner</span>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Web Development Fundamentals</h3>
                    <p class="course-desc">Pelajari dasar-dasar web development dari HTML, CSS hingga JavaScript</p>
                    <div class="course-meta">
                        <span><i class="fas fa-clock"></i> 40 jam</span>
                        <span><i class="fas fa-users"></i> 250 peserta</span>
                    </div>
                    <div class="course-footer">
                        <div class="course-price">Rp 500.000</div>
                        <a href="{{ route('edutech.landing') }}" class="btn-course">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">
                    <img src="https://via.placeholder.com/400x250/48bb78/ffffff?text=Data+Science" alt="Course">
                    <span class="course-badge">Intermediate</span>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Data Science dengan Python</h3>
                    <p class="course-desc">Kuasai analisis data dan machine learning menggunakan Python</p>
                    <div class="course-meta">
                        <span><i class="fas fa-clock"></i> 60 jam</span>
                        <span><i class="fas fa-users"></i> 180 peserta</span>
                    </div>
                    <div class="course-footer">
                        <div class="course-price">Rp 750.000</div>
                        <a href="{{ route('edutech.landing') }}" class="btn-course">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <div class="course-card">
                <div class="course-thumbnail">
                    <img src="https://via.placeholder.com/400x250/f56565/ffffff?text=UI+UX+Design" alt="Course">
                    <span class="course-badge">Beginner</span>
                </div>
                <div class="course-content">
                    <h3 class="course-title">UI/UX Design Mastery</h3>
                    <p class="course-desc">Desain interface yang menarik dan user experience yang optimal</p>
                    <div class="course-meta">
                        <span><i class="fas fa-clock"></i> 35 jam</span>
                        <span><i class="fas fa-users"></i> 320 peserta</span>
                    </div>
                    <div class="course-footer">
                        <div class="course-price">Rp 450.000</div>
                        <a href="{{ route('edutech.landing') }}" class="btn-course">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="view-all-wrapper">
            <a href="{{ route('edutech.landing') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-book"></i> Lihat Semua Kursus
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Cara Memulai Belajar</h2>
            <p class="section-subtitle">Hanya 4 langkah mudah untuk mulai belajar</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Daftar Akun</h3>
                <p>Buat akun gratis dengan email Anda</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Pilih Kursus</h3>
                <p>Jelajahi dan pilih kursus yang sesuai minat</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Lakukan Pembayaran</h3>
                <p>Bayar kursus dengan metode yang tersedia</p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Mulai Belajar</h3>
                <p>Akses materi dan selesaikan kursus Anda</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Apa Kata Mereka?</h2>
            <p class="section-subtitle">Testimoni dari peserta yang puas</p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">
                    "Platform yang sangat membantu! Materi mudah dipahami dan instruktur sangat responsif. 
                    Saya berhasil meningkatkan skill programming saya dalam 3 bulan."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/667eea/ffffff?text=A" alt="Author">
                    <div>
                        <div class="author-name">Ahmad Rizki</div>
                        <div class="author-title">Web Developer</div>
                    </div>
                </div>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">
                    "Sertifikat dari KIM Edutech sangat membantu karir saya. Sekarang saya sudah bekerja 
                    di perusahaan IT ternama berkat kursus yang saya ambil di sini."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/48bb78/ffffff?text=S" alt="Author">
                    <div>
                        <div class="author-name">Siti Nurhaliza</div>
                        <div class="author-title">Data Analyst</div>
                    </div>
                </div>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">
                    "Fleksibilitas belajar yang ditawarkan sangat cocok untuk saya yang bekerja. 
                    Bisa belajar kapan saja dan materi bisa diakses berkali-kali."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/f56565/ffffff?text=B" alt="Author">
                    <div>
                        <div class="author-name">Budi Santoso</div>
                        <div class="author-title">UI/UX Designer</div>
                    </div>
                </div>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-edutech">
    <div class="container">
        <div class="cta-content">
            <h2>Siap Meningkatkan Skill Anda?</h2>
            <p>Bergabunglah dengan ribuan peserta lainnya dan mulai perjalanan belajar Anda hari ini</p>
            <div class="cta-buttons">
                <a href="{{ route('edutech.landing') }}" class="btn btn-white btn-lg">
                    <i class="fas fa-rocket"></i> Jelajahi Platform
                </a>
                <a href="{{ route('edutech.register') }}" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Edutech Hero */
.edutech-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0 80px;
    position: relative;
    overflow: hidden;
}

.edutech-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" fill-opacity="0.1"/></svg>');
    background-size: 50px 50px;
}

.hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

.hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 25px;
}

.gradient-text {
    background: linear-gradient(to right, #fff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.95;
    line-height: 1.7;
    margin-bottom: 40px;
}

.hero-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 50px;
}

.btn {
    padding: 16px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: white;
    color: #667eea;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

.btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline:hover {
    background: white;
    color: #667eea;
}

.btn-lg {
    padding: 18px 40px;
    font-size: 1.1rem;
}

.hero-stats {
    display: flex;
    gap: 50px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.hero-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* Features Section */
.features-section {
    padding: 100px 0;
    background: white;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #718096;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 35px;
}

.feature-card {
    text-align: center;
    padding: 40px 30px;
    background: #f8f9fa;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.feature-card:hover {
    background: white;
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 2rem;
}

.feature-card h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
}

.feature-card p {
    color: #718096;
    line-height: 1.7;
}

/* Course Preview */
.course-preview-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 35px;
    margin-bottom: 50px;
}

.course-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.course-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.course-thumbnail {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.course-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: white;
    color: #667eea;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.course-content {
    padding: 25px;
}

.course-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
}

.course-desc {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 20px;
}

.course-meta {
    display: flex;
    gap: 20px;
    font-size: 0.9rem;
    color: #a0aec0;
    margin-bottom: 20px;
}

.course-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.course-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.btn-course {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 10px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-course:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.view-all-wrapper {
    text-align: center;
}

/* How It Works */
.how-it-works {
    padding: 100px 0;
    background: white;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

.step-card {
    text-align: center;
    position: relative;
}

.step-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 20px;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: #667eea;
}

.step-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 12px;
}

.step-card p {
    color: #718096;
}

/* Testimonials */
.testimonials-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 35px;
}

.testimonial-card {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.quote-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    margin-bottom: 20px;
}

.testimonial-text {
    color: #4a5568;
    line-height: 1.7;
    margin-bottom: 25px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.testimonial-author img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.author-name {
    font-weight: 600;
    color: #2d3748;
}

.author-title {
    font-size: 0.9rem;
    color: #718096;
}

.rating {
    display: flex;
    gap: 5px;
}

.rating i {
    color: #f59e0b;
}

/* CTA Edutech */
.cta-edutech {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    text-align: center;
    color: white;
}

.cta-content h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-content p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 40px;
}

.btn-white {
    background: white;
    color: #667eea;
}

.btn-outline-white {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline-white:hover {
    background: white;
    color: #667eea;
}

/* Responsive */
@media (max-width: 968px) {
    .hero-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-buttons {
        justify-content: center;
    }

    .hero-stats {
        justify-content: center;
    }

    .courses-grid,
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush