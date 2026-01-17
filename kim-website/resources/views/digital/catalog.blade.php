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
    position: relative;
    z-index: 1;
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
    box-shadow: 0 2px 5px rgba(102, 126, 234, 0.2);
}

.filter-tag button {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 1.1rem;
    padding: 0;
    margin-left: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    transition: background 0.2s;
}

.filter-tag button:hover {
    background: rgba(255, 255, 255, 0.2);
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
    position: relative;
    z-index: 2;
}

.filter-section h3 {
    font-size: 1.1rem;
    color: #2d3748;
    margin-bottom: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-section h3 i {
    color: #667eea;
    font-size: 1rem;
}

.filter-option {
    display: flex;
    align-items: center;
    padding: 12px 10px;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 5px;
}

.filter-option:hover {
    background: #f7fafc;
}

.filter-option input {
    margin-right: 12px;
    accent-color: #667eea;
    cursor: pointer;
    width: 18px;
    height: 18px;
}

.filter-option label {
    cursor: pointer;
    font-size: 0.95rem;
    color: #4a5568;
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-option .count {
    color: #718096;
    font-size: 0.85rem;
    background: #f7fafc;
    padding: 2px 8px;
    border-radius: 12px;
}

/* Styles for expandable category */
.cekma-category {
    position: relative;
}

.cekma-toggle {
    position: absolute;
    right: -10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #718096;
    transition: transform 0.3s ease;
}

.cekma-toggle.expanded {
    transform: translateY(-50%) rotate(90deg);
}

.cekma-subcategories {
    padding-left: 20px;
    margin-top: 5px;
    display: none;
}

.cekma-subcategories.expanded {
    display: block;
}

.cekma-subcategory {
    padding: 8px 10px;
    border-left: 2px solid #e2e8f0;
    margin-bottom: 5px;
    cursor: pointer;
    border-radius: 0 8px 8px 0;
    transition: all 0.2s ease;
}

.cekma-subcategory:hover {
    border-left-color: #667eea;
    background: #f7fafc;
}

.cekma-subcategory.active {
    border-left-color: #667eea;
    background: #f0f4ff;
}

.cekma-subcategory input {
    margin-right: 8px;
}

.cekma-subcategory label {
    font-size: 0.9rem;
}

.products-main {
    min-height: 400px;
    position: relative;
    z-index: 1;
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
    position: relative;
    z-index: 1;
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
    background: linear-gradient(135deg, #8397f0ff, #764ba2);
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

.btn-detail {
    background: linear-gradient(135deg, #4f6ceeff, #764ba2);
    color: rgba(255, 255, 255, 1);
    padding: 10px 20px;
    margin: auto;
    border-bottom-left-radius: 30px;
    border-top-right-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    width: 77%;
    gap: 8px;
}

.btn-detail:hover {
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

.reset-filter {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    color: #667eea;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s ease;
    padding: 8px 15px;
    border-radius: 8px;
}

.reset-filter:hover {
    background: #f7fafc;
    color: #764ba2;
}

/* Mobile Filter Toggle Button */
.mobile-filter-toggle {
    display: none;
    width: 100%;
    padding: 15px 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 20px;
    cursor: pointer;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.mobile-filter-toggle:active {
    transform: scale(0.98);
}

.mobile-filter-toggle i#filter-chevron {
    transition: transform 0.3s ease;
}

/* Animasi smooth untuk collapse/expand */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Media query untuk tablet dan mobile */
@media (max-width: 968px) {
    .mobile-filter-toggle {
        display: flex;
    }

    .catalog-layout {
        grid-template-columns: 1fr;
    }

    .sidebar {
        position: relative;
        top: auto;
        margin-bottom: 20px;
        z-index: 10;
        transition: all 0.3s ease;
    }

    /* State collapsed (default di mobile) */
    .sidebar.collapsed {
        display: none;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
    }

    /* State expanded (saat tombol diklik) */
    .sidebar.expanded {
        display: block !important;
        max-height: 5000px;
        opacity: 1;
        margin-bottom: 30px;
        animation: slideDown 0.3s ease;
    }

    .filter-section {
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
    }

    .catalog-toolbar {
        flex-direction: column;
        align-items: stretch;
        position: relative;
        /* UBAH DARI STICKY KE RELATIVE */
        top: auto;
        z-index: 5;
        margin-bottom: 20px;
    }

    .search-box {
        width: 100%;
        min-width: 100%;
    }

    .filter-group {
        width: 100%;
        justify-content: space-between;
    }

    .products-main {
        position: relative;
        z-index: 1;
        background: transparent;
    }
}

/* Media query untuk desktop */
@media (min-width: 969px) {
    .mobile-filter-toggle {
        display: none !important;
    }

    .sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
        z-index: 10;
        display: block !important;
        opacity: 1 !important;
        max-height: none !important;
    }

    .sidebar.collapsed,
    .sidebar.expanded {
        display: block !important;
    }
}

/* Perbaikan untuk mobile kecil */
@media (max-width: 600px) {
    .products-grid {
        grid-template-columns: 1fr;
    }

    .catalog-header h1 {
        font-size: 1.8rem;
    }

    .catalog-header p {
        font-size: 0.95rem;
    }

    .mobile-filter-toggle {
        font-size: 0.95rem;
        padding: 12px 18px;
    }

    .filter-section {
        padding: 20px;
    }

    .product-card {
        border-radius: 15px;
    }

    .product-body {
        padding: 20px;
    }

    .product-title {
        font-size: 1.1rem;
    }

    .product-price {
        font-size: 1.3rem;
    }
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination .page-item {
    margin: 0;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 8px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.pagination .page-link:hover {
    background: #f7fafc;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f7fafc;
    color: #a0aec0;
}

.pagination .page-item.disabled .page-link:hover {
    transform: none;
    border-color: #e2e8f0;
}

/* Previous & Next buttons */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    padding: 8px 16px;
    font-weight: 700;
}

/* Icon sizing untuk Previous/Next */
.pagination .page-link svg {
    width: 16px !important;
    height: 16px !important;
}

/* Dots (...) styling */
.pagination .page-item .page-link[aria-disabled="true"] {
    border: none;
    background: transparent;
    cursor: default;
}

.pagination .page-item .page-link[aria-disabled="true"]:hover {
    transform: none;
}

/* Mobile responsive */
@media (max-width: 600px) {
    .pagination .page-link {
        min-width: 36px;
        height: 36px;
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    .pagination {
        gap: 5px;
    }

    /* Hide some page numbers on mobile */
    .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
        display: none;
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
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}"
                    onchange="this.form.submit()">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('cekma_type'))
                <input type="hidden" name="cekma_type" value="{{ request('cekma_type') }}">
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
                @if(request('cekma_type'))
                <input type="hidden" name="cekma_type" value="{{ request('cekma_type') }}">
                @endif
                <select name="sort" class="filter-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah
                    </option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi
                    </option>
                </select>
            </form>
        </div>
    </div>

    <!-- Active Filters -->
    @if(request('category') || request('search') || request('cekma_type'))
    <div class="active-filters">
        @if(request('category'))
        <div class="filter-tag">
            <span>Kategori:
                {{ $categories->where('slug', request('category'))->first()->name ?? request('category') }}</span>
            <a href="{{ route('digital.catalog', ['search' => request('search'), 'sort' => request('sort')]) }}"
                style="color: white;">
                <button>×</button>
            </a>
        </div>
        @endif

        @if(request('cekma_type'))
        <div class="filter-tag">
            <span>CEKMA: {{ Str::title(str_replace('_', ' ', request('cekma_type'))) }}</span>
            <a href="{{ route('digital.catalog', request()->except('cekma_type')) }}" style="color:white;">
                <button>×</button>
            </a>
        </div>
        @endif

        @if(request('search'))
        <div class="filter-tag">
            <span>Pencarian: "{{ request('search') }}"</span>
            <a href="{{ route('digital.catalog', ['category' => request('category'), 'cekma_type' => request('cekma_type'), 'sort' => request('sort')]) }}"
                style="color: white;">
                <button>×</button>
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- Mobile Filter Toggle Button -->
    <button class="mobile-filter-toggle" onclick="toggleMobileFilter()">
        <span><i class="fas fa-filter"></i> Filter & Kategori</span>
        <i class="fas fa-chevron-down" id="filter-chevron"></i>
    </button>

    <!-- Main Layout -->
    <div class="catalog-layout">
        <!-- Sidebar Filters - TAMBAHKAN ID "mobile-sidebar" DI SINI -->
        <aside class="sidebar" id="mobile-sidebar">
            <!-- Filter Kategori -->
            <div class="filter-section">
                <h3><i class="fas fa-folder"></i> Kategori</h3>
                @foreach($categories as $category)
                @if($category->slug === 'cekma')
                <!-- CEKMA Category with Dropdown -->
                <div class="filter-option cekma-category">
                    <input type="radio" name="category_filter" id="cat-{{ $category->id }}"
                        {{ request('category') == $category->slug && !request('cekma_type') ? 'checked' : '' }}
                        onchange="window.location.href='{{ route('digital.catalog', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}'">
                    <label for="cat-{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                    <i
                        class="fas fa-chevron-right cekma-toggle {{ request('category') == $category->slug || request('cekma_type') ? 'expanded' : '' }}"></i>
                </div>

                <div
                    class="cekma-subcategories {{ request('category') == $category->slug || request('cekma_type') ? 'expanded' : '' }}">
                    @foreach($cekmaStats as $type => $stat)
                    <div class="cekma-subcategory {{ request('cekma_type') === $type ? 'active' : '' }}">
                        <input type="radio" name="cekma_type" id="cekma-{{ $type }}"
                            {{ request('cekma_type') === $type ? 'checked' : '' }}
                            onchange="window.location.href='{{ route('digital.catalog', array_merge(request()->except('category'), ['cekma_type' => $type])) }}'">
                        <label for="cekma-{{ $type }}">
                            CEKMA {{ Str::title(str_replace('_', ' ', $type)) }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Regular Category -->
                <div class="filter-option">
                    <input type="radio" name="category_filter" id="cat-{{ $category->id }}"
                        {{ request('category') == $category->slug ? 'checked' : '' }}
                        onchange="window.location.href='{{ route('digital.catalog', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}'">
                    <label for="cat-{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                </div>
                @endif
                @endforeach

                @if(request('category') || request('cekma_type'))
                <a href="{{ route('digital.catalog', ['search' => request('search'), 'sort' => request('sort')]) }}"
                    class="reset-filter">
                    <i class="fas fa-times-circle"></i> Reset Filter
                </a>
                @endif
            </div>

            <!-- Filter Harga -->
            <div class="filter-section">
                <h3><i class="fas fa-tag"></i> Harga</h3>
                <form action="{{ route('digital.catalog') }}" method="GET">
                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('cekma_type'))
                    <input type="hidden" name="cekma_type" value="{{ request('cekma_type') }}">
                    @endif
                    @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; margin-bottom: 5px; font-size: 0.9rem; color: #4a5568;">Minimum</label>
                        <input type="number" name="min_price" placeholder="Rp 0" value="{{ request('min_price') }}"
                            style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; margin-bottom: 5px; font-size: 0.9rem; color: #4a5568;">Maksimum</label>
                        <input type="number" name="max_price" placeholder="Rp 1000000"
                            value="{{ request('max_price') }}"
                            style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>

                    <button type="submit" class="btn-view" style="width: 100%; justify-content: center;">
                        Terapkan Filter
                    </button>

                    @if(request('min_price') || request('max_price'))
                    <a href="{{ route('digital.catalog', request()->except(['min_price', 'max_price'])) }}"
                        class="reset-filter" style="width: 100%; justify-content: center; margin-top: 10px;">
                        <i class="fas fa-times-circle"></i> Reset Harga
                    </a>
                    @endif
                </form>
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
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                        <i
                            class="fas {{ $product->type === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
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
                        </div>
                        <a href="{{ route('digital.show', $product->slug) }}" class="btn-detail"
                            onclick="event.stopPropagation()">
                            Lihat Detail <i class="fas fa-arrow-right"></i>
                        </a>
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
                {{ $products->appends(request()->query())->links('vendor.pagination.admin') }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleMobileFilter() {
    const sidebar = document.getElementById('mobile-sidebar');
    const chevron = document.getElementById('filter-chevron');

    if (window.innerWidth <= 968) {
        // Toggle antara collapsed dan expanded
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.add('expanded');
            chevron.style.transform = 'rotate(180deg)';
        } else {
            sidebar.classList.remove('expanded');
            sidebar.classList.add('collapsed');
            chevron.style.transform = 'rotate(0deg)';
        }
    }
}

// Set initial state di mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('mobile-sidebar');
    const chevron = document.getElementById('filter-chevron');

    if (window.innerWidth <= 968) {
        sidebar.classList.add('collapsed');
        chevron.style.transform = 'rotate(0deg)';
    }

    // Toggle CEKMA subcategories
    const cekmaToggle = document.querySelector('.cekma-toggle');
    const cekmaSubcategories = document.querySelector('.cekma-subcategories');

    if (cekmaToggle && cekmaSubcategories) {
        cekmaToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('expanded');
            cekmaSubcategories.classList.toggle('expanded');
        });
    }
});
</script>
@endsection