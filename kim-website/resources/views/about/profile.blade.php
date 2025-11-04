@extends('layouts.app')

@section('title', 'Profil Perusahaan - PT KIM')

@section('content')
<!-- Page Header -->
<section class="profile-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title animate-fade-in">Tentang PT KIM</h1>
            <p class="page-subtitle animate-fade-in-delay">
                Mitra terpercaya untuk transformasi digital dan pengembangan bisnis Anda
            </p>
        </div>
    </div>
    <div class="header-decoration"></div>
</section>

<!-- Company Overview -->
<section class="company-overview">
    <div class="container">
        <div class="overview-grid">
            <div class="overview-image">
                <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=PT+KIM+Office" alt="PT KIM Office">
                <div class="image-decoration"></div>
            </div>
            <div class="overview-content">
                <span class="section-badge">Profil Perusahaan</span>
                <h2 class="section-title">Siapa Kami?</h2>
                <p class="overview-text">
                    <strong>KIM</strong> adalah perusahaan yang bergerak di bidang konsultasi
                    bisnis,
                    pengembangan teknologi informasi, dan solusi pendidikan digital. Didirikan pada tahun - ,
                    kami telah membantu ratusan perusahaan dan institusi dalam mencapai tujuan bisnis mereka
                    melalui inovasi dan teknologi.
                </p>
                <p class="overview-text">
                    Dengan tim profesional yang berpengalaman lebih dari 10 tahun di berbagai industri,
                    kami berkomitmen untuk memberikan solusi terbaik yang disesuaikan dengan kebutuhan
                    spesifik setiap klien.
                </p>
                <div class="overview-stats">
                    <div class="stat-box">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Tahun Pengalaman</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Klien Puas</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Proyek Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="vision-mission">
    <div class="container">
        <div class="vm-grid">
            <div class="vm-card vision-card">
                <div class="vm-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Visi Kami</h3>
                <p>
                    Menjadi perusahaan konsultan dan pengembang teknologi terdepan di Indonesia yang
                    dikenal dengan solusi inovatif, berkualitas tinggi, dan memberikan dampak nyata
                    bagi kemajuan bisnis klien.
                </p>
            </div>
            <div class="vm-card mission-card">
                <div class="vm-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Misi Kami</h3>
                <ul class="mission-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Memberikan layanan konsultasi profesional dengan pendekatan yang komprehensif
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Mengembangkan solusi teknologi yang inovatif dan mudah digunakan
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Memberdayakan SDM melalui pendidikan dan pelatihan berkualitas
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        Membangun kemitraan jangka panjang dengan klien
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="core-values">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Nilai-Nilai Kami</span>
            <h2 class="section-title">Core Values</h2>
            <p class="section-subtitle">
                Nilai-nilai fundamental yang menjadi landasan dalam setiap pekerjaan kami
            </p>
        </div>

        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Excellence</h3>
                <p>Selalu memberikan hasil terbaik dengan standar kualitas tertinggi dalam setiap proyek</p>
            </div>

            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Innovation</h3>
                <p>Terus berinovasi dan mengadopsi teknologi terkini untuk solusi yang lebih baik</p>
            </div>

            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #3182ce, #2c5aa0);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Integrity</h3>
                <p>Menjunjung tinggi kejujuran, transparansi, dan etika bisnis yang baik</p>
            </div>

            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #f56565, #e53e3e);">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Collaboration</h3>
                <p>Membangun kerja sama yang solid dengan klien dan tim untuk hasil optimal</p>
            </div>

            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #805ad5, #6b46c1);">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3>Customer Focus</h3>
                <p>Mengutamakan kepuasan dan keberhasilan klien dalam setiap layanan kami</p>
            </div>

            <div class="value-card">
                <div class="value-icon" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Commitment</h3>
                <p>Berkomitmen penuh dalam menyelesaikan setiap proyek tepat waktu dan sesuai ekspektasi</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="services-overview">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Layanan Kami</span>
            <h2 class="section-title">Apa yang Kami Tawarkan?</h2>
        </div>

        <div class="services-grid">
            <div class="service-item">
                <div class="service-number">01</div>
                <div class="service-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>KIM Consultant</h3>
                <p>
                    Layanan konsultasi profesional di berbagai bidang: Pendidikan, Manajemen,
                    Teknik Industri, TIK, Pertanian, Pariwisata, dan Desain.
                </p>
                <a href="{{ route('consultant.index') }}" class="service-link">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="service-item">
                <div class="service-number">02</div>
                <div class="service-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>KIM Developer</h3>
                <p>
                    Pengembangan aplikasi dan sistem informasi custom dengan teknologi terkini
                    untuk berbagai kebutuhan bisnis Anda.
                </p>
                <a href="{{ route('developer.index') }}" class="service-link">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="service-item">
                <div class="service-number">03</div>
                <div class="service-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>KIM Edutech</h3>
                <p>
                    Platform pembelajaran digital dan solusi edutech untuk transformasi
                    pendidikan modern di era digital.
                </p>
                <a href="{{ route('edutech.index') }}" class="service-link">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose">
    <div class="container">
        <div class="why-grid">
            <div class="why-content">
                <span class="section-badge">Keunggulan Kami</span>
                <h2 class="section-title">Mengapa Memilih PT KIM?</h2>
                <p class="why-description">
                    Kami memahami bahwa setiap bisnis memiliki tantangan unik. Oleh karena itu,
                    kami menawarkan pendekatan yang personal dan solusi yang disesuaikan dengan
                    kebutuhan spesifik Anda.
                </p>

                <div class="why-list">
                    <div class="why-item">
                        <div class="why-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="why-text">
                            <h4>Tim Profesional Bersertifikat</h4>
                            <p>Konsultan dan developer berpengalaman dengan sertifikasi internasional</p>
                        </div>
                    </div>

                    <div class="why-item">
                        <div class="why-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="why-text">
                            <h4>Track Record Terbukti</h4>
                            <p>500+ klien puas dari berbagai industri di seluruh Indonesia</p>
                        </div>
                    </div>

                    <div class="why-item">
                        <div class="why-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div class="why-text">
                            <h4>Support 24/7</h4>
                            <p>Tim support yang siap membantu Anda kapan saja</p>
                        </div>
                    </div>

                    <div class="why-item">
                        <div class="why-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="why-text">
                            <h4>Harga Kompetitif</h4>
                            <p>Solusi berkualitas dengan harga yang terjangkau dan transparan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="why-image">
                <img src="https://via.placeholder.com/500x600/667eea/ffffff?text=Why+Choose+Us" alt="Why Choose Us">
                <div class="image-badge">
                    <div class="badge-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="badge-text">
                        <div class="badge-number">4.9/5</div>
                        <div class="badge-label">Rating Klien</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Clients & Partners -->
<section class="clients-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Klien & Mitra</span>
            <h2 class="section-title">Dipercaya Oleh</h2>
            <p class="section-subtitle">
                Berbagai perusahaan dan institusi terkemuka telah mempercayai kami
            </p>
        </div>

        <div class="clients-grid">
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+1" alt="Client 1">
            </div>
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+2" alt="Client 2">
            </div>
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+3" alt="Client 3">
            </div>
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+4" alt="Client 4">
            </div>
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+5" alt="Client 5">
            </div>
            <div class="client-logo">
                <img src="https://via.placeholder.com/200x100/e0e0e0/666666?text=Client+6" alt="Client 6">
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-profile">
    <div class="container">
        <div class="cta-content">
            <h2>Mari Bergabung dengan 500+ Klien Kami</h2>
            <p>Konsultasi gratis untuk menentukan solusi terbaik bagi bisnis Anda</p>
            <div class="cta-buttons">
                <a href="{{ route('contact.index') }}" class="btn btn-white">
                    <i class="fas fa-phone-alt"></i> Hubungi Kami
                </a>
                <a href="{{ route('about.organization') }}" class="btn btn-outline-white">
                    <i class="fas fa-sitemap"></i> Lihat Tim Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Profile Header */
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 100px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.header-decoration {
    position: absolute;
    top: 0;
    right: 0;
    width: 600px;
    height: 600px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.page-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
}

/* Company Overview */
.company-overview {
    padding: 100px 0;
    background: white;
}

.overview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.overview-image {
    position: relative;
}

.overview-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.image-decoration {
    position: absolute;
    top: -20px;
    left: -20px;
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 20px;
    opacity: 0.2;
    z-index: -1;
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
    margin-bottom: 25px;
}

.overview-text {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 20px;
}

.overview-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 40px;
}

.stat-box {
    text-align: center;
    padding: 25px;
    background: #f7fafc;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.stat-box:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    transform: translateY(-5px);
}

.stat-box:hover .stat-number,
.stat-box:hover .stat-label {
    color: white;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 5px;
    transition: color 0.3s ease;
}

.stat-label {
    font-size: 0.95rem;
    color: #718096;
    font-weight: 500;
    transition: color 0.3s ease;
}

/* Vision & Mission */
.vision-mission {
    padding: 100px 0;
    background: linear-gradient(to bottom, #f8f9fa, white);
}

.vm-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}

.vm-card {
    background: white;
    padding: 50px 40px;
    border-radius: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.vm-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.vm-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 30px;
}

.vision-card .vm-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.mission-card .vm-icon {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
}

.vm-card h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
}

.vm-card p {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.8;
}

.mission-list {
    list-style: none;
    padding: 0;
}

.mission-list li {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.7;
}

.mission-list li i {
    color: #48bb78;
    font-size: 1.2rem;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Core Values */
.core-values {
    padding: 100px 0;
    background: white;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #718096;
    max-width: 600px;
    margin: 15px auto 0;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.value-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.value-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 1.8rem;
}

.value-card h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
}

.value-card p {
    color: #718096;
    line-height: 1.7;
}

/* Services Overview */
.services-overview {
    padding: 100px 0;
    background: #f8f9fa;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
}

.service-item {
    background: white;
    padding: 45px 35px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.service-number {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 4rem;
    font-weight: 800;
    color: #f7fafc;
}

.service-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    margin-bottom: 25px;
    position: relative;
    z-index: 2;
}

.service-item h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.service-item p {
    color: #718096;
    line-height: 1.7;
    margin-bottom: 25px;
}

.service-link {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.service-link:hover {
    gap: 15px;
}

/* Why Choose Us */
.why-choose {
    padding: 100px 0;
    background: white;
}

.why-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
}

.why-description {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 40px;
}

.why-list {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.why-item {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.why-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.why-text h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.why-text p {
    color: #718096;
    line-height: 1.6;
}

.why-image {
    position: relative;
}

.why-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.image-badge {
    position: absolute;
    bottom: 30px;
    left: 30px;
    background: white;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 15px;
}

.badge-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.badge-number {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2d3748;
}

.badge-label {
    font-size: 0.9rem;
    color: #718096;
}

/* Clients Section */
.clients-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.clients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
}

.client-logo {
    background: white;
    padding: 30px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.client-logo:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.client-logo img {
    max-width: 100%;
    height: auto;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.client-logo:hover img {
    opacity: 1;
}

/* CTA Profile */
.cta-profile {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    text-align: center;
    color: white;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-content p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 40px;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 16px 32px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn i {
    font-size: 1rem;
}

.btn-white {
    background: white;
    color: #4a4a4a;
}

.btn-white:hover {
    background: #f0f0f0;
    transform: translateY(-3px);
}

.btn-outline-white {
    border: 2px solid white;
    color: white;
}

.btn-outline-white:hover {
    background: white;
    color: #4a4a4a;
    transform: translateY(-3px);
}

/* Animation (Optional Fade-in) */
.animate-fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeIn 1s forwards;
}

.animate-fade-in-delay {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeIn 1s forwards 0.5s;
}

@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush