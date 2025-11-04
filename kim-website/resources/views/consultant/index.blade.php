@extends('layouts.app')

@section('title', 'KIM Consultant - Layanan Konsultasi Profesional')

@section('content')
<!-- Page Header -->
<section class="page-header consultant-header">
    <div class="container">
        <div class="header-content">
            <h1 class="page-title">KIM Consultant</h1>
            <p class="page-subtitle">
                Solusi konsultasi profesional untuk berbagai kebutuhan bisnis dan industri Anda
            </p>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-intro">
            <h2>Pilih Bidang Konsultasi Anda</h2>
            <p>Klik kategori di bawah untuk informasi lebih detail dan form konsultasi</p>
        </div>

        <!-- Main Categories -->
        <div class="categories-grid">
            @foreach($mainCategories as $key => $category)
            @if($key !== 'manajemen')
            <a href="{{ route('consultant.show', $key) }}" class="category-card"
                style="--card-color: {{ $category['color'] }}">
                <div class="category-icon">
                    <i class="fas {{ $category['icon'] }}"></i>
                </div>
                <h3 class="category-title">{{ $category['title'] }}</h3>
                <p class="category-description">{{ $category['description'] }}</p>
                <div class="category-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @endif
            @endforeach
        </div>

        <!-- Management Special Section -->
        <div class="management-section">
            <div class="management-header">
                <div class="management-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h2>Manajemen</h2>
                    <p>Konsultasi manajemen dalam berbagai aspek bisnis</p>
                </div>
            </div>

            <div class="management-grid">
                @foreach($managementCategories as $key => $category)
                <a href="{{ route('consultant.show', $key) }}" class="management-card"
                    style="--card-color: {{ $category['color'] }}">
                    <div class="management-card-icon">
                        <i class="fas {{ $category['icon'] }}"></i>
                    </div>
                    <div class="management-card-content">
                        <h3>{{ $category['title'] }}</h3>
                        <p>{{ $category['description'] }}</p>
                    </div>
                    <div class="management-card-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <h2 class="section-title">Proses Konsultasi Kami</h2>

        <div class="process-timeline">
            <div class="process-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Konsultasi Awal</h3>
                    <p>Diskusi kebutuhan dan analisis situasi bisnis Anda</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Analisis Mendalam</h3>
                    <p>Tim ahli melakukan riset dan analisis komprehensif</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Solusi & Strategi</h3>
                    <p>Penyusunan rekomendasi dan rencana aksi</p>
                </div>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Implementasi</h3>
                    <p>Pendampingan dalam eksekusi dan monitoring</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-consultant">
    <div class="container">
        <div class="cta-box">
            <h2>Butuh Konsultasi Khusus?</h2>
            <p>Tim kami siap membantu Anda dengan solusi yang tepat</p>
            <a href="{{ route('contact.index') }}" class="btn btn-white-outline">
                <i class="fas fa-phone-alt"></i> Hubungi Kami Sekarang
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.consultant-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0 80px;
    text-align: center;
}

.page-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.2rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
}

.categories-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.section-intro {
    text-align: center;
    margin-bottom: 50px;
}

.section-intro h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3748;
}

.section-intro p {
    font-size: 1.1rem;
    color: #718096;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.category-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--card-color);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: var(--card-color);
}

.category-card:hover::before {
    transform: scaleX(1);
}

.category-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--card-color);
    color: white;
    font-size: 2rem;
    margin-bottom: 20px;
    transition: all 0.4s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1) rotate(5deg);
}

.category-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #2d3748;
}

.category-description {
    font-size: 1rem;
    color: #718096;
    line-height: 1.6;
    margin-bottom: 20px;
}

.category-arrow {
    color: var(--card-color);
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.category-card:hover .category-arrow {
    transform: translateX(10px);
}

/* Management Section */
.management-section {
    background: white;
    border-radius: 25px;
    padding: 50px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.management-header {
    display: flex;
    align-items: center;
    gap: 25px;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 3px solid #f1f5f9;
}

.management-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.management-header h2 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #2d3748;
}

.management-header p {
    font-size: 1.1rem;
    color: #718096;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.management-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.management-card:hover {
    background: white;
    border-color: var(--card-color);
    transform: translateX(8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.management-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--card-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.management-card:hover .management-card-icon {
    transform: scale(1.1);
}

.management-card-content {
    flex: 1;
}

.management-card-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2d3748;
}

.management-card-content p {
    font-size: 0.9rem;
    color: #718096;
    line-height: 1.4;
}

.management-card-arrow {
    color: var(--card-color);
    font-size: 1.2rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.management-card:hover .management-card-arrow {
    opacity: 1;
    transform: translateX(5px);
}

/* Process Section */
.process-section {
    padding: 80px 0;
    background: white;
}

.process-timeline {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
}

.process-step {
    text-align: center;
    position: relative;
}

.step-number {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.step-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2d3748;
}

.step-content p {
    color: #718096;
    line-height: 1.6;
}

/* CTA */
.cta-consultant {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.cta-box {
    text-align: center;
    color: white;
}

.cta-box h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.cta-box p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.95;
}

.btn-white-outline {
    background: transparent;
    border: 2px solid white;
    color: white;
    padding: 15px 40px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
}

.btn-white-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }

    .management-section {
        padding: 30px 20px;
    }

    .management-header {
        flex-direction: column;
        text-align: center;
    }

    .management-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush