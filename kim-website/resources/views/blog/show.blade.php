@extends('layouts.app')

@section('title', $article->title . ' - Blog PT KIM')

@section('content')

<!-- Article Hero -->
<section class="article-hero">
    <div class="hero-inner">
        <div class="container">
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span class="sep">/</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span class="sep">/</span>
                <span class="current">{{ Str::limit($article->title, 40) }}</span>
            </nav>

            <div class="hero-body">
                <span class="category-pill">{{ $article->category }}</span>
                <h1 class="hero-title">{{ $article->title }}</h1>
                <p class="hero-excerpt">{{ $article->excerpt }}</p>

                <div class="hero-meta">
                    <div class="meta-author">
                        <div class="author-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <span class="author-name">{{ $article->author }}</span>
                            <span class="meta-dot">·</span>
                            <span class="meta-date">{{ $article->formatted_date }}</span>
                        </div>
                    </div>
                    <div class="meta-stats">
                        <span><i class="fas fa-clock"></i> {{ $article->reading_time }}</span>
                        <span><i class="fas fa-eye"></i> {{ number_format($article->views) }} views</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="article-page">
    <div class="container">

        <!-- Article -->
        <div class="article-wrap">

            @if($article->image)
            <div class="article-cover">
                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
            </div>
            @endif

            <div class="article-body">
                {!! $article->content !!}
            </div>

            <!-- Share -->
            <div class="article-footer">
                <span class="share-label">Bagikan:</span>
                <div class="share-links">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                       target="_blank" class="share-link fb" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}"
                       target="_blank" class="share-link tw" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($article->title) }}"
                       target="_blank" class="share-link li" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->fullUrl()) }}"
                       target="_blank" class="share-link wa" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Meta Bar -->
        <div class="meta-bar">

            <div class="meta-bar-author">
                <div class="mb-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="mb-author-text">
                    <span class="mb-label">Ditulis oleh</span>
                    <span class="mb-name">{{ $article->author }}</span>
                    <span class="mb-desc">Tim konten PT KIM</span>
                </div>
            </div>

            <div class="meta-bar-divider"></div>

            <div class="meta-bar-info">
                <div class="mb-info-item">
                    <i class="fas fa-folder-open"></i>
                    <div>
                        <span class="mb-label">Kategori</span>
                        <span class="mb-val">{{ $article->category }}</span>
                    </div>
                </div>
                <div class="mb-info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <span class="mb-label">Diterbitkan</span>
                        <span class="mb-val">{{ $article->formatted_date }}</span>
                    </div>
                </div>
                <div class="mb-info-item">
                    <i class="fas fa-eye"></i>
                    <div>
                        <span class="mb-label">Dilihat</span>
                        <span class="mb-val">{{ number_format($article->views) }}x</span>
                    </div>
                </div>
            </div>

            <div class="meta-bar-divider"></div>

            <div class="meta-bar-cta">
                <a href="{{ route('contact.index') }}" class="mb-cta-btn">
                    <i class="fas fa-paper-plane"></i> Hubungi Kami
                </a>
                <a href="{{ route('blog.index') }}" class="mb-back-btn">
                    <i class="fas fa-arrow-left"></i> Blog
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
        <section class="related-section">
            <div class="related-header">
                <h2 class="related-title">Artikel Terkait</h2>
                <a href="{{ route('blog.index', ['category' => $article->category]) }}" class="related-see-all">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="related-grid">
                @foreach($relatedArticles as $related)
                <a href="{{ route('blog.show', $related->slug) }}" class="related-card">
                    <div class="related-img-wrap">
                        @if($related->image)
                        <img src="{{ asset($related->image) }}" alt="{{ $related->title }}">
                        @else
                        <div class="related-img-fallback">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        @endif
                        <span class="related-cat">{{ $related->category }}</span>
                    </div>
                    <div class="related-body">
                        <h3 class="related-art-title">{{ $related->title }}</h3>
                        <p class="related-art-excerpt">{{ Str::limit($related->excerpt, 80) }}</p>
                        <span class="related-read">Baca artikel <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</main>

@endsection

@push('styles')
<style>
:root {
    --brand: #667eea;
    --brand-dark: #764ba2;
    --brand-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --text-primary: #1a202c;
    --text-secondary: #4a5568;
    --text-muted: #718096;
    --border: #e2e8f0;
    --bg-page: #f7f8fc;
    --bg-white: #ffffff;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 20px;
    --shadow-sm: 0 1px 4px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
}

/* ── Hero ───────────────────────────────────────── */
.article-hero {
    background: var(--brand-gradient);
    position: relative;
    overflow: hidden;
}
.article-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-inner { padding: 110px 0 60px; position: relative; }

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    margin-bottom: 32px;
}
.breadcrumb-nav a { color: rgba(255,255,255,0.75); text-decoration: none; transition: color 0.2s; }
.breadcrumb-nav a:hover { color: #fff; }
.breadcrumb-nav .sep { opacity: 0.4; color: white; }
.breadcrumb-nav .current { color: rgba(255,255,255,0.55); }

.hero-body { max-width: 820px; }

.category-pill {
    display: inline-block;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 6px 18px;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.hero-title {
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.25;
    margin-bottom: 18px;
    letter-spacing: -0.02em;
}

.hero-excerpt {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.82);
    line-height: 1.7;
    margin-bottom: 30px;
    max-width: 700px;
}

.hero-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.18);
}

.meta-author { display: flex; align-items: center; gap: 12px; }

.author-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
    flex-shrink: 0;
}

.author-name { font-weight: 700; font-size: 0.95rem; color: #fff; }
.meta-dot { margin: 0 4px; opacity: 0.5; color: white; }
.meta-date { font-size: 0.88rem; color: rgba(255,255,255,0.7); }

.meta-stats { display: flex; gap: 20px; font-size: 0.88rem; color: rgba(255,255,255,0.75); }
.meta-stats i { margin-right: 5px; }

/* ── Page ───────────────────────────────────────── */
.article-page {
    background: var(--bg-page);
    padding: 48px 0 80px;
}

/* ── Article Wrap ───────────────────────────────── */
.article-wrap {
    max-width: 860px;
    margin: 0 auto;
}

.article-cover {
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    overflow: hidden;
    box-shadow: var(--shadow-md);
}
.article-cover img {
    width: 100%;
    max-height: 480px;
    object-fit: cover;
    display: block;
}

.article-body {
    background: var(--bg-white);
    padding: 48px 56px;
    box-shadow: var(--shadow-sm);
    font-size: 1.05rem;
    line-height: 1.85;
    color: var(--text-secondary);
    border-radius: var(--radius-lg);
}

/* when cover is present, top radius removed */
.article-cover + .article-body {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.article-body p { margin-bottom: 1.5rem; }
.article-body p:last-child { margin-bottom: 0; }
.article-body h2 { font-size: 1.55rem; font-weight: 700; color: var(--text-primary); margin: 2.5rem 0 1rem; letter-spacing: -0.01em; }
.article-body h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin: 2rem 0 0.75rem; }
.article-body ul, .article-body ol { margin: 1.5rem 0; padding-left: 1.5rem; }
.article-body li { margin-bottom: 0.6rem; }
.article-body blockquote {
    border-left: 4px solid var(--brand);
    margin: 2rem 0;
    padding: 1rem 1.5rem;
    background: #f0f2ff;
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    font-style: italic;
    color: var(--text-primary);
}
.article-body a { color: var(--brand); text-decoration: underline; }
.article-body img { max-width: 100%; border-radius: var(--radius-md); margin: 1.5rem 0; }

/* Share */
.article-footer {
    background: var(--bg-white);
    border-top: 1px solid var(--border);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    padding: 18px 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow-sm);
    margin-top: -1px;
}

.share-label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    white-space: nowrap;
}

.share-links { display: flex; gap: 8px; }

.share-link {
    width: 34px;
    height: 34px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.85rem;
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.share-link:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.share-link.fb { background: #1877f2; }
.share-link.tw { background: #1da1f2; }
.share-link.li { background: #0077b5; }
.share-link.wa { background: #25d366; }

/* ── Meta Bar ───────────────────────────────────── */
.meta-bar {
    max-width: 860px;
    margin: 12px auto 0;
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    overflow: hidden;
}

.meta-bar-author {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    flex-shrink: 0;
}

.mb-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--brand-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.mb-author-text {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.mb-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--text-muted);
}

.mb-name {
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--text-primary);
}

.mb-desc {
    font-size: 0.78rem;
    color: var(--text-muted);
}

.meta-bar-divider {
    width: 1px;
    height: 44px;
    background: var(--border);
    flex-shrink: 0;
}

.meta-bar-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.mb-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 18px 20px;
    flex: 1;
    border-right: 1px solid var(--border);
}

.mb-info-item:last-child { border-right: none; }

.mb-info-item i {
    font-size: 0.95rem;
    color: var(--brand);
    flex-shrink: 0;
    width: 16px;
    text-align: center;
}

.mb-info-item > div { display: flex; flex-direction: column; gap: 1px; }

.mb-val {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-primary);
}

.meta-bar-cta {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 20px 20px 20px 16px;
    flex-shrink: 0;
}

.mb-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--brand-gradient);
    color: white;
    padding: 9px 18px;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    white-space: nowrap;
    transition: transform 0.2s, box-shadow 0.2s;
}
.mb-cta-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

.mb-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.83rem;
    font-weight: 600;
    white-space: nowrap;
    padding: 9px 12px;
    border-radius: var(--radius-sm);
    transition: color 0.2s, background 0.2s;
}
.mb-back-btn:hover { color: var(--brand); background: #f0f2ff; }

/* ── Related ────────────────────────────────────── */
.related-section {
    max-width: 860px;
    margin: 40px auto 0;
}

.related-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.related-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}

.related-see-all {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--brand);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.2s;
}
.related-see-all:hover { gap: 8px; }

.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.related-card {
    background: var(--bg-white);
    border-radius: var(--radius-md);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    transition: transform 0.25s, box-shadow 0.25s;
}
.related-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

.related-img-wrap {
    position: relative;
    height: 140px;
    overflow: hidden;
    flex-shrink: 0;
}
.related-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
.related-card:hover .related-img-wrap img { transform: scale(1.06); }

.related-img-fallback {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #eef0ff, #e8e0f5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #c3c8e8;
}

.related-cat {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--brand-gradient);
    color: white;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 3px 9px;
    border-radius: 999px;
}

.related-body {
    padding: 14px 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.related-art-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.52em;
}

.related-art-excerpt {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.related-read {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--brand);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: auto;
    transition: gap 0.2s;
}
.related-card:hover .related-read { gap: 7px; }

/* ── Responsive ─────────────────────────────────── */
@media (max-width: 860px) {
    .article-body { padding: 36px 28px; }
    .article-footer { padding: 16px 28px; }

    .meta-bar-info { flex-wrap: wrap; }
    .mb-info-item { flex: none; border-right: none; border-bottom: 1px solid var(--border); }
    .mb-info-item:last-child { border-bottom: none; }
    .meta-bar-divider { display: none; }
    .meta-bar { flex-wrap: wrap; }
    .meta-bar-author,
    .meta-bar-cta { border-bottom: 1px solid var(--border); width: 100%; }
}

@media (max-width: 600px) {
    .article-body { padding: 24px 18px; }
    .article-footer { padding: 14px 18px; }
    .related-grid { grid-template-columns: 1fr; }
    .hero-inner { padding: 90px 0 44px; }
    .hero-title { font-size: 1.7rem; }
    .hero-meta { flex-direction: column; align-items: flex-start; }
    .meta-bar { flex-direction: column; }
    .meta-bar-info { flex-direction: column; width: 100%; }
    .meta-bar-author,
    .meta-bar-cta { padding: 16px 18px; }
}
</style>
@endpush