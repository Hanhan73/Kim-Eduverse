@extends('layouts.app')

@section('title', $category['title'].' - KIM Developer')

@section('content')

<!-- Header -->
<section class="developer-detail-header" style="--accent: {{ $category['color'] }}">
    <div class="container">
        <div class="header-box">
            <div class="icon">
                <i class="fas {{ $category['icon'] }}"></i>
            </div>
            <h1>{{ $category['title'] }}</h1>
            <p>{{ $category['description'] }}</p>
        </div>
    </div>
</section>

<!-- Content -->
<section class="developer-detail-content">
    <div class="container detail-grid">

        <!-- Info -->
        <div class="detail-info">
            <h2>Apa yang Bisa Kami Bangun?</h2>
            <ul>
                <li>Sistem sesuai kebutuhan bisnis</li>
                <li>Desain modern & responsif</li>
                <li>Aman dan scalable</li>
                <li>Dukungan pengembangan lanjutan</li>
            </ul>

            <div class="info-note">
                💡 Ceritakan ide Anda, tim kami akan bantu merumuskannya menjadi aplikasi nyata.
            </div>
        </div>

        <!-- Form -->
        <div class="detail-form">
            <h2>Ajukan Kebutuhan Aplikasi</h2>

            @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('developer.store') }}">
                @csrf
                <input type="hidden" name="category" value="{{ $categoryKey }}">

                <div class="form-group">
                    <label>Nama <span class="required">*</span></label>
                    <input type="text" name="name" required placeholder="Nama Anda">
                </div>

                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" required placeholder="email@contoh.com">
                </div>

                <div class="form-group">
                    <label>No WhatsApp <span class="optional">(opsional)</span></label>
                    <input type="text" name="whatsapp" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label>Deskripsi Kebutuhan <span class="required">*</span></label>
                    <textarea name="description" rows="4" required
                        placeholder="Ceritakan aplikasi yang Anda butuhkan..."></textarea>
                </div>

                <button class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Permintaan
                </button>
            </form>

            <p class="form-note">
                Email digunakan sebagai kontak utama.
                WhatsApp bersifat opsional untuk komunikasi lebih cepat.
            </p>
        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
.developer-detail-header {
    background: linear-gradient(135deg, var(--accent), #2d3748);
    color: white;
    padding: 100px 0 70px;
    text-align: center;
}

.header-box .icon {
    width: 90px;
    height: 90px;
    background: rgba(255, 255, 255, .15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 20px;
}

.header-box h1 {
    font-size: 2.6rem;
    font-weight: 700;
}

.header-box p {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 15px auto 0;
    opacity: .95;
}

/* Content */
.developer-detail-content {
    padding: 80px 0;
    background: #f8f9fa;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 40px;
}

.detail-info {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
}

.detail-info h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
}

.detail-info ul {
    padding-left: 20px;
    margin-bottom: 25px;
}

.detail-info li {
    margin-bottom: 12px;
    color: #4a5568;
}

.info-note {
    background: #edf2f7;
    padding: 15px 20px;
    border-radius: 12px;
    font-size: .95rem;
}

/* Form */
.detail-form {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
}

.detail-form h2 {
    font-size: 1.6rem;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    font-weight: 600;
    font-size: .9rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    margin-top: 6px;
}

.btn-submit {
    width: 100%;
    padding: 14px;
    border-radius: 50px;
    border: none;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 10px;
}

.btn-submit i {
    margin-right: 8px;
}

.form-note {
    font-size: .85rem;
    text-align: center;
    margin-top: 15px;
    color: #718096;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
}

@media(max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush