@extends('layouts.admin-digital')

@section('title', 'Edit Landing Page - ' . $product->name)

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>{{ $landingPage->exists ? 'Edit' : 'Buat' }} Landing Page</h1>
            <p>{{ $product->name }} - {{ $product->formatted_price }}</p>
        </div>
        <div class="header-actions">
            @if($landingPage->exists)
            <a href="{{ route('admin.digital.landing-pages.preview', $product->id) }}" class="btn-preview-top"
                target="_blank">
                <i class="fas fa-eye"></i> Preview
            </a>
            @endif
            <a href="{{ route('admin.digital.landing-pages.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.digital.landing-pages.update', $product->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Main Content -->
            <div class="form-card">
                <h3><i class="fas fa-code"></i> HTML Content</h3>
                <p class="form-help">Paste kode HTML landing page di sini. Sistem akan otomatis menambahkan navbar
                    sticky di atas.</p>

                <div class="form-group">
                    <textarea name="html_content" id="html_content" rows="25" required
                        placeholder="Paste HTML landing page di sini...">{{ old('html_content', $landingPage->html_content) }}</textarea>
                    @error('html_content')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="tips-card">
                    <h4><i class="fas fa-lightbulb"></i> Tips</h4>
                    <ul>
                        <li>Copy seluruh HTML dari file landing page (termasuk tag <code>&lt;style&gt;</code>)</li>
                        <li>Tidak perlu include <code>&lt;html&gt;</code>, <code>&lt;head&gt;</code>, atau
                            <code>&lt;body&gt;</code> - cukup konten di dalam body
                        </li>
                        <li>Navbar dengan tombol pembayaran akan ditambahkan otomatis di atas</li>
                        <li>Pastikan tidak ada <code>&lt;script&gt;</code> berbahaya</li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="form-sidebar">
                <div class="form-card">
                    <h3><i class="fas fa-cog"></i> Pengaturan Navbar</h3>

                    <div class="form-group">
                        <label>Teks Logo</label>
                        <input type="text" name="navbar_logo_text"
                            value="{{ old('navbar_logo_text', $landingPage->navbar_logo_text ?? 'KIM Digital') }}"
                            placeholder="KIM Digital">
                        <small>Teks yang tampil di kiri navbar</small>
                    </div>

                    <div class="form-group">
                        <label>Teks Tombol</label>
                        <input type="text" name="navbar_button_text"
                            value="{{ old('navbar_button_text', $landingPage->navbar_button_text ?? 'Beli Sekarang') }}"
                            placeholder="Beli Sekarang">
                        <small>Teks tombol CTA di navbar</small>
                    </div>
                </div>

                <div class="form-card">
                    <h3><i class="fas fa-toggle-on"></i> Status</h3>

                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $landingPage->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktifkan Landing Page</label>
                    </div>
                    <small class="status-help">Jika tidak aktif, produk akan menggunakan template default</small>
                </div>

                <div class="form-card product-info-card">
                    <h3><i class="fas fa-box"></i> Info Produk</h3>
                    <div class="product-detail">
                        <span class="label">Nama:</span>
                        <span class="value">{{ $product->name }}</span>
                    </div>
                    <div class="product-detail">
                        <span class="label">Harga:</span>
                        <span class="value price">{{ $product->formatted_price }}</span>
                    </div>
                    <div class="product-detail">
                        <span class="label">Slug:</span>
                        <span class="value">{{ $product->slug }}</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Landing Page
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 1.8rem;
    color: #2d3748;
    margin-bottom: 5px;
}

.page-header p {
    color: #718096;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-secondary,
.btn-preview-top {
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-preview-top {
    background: #48bb78;
    color: white;
    border: none;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
}

.alert-error {
    background: #fed7d7;
    color: #742a2a;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 20px;
}

.form-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.form-card h3 {
    font-size: 1.1rem;
    color: #2d3748;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-card h3 i {
    color: #667eea;
}

.form-help {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-group textarea {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
}

.form-group small {
    display: block;
    color: #a0aec0;
    margin-top: 5px;
    font-size: 0.85rem;
}

.error {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}

.tips-card {
    background: #fffbeb;
    border: 2px solid #fcd34d;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}

.tips-card h4 {
    color: #92400e;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tips-card ul {
    margin: 0;
    padding-left: 20px;
    color: #78350f;
}

.tips-card li {
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.tips-card code {
    background: #fef3c7;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.form-sidebar {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-check input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: #667eea;
}

.status-help {
    display: block;
    color: #a0aec0;
    font-size: 0.85rem;
    margin-top: 10px;
}

.product-info-card {
    background: #f7fafc;
}

.product-detail {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e2e8f0;
}

.product-detail:last-child {
    border-bottom: none;
}

.product-detail .label {
    color: #718096;
    font-size: 0.9rem;
}

.product-detail .value {
    color: #2d3748;
    font-weight: 600;
    font-size: 0.9rem;
}

.product-detail .value.price {
    color: #667eea;
}

.btn-submit {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-sidebar {
        position: static;
    }
}
</style>
@endsection