@extends('layouts.app')

@section('title', 'PT KIM - Konsultan, Developer & Edutech')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="hero-text">
            <h1 class="hero-title animate-fade-in">
                Transformasi Digital <br>
                <span class="gradient-text">Untuk Masa Depan</span>
            </h1>
            <p class="hero-subtitle animate-fade-in-delay">
                PT KIM hadir sebagai mitra terpercaya untuk konsultasi bisnis,
                pengembangan teknologi, dan solusi pendidikan digital yang inovatif
            </p>
            <div class="hero-buttons animate-fade-in-delay-2">
                <a href="#produk" class="btn btn-primary btn-lg">
                    <i class="fas fa-rocket"></i> Jelajahi Layanan
                </a>
                <a href="{{ route('contact.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-phone-alt"></i> Hubungi Kami
                </a>
            </div>
        </div>
        <div class="hero-image animate-float">
            <img src="/images/hero-illustration.svg" alt="KIM Illustration">
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
            <h2 class="section-title">Produk & Layanan Kami</h2>
            <p class="section-subtitle">
                Solusi lengkap untuk kebutuhan bisnis dan teknologi Anda
            </p>
        </div>

        <div class="products-grid">
            <!-- KIM Consultant -->
            <div class="product-card consultant-card">
                <div class="product-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="product-title">KIM Consultant</h3>
                <p class="product-description">
                    Konsultasi bisnis profesional di berbagai bidang:
                    Pendidikan, Manajemen, Teknik Industri, TIK, Pertanian, Pariwisata, dan Desain
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Analisis mendalam</li>
                    <li><i class="fas fa-check"></i> Solusi customized</li>
                    <li><i class="fas fa-check"></i> Tim ahli berpengalaman</li>
                    <li><i class="fas fa-check"></i> Follow-up implementasi</li>
                </ul>
                <a href="{{ route('consultant.index') }}" class="btn btn-product">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- KIM Developer -->
            <div class="product-card developer-card">
                <div class="product-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3 class="product-title">KIM Developer</h3>
                <p class="product-description">
                    Pengembangan aplikasi dan sistem informasi custom
                    dengan teknologi terkini untuk solusi digital bisnis Anda
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Web & Mobile App</li>
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
                <div class="product-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="product-title">KIM Edutech</h3>
                <p class="product-description">
                    Platform pembelajaran digital dan solusi edutech
                    untuk transformasi pendidikan modern di era digital
                </p>
                <ul class="product-features">
                    <li><i class="fas fa-check"></i> Learning Management System</li>
                    <li><i class="fas fa-check"></i> Kursus Online</li>
                    <li><i class="fas fa-check"></i> Sertifikasi Digital</li>
                    <li><i class="fas fa-check"></i> Live Learning</li>
                </ul>
                <a href="{{ route('edutech.index') }}" class="btn btn-product">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
</section>
@endsection