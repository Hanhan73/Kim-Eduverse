@extends('layouts.app')
@if($hasQuestionnaire && !$questionnaireCompleted)
    @section('title', 'Pembayaran Berhasil - Isi Angket - KIM Digital')
@else
    @section('title', 'Angket Selesai Diisi - KIM Digital')
@endif

@push('styles')
<style>
    .success-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .success-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 3.5rem;
        color: white;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .success-card h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .success-card .subtitle {
        font-size: 1.2rem;
        color: #718096;
        margin-bottom: 40px;
    }

    .order-number {
        background: #f8f9fa;
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 40px;
    }

    .order-number label {
        display: block;
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .order-number .number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        font-family: monospace;
    }

    .next-steps {
        background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
        border: 2px solid #e0e7ff;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: left;
    }

    .next-steps h3 {
        font-size: 1.3rem;
        color: #2d3748;
        margin-bottom: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .next-steps h3 i {
        color: #667eea;
    }

    .steps-list {
        list-style: none;
        padding: 0;
    }

    .step-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .step-item:last-child {
        border-bottom: none;
    }

    .step-number {
        width: 35px;
        height: 35px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-content {
        flex: 1;
    }

    .step-content strong {
        display: block;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .step-content span {
        color: #718096;
        font-size: 0.95rem;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid #667eea;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #f0f4ff;
    }

    .info-box {
        background: #fff7ed;
        border: 2px solid #fed7aa;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: start;
        gap: 12px;
        margin-top: 30px;
        font-size: 0.9rem;
        color: #9a3412;
    }

    .info-box i {
        font-size: 1.2rem;
        margin-top: 2px;
    }

    @media (max-width: 600px) {
        .success-card {
            padding: 30px 20px;
        }

        .success-card h1 {
            font-size: 2rem;
        }

        .order-number .number {
            font-size: 1.4rem;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        @if($hasQuestionnaire && !$questionnaireCompleted)
            <h1>Pembayaran Berhasil!</h1>
            <p class="subtitle">Terima kasih atas pembelian Anda</p>
        @else
            <h1>Angket Selesai Diisi!</h1>
            <p class="subtitle">Terima kasih telah menyelesaikan angket</p>
        @endif

        <div class="order-number">
            <label>Nomor Pesanan</label>
            <div class="number">{{ $order->order_number }}</div>
        </div>

        <!-- Next Steps -->
        <div class="next-steps">
            <h3>
                <i class="fas fa-list-check"></i>
                Langkah Selanjutnya
            </h3>
            <ul class="steps-list">
                @if($hasQuestionnaire)
                    @if($questionnaireCompleted)
                        <!-- Sudah selesai isi angket -->
                        <li class="step-item">
                            <div class="step-number">✓</div>
                            <div class="step-content">
                                <strong>Terima Kasih!</strong>
                                <span>Anda sudah menyelesaikan pengisian angket</span>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">✓</div>
                            <div class="step-content">
                                <strong>Hasil Sudah Dikirim</strong>
                                <span>Hasil analisis lengkap sudah dikirim ke email <strong>{{ $order->customer_email }}</strong> dalam format PDF</span>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <strong>Cek Email</strong>
                                <span>Buka email Anda dan download file PDF. Periksa folder spam jika tidak ditemukan di inbox.</span>
                            </div>
                        </li>
                    @else
                        <!-- Belum isi angket -->
                        <li class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <strong>Isi Angket</strong>
                                <span>Klik tombol "Isi Angket Sekarang" untuk mulai mengisi kuesioner</span>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <strong>Terima Hasil</strong>
                                <span>Hasil analisis akan dikirim ke email Anda dalam format PDF</span>
                            </div>
                        </li>
                    @endif
                @else
                    <!-- Bukan questionnaire -->
                    <li class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <strong>Cek Email</strong>
                            <span>Kami telah mengirim email konfirmasi dan link download produk</span>
                        </div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <strong>Download Produk</strong>
                            <span>Klik link di email untuk download produk digital Anda</span>
                        </div>
                    </li>
                @endif
                <li class="step-item">
                    <div class="step-number">{{ $hasQuestionnaire ? ($questionnaireCompleted ? '4' : '3') : '3' }}</div>
                    <div class="step-content">
                        <strong>Butuh Bantuan?</strong>
                        <span>Hubungi customer service kami jika ada pertanyaan</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- CTA Buttons -->
        <div class="cta-buttons">
            @if($hasQuestionnaire && !$questionnaireCompleted)
                <!-- Belum isi angket -->
                <a href="{{ route('digital.questionnaire.show', $order->order_number) }}" class="btn-primary">
                    <i class="fas fa-clipboard-list"></i>
                    Isi Angket Sekarang
                </a>
            @else
                <!-- Sudah selesai atau bukan questionnaire -->
                <a href="{{ route('digital.index') }}" class="btn-primary">
                    <i class="fas fa-home"></i>
                    Kembali ke Home KIM Digital
                </a>
            @endif
            <a href="{{ route('digital.catalog') }}" class="btn-secondary">
                <i class="fas fa-shopping-bag"></i>
                Belanja Lagi
            </a>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-envelope"></i>
            <div>
                <strong>Email Konfirmasi Dikirim</strong><br>
                Kami telah mengirim email konfirmasi ke <strong>{{ $order->customer_email }}</strong>. 
                Jika tidak menemukan email, cek folder spam/junk.
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    // Celebration animation or confetti can be added here
    console.log('Payment successful!');
</script>
@endif
@endsection