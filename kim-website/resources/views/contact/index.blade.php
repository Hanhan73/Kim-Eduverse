@extends('layouts.app')

@section('title', 'Hubungi Kami - PT KIM')

@section('content')
<!-- Page Header -->
<section class="contact-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title animate-fade-in">Hubungi Kami</h1>
            <p class="page-subtitle animate-fade-in-delay">
                Kami siap membantu mewujudkan transformasi digital bisnis Anda
            </p>
        </div>
    </div>
</section>

<!-- Contact Methods -->
<section class="contact-methods">
    <div class="container">
        <div class="methods-grid">
            <div class="method-card">
                <div class="method-icon phone">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Telepon</h3>
                <p class="method-desc">Hubungi kami langsung untuk konsultasi</p>
                <a href="tel:+6281234567890" class="method-link">+62 812-3456-7890</a>
                <p class="method-time">Senin - Jumat: 08:00 - 17:00 WIB</p>
            </div>

            <div class="method-card">
                <div class="method-icon whatsapp">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>WhatsApp</h3>
                <p class="method-desc">Chat dengan tim kami via WhatsApp</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="method-link">+62 812-3456-7890</a>
                <p class="method-time">Fast Response 24/7</p>
            </div>

            <div class="method-card">
                <div class="method-icon email">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p class="method-desc">Kirim email untuk pertanyaan detail</p>
                <a href="mailto:info@kim.co.id" class="method-link">info@kim.co.id</a>
                <p class="method-time">Response within 24 hours</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="contact-form-section">
    <div class="container">
        <div class="contact-layout">
            <!-- Contact Form -->
            <div class="form-container">
                <div class="form-header">
                    <h2>Kirim Pesan</h2>
                    <p>Isi form di bawah dan kami akan menghubungi Anda segera</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Terima kasih!</strong>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Oops!</strong>
                        <p>Mohon periksa kembali form Anda</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama" class="form-label">
                                <i class="fas fa-user"></i> Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ old('nama') }}"
                                   required>
                            @error('nama')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email <span class="required">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="nama@email.com"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telepon" class="form-label">
                                <i class="fas fa-phone"></i> Nomor Telepon
                            </label>
                            <input type="tel" 
                                   id="telepon" 
                                   name="telepon" 
                                   class="form-control"
                                   placeholder="08xx-xxxx-xxxx"
                                   value="{{ old('telepon') }}">
                        </div>

                        <div class="form-group">
                            <label for="subjek" class="form-label">
                                <i class="fas fa-tag"></i> Subjek <span class="required">*</span>
                            </label>
                            <select id="subjek" 
                                    name="subjek" 
                                    class="form-control @error('subjek') is-invalid @enderror"
                                    required>
                                <option value="">Pilih Subjek</option>
                                <option value="Konsultasi" {{ old('subjek') == 'Konsultasi' ? 'selected' : '' }}>Konsultasi Bisnis</option>
                                <option value="Pengembangan Aplikasi" {{ old('subjek') == 'Pengembangan Aplikasi' ? 'selected' : '' }}>Pengembangan Aplikasi</option>
                                <option value="Platform Edutech" {{ old('subjek') == 'Platform Edutech' ? 'selected' : '' }}>Platform Edutech</option>
                                <option value="Kerjasama" {{ old('subjek') == 'Kerjasama' ? 'selected' : '' }}>Kerjasama/Partnership</option>
                                <option value="Lainnya" {{ old('subjek') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('subjek')
                            <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pesan" class="form-label">
                            <i class="fas fa-comment-dots"></i> Pesan <span class="required">*</span>
                        </label>
                        <textarea id="pesan" 
                                  name="pesan" 
                                  class="form-control @error('pesan') is-invalid @enderror"
                                  rows="6"
                                  placeholder="Ceritakan kebutuhan Anda..."
                                  required>{{ old('pesan') }}</textarea>
                        @error('pesan')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Pesan
                        </button>
                        <p class="form-note">
                            <i class="fas fa-shield-alt"></i>
                            Data Anda aman dan tidak akan dibagikan kepada pihak ketiga
                        </p>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="info-container">
                <div class="info-box">
                    <h3>Informasi Kontak</h3>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Alamat Kantor</h4>
                            <p>Jl. 123<br>Bandung 55281<br>Indonesia</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Hubungi Kami</h4>
                            <p>Phone: <a href="tel:+6281234567890">+62 812-3456-7890</a><br>
                            Email: <a href="mailto:info@kim.co.id">info@kim.co.id</a></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Media Sosial</h4>
                            <div class="social-links">
                                <a href="#" class="social-btn instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-btn linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="social-btn youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quick-links-box">
                    <h3>Link Cepat</h3>
                    <div class="quick-links">
                        <a href="{{ route('consultant.index') }}" class="quick-link">
                            <i class="fas fa-handshake"></i>
                            <span>KIM Consultant</span>
                        </a>
                        <a href="{{ route('developer.index') }}" class="quick-link">
                            <i class="fas fa-code"></i>
                            <span>KIM Developer</span>
                        </a>
                        <a href="{{ route('edutech.index') }}" class="quick-link">
                            <i class="fas fa-graduation-cap"></i>
                            <span>KIM Edutech</span>
                        </a>
                        <a href="{{ route('about.profile') }}" class="quick-link">
                            <i class="fas fa-info-circle"></i>
                            <span>Tentang Kami</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
            <p class="section-subtitle">Temukan jawaban untuk pertanyaan umum</p>
        </div>

        <div class="faq-container">
            <div class="faq-item">
                <button class="faq-question">
                    <span>Bagaimana cara berkonsultasi dengan PT KIM?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Anda dapat menghubungi kami melalui form di atas, WhatsApp, telepon, atau email. Tim kami akan merespons dalam 24 jam untuk menjadwalkan sesi konsultasi gratis.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Berapa lama waktu pengerjaan proyek?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Waktu pengerjaan bervariasi tergantung kompleksitas proyek. Proyek kecil 1-2 bulan, menengah 3-6 bulan, dan besar 6-12 bulan. Kami akan memberikan timeline detail setelah analisis kebutuhan.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Apakah ada garansi untuk layanan yang diberikan?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Ya, kami memberikan garansi bug fix minimal 3-6 bulan tergantung paket yang dipilih. Maintenance dan support jangka panjang juga tersedia dengan biaya terpisah.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Apakah PT KIM melayani klien di luar Bandung?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Ya, kami melayani klien di seluruh Indonesia bahkan internasional. Banyak meeting dapat dilakukan secara online, dan kami juga bersedia melakukan kunjungan ke lokasi klien jika diperlukan.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Bagaimana sistem pembayaran yang diterapkan?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Kami menerapkan sistem pembayaran bertahap (termin) sesuai milestone proyek. Biasanya: 30% DP, 40% saat development selesai, dan 30% saat project delivery. Term dapat disesuaikan dengan kesepakatan.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Apakah saya mendapatkan source code aplikasi?</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Ya, untuk paket full custom development, Anda akan mendapatkan full source code beserta dokumentasi lengkap. Untuk beberapa produk ready-made, kami menawarkan opsi lisensi penggunaan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="contact-cta">
    <div class="container">
        <div class="cta-box">
            <div class="cta-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h2>Masih Ada Pertanyaan?</h2>
            <p>Tim kami siap membantu menjawab semua pertanyaan Anda</p>
            <div class="cta-buttons">
                <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                </a>
                <a href="tel:+6281234567890" class="btn btn-call">
                    <i class="fas fa-phone-alt"></i> Telepon Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Contact Header */
.contact-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.contact-header::before {
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
    max-width: 700px;
    margin: 0 auto;
}

/* Contact Methods */
.contact-methods {
    padding: 80px 0;
    background: white;
    margin-top: -40px;
    position: relative;
    z-index: 10;
}

.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.method-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-top: 4px solid transparent;
}

.method-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.method-icon {
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

.method-icon.phone {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.method-card:nth-child(1) {
    border-top-color: #667eea;
}

.method-icon.whatsapp {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.method-card:nth-child(2) {
    border-top-color: #25D366;
}

.method-icon.email {
    background: linear-gradient(135deg, #f56565, #e53e3e);
}

.method-card:nth-child(3) {
    border-top-color: #f56565;
}

.method-icon.location {
    background: linear-gradient(135deg, #3182ce, #2c5aa0);
}

.method-card:nth-child(4) {
    border-top-color: #3182ce;
}

.method-card h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.method-desc {
    color: #718096;
    margin-bottom: 15px;
    font-size: 0.95rem;
}

.method-link {
    display: block;
    font-size: 1.1rem;
    font-weight: 600;
    color: #667eea;
    text-decoration: none;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

.method-link:hover {
    color: #764ba2;
}

.method-time {
    font-size: 0.85rem;
    color: #a0aec0;
}

/* Contact Form Section */
.contact-form-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.contact-layout {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 50px;
}

.form-container {
    background: white;
    padding: 50px 45px;
    border-radius: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.form-header {
    margin-bottom: 35px;
}

.form-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.form-header p {
    color: #718096;
    font-size: 1rem;
}

.alert {
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 30px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.alert i {
    font-size: 1.5rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert strong {
    display: block;
    margin-bottom: 5px;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 10px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    color: #667eea;
    font-size: 0.9rem;
}

.required {
    color: #e53e3e;
}

.form-control {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #e53e3e;
}

.error-text {
    display: block;
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 6px;
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

select.form-control {
    cursor: pointer;
}

.form-footer {
    margin-top: 10px;
}

.btn-submit {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.form-note {
    margin-top: 20px;
    font-size: 0.9rem;
    color: #718096;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.form-note i {
    color: #48bb78;
}

/* Info Container */
.info-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.info-box,
.quick-links-box {
    background: white;
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.info-box h3,
.quick-links-box h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-icon {
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

.info-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.info-content p {
    color: #718096;
    line-height: 1.7;
    font-size: 0.95rem;
}

.info-content a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.info-content a:hover {
    color: #764ba2;
}

.social-links {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.social-btn {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.social-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.social-btn.facebook {
    background: #1877f2;
}

.social-btn.twitter {
    background: #1da1f2;
}

.social-btn.instagram {
    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
}

.social-btn.linkedin {
    background: #0077b5;
}

.social-btn.youtube {
    background: #ff0000;
}

/* Quick Links */
.quick-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    background: #f7fafc;
    border-radius: 12px;
    text-decoration: none;
    color: #4a5568;
    font-weight: 500;
    transition: all 0.3s ease;
}

.quick-link:hover {
    background: #667eea;
    color: white;
    transform: translateX(5px);
}

.quick-link i {
    font-size: 1.2rem;
    color: #667eea;
    transition: color 0.3s ease;
}

.quick-link:hover i {
    color: white;
}

/* FAQ Section */
.faq-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.faq-container {
    max-width: 900px;
    margin: 0 auto;
}

.faq-item {
    background: white;
    margin-bottom: 20px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.faq-question {
    width: 100%;
    padding: 25px 30px;
    background: white;
    border: none;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.faq-question:hover {
    color: #667eea;
}

.faq-question i {
    font-size: 1rem;
    color: #718096;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
    color: #667eea;
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.faq-item.active .faq-answer {
    max-height: 500px;
    padding: 0 30px 25px 30px;
}

.faq-answer p {
    color: #4a5568;
    line-height: 1.8;
    font-size: 1rem;
}

/* Contact CTA */
.contact-cta {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.contact-cta::before {
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
}

.cta-box h2 {
    font-size: 2.8rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-box p {
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

.btn-whatsapp {
    background: #25D366;
    color: white;
}

.btn-whatsapp:hover {
    background: #128C7E;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(37, 211, 102, 0.4);
}

.btn-call {
    background: white;
    color: #667eea;
}

.btn-call:hover {
    background: #f7fafc;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 968px) {
    .page-title {
        font-size: 2.5rem;
    }

    .methods-grid {
        grid-template-columns: 1fr;
    }

    .contact-layout {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-container {
        padding: 40px 30px;
    }

    .directions-grid {
        grid-template-columns: 1fr;
    }

    .cta-box h2 {
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

/* Loading state for form */
.form-control:disabled {
    background: #f7fafc;
    cursor: not-allowed;
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Success animation */
@keyframes successPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.alert-success {
    animation: successPulse 0.5s ease;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            // Close other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });

    // Form validation enhancement
    const form = document.querySelector('.contact-form');
    const submitBtn = document.querySelector('.btn-submit');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            
            // Re-enable after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pesan';
            }, 3000);
        });

        // Real-time validation
        const inputs = form.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Email validation
        const emailInput = form.querySelector('input[type="email"]');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                }
            });
        }
    }


    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(30px)';
                entry.target.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements
    document.querySelectorAll('.method-card, .info-box, .direction-item, .faq-item').forEach(el => {
        observer.observe(el);
    });

    // Auto-hide success/error alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Character counter for textarea
    const textarea = document.querySelector('textarea[name="pesan"]');
    if (textarea) {
        const maxLength = 2000;
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.cssText = 'text-align: right; font-size: 0.85rem; color: #718096; margin-top: 5px;';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} karakter tersisa`;
            
            if (remaining < 100) {
                counter.style.color = '#e53e3e';
            } else {
                counter.style.color = '#718096';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Click tracking for contact methods
    document.querySelectorAll('.method-link, .social-btn, .quick-link').forEach(link => {
        link.addEventListener('click', function() {
            console.log('Contact method clicked:', this.textContent || this.getAttribute('href'));
        });
    });

    // Parallax effect for CTA section
    window.addEventListener('scroll', function() {
        const ctaSection = document.querySelector('.contact-cta');
        if (ctaSection) {
            const scrolled = window.pageYOffset;
            const ctaBefore = ctaSection.querySelector('::before');
            if (ctaBefore) {
                ctaSection.style.backgroundPosition = `center ${scrolled * 0.5}px`;
            }
        }
    });
});
</script>
@endpush