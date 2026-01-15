@extends('layouts.app')

@section('title', 'KIM EDUVERSE - Center of Excellent Competency')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="hero-text">
            <span class="hero-badge animate-fade-in">PT Kompetensi Indonesia Mandiri</span>
            <h1 class="hero-title animate-fade-in">
                Transformasi Digital <br>
                <span class="gradient-text">Dimulai dari Sini</span>
            </h1>
            <p class="hero-subtitle animate-fade-in-delay">
                Pelatihan, Konsultasi, Sertifikasi, dan Pengembangan Teknologi untuk
                Meningkatkan Kompetensi Organisasi Anda
            </p>
            <div class="hero-stats animate-fade-in-delay">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Klien Puas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Proyek Selesai</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Dukungan</div>
                </div>
            </div>
            <div class="hero-buttons animate-fade-in-delay-2">
                <a href="#produk" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket"></i> Jelajahi Layanan
                </a>
                <a href="{{ route('contact.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-phone-alt"></i> Konsultasi Gratis
                </a>
            </div>
        </div>
        <div class="hero-image animate-float">
            <div class="hero-image-wrapper">
                <img src="{{ asset('storage/images/building.jpg') }}" alt="KIM EDUVERSE">
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- Products Section -->
<section id="produk" class="products-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Produk & Layanan</span>
            <h2 class="section-title">Solusi Lengkap untuk Kebutuhan Anda</h2>
            <p class="section-subtitle">
                Tiga layanan utama yang kami tawarkan untuk membantu transformasi dan pertumbuhan bisnis Anda
            </p>
        </div>

        <div class="products-grid">
            <!-- KIM Consultant -->
            <div class="product-card consultant-card">
                <div class="product-header">
                    <div class="product-number">01</div>
                    <div class="product-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <h3 class="product-title">KIM Consultant</h3>
                <p class="product-description">
                    Konsultasi bisnis profesional di berbagai bidang: Pendidikan, Manajemen,
                    Teknik Industri, TIK, Pertanian, Pariwisata, dan Desain
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Analisis mendalam & terukur</li>
                    <li><i class="fas fa-check"></i> Solusi sesuai kebutuhan</li>
                    <li><i class="fas fa-check"></i> Tim ahli berpengalaman</li>
                    <li><i class="fas fa-check"></i> Pendampingan implementasi</li>
                </ul>
                <a href="{{ route('consultant.index') }}" class="btn btn-product">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- KIM Developer -->
            <div class="product-card developer-card">
                <div class="product-header">
                    <div class="product-number">02</div>
                    <div class="product-icon">
                        <i class="fas fa-code"></i>
                    </div>
                </div>
                <h3 class="product-title">KIM Developer</h3>
                <p class="product-description">
                    Pengembangan aplikasi dan sistem informasi dengan teknologi
                    terkini untuk solusi digital bisnis Anda
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Aplikasi Web & Mobile</li>
                    <li><i class="fas fa-check"></i> Sistem Enterprise</li>
                    <li><i class="fas fa-check"></i> UI/UX Modern</li>
                    <li><i class="fas fa-check"></i> Maintenance & Support</li>
                </ul>
                <a href="{{ route('developer.index') }}" class="btn btn-product">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- KIM Edutech -->
            <div class="product-card edutech-card">
                <div class="product-header">
                    <div class="product-number">03</div>
                    <div class="product-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <h3 class="product-title">KIM Edutech</h3>
                <p class="product-description">
                    Platform pembelajaran digital dan solusi edutech untuk transformasi
                    pendidikan modern di era digital
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Learning Management System</li>
                    <li><i class="fas fa-check"></i> Kursus Online & Sertifikasi</li>
                    <li><i class="fas fa-check"></i> Live Learning & Webinar</li>
                    <li><i class="fas fa-check"></i> Pelacakan Progres</li>
                </ul>
                <a href="{{ route('edutech.index') }}" class="btn btn-product">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Mengapa PT KIM?</span>
            <h2 class="section-title">Keunggulan Kami</h2>
            <p class="section-subtitle">
                Alasan mengapa ratusan klien mempercayai kami sebagai mitra bisnis mereka
            </p>
        </div>

        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Tim Profesional</h3>
                <p>Konsultan dan developer berpengalaman dengan sertifikasi internasional</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Cepat & Efektif</h3>
                <p>Proses kerja yang efisien dengan hasil maksimal sesuai deadline</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3>Harga Kompetitif</h3>
                <p>Solusi berkualitas tinggi dengan harga yang terjangkau dan transparan</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Dukungan 24/7</h3>
                <p>Tim support yang siap membantu Anda kapan saja dibutuhkan</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Teknologi Terkini</h3>
                <p>Menggunakan teknologi mutakhir yang sesuai standar industri</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Kemitraan Jangka Panjang</h3>
                <p>Berkomitmen untuk kesuksesan jangka panjang klien kami</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Cara Kerja</span>
            <h2 class="section-title">Proses Kolaborasi</h2>
            <p class="section-subtitle">
                Hanya 4 langkah mudah untuk memulai proyek Anda bersama kami
            </p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Konsultasi</h3>
                <p>Diskusi kebutuhan dan tantangan bisnis Anda dengan tim ahli kami</p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>Perencanaan</h3>
                <p>Kami merancang solusi yang paling sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Eksekusi</h3>
                <p>Tim profesional kami mengerjakan proyek dengan standar terbaik</p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Serah Terima</h3>
                <p>Proyek selesai dengan dukungan berkelanjutan untuk kesuksesan Anda</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Testimoni</span>
            <h2 class="section-title">Apa Kata Klien Kami?</h2>
            <p class="section-subtitle">
                Kepercayaan dan kepuasan klien adalah prioritas utama kami
            </p>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "PT KIM membantu kami dalam transformasi digital dengan sangat profesional.
                    Sistem yang mereka kembangkan sangat sesuai dengan kebutuhan kami."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <h4>Budi Santoso</h4>
                        <p>CEO, PT ABC Corporation</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "Konsultasi manajemen dari PT KIM sangat membantu meningkatkan efisiensi
                    operasional perusahaan kami hingga 40%. Sangat direkomendasikan!"
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <h4>Siti Nurhaliza</h4>
                        <p>Manajer, PT XYZ Industries</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "Platform edutech dari PT KIM sangat mudah digunakan dan memudahkan kami
                    dalam mengelola pembelajaran online. Fiturnya lengkap dan dukungannya responsif."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="author-info">
                        <h4>Ahmad Hidayat</h4>
                        <p>Kepala Sekolah, SMA Negeri 1</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News/Blog -->
<section class="latest-blog">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Blog & Artikel</span>
            <h2 class="section-title">Berita & Insight Terbaru</h2>
            <p class="section-subtitle">
                Baca artikel terbaru tentang teknologi, bisnis, dan pengembangan kompetensi
            </p>
        </div>

        <div class="blog-grid">
            <div class="blog-card">
                <div class="blog-image">
                    <img src="https://via.placeholder.com/400x250/667eea/ffffff?text=Artikel+1" alt="Blog">
                    <span class="blog-category">Teknologi</span>
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="fas fa-calendar"></i> 15 Nov 2024</span>
                        <span><i class="fas fa-user"></i> Admin</span>
                    </div>
                    <h3>Transformasi Digital untuk UMKM di Era Modern</h3>
                    <p>Panduan lengkap bagaimana UMKM dapat memanfaatkan teknologi digital untuk meningkatkan bisnis...
                    </p>
                    <a href="{{ route('blog.index') }}" class="blog-link">Baca Selengkapnya <i
                            class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="blog-card">
                <div class="blog-image">
                    <img src="https://via.placeholder.com/400x250/48bb78/ffffff?text=Artikel+2" alt="Blog">
                    <span class="blog-category">Bisnis</span>
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="fas fa-calendar"></i> 12 Nov 2024</span>
                        <span><i class="fas fa-user"></i> Admin</span>
                    </div>
                    <h3>5 Strategi Meningkatkan Produktivitas Tim</h3>
                    <p>Tips dan trik untuk meningkatkan produktivitas tim Anda secara efektif dengan metode yang
                        terbukti...</p>
                    <a href="{{ route('blog.index') }}" class="blog-link">Baca Selengkapnya <i
                            class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="blog-card">
                <div class="blog-image">
                    <img src="https://via.placeholder.com/400x250/3182ce/ffffff?text=Artikel+3" alt="Blog">
                    <span class="blog-category">Pendidikan</span>
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="fas fa-calendar"></i> 10 Nov 2024</span>
                        <span><i class="fas fa-user"></i> Admin</span>
                    </div>
                    <h3>E-Learning: Masa Depan Pendidikan Indonesia</h3>
                    <p>Bagaimana e-learning mengubah landscape pendidikan di Indonesia dan peluang yang ada...</p>
                    <a href="{{ route('blog.index') }}" class="blog-link">Baca Selengkapnya <i
                            class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="view-all-wrapper">
            <a href="{{ route('blog.index') }}" class="btn btn-primary">
                <i class="fas fa-book"></i> Lihat Semua Artikel
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Siap Meningkatkan Kompetensi Organisasi Anda?</h2>
            <p>Mari bergabung dengan 500+ klien yang telah mempercayai PT KIM</p>
            <p class="cta-tagline">
                <strong>We're here to help. We're Center of Excellent Competency. <br>Because We're KIM.</strong>
            </p>
            <div class="cta-buttons">
                <a href="{{ route('contact.index') }}" class="btn btn-white btn-lg">
                    <i class="fas fa-phone-alt"></i> Hubungi Kami Sekarang
                </a>
                <a href="{{ route('about.profile') }}" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-info-circle"></i> Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Hero Section */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" fill-opacity="0.1"/></svg>');
    background-size: 100px 100px;
}

.hero-content {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 60px;
    align-items: center;
    padding: 80px 0;
}

.hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 25px;
}

.gradient-text {
    background: linear-gradient(to right, #faa825ff, #e9c870ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.25rem;
    line-height: 1.7;
    opacity: 0.95;
    margin-bottom: 30px;
    max-width: 600px;
}

.hero-stats {
    display: flex;
    gap: 40px;
    margin-bottom: 40px;
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
    font-size: 0.95rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: none;
    cursor: pointer;
}

.btn-lg {
    padding: 16px 40px;
    font-size: 1.05rem;
}

.btn-primary {
    background: white;
    color: #667eea;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-outline {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.btn-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

.hero-image-wrapper {
    position: relative;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
}

.hero-image-wrapper img {
    width: 100%;
    display: block;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-20px);
    }
}

.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 1.5rem;
    animation: bounce 2s infinite;
    cursor: pointer;
}

@keyframes bounce {

    0%,
    100% {
        transform: translate(-50%, 0);
    }

    50% {
        transform: translate(-50%, 10px);
    }
}

/* Products Section */
.products-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
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
    max-width: 600px;
    margin: 0 auto;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 40px;
}

.product-card {
    background: white;
    padding: 45px 35px;
    border-radius: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.product-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.product-card:hover::before {
    transform: scaleX(1);
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.product-number {
    font-size: 4rem;
    font-weight: 800;
    color: #f7fafc;
}

.product-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
}

.product-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.product-description {
    color: #718096;
    line-height: 1.7;
    margin-bottom: 25px;
}

.product-features {
    list-style: none;
    padding: 0;
    margin-bottom: 30px;
}

.product-features li {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    color: #4a5568;
}

.product-features i {
    color: #48bb78;
    font-size: 1.1rem;
}

.btn-product {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    width: 100%;
    justify-content: center;
}

.btn-product:hover {
    transform: none;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
}

/* Why Choose Us */
.why-choose-us {
    padding: 100px 0;
    background: white;
}

.why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.why-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.why-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.why-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 1.8rem;
}

.why-card h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
}

.why-card p {
    color: #718096;
    line-height: 1.7;
}

/* How It Works */
.how-it-works {
    padding: 100px 0;
    background: linear-gradient(to bottom, #f8f9fa, white);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

.step-card {
    position: relative;
    text-align: center;
    padding: 40px 30px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.step-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.step-number {
    position: absolute;
    top: -20px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 800;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.step-icon {
    width: 80px;
    height: 80px;
    background: #f7fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: #667eea;
    font-size: 2rem;
}

.step-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 12px;
}

.step-card p {
    color: #718096;
    line-height: 1.7;
}

/* Testimonials */
.testimonials {
    padding: 100px 0;
    background: white;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}

.testimonial-card {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

.testimonial-rating {
    color: #f59e0b;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.testimonial-text {
    color: #4a5568;
    line-height: 1.8;
    font-size: 1.05rem;
    margin-bottom: 25px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.author-info h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 3px;
}

.author-info p {
    font-size: 0.9rem;
    color: #718096;
}

/* Latest Blog */
.latest-blog {
    padding: 100px 0;
    background: #f8f9fa;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.blog-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.blog-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.blog-card:hover .blog-image img {
    transform: scale(1.1);
}

.blog-category {
    position: absolute;
    top: 20px;
    left: 20px;
    background: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #667eea;
}

.blog-content {
    padding: 30px;
}

.blog-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 0.9rem;
    color: #718096;
}

.blog-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.blog-content h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 12px;
    line-height: 1.4;
}

.blog-content p {
    color: #718096;
    line-height: 1.7;
    margin-bottom: 20px;
}

.blog-link {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: gap 0.3s ease;
}

.blog-link:hover {
    gap: 15px;
}

.view-all-wrapper {
    text-align: center;
}

/* CTA Section */
.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    text-align: center;
    color: white;
}

.cta-content h2 {
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-content p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 20px;
}

.cta-tagline {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 40px;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-white {
    background: white;
    color: #667eea;
}

.btn-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.btn-outline-white {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.btn-outline-white:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

/* Animations */
.animate-fade-in {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 1s forwards;
}

.animate-fade-in-delay {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 1s forwards 0.3s;
}

.animate-fade-in-delay-2 {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 1s forwards 0.6s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }
}

@media (max-width: 992px) {
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .hero-title {
        font-size: 3rem;
    }

    .hero-stats {
        justify-content: center;
    }

    .hero-buttons {
        justify-content: center;
    }

    .products-grid,
    .why-grid,
    .steps-grid,
    .testimonials-grid,
    .blog-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .hero-stats {
        flex-direction: column;
        gap: 20px;
    }
}

p,
span,
li,
small {
    opacity: 1 !important;
}

/* ================= HERO SECTION ================= */

.hero-subtitle {
    opacity: 1 !important;
    color: #ffffff !important;
    font-weight: 500;
}

.hero-badge {
    background: rgba(255, 255, 255, 0.35) !important;
    color: #ffffff !important;
    font-weight: 700;
}

/* HERO STATS */
.stat-number {
    opacity: 1 !important;
    color: #ffffff !important;
    font-weight: 900;
}

.stat-label {
    opacity: 1 !important;
    color: #ffffff !important;
    font-weight: 600;
}

/* ================= SECTION HEADER ================= */

.section-title {
    color: #1a202c !important;
    font-weight: 800;
}

.section-subtitle {
    color: #2d3748 !important;
    opacity: 1 !important;
    font-weight: 500;
}

/* ================= PRODUCT CARDS ================= */

.product-title {
    color: #1a202c !important;
    font-weight: 700;
}

.product-description {
    color: #2d3748 !important;
    opacity: 1 !important;
    font-weight: 500;
}

.product-features li {
    color: #1a202c !important;
    font-weight: 500;
}

/* Angka besar 01 02 03 */
.product-number {
    color: #667eea !important;
    opacity: 1 !important;
    font-weight: 900;
}

/* ================= WHY CHOOSE US ================= */

.why-card h3 {
    color: #1a202c !important;
    font-weight: 700;
}

.why-card p {
    color: #2d3748 !important;
    opacity: 1 !important;
    font-weight: 500;
}

/* ================= HOW IT WORKS ================= */

.step-card h3 {
    color: #1a202c !important;
    font-weight: 700;
}

.step-card p {
    color: #2d3748 !important;
    opacity: 1 !important;
    font-weight: 500;
}

.step-number {
    font-weight: 900;
}

/* ================= TESTIMONIALS ================= */

.testimonial-text {
    color: #1a202c !important;
    opacity: 1 !important;
    font-style: normal;
    font-weight: 500;
}

.author-info h4 {
    color: #1a202c !important;
    font-weight: 700;
}

.author-info p {
    color: #2d3748 !important;
    opacity: 1 !important;
}

/* ================= BLOG SECTION ================= */

.blog-content h3 {
    color: #1a202c !important;
    font-weight: 700;
}

.blog-content p {
    color: #2d3748 !important;
    opacity: 1 !important;
    font-weight: 500;
}

.blog-meta {
    color: #1a202c !important;
    font-weight: 500;
}

/* ================= CTA SECTION ================= */

.cta-content h2 {
    color: #ffffff !important;
    font-weight: 900;
}

.cta-content p,
.cta-tagline {
    color: #ffffff !important;
    opacity: 1 !important;
    font-weight: 600;
}

/* ================= BUTTON TEXT ================= */

.btn,
.btn span,
.btn i {
    opacity: 1 !important;
    font-weight: 600;
}
</style>
@endpush