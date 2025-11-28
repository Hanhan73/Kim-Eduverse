@extends('layouts.app')

@section('title', $product->name . ' - KIM Digital')

@push('styles')
<style>
    .product-detail {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .breadcrumb {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        font-size: 0.9rem;
        color: #718096;
    }

    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 60px;
    }

    .product-visual {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .product-image-main {
        width: 100%;
        height: 500px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .product-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-featured {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-category {
        background: #e0e7ff;
        color: #667eea;
    }

    .product-info h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .product-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f7fafc;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #718096;
        font-size: 0.95rem;
    }

    .meta-item i {
        color: #667eea;
        font-size: 1.1rem;
    }

    .price-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        border: 2px solid #e0e7ff;
    }

    .price-label {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 10px;
    }

    .price-main {
        font-size: 3rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 10px;
    }

    .price-note {
        font-size: 0.85rem;
        color: #718096;
    }

    .product-description {
        margin-bottom: 30px;
    }

    .product-description h3 {
        font-size: 1.3rem;
        color: #2d3748;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .product-description p {
        color: #4a5568;
        line-height: 1.8;
        font-size: 1rem;
    }

    .features-list {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .features-list h3 {
        font-size: 1.3rem;
        color: #2d3748;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .features-list ul {
        list-style: none;
        padding: 0;
    }

    .features-list li {
        padding: 12px 0;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #f7fafc;
    }

    .features-list li:last-child {
        border-bottom: none;
    }

    .features-list li i {
        color: #48bb78;
        font-size: 1.2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .btn-add-cart {
        flex: 1;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 18px 40px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-buy-now {
        flex: 1;
        background: white;
        color: #667eea;
        padding: 18px 40px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        border: 2px solid #667eea;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-buy-now:hover {
        background: #667eea;
        color: white;
    }

    .security-note {
        background: #f0fff4;
        border: 2px solid #9ae6b4;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
        color: #22543d;
    }

    .security-note i {
        color: #48bb78;
        font-size: 1.5rem;
    }

    .related-products {
        margin-top: 80px;
    }

    .related-products h2 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .related-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }

    .related-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
    }

    .related-body {
        padding: 20px;
    }

    .related-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .related-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #667eea;
    }

    @media (max-width: 968px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .product-visual {
            position: static;
        }

        .product-info h1 {
            font-size: 2rem;
        }

        .price-main {
            font-size: 2.5rem;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="product-detail">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('digital.index') }}">Home</a>
        <span>/</span>
        <a href="{{ route('digital.catalog') }}">Produk</a>
        <span>/</span>
        <a href="{{ route('digital.catalog', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
        <span>/</span>
        <span>{{ $product->name }}</span>
    </div>

    <!-- Product Detail -->
    <div class="product-layout">
        <!-- Visual -->
        <div class="product-visual">
            <div class="product-image-main">
                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                         alt="{{ $product->name }}" 
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;">
                @else
                    <i class="fas {{ $product->type === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                @endif
            </div>

            <div class="product-badges">
                @if($product->is_featured)
                <span class="badge badge-featured">
                    <i class="fas fa-star"></i> Featured
                </span>
                @endif
                <span class="badge badge-category">{{ $product->category->name }}</span>
            </div>
        </div>

        <!-- Info -->
        <div class="product-info">
            <h1>{{ $product->name }}</h1>

            <div class="product-meta">
                @if($product->duration_minutes)
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $product->duration_minutes }} menit</span>
                </div>
                @endif
                <div class="meta-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ $product->sold_count }} terjual</span>
                </div>
                @if($product->type === 'questionnaire')
                <div class="meta-item">
                    <i class="fas fa-file-pdf"></i>
                    <span>Hasil PDF via Email</span>
                </div>
                @endif
            </div>

            <!-- Price -->
            <div class="price-section">
                <div class="price-label">Harga</div>
                <div class="price-main">{{ $product->formatted_price }}</div>
                <div class="price-note">
                    <i class="fas fa-check-circle"></i> Termasuk analisis lengkap & konsultasi dasar
                </div>
            </div>

            <!-- Description -->
            <div class="product-description">
                <h3>Deskripsi Produk</h3>
                <p>{{ $product->description }}</p>
            </div>

            <!-- Features -->
            @if($product->features && count($product->features) > 0)
            <div class="features-list">
                <h3>Yang Anda Dapatkan</h3>
                <ul>
                    @foreach($product->features as $feature)
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- CTA Buttons -->
            <div class="cta-buttons">
                <form action="{{ route('digital.cart.add', $product->id) }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="btn-add-cart" style="width: 100%;">
                        <i class="fas fa-shopping-cart"></i>
                        Beli Sekarang
                    </button>
                </form>
            </div>

            <!-- Security Note -->
            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>Pembayaran Aman & Terenkripsi</strong><br>
                    Powered by Midtrans - Bank Transfer, E-Wallet, Credit Card
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <h2>Produk Terkait</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $related)
            <a href="{{ route('digital.show', $related->slug) }}" class="related-card">
                <div class="related-image">
                    @if($related->thumbnail)
                        <img src="{{ asset('storage/' . $related->thumbnail) }}" 
                             alt="{{ $related->name }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fas {{ $related->type === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                    @endif
                </div>
                <div class="related-body">
                    <h3 class="related-title">{{ Str::limit($related->name, 50) }}</h3>
                    <div class="related-price">{{ $related->formatted_price }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@endsection