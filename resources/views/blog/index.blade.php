@extends('layouts.app')

@section('title', 'Blog - PT KIM')

@section('content')
<!-- Page Header -->
<section class="blog-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title animate-fade-in">Blog & Artikel</h1>
            <p class="page-subtitle animate-fade-in-delay">
                Berbagai informasi, tips, dan insights seputar teknologi, bisnis, dan pendidikan
            </p>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="blog-section">
    <div class="container">
        <div class="blog-layout">
            <!-- Main Content -->
            <div class="blog-main">
                <!-- Search & Filter -->
                <div class="blog-controls">
                    <form action="{{ route('blog.index') }}" method="GET" class="search-form">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" 
                                   name="search" 
                                   class="search-input" 
                                   placeholder="Cari artikel..."
                                   value="{{ request('search') }}">
                        </div>
                        
                        <select name="category" class="category-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <!-- Articles Grid -->
                @if($articles->count() > 0)
                <div class="articles-grid">
                    @foreach($articles as $article)
                    <article class="article-card">
                        <a href="{{ route('blog.show', $article->slug) }}" class="article-link">
                            <div class="article-image">
                                @if($article->image)
                                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                                @else
                                <div class="article-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                @endif
                                <span class="article-category">{{ $article->category }}</span>
                            </div>
                            
                            <div class="article-content">
                                <h2 class="article-title">{{ $article->title }}</h2>
                                <p class="article-excerpt">{{ $article->excerpt }}</p>
                                
                                <div class="article-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-user"></i> {{ $article->author }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-calendar"></i> {{ $article->formatted_date }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-eye"></i> {{ $article->views }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
                @else
                <div class="no-articles">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada artikel ditemukan</h3>
                    <p>Coba kata kunci atau kategori lain</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <!-- Popular Articles -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-fire"></i> Artikel Populer
                    </h3>
                    <div class="popular-list">
                        @foreach($popularArticles as $popular)
                        <a href="{{ route('blog.show', $popular->slug) }}" class="popular-item">
                            <div class="popular-number">{{ $loop->iteration }}</div>
                            <div class="popular-content">
                                <h4>{{ Str::limit($popular->title, 50) }}</h4>
                                <span class="popular-views">
                                    <i class="fas fa-eye"></i> {{ $popular->views }} views
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-folder"></i> Kategori
                    </h3>
                    <div class="categories-list">
                        <a href="{{ route('blog.index') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                            <span>Semua</span>
                            <span class="category-count">{{ $totalArticles }}</span>
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat]) }}" 
                        class="category-item {{ request('category') == $cat ? 'active' : '' }}">
                            <span>{{ $cat }}</span>
                            <span class="category-count">{{ $categoryCounts[$cat] ?? 0 }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- CTA Widget -->
                <div class="sidebar-widget cta-widget">
                    <h3>Butuh Konsultasi?</h3>
                    <p>Tim kami siap membantu transformasi digital bisnis Anda</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-cta">
                        <i class="fas fa-phone-alt"></i> Hubungi Kami
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.blog-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0 60px;
    text-align: center;
}

.page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.2rem;
    opacity: 0.95;
}

.blog-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.blog-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.blog-controls {
    background: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.search-form {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 15px;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
}

.search-input {
    width: 100%;
    padding: 12px 12px 12px 45px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
}

.category-select {
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    cursor: pointer;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.article-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.article-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.article-link {
    text-decoration: none;
    color: inherit;
}

.article-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f0f0f0;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.article-card:hover .article-image img {
    transform: scale(1.1);
}

.article-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #cbd5e0;
}

.article-category {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.article-content {
    padding: 25px;
}

.article-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
    line-height: 1.4;
}

.article-excerpt {
    color: #718096;
    line-height: 1.6;
    margin-bottom: 15px;
}

.article-meta {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    font-size: 0.875rem;
    color: #a0aec0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Sidebar */
.sidebar-widget {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.widget-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #2d3748;
}

.popular-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.popular-item {
    display: flex;
    gap: 15px;
    text-decoration: none;
    padding: 12px;
    border-radius: 10px;
    transition: all 0.3s;
}

.popular-item:hover {
    background: #f7fafc;
}

.popular-number {
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.popular-content h4 {
    font-size: 0.95rem;
    color: #2d3748;
    margin-bottom: 5px;
}

.popular-views {
    font-size: 0.8rem;
    color: #a0aec0;
}

.categories-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-item {
    padding: 12px 15px;
    border-radius: 10px;
    text-decoration: none;
    color: #4a5568;
    display: flex;
    justify-content: space-between;
    transition: all 0.3s;
}

.category-item:hover,
.category-item.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.cta-widget {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-align: center;
}

.cta-widget h3 {
    color: white;
    margin-bottom: 10px;
}

.cta-widget p {
    margin-bottom: 20px;
    opacity: 0.95;
}

.btn-cta {
    background: white;
    color: #667eea;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.no-articles {
    text-align: center;
    padding: 80px 20px;
    color: #718096;
}

.no-articles i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #cbd5e0;
}

@media (max-width: 968px) {
    .blog-layout {
        grid-template-columns: 1fr;
    }
    
    .search-form {
        grid-template-columns: 1fr;
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush