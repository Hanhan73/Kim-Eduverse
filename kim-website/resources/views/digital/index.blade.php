@extends('layouts.app')

@section('title', 'KIM Digital - Produk Digital')

@push('styles')
<style>
    .digital-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .digital-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        text-align: center;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto 40px;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-hero {
        padding: 16px 40px;
        font-size: 1.1rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-hero-primary {
        background: white;
        color: #667eea;
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-hero-outline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-hero-outline:hover {
        background: white;
        color: #667eea;
        transform: translateY(-3px);
    }

    /* Categories Section */
    .categories-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .section-header {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 60px;
    }

    .section-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
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

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .category-card {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .category-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }

    .category-card h3 {
        font-size: 1.3rem;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .category-card p {
        color: #718096;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .product-count {
        display: inline-block;
        background: #f0f4ff;
        color: #667eea;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Featured Products */
    .featured-section {
        padding: 80px 0;
        background: white;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .product-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
    }

    .product-body {
        padding: 25px;
    }

    .product-badge {
        display: inline-block;
        background: #f0f4ff;
        color: #667eea;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .product-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
    }

    .product-description {
        color: #718096;
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 2px solid #f7fafc;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .btn-product {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-product:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* How It Works */
    .how-it-works {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .step-card {
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 60px;
        height: 60px;
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

    .step-card h3 {
        font-size: 1.2rem;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .step-card p {
        color: #718096;
        font-size: 0.95rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 20px;
        text-align: center;
    }

    .cta-content {
        max-width: 700px;
        margin: 0 auto;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .cta-section p {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 40px;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .categories-grid,
        .products-grid,
        .steps-grid {
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="digital-hero">
    <div class="hero-content">
        <span class="hero-badge">🎯 Produk Digital Berkualitas</span>
        <h1 class="hero-title">Temukan Produk Digital Terbaik untuk Kebutuhan Anda</h1>
        <p class="hero-subtitle">
            Dari cekma psikologi profesional hingga bahan pembelajaran interaktif - 
            semua produk digital yang Anda butuhkan ada di sini
        </p>
        <div class="hero-buttons">
            <a href="{{ route('digital.catalog') }}" class="btn-hero btn-hero-primary">
                <i class="fas fa-shopping-bag"></i>
                Jelajahi Produk
            </a>
            <a href="#categories" class="btn-hero btn-hero-outline">
                <i class="fas fa-list"></i>
                Lihat Kategori
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="categories-section">
    <div class="section-header">
        <span class="section-badge">Kategori</span>
        <h2 class="section-title">Jelajahi Berdasarkan Kategori</h2>
        <p class="section-subtitle">Temukan produk digital yang sesuai dengan kebutuhan Anda</p>
    </div>

    <div class="categories-grid">
        @forelse($categories as $category)
        <a href="{{ route('digital.catalog', ['category' => $category->slug]) }}" class="category-card">
            <div class="category-icon">
                <i class="fas {{ $category->icon ?? 'fa-box' }}"></i>
            </div>
            <h3>{{ $category->name }}</h3>
            <p>{{ $category->description }}</p>
            <span class="product-count">{{ $category->active_products_count }} Produk</span>
        </a>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
            <p style="color: #718096;">Belum ada kategori tersedia</p>
        </div>
        @endforelse
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section">
    <div class="section-header">
        <span class="section-badge">Produk Unggulan</span>
        <h2 class="section-title">Produk Digital Terpopuler</h2>
        <p class="section-subtitle">Dipilih khusus untuk Anda berdasarkan kualitas dan popularitas</p>
    </div>

    <div class="products-grid">
        @forelse($featuredProducts as $product)
        <div class="product-card">
            <div class="product-image">
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fas {{ $product->type === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                @endif
            </div>
            <div class="product-body">
                <span class="product-badge">{{ $product->category->name }}</span>
                <h3 class="product-title">{{ $product->name }}</h3>
                <p class="product-description">
                    {{ Str::limit($product->description, 100) }}
                </p>
                <div class="product-footer">
                    <div class="product-price">{{ $product->formatted_price }}</div>
                    <a href="{{ route('digital.show', $product->slug) }}" class="btn-product">
                        Lihat Detail <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
            <i class="fas fa-box-open" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 20px;"></i>
            <p style="font-size: 1.2rem; color: #718096;">Belum ada produk tersedia</p>
        </div>
        @endforelse
    </div>

    @if($featuredProducts->count() > 0)
    <div style="text-align: center; margin-top: 50px;">
        <a href="{{ route('digital.catalog') }}" class="btn-hero btn-hero-primary">
            Lihat Semua Produk <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    @endif
</section>

<!-- How It Works -->
<section class="how-it-works">
    <div class="section-header">
        <span class="section-badge">Cara Kerja</span>
        <h2 class="section-title">Mudah & Cepat</h2>
        <p class="section-subtitle">Hanya 4 langkah untuk mendapatkan produk digital Anda</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3>Pilih Produk</h3>
            <p>Browse dan pilih produk digital yang sesuai kebutuhan Anda</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3>Checkout</h3>
            <p>Lakukan pembayaran dengan mudah melalui berbagai metode</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3>Isi CEKMA</h3>
            <p>Untuk produk cekma, isi pertanyaan dengan jujur</p>
        </div>

        <div class="step-card">
            <div class="step-number">4</div>
            <h3>Terima Hasil</h3>
            <p>Dapatkan hasil analisis lengkap via email dalam format PDF</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Siap Memulai?</h2>
        <p>Temukan produk digital yang tepat untuk kebutuhan Anda sekarang juga</p>
        <a href="{{ route('digital.catalog') }}" class="btn-hero btn-hero-primary">
            <i class="fas fa-rocket"></i>
            Mulai Jelajahi
        </a>
    </div>
</section>
@endsection
