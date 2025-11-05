@extends('layouts.app')

@section('title', $article->title . ' - Blog PT KIM')

@section('content')
<!-- Article Header -->
<section class="article-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('blog.index') }}">Blog</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ $article->title }}</span>
        </div>

        <div class="article-header-content">
            <span class="article-category-badge">{{ $article->category }}</span>
            <h1 class="article-title">{{ $article->title }}</h1>
            
            <div class="article-meta">
                <span class="meta-item">
                    <i class="fas fa-user-circle"></i> {{ $article->author }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-calendar"></i> {{ $article->formatted_date }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-clock"></i> {{ $article->reading_time }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-eye"></i> {{ $article->views }} views
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="article-content-section">
    <div class="container">
        <div class="article-layout">
            <!-- Main Content -->
            <article class="article-main">
                @if($article->image)
                <div class="featured-image">
                    <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                </div>
                @endif

                <div class="article-body">
                    {!! $article->content !!}
                </div>

                <!-- Share Buttons -->
                <div class="share-section">
                    <h3>Bagikan Artikel Ini:</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                           target="_blank" 
                           class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}" 
                           target="_blank" 
                           class="share-btn twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($article->title) }}" 
                           target="_blank" 
                           class="share-btn linkedin">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->fullUrl()) }}" 
                           target="_blank" 
                           class="share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                <div class="related-articles">
                    <h3>Artikel Terkait</h3>
                    <div class="related-grid">
                        @foreach($relatedArticles as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="related-card">
                            @if($related->image)
                            <img src="{{ asset($related->image) }}" alt="{{ $related->title }}">
                            @else
                            <div class="related-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            @endif
                            <div class="related-content">
                                <h4>{{ $related->title }}</h4>
                                <p>{{ Str::limit($related->excerpt, 80) }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>

            <!-- Sidebar -->
            <aside class="article-sidebar">
                <div class="sidebar-widget">
                    <h3>Tentang Penulis</h3>
                    <div class="author-box">
                        <div class="author-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>{{ $article->author }}</h4>
                        <p>Tim konten PT KIM yang berdedikasi untuk berbagi informasi dan insights bermanfaat.</p>
                    </div>
                </div>

                <div class="sidebar-widget">
                    <h3>Hubungi Kami</h3>
                    <p>Butuh konsultasi lebih lanjut?</p>
                    <a href="{{ route('contact.index') }}" class="btn-sidebar">
                        <i class="fas fa-envelope"></i> Kirim Pesan
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.article-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0 60px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: white;
    text-decoration: none;
    opacity: 0.8;
}

.breadcrumb a:hover {
    opacity: 1;
}

.article-category-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.article-header-content .article-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 25px;
}

.article-meta {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    font-size: 1rem;
}

.article-content-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.article-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.article-main {
    background: white;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.featured-image {
    margin-bottom: 40px;
    border-radius: 15px;
    overflow: hidden;
}

.featured-image img {
    width: 100%;
    display: block;
}

.article-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2d3748;
}

.article-body p {
    margin-bottom: 1.5rem;
}

.article-body h2 {
    font-size: 2rem;
    margin: 2rem 0 1rem;
}

.article-body ul, .article-body ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.share-section {
    margin-top: 50px;
    padding-top: 50px;
    border-top: 2px solid #e2e8f0;
}

.share-section h3 {
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.share-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.share-btn {
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.share-btn.facebook { background: #1877f2; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.linkedin { background: #0077b5; }
.share-btn.whatsapp { background: #25d366; }

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.related-articles {
    margin-top: 60px;
    padding-top: 60px;
    border-top: 2px solid #e2e8f0;
}

.related-articles h3 {
    font-size: 1.8rem;
    margin-bottom: 30px;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.related-card {
    text-decoration: none;
    color: inherit;
    border-radius: 12px;
    overflow: hidden;
    background: #f8f9fa;
    transition: all 0.3s;
}

.related-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.related-card img,
.related-placeholder {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e2e8f0;
    color: #cbd5e0;
    font-size: 3rem;
}

.related-content {
    padding: 20px;
}

.related-content h4 {
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.related-content p {
    font-size: 0.9rem;
    color: #718096;
}

/* Sidebar */
.sidebar-widget {
    background: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.sidebar-widget h3 {
    font-size: 1.2rem;
    margin-bottom: 20px;
}

.author-box {
    text-align: center;
}

.author-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 2rem;
}

.author-box h4 {
    font-size: 1.2rem;
    margin-bottom: 10px;
}

.author-box p {
    color: #718096;
    font-size: 0.95rem;
    line-height: 1.6;
}

.btn-sidebar {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 15px;
}

@media (max-width: 968px) {
    .article-layout {
        grid-template-columns: 1fr;
    }
    
    .article-main {
        padding: 30px 20px;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush