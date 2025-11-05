@extends('layouts.app')

@section('title', 'Tim Kami - PT KIM')

@section('content')
<!-- Page Header -->
<section class="team-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title animate-fade-in">Tim Kami</h1>
            <p class="page-subtitle animate-fade-in-delay">
                Bertemu dengan orang-orang hebat di balik kesuksesan PT KIM
            </p>
        </div>
    </div>
    <div class="header-wave">
    </div>
</section>

<!-- Leadership Team -->
<section class="leadership-team">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Kepemimpinan</span>
            <h2 class="section-title">Tim Kepemimpinan</h2>
            <p class="section-subtitle">
                Pemimpin berpengalaman yang membawa visi dan misi perusahaan
            </p>
        </div>

        <div class="leadership-grid">
            <!-- CEO -->
            <div class="leader-card featured">
                <div class="leader-image">
                    <img src="https://via.placeholder.com/400x500/667eea/ffffff?text=CEO" alt="CEO">
                    <div class="leader-overlay">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="leader-info">
                    <div class="leader-badge">CEO</div>
                    <h3 class="leader-name">Dr. Ahmad Wijaya, MBA</h3>
                    <p class="leader-position">Chief Executive Officer</p>
                    <p class="leader-bio">
                        Lebih dari 15 tahun pengalaman di industri teknologi dan konsultasi.
                        Lulusan S3 Manajemen Teknologi dari ITB dan MBA dari INSEAD.
                    </p>
                    <div class="leader-expertise">
                        <span class="expertise-tag">Strategic Planning</span>
                        <span class="expertise-tag">Business Development</span>
                        <span class="expertise-tag">Innovation</span>
                    </div>
                </div>
            </div>

            <!-- CTO -->
            <div class="leader-card">
                <div class="leader-image">
                    <img src="https://via.placeholder.com/400x500/48bb78/ffffff?text=CTO" alt="CTO">
                    <div class="leader-overlay">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="leader-info">
                    <div class="leader-badge">CTO</div>
                    <h3 class="leader-name">Budi Santoso, M.Kom</h3>
                    <p class="leader-position">Chief Technology Officer</p>
                    <p class="leader-bio">
                        Expert dalam software architecture dan AI. 12 tahun pengalaman
                        memimpin tim developer di berbagai startup dan korporasi.
                    </p>
                    <div class="leader-expertise">
                        <span class="expertise-tag">Software Architecture</span>
                        <span class="expertise-tag">AI & Machine Learning</span>
                        <span class="expertise-tag">DevOps</span>
                    </div>
                </div>
            </div>

            <!-- COO -->
            <div class="leader-card">
                <div class="leader-image">
                    <img src="https://via.placeholder.com/400x500/f56565/ffffff?text=COO" alt="COO">
                    <div class="leader-overlay">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <div class="leader-info">
                    <div class="leader-badge">COO</div>
                    <h3 class="leader-name">Siti Nurhaliza, MBA</h3>
                    <p class="leader-position">Chief Operating Officer</p>
                    <p class="leader-bio">
                        Spesialis dalam operational excellence dan project management.
                        10 tahun pengalaman di konsultasi manajemen.
                    </p>
                    <div class="leader-expertise">
                        <span class="expertise-tag">Operations</span>
                        <span class="expertise-tag">Project Management</span>
                        <span class="expertise-tag">Process Improvement</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Department Heads -->
<section class="department-heads">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Kepala Divisi</span>
            <h2 class="section-title">Tim Divisi</h2>
        </div>

        <div class="department-grid">
            <!-- Consultant Division -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>KIM Consultant</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/667eea/ffffff?text=RW" alt="Head">
                    <div>
                        <div class="dept-head-name">Rizki Wahyudi, MBA</div>
                        <div class="dept-head-title">Head of Consultant Division</div>
                    </div>
                </div>
                <p class="dept-description">
                    Memimpin tim konsultan profesional di berbagai bidang industri
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Konsultan</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">Proyek</div>
                    </div>
                </div>
            </div>

            <!-- Developer Division -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                    <i class="fas fa-code"></i>
                </div>
                <h3>KIM Developer</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/48bb78/ffffff?text=DN" alt="Head">
                    <div>
                        <div class="dept-head-name">Dimas Nugroho, M.T</div>
                        <div class="dept-head-title">Head of Developer Division</div>
                    </div>
                </div>
                <p class="dept-description">
                    Mengkoordinir tim developer dalam pembuatan aplikasi berkualitas
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">25+</div>
                        <div class="stat-label">Developer</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Aplikasi</div>
                    </div>
                </div>
            </div>

            <!-- Edutech Division -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #f56565, #e53e3e);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>KIM Edutech</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/f56565/ffffff?text=LP" alt="Head">
                    <div>
                        <div class="dept-head-name">Linda Permata, M.Pd</div>
                        <div class="dept-head-title">Head of Edutech Division</div>
                    </div>
                </div>
                <p class="dept-description">
                    Mengembangkan solusi edutech dan platform pembelajaran digital
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Instruktur</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">5K+</div>
                        <div class="stat-label">Peserta</div>
                    </div>
                </div>
            </div>

            <!-- HR & Admin -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #805ad5, #6b46c1);">
                    <i class="fas fa-users"></i>
                </div>
                <h3>HR & Administration</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/805ad5/ffffff?text=FS" alt="Head">
                    <div>
                        <div class="dept-head-name">Fitri Suryani, S.Psi</div>
                        <div class="dept-head-title">Head of HR & Admin</div>
                    </div>
                </div>
                <p class="dept-description">
                    Mengelola SDM dan operasional administrasi perusahaan
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">60+</div>
                        <div class="stat-label">Karyawan</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Kepuasan</div>
                    </div>
                </div>
            </div>

            <!-- Marketing -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #3182ce, #2c5aa0);">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3>Marketing & Sales</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/3182ce/ffffff?text=AP" alt="Head">
                    <div>
                        <div class="dept-head-name">Ari Prasetyo, S.E</div>
                        <div class="dept-head-title">Head of Marketing</div>
                    </div>
                </div>
                <p class="dept-description">
                    Mengembangkan strategi pemasaran dan hubungan dengan klien
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">12+</div>
                        <div class="stat-label">Tim</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Klien</div>
                    </div>
                </div>
            </div>

            <!-- Finance -->
            <div class="dept-card">
                <div class="dept-icon" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Finance & Accounting</h3>
                <div class="dept-head">
                    <img src="https://via.placeholder.com/80x80/d69e2e/ffffff?text=RA" alt="Head">
                    <div>
                        <div class="dept-head-name">Rudi Atmaja, SE., Ak</div>
                        <div class="dept-head-title">Head of Finance</div>
                    </div>
                </div>
                <p class="dept-description">
                    Mengelola keuangan dan akuntansi perusahaan secara profesional
                </p>
                <div class="dept-stats">
                    <div class="dept-stat">
                        <div class="stat-number">8+</div>
                        <div class="stat-label">Tim</div>
                    </div>
                    <div class="dept-stat">
                        <div class="stat-number">AAA</div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Culture -->
<section class="team-culture">
    <div class="container">
        <div class="culture-grid">
            <div class="culture-content">
                <span class="section-badge">Budaya Tim</span>
                <h2 class="section-title">Budaya Kerja Kami</h2>
                <p class="culture-description">
                    Kami percaya bahwa tim yang bahagia dan termotivasi adalah kunci kesuksesan.
                    Budaya kerja kami dibangun atas dasar kolaborasi, inovasi, dan pembelajaran berkelanjutan.
                </p>

                <div class="culture-values">
                    <div class="culture-item">
                        <div class="culture-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h4>Innovation First</h4>
                            <p>Kami mendorong ide-ide baru dan eksperimen</p>
                        </div>
                    </div>

                    <div class="culture-item">
                        <div class="culture-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div>
                            <h4>Collaboration</h4>
                            <p>Bekerja bersama untuk hasil yang lebih baik</p>
                        </div>
                    </div>

                    <div class="culture-item">
                        <div class="culture-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h4>Continuous Learning</h4>
                            <p>Program training dan sertifikasi rutin</p>
                        </div>
                    </div>

                    <div class="culture-item">
                        <div class="culture-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div>
                            <h4>Work-Life Balance</h4>
                            <p>Fleksibilitas waktu dan remote working</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="culture-images">
                <div class="culture-image main">
                    <img src="https://via.placeholder.com/500x400/667eea/ffffff?text=Team+Culture" alt="Team Culture">
                </div>
                <div class="culture-image small">
                    <img src="https://via.placeholder.com/250x200/48bb78/ffffff?text=Team+Work" alt="Team Work">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join Team CTA -->
<section class="join-team-cta">
    <div class="container">
        <div class="cta-box">
            <div class="cta-icon">
                <i class="fas fa-users"></i>
            </div>
            <h2>Bergabung dengan Tim Kami</h2>
            <p>
                Kami selalu mencari talenta terbaik untuk bergabung dengan keluarga besar PT KIM.
                Kirimkan CV Anda dan jadilah bagian dari perubahan.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('contact.index') }}" class="btn btn-primary-light">
                    <i class="fas fa-paper-plane"></i> Kirim Lamaran
                </a>
                <a href="#" class="btn btn-outline-light">
                    <i class="fas fa-briefcase"></i> Lihat Lowongan
                </a>
            </div>

            <div class="cta-benefits">
                <div class="benefit-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Gaji Kompetitif</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-check-circle"></i>
                    <span>BPJS & Asuransi</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Training Gratis</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Career Growth</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Team Header */
.team-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.team-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" fill-opacity="0.1"/></svg>');
    background-size: 100px 100px;
}

.header-content {
    position: relative;
    z-index: 2;
}

.page-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 600px;
    margin: 0 auto;
}

.header-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
}

.header-wave svg {
    display: block;
    width: 100%;
    height: 60px;
}

/* Organization Structure */
.org-structure {
    padding: 100px 0;
    background: white;
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

.section-header {
    text-align: center;
    margin-bottom: 80px;
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

.org-chart {
    max-width: 1000px;
    margin: 0 auto;
}

.org-level {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.org-box {
    background: white;
    padding: 30px 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-width: 220px;
}

.org-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.ceo-box {
    border-top: 4px solid #f59e0b;
}

.cto-box {
    border-top: 4px solid #48bb78;
}

.coo-box {
    border-top: 4px solid #3182ce;
}

.cfo-box {
    border-top: 4px solid #805ad5;
}

.dept-box {
    border-top: 4px solid #667eea;
    min-width: 180px;
}

.org-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.8rem;
}

.org-icon.small {
    width: 50px;
    height: 50px;
    font-size: 1.4rem;
}

.org-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #718096;
    margin-bottom: 8px;
}

.org-title.small {
    font-size: 0.85rem;
}

.org-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
}

.org-connector-vertical {
    width: 2px;
    height: 40px;
    background: #cbd5e0;
    margin: 0 auto;
}

.org-connector-line {
    width: 100%;
    height: 2px;
    background: #cbd5e0;
    margin: 20px 0;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

/* Leadership Team */
.leadership-team {
    padding: 100px 0;
    background: #f8f9fa;
}

.leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
}

.leader-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.leader-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.leader-card.featured {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 1fr 1.5fr;
}

.leader-image {
    position: relative;
    overflow: hidden;
    height: 500px;
}

.leader-card.featured .leader-image {
    height: auto;
}

.leader-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.leader-card:hover .leader-image img {
    transform: scale(1.1);
}

.leader-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 30px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.leader-card:hover .leader-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-3px);
}

.leader-info {
    padding: 40px 35px;
}

.leader-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.leader-name {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.leader-position {
    font-size: 1.05rem;
    color: #667eea;
    font-weight: 600;
    margin-bottom: 20px;
}

.leader-bio {
    font-size: 1rem;
    color: #4a5568;
    line-height: 1.7;
    margin-bottom: 25px;
}

.leader-expertise {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.expertise-tag {
    background: #f7fafc;
    color: #4a5568;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.expertise-tag:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

/* Department Heads */
.department-heads {
    padding: 100px 0;
    background: white;
}

.department-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 35px;
}

.dept-card {
    background: white;
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.dept-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    border-color: #667eea;
}

.dept-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    margin-bottom: 25px;
}

.dept-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 25px;
}

.dept-head {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding: 20px;
    background: #f7fafc;
    border-radius: 12px;
}

.dept-head img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.dept-head-name {
    font-size: 1.05rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 4px;
}

.dept-head-title {
    font-size: 0.9rem;
    color: #718096;
}

.dept-description {
    font-size: 0.95rem;
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 25px;
}

.dept-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.dept-stat {
    text-align: center;
    padding: 15px;
    background: #f7fafc;
    border-radius: 10px;
}

.dept-stat .stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 5px;
}

.dept-stat .stat-label {
    font-size: 0.85rem;
    color: #718096;
}

/* Team Culture */
.team-culture {
    padding: 100px 0;
    background: linear-gradient(to bottom, #f8f9fa, white);
}

.culture-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
}

.culture-description {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 40px;
}

.culture-values {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.culture-item {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.culture-icon {
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

.culture-item h4 {
    font-size: 1.15rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.culture-item p {
    color: #718096;
    line-height: 1.6;
}

.culture-images {
    position: relative;
}

.culture-image.main {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.culture-image.main img {
    width: 100%;
    display: block;
}

.culture-image.small {
    position: absolute;
    bottom: -30px;
    left: -40px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    border: 5px solid white;
}

.culture-image.small img {
    width: 100%;
    display: block;
}

/* Join Team CTA */
.join-team-cta {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.join-team-cta::before {
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

.cta-box {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    color: white;
    position: relative;
    z-index: 2;
}

.cta-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    font-size: 3rem;
    color: white;
}

.cta-box h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-box p {
    font-size: 1.2rem;
    opacity: 0.95;
    line-height: 1.7;
    margin-bottom: 40px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 50px;
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

.btn-primary-light {
    background: white;
    color: #667eea;
}

.btn-primary-light:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

.btn-outline-light {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline-light:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

.cta-benefits {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 500;
}

.benefit-item i {
    font-size: 1.2rem;
    color: #48bb78;
}

/* Responsive */
@media (max-width: 968px) {
    .page-title {
        font-size: 2.5rem;
    }

    .org-level {
        flex-direction: column;
        align-items: center;
    }

    .org-connector-line {
        display: none;
    }

    .leadership-grid {
        grid-template-columns: 1fr;
    }

    .leader-card.featured {
        grid-column: 1;
        grid-template-columns: 1fr;
    }

    .leader-image {
        height: 400px;
    }

    .department-grid {
        grid-template-columns: 1fr;
    }

    .culture-grid {
        grid-template-columns: 1fr;
    }

    .culture-image.small {
        left: 20px;
        bottom: 20px;
    }

    .cta-box h2 {
        font-size: 2rem;
    }

    .cta-benefits {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 640px) {
    .org-box {
        min-width: 100%;
    }

    .dept-card {
        padding: 30px 25px;
    }

    .culture-image.small {
        display: none;
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

/* Hover Effects */
.dept-card:hover .dept-icon {
    transform: scale(1.1) rotate(5deg);
}

.culture-item:hover .culture-icon {
    transform: scale(1.1) rotate(-5deg);
}

/* Loading State */
.leader-card img,
.culture-image img,
.client-logo img {
    background: #f0f0f0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.leader-card, .dept-card, .culture-item').forEach(el => {
        observer.observe(el);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add hover effect sound (optional)
    const cards = document.querySelectorAll('.leader-card, .dept-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        });
    });

    // Lazy load images
    const images = document.querySelectorAll('img[src*="placeholder"]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
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

    images.forEach(img => imageObserver.observe(img));

    // Parallax effect for culture images
    const cultureImages = document.querySelectorAll('.culture-image');

    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;

        cultureImages.forEach((img, index) => {
            const speed = index === 0 ? 0.5 : 0.8;
            const yPos = -(scrolled * speed / 10);
            img.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Organization chart animation
    const orgBoxes = document.querySelectorAll('.org-box');
    orgBoxes.forEach((box, index) => {
        setTimeout(() => {
            box.style.opacity = '0';
            box.style.transform = 'translateY(20px)';
            box.style.transition = 'all 0.5s ease';

            setTimeout(() => {
                box.style.opacity = '1';
                box.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });

    // Counter animation for department stats
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

    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target.querySelector('.stat-number');
                if (statNumber && statNumber.textContent.includes('+')) {
                    const target = parseInt(statNumber.textContent);
                    if (!isNaN(target)) {
                        statNumber.textContent = '0+';
                        animateCounter(statNumber, target);
                    }
                }
                statObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    document.querySelectorAll('.dept-stat').forEach(stat => {
        statObserver.observe(stat);
    });

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush