@extends('layouts.app')

@section('title', 'Mitra Kami - PT KIM')

@section('content')
<!-- Page Header -->
<section class="partner-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title animate-fade-in">Mitra Kami</h1>
            <p class="page-subtitle animate-fade-in-delay">
                Dipercaya oleh perusahaan dan institusi terkemuka di Indonesia
            </p>
        </div>
    </div>
    <div class="header-decoration"></div>
</section>

<!-- Partner Stats -->
<section class="partner-stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-target="500">0</div>
                    <div class="stat-label">Mitra Perusahaan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-target="150">0</div>
                    <div class="stat-label">Institusi Pendidikan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-target="200">0</div>
                    <div class="stat-label">Proyek Kolaborasi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-target="25">0</div>
                    <div class="stat-label">Kota di Indonesia</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partner Categories -->
<section class="partner-categories">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Kategori Mitra</h2>
            <p class="section-subtitle">Kami bekerja sama dengan berbagai jenis organisasi</p>
        </div>

        <div class="category-filter">
            <button class="filter-btn active" data-category="all">
                <i class="fas fa-th"></i> Semua Mitra
            </button>
            <button class="filter-btn" data-category="corporate">
                <i class="fas fa-building"></i> Korporat
            </button>
            <button class="filter-btn" data-category="education">
                <i class="fas fa-graduation-cap"></i> Pendidikan
            </button>
            <button class="filter-btn" data-category="government">
                <i class="fas fa-landmark"></i> Pemerintah
            </button>
            <button class="filter-btn" data-category="startup">
                <i class="fas fa-rocket"></i> Startup
            </button>
            <button class="filter-btn" data-category="ngo">
                <i class="fas fa-hands-helping"></i> NGO/Komunitas
            </button>
        </div>
    </div>
</section>

<!-- Partners Grid -->
<section class="partners-grid-section">
    <div class="container">
        <!-- Corporate Partners -->
        <div class="partner-group" data-category="corporate">
            <h3 class="group-title">
                <i class="fas fa-building"></i> Mitra Korporat
            </h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/667eea/ffffff?text=Bank+ABC" alt="Bank ABC">
                    </div>
                    <div class="partner-info">
                        <h4>Bank ABC Indonesia</h4>
                        <p class="partner-type">Perbankan</p>
                        <p class="partner-desc">Implementasi Core Banking System dan Digital Banking</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/48bb78/ffffff?text=PT+XYZ" alt="PT XYZ">
                    </div>
                    <div class="partner-info">
                        <h4>PT XYZ Manufacturing</h4>
                        <p class="partner-type">Manufaktur</p>
                        <p class="partner-desc">ERP System dan Smart Factory Implementation</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/f56565/ffffff?text=Telco+Group" alt="Telco">
                    </div>
                    <div class="partner-info">
                        <h4>Telco Group Indonesia</h4>
                        <p class="partner-type">Telekomunikasi</p>
                        <p class="partner-desc">Digital Transformation & Network Management</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/805ad5/ffffff?text=Retail+Chain" alt="Retail">
                    </div>
                    <div class="partner-info">
                        <h4>National Retail Chain</h4>
                        <p class="partner-type">Retail</p>
                        <p class="partner-desc">POS System & Inventory Management</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education Partners -->
        <div class="partner-group" data-category="education">
            <h3 class="group-title">
                <i class="fas fa-graduation-cap"></i> Mitra Pendidikan
            </h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/3182ce/ffffff?text=Universitas+A" alt="Univ A">
                    </div>
                    <div class="partner-info">
                        <h4>Universitas Negeri A</h4>
                        <p class="partner-type">Perguruan Tinggi</p>
                        <p class="partner-desc">Learning Management System & E-Learning Platform</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/d69e2e/ffffff?text=SMA+Network" alt="SMA">
                    </div>
                    <div class="partner-info">
                        <h4>SMA Network Indonesia</h4>
                        <p class="partner-type">Sekolah Menengah</p>
                        <p class="partner-desc">Academic Management System</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/38b2ac/ffffff?text=Lembaga+Kursus" alt="Kursus">
                    </div>
                    <div class="partner-info">
                        <h4>Lembaga Kursus Nasional</h4>
                        <p class="partner-type">Pelatihan</p>
                        <p class="partner-desc">Online Course Platform & Certificate Management</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/9f7aea/ffffff?text=Sekolah+Vokasi" alt="Vokasi">
                    </div>
                    <div class="partner-info">
                        <h4>Sekolah Vokasi Tech</h4>
                        <p class="partner-type">Pendidikan Vokasi</p>
                        <p class="partner-desc">Digital Skills Training Platform</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Government Partners -->
        <div class="partner-group" data-category="government">
            <h3 class="group-title">
                <i class="fas fa-landmark"></i> Mitra Pemerintah
            </h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/e53e3e/ffffff?text=Pemda+X" alt="Pemda">
                    </div>
                    <div class="partner-info">
                        <h4>Pemerintah Daerah X</h4>
                        <p class="partner-type">Pemerintahan</p>
                        <p class="partner-desc">Smart City Solution & Public Service Platform</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/667eea/ffffff?text=Kementerian" alt="Kementerian">
                    </div>
                    <div class="partner-info">
                        <h4>Kementerian ABC</h4>
                        <p class="partner-type">Kementerian</p>
                        <p class="partner-desc">Document Management & Reporting System</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/48bb78/ffffff?text=BUMN" alt="BUMN">
                    </div>
                    <div class="partner-info">
                        <h4>BUMN Industry</h4>
                        <p class="partner-type">BUMN</p>
                        <p class="partner-desc">Enterprise Resource Planning Implementation</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Startup Partners -->
        <div class="partner-group" data-category="startup">
            <h3 class="group-title">
                <i class="fas fa-rocket"></i> Mitra Startup
            </h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/f56565/ffffff?text=FinTech+Co" alt="Fintech">
                    </div>
                    <div class="partner-info">
                        <h4>FinTech Startup Co</h4>
                        <p class="partner-type">Financial Technology</p>
                        <p class="partner-desc">Payment Gateway & Mobile Banking App</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/805ad5/ffffff?text=EdTech+Hub" alt="Edtech">
                    </div>
                    <div class="partner-info">
                        <h4>EdTech Hub Indonesia</h4>
                        <p class="partner-type">Education Technology</p>
                        <p class="partner-desc">Learning Platform Development</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/3182ce/ffffff?text=E-Commerce" alt="Ecommerce">
                    </div>
                    <div class="partner-info">
                        <h4>E-Commerce Startup</h4>
                        <p class="partner-type">E-Commerce</p>
                        <p class="partner-desc">Marketplace Platform & Logistics Integration</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- NGO/Community Partners -->
        <div class="partner-group" data-category="ngo">
            <h3 class="group-title">
                <i class="fas fa-hands-helping"></i> Mitra NGO & Komunitas
            </h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/d69e2e/ffffff?text=Yayasan+XYZ" alt="Yayasan">
                    </div>
                    <div class="partner-info">
                        <h4>Yayasan Pendidikan XYZ</h4>
                        <p class="partner-type">Yayasan</p>
                        <p class="partner-desc">Scholarship Management System</p>
                    </div>
                </div>

                <div class="partner-card">
                    <div class="partner-logo">
                        <img src="https://via.placeholder.com/250x120/38b2ac/ffffff?text=Komunitas" alt="Komunitas">
                    </div>
                    <div class="partner-info">
                        <h4>Komunitas Developer Indonesia</h4>
                        <p class="partner-type">Komunitas</p>
                        <p class="partner-desc">Community Platform & Event Management</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partner Benefits -->
<section class="partner-benefits">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Keuntungan Bermitra dengan Kami</h2>
            <p class="section-subtitle">Mengapa lebih dari 500 organisasi memilih PT KIM</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Kualitas Terjamin</h3>
                <p>Standar internasional dengan tim bersertifikat dan berpengalaman</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Support 24/7</h3>
                <p>Tim support yang siap membantu kapan saja untuk memastikan operasional lancar</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Hasil Terukur</h3>
                <p>ROI yang jelas dengan metrics dan reporting yang transparan</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Keamanan Data</h3>
                <p>Standar keamanan tinggi dengan enkripsi dan backup rutin</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h3>Update Berkala</h3>
                <p>Sistem selalu up-to-date dengan teknologi dan fitur terbaru</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Training Gratis</h3>
                <p>Pelatihan lengkap untuk memaksimalkan penggunaan sistem</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="partner-testimonials">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Apa Kata Mitra Kami</h2>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p class="testimonial-text">
                    "PT KIM sangat profesional dalam menangani proyek ERP kami. Tim mereka responsif 
                    dan solusi yang diberikan sangat sesuai dengan kebutuhan bisnis kami."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/667eea/ffffff?text=JD" alt="Author">
                    <div>
                        <div class="author-name">John Doe</div>
                        <div class="author-position">CEO, PT Manufacturing XYZ</div>
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
                    "LMS yang dikembangkan oleh KIM Edutech sangat membantu kami dalam mengelola 
                    pembelajaran online. Interface-nya user-friendly dan fiturnya lengkap."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/48bb78/ffffff?text=SM" alt="Author">
                    <div>
                        <div class="author-name">Siti Mahmudah</div>
                        <div class="author-position">Rektor, Universitas Negeri A</div>
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
                    "Konsultan dari PT KIM membantu kami merestrukturisasi manajemen operasional. 
                    Hasilnya sangat signifikan, efisiensi meningkat 40% dalam 6 bulan."
                </p>
                <div class="testimonial-author">
                    <img src="https://via.placeholder.com/60x60/f56565/ffffff?text=BP" alt="Author">
                    <div>
                        <div class="author-name">Budi Prasetyo</div>
                        <div class="author-position">COO, Bank ABC Indonesia</div>
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
<section class="partner-cta">
    <div class="container">
        <div class="cta-content">
            <h2>Tertarik Bermitra dengan Kami?</h2>
            <p>Mari bergabung dengan 500+ organisasi yang telah mempercayai PT KIM</p>
            <div class="cta-buttons">
                <a href="{{ route('contact.index') }}" class="btn btn-white">
                    <i class="fas fa-handshake"></i> Jadilah Mitra Kami
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-white">
                    <i class="fas fa-info-circle"></i> Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Partner Header */
.partner-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.header-decoration {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
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

/* Partner Stats */
.partner-stats {
    padding: 80px 0;
    background: white;
    margin-top: -40px;
    position: relative;
    z-index: 10;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.stat-card {
    background: white;
    padding: 35px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 25px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.95rem;
    color: #718096;
    font-weight: 500;
}

/* Partner Categories */
.partner-categories {
    padding: 60px 0 40px;
    background: #f8f9fa;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
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

.category-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-btn {
    padding: 12px 28px;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 50px;
    font-weight: 600;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.filter-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
}

/* Partners Grid Section */
.partners-grid-section {
    padding: 60px 0 100px;
    background: #f8f9fa;
}

.partner-group {
    margin-bottom: 60px;
}

.partner-group:last-child {
    margin-bottom: 0;
}

.group-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 35px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.group-title i {
    color: #667eea;
}

.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.partner-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.partner-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.partner-logo {
    background: #f7fafc;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 150px;
}

.partner-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.partner-info {
    padding: 25px 30px;
}

.partner-info h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.partner-type {
    font-size: 0.9rem;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 12px;
}

.partner-desc {
    font-size: 0.95rem;
    color: #718096;
    line-height: 1.6;
}

/* Partner Benefits */
.partner-benefits {
    padding: 100px 0;
    background: white;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 35px;
}

.benefit-card {
    text-align: center;
    padding: 40px 30px;
    background: #f8f9fa;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.benefit-card:hover {
    background: white;
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.benefit-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 2rem;
    transition: transform 0.3s ease;
}

.benefit-card:hover .benefit-icon {
    transform: scale(1.1) rotate(5deg);
}

.benefit-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 15px;
}

.benefit-card p {
    color: #718096;
    line-height: 1.7;
}

/* Testimonials */
.partner-testimonials {
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
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
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
    font-size: 1.5rem;
    margin-bottom: 25px;
}

.testimonial-text {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 30px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.testimonial-author img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #f7fafc;
}

.author-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 4px;
}

.author-position {
    font-size: 0.9rem;
    color: #718096;
}

.rating {
    display: flex;
    gap: 5px;
}

.rating i {
    color: #f59e0b;
    font-size: 1.1rem;
}

/* Partner CTA */
.partner-cta {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.partner-cta::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 600px;
    height: 600px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-content h2 {
    font-size: 2.8rem;
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
    padding: 16px 35px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    font-size: 1.05rem;
}

.btn-white {
    background: white;
    color: #667eea;
}

.btn-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

.btn-outline-white {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline-white:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 968px) {
    .page-title {
        font-size: 2.5rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .category-filter {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-btn {
        justify-content: center;
    }

    .partners-grid {
        grid-template-columns: 1fr;
    }

    .benefits-grid {
        grid-template-columns: 1fr;
    }

    .testimonials-grid {
        grid-template-columns: 1fr;
    }

    .cta-content h2 {
        font-size: 2rem;
    }

    .cta-buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.8s ease-out;
}

.animate-fade-in-delay {
    animation: fadeInUp 0.8s ease-out 0.2s both;
}

/* Filter animation */
.partner-group {
    animation: fadeInUp 0.6s ease-out;
}

.partner-group.hidden {
    display: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + '+';
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + '+';
            }
        }, 30);
    }

    // Observe stats section
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const target = parseInt(stat.getAttribute('data-target'));
                    animateCounter(stat, target);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.partner-stats');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const partnerGroups = document.querySelectorAll('.partner-group');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');

            const category = this.getAttribute('data-category');

            // Filter partner groups
            partnerGroups.forEach(group => {
                const groupCategory = group.getAttribute('data-category');
                
                if (category === 'all' || groupCategory === category) {
                    group.classList.remove('hidden');
                    group.style.display = 'block';
                    
                    // Animate cards
                    setTimeout(() => {
                        group.style.animation = 'fadeInUp 0.6s ease-out';
                    }, 10);
                } else {
                    group.classList.add('hidden');
                    group.style.display = 'none';
                }
            });

            // Smooth scroll to partners section
            document.querySelector('.partners-grid-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Card hover effect
    const partnerCards = document.querySelectorAll('.partner-card');
    partnerCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        });
    });

    // Lazy load partner logos
    const partnerLogos = document.querySelectorAll('.partner-logo img');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    img.style.opacity = '1';
                }, 100);
                
                imageObserver.unobserve(img);
            }
        });
    });

    partnerLogos.forEach(img => imageObserver.observe(img));

    // Testimonial card animation on scroll
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const testimonialObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    entry.target.style.transition = 'all 0.6s ease';
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
                
                testimonialObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    testimonialCards.forEach(card => testimonialObserver.observe(card));

    // Benefits card stagger animation
    const benefitCards = document.querySelectorAll('.benefit-card');
    const benefitObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';
                    entry.target.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
                
                benefitObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    benefitCards.forEach(card => benefitObserver.observe(card));
});
</script>
@endpush