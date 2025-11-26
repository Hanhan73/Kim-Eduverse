@extends('layouts.app')

@section('title', 'Katalog Produk - KIM Digital')

@push('styles')
<style>
    .catalog-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0 40px;
        text-align: center;
    }

    .catalog-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .catalog-header p {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .catalog-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 50px 20px;
    }

    .catalog-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
        gap: 20px;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
    }

    .filter-group {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: 0.95rem;
        cursor: pointer;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
    }

    .active-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-tag {
        background: #667eea;
        color: white;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 0;
        margin-left: 5px;
    }

    .catalog-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 40px;
    }

    .sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .filter-section {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .filter-section h3 {
        font-size: 1.1rem;
        color: #2d3748;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .filter-option {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .filter-option:hover {
        background: #f7fafc;
    }

    .filter-option input {
        margin-right: 10px;
        accent-color: #667eea;
        cursor: pointer;
    }

    .filter-option label {
        cursor: pointer;
        font-size: 0.95rem;
        color: #4a5568;
    }

    .products-main {
        min-height: 400px;
    }

    .result-info {
        color: #718096;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .product-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
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
        font-size: 3.5rem;
        color: white;
        position: relative;
    }

    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        color: #667eea;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-body {
        padding: 25px;
    }

    .product-category {
        color: #667eea;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .product-description {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-top: 15px;
        border-top: 1px solid #f7fafc;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #718096;
        font-size: 0.85rem;
    }

    .meta-item i {
        color: #667eea;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-view:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .no-results {
        text-align: center;
        padding: 80px 20px;
        grid-column: 1/-1;
    }

    .no-results i {
        font-size: 5rem;
        color: #e2e8f0;
        margin-bottom: 20px;
    }

    .no-results h3 {
        font-size: 1.5rem;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .no-results p {
        color: #718096;
        font-size: 1rem;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    @media (max-width: 968px) {
        .catalog-layout {
            grid-template-columns: 1fr;
        }

        .sidebar {
            position: static;
        }

        .catalog-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
        }

        .filter-group {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 600px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Header -->
<section class="catalog-header">
    <h1>Katalog Produk Digital</h1>
    <p>Temukan produk digital yang sesuai dengan kebutuhan Anda</p>
</section>

<!-- Catalog Content -->
<div class="catalog-container">
    <!-- Toolbar -->
    <div class="catalog-toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <form action="{{ route('digital.catalog') }}" method="GET">
                <input type="text" 
                       name="search" 
                       placeholder="Cari produk..." 
                       value="{{ request('search') }}"
                       onchange="this.form.submit()">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
            </form>
        </div>

        <div class="filter-group">
            <form action="{{ route('digital.catalog') }}" method="GET" id="sortForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <select name="sort" class="filter-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Active Filters -->
    @if(request('category') || request('search'))
    <div class="active-filters">
        @if(request('category'))
        <div class="filter-tag">
            <span>Kategori: {{ $categories->where('slug', request('category'))->first()->name ?? request('category') }}</span>
            <a href="{{ route('digital.catalog', ['search' => request('search'), 'sort' => request('sort')]) }}" style="color: white;">
                <button>×</button>
            </a>
        </div>
        @endif

        @if(request('search'))
        <div class="filter-tag">
            <span>Pencarian: "{{ request('search') }}"</span>
            <a href="{{ route('digital.catalog', ['category' => request('category'), 'sort' => request('sort')]) }}" style="color: white;">
                <button>×</button>
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Layout -->
    <div class="catalog-layout">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <div class="filter-section">
                <h3>Kategori</h3>
                @foreach($categories as $category)
                <div class="filter-option">
                    <input type="radio" 
                           name="category_filter" 
                           id="cat-{{ $category->id }}"
                           {{ request('category') == $category->slug ? 'checked' : '' }}
                           onchange="window.location.href='{{ route('digital.catalog', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}'">
                    <label for="cat-{{ $category->id }}">{{ $category->name }}</label>
                </div>
                @endforeach
                
                @if(request('category'))
                <a href="{{ route('digital.catalog', ['search' => request('search'), 'sort' => request('sort')]) }}" 
                   style="display: block; margin-top: 15px; color: #667eea; font-size: 0.9rem; text-decoration: none;">
                    <i class="fas fa-times-circle"></i> Reset Filter
                </a>
                @endif
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="products-main">
            <div class="result-info">
                Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
            </div>

            <div class="products-grid">
                @forelse($products as $product)
                <div class="product-card" onclick="window.location.href='{{ route('digital.show', $product->slug) }}'">
                    <div class="product-image">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas {{ $product->type === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                        @endif
                        @if($product->is_featured)
                        <span class="product-badge">Featured</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-description">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        
                        <div class="product-meta">
                            @if($product->duration_minutes)
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $product->duration_minutes }} menit</span>
                            </div>
                            @endif
                            @if($product->sold_count > 0)
                            <div class="meta-item">
                                <i class="fas fa-shopping-cart"></i>
                                <span>{{ $product->sold_count }} terjual</span>
                            </div>
                            @endif
                        </div>

                        <div class="product-footer">
                            <div class="product-price">{{ $product->formatted_price }}</div>
                            <a href="{{ route('digital.show', $product->slug) }}" class="btn-view" onclick="event.stopPropagation()">
                                Lihat Detail <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>Produk Tidak Ditemukan</h3>
                    <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                    <a href="{{ route('digital.catalog') }}" class="btn-view" style="margin-top: 20px;">
                        Lihat Semua Produk
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
