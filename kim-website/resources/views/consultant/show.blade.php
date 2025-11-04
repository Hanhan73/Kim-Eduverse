@extends('layouts.app')

@section('title', $categoryData['title'] . ' - KIM Consultant')

@section('content')
<!-- Header -->
<section class="inquiry-header"
    style="background: linear-gradient(135deg, {{ $categoryData['color'] }}, {{ $categoryData['color'] }}dd);">
    <div class="container">
        <div class="header-back">
            <a href="{{ route('consultant.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="header-content">
            <div class="header-icon">
                <i class="fas {{ $categoryData['icon'] }}"></i>
            </div>
            <h1>{{ $categoryData['title'] }}</h1>
            <p>{{ $categoryData['description'] }}</p>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="inquiry-section">
    <div class="container">
        <div class="inquiry-layout">
            <!-- Left Side - Info -->
            <div class="inquiry-info">
                <h2>Kenapa Konsultasi dengan Kami?</h2>

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h3>Tim Ahli Berpengalaman</h3>
                            <p>Konsultan profesional dengan pengalaman puluhan tahun di bidangnya</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h3>Solusi Terukur</h3>
                            <p>Setiap rekomendasi didukung data dan analisis mendalam</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h3>Pendampingan Implementasi</h3>
                            <p>Kami tidak hanya memberikan saran, tapi juga membantu eksekusi</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h3>Kerahasiaan Terjamin</h3>
                            <p>Data dan informasi bisnis Anda 100% aman dan rahasia</p>
                        </div>
                    </div>
                </div>

                <div class="contact-box">
                    <h3>Perlu Bicara Langsung?</h3>
                    <p>Hubungi kami untuk diskusi lebih lanjut</p>
                    <div class="contact-methods">
                        <a href="tel:+6281234567890" class="contact-link">
                            <i class="fas fa-phone"></i> +62 812-3456-7890
                        </a>
                        <a href="mailto:konsultan@kim.co.id" class="contact-link">
                            <i class="fas fa-envelope"></i> konsultan@kim.co.id
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="inquiry-form-container">
                <div class="form-header">
                    <h2>Form Konsultasi</h2>
                    <p>Isi form di bawah dan tim kami akan menghubungi Anda</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    Mohon periksa kembali form Anda
                </div>
                @endif

                <form action="{{ route('consultant.inquiry') }}" method="POST" class="inquiry-form">
                    @csrf
                    <input type="hidden" name="kategori" value="{{ $category }}">

                    <div class="form-group">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input type="text" id="nama" name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap Anda" value="{{ old('nama') }}" required>
                        @error('nama')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                        <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="perusahaan" class="form-label">
                            Nama Perusahaan/Instansi
                        </label>
                        <input type="text" id="perusahaan" name="perusahaan" class="form-control" placeholder="Opsional"
                            value="{{ old('perusahaan') }}">
                    </div>

                    <div class="form-group">
                        <label for="telepon" class="form-label">
                            Nomor Telepon/WhatsApp
                        </label>
                        <input type="tel" id="telepon" name="telepon" class="form-control"
                            placeholder="08xx-xxxx-xxxx (Opsional)" value="{{ old('telepon') }}">
                        <small class="form-help">Nomor WhatsApp lebih diutamakan untuk respon cepat</small>
                    </div>

                    <div class="form-group">
                        <label for="pesan" class="form-label">
                            Ceritakan Kebutuhan Anda
                        </label>
                        <textarea id="pesan" name="pesan" class="form-control" rows="5"
                            placeholder="Jelaskan singkat tentang kebutuhan konsultasi Anda (opsional)">{{ old('pesan') }}</textarea>
                    </div>

                    <div class="form-footer">
                        <p class="form-note">
                            <i class="fas fa-info-circle"></i>
                            Dengan mengisi form ini, Anda menyetujui untuk dihubungi oleh tim KIM Consultant
                        </p>
                        <button type="submit" class="btn btn-submit" style="background: {{ $categoryData['color'] }};">
                            <i class="fas fa-paper-plane"></i>
                            Saya Tertarik untuk Berkonsultasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Related Services -->
<section class="related-section">
    <div class="container">
        <h2>Layanan Konsultasi Lainnya</h2>
        <p class="section-subtitle">Jelajahi bidang konsultasi lain yang mungkin Anda butuhkan</p>

        <div class="related-grid">
            <a href="{{ route('consultant.index') }}" class="related-card">
                <i class="fas fa-th-large"></i>
                <span>Lihat Semua Kategori</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.inquiry-header {
    color: white;
    padding: 120px 0 60px;
    position: relative;
    overflow: hidden;
}

.inquiry-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 500px;
    height: 500px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.header-back {
    margin-bottom: 30px;
}

.btn-back {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 10px 20px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateX(-5px);
}

.header-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.header-icon {
    width: 100px;
    height: 100px;
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 25px;
}

.header-content h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.header-content p {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
}

.inquiry-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.inquiry-layout {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 50px;
}

/* Info Side */
.inquiry-info h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 30px;
    color: #2d3748;
}

.info-list {
    margin-bottom: 40px;
}

.info-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.info-item h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2d3748;
}

.info-item p {
    color: #718096;
    line-height: 1.6;
}

.contact-box {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.contact-box h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2d3748;
}

.contact-box p {
    color: #718096;
    margin-bottom: 20px;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-link {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #3182ce;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.contact-link:hover {
    color: #2c5aa0;
    transform: translateX(5px);
}

/* Form Side */
.inquiry-form-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 35px;
}

.form-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #2d3748;
}

.form-header p {
    color: #718096;
    font-size: 1rem;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
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

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2d3748;
    font-size: 0.95rem;
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

.form-help {
    display: block;
    color: #718096;
    font-size: 0.85rem;
    margin-top: 6px;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-footer {
    margin-top: 30px;
}

.form-note {
    background: #f7fafc;
    padding: 15px;
    border-radius: 10px;
    font-size: 0.9rem;
    color: #718096;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.form-note i {
    color: #3182ce;
    margin-top: 2px;
}

.btn-submit {
    width: 100%;
    padding: 16px;
    border: none;
    border-radius: 12px;
    color: white;
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
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

/* Related Section */
.related-section {
    padding: 80px 0;
    text-align: center;
    background: white;
}

.related-section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #2d3748;
}

.section-subtitle {
    color: #718096;
    font-size: 1.1rem;
    margin-bottom: 40px;
}

.related-grid {
    display: flex;
    justify-content: center;
}

.related-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 20px 40px;
    border-radius: 15px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.related-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

@media (max-width: 968px) {
    .inquiry-layout {
        grid-template-columns: 1fr;
    }

    .header-content h1 {
        font-size: 2rem;
    }

    .inquiry-form-container {
        padding: 30px 20px;
    }
}
</style>
@endpush