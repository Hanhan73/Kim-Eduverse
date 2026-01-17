@extends('layouts.app')

@section('title', 'Checkout - KIM Digital')

@push('styles')
<style>
.checkout-container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
}

.checkout-header {
    text-align: center;
    margin-bottom: 50px;
}

.checkout-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.checkout-steps {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 30px;
}

.step {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #718096;
}

.step.active {
    color: #667eea;
    font-weight: 700;
}

.step-number {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #718096;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.step.active .step-number {
    background: #667eea;
    color: white;
}

.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 450px;
    gap: 40px;
}

.checkout-form {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    padding: 40px;
}

.form-section {
    margin-bottom: 35px;
}

.form-section h3 {
    font-size: 1.3rem;
    color: #2d3748;
    margin-bottom: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section h3 i {
    color: #667eea;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group label span {
    color: #f56565;
}

.form-control {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.error {
    border-color: #f56565;
}

.error-message {
    color: #f56565;
    font-size: 0.85rem;
    margin-top: 5px;
}

.info-box {
    background: #f0f4ff;
    border: 2px solid #c7d2fe;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    align-items: start;
    gap: 12px;
    font-size: 0.9rem;
    color: #4338ca;
}

.info-box i {
    font-size: 1.2rem;
    margin-top: 2px;
}

.order-summary {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 20px;
    padding: 30px;
    height: fit-content;
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f7fafc;
}

.order-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f7fafc;
}

.order-item:last-child {
    border-bottom: none;
}

.item-thumb {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
}

.item-info h4 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 5px;
}

.item-info .price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #667eea;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    font-size: 1rem;
    color: #4a5568;
}

.summary-row.total {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    padding-top: 20px;
    border-top: 2px solid #f7fafc;
    margin-top: 10px;
}

.summary-row.total .amount {
    color: #667eea;
    font-size: 1.8rem;
}

.btn-submit {
    width: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 18px;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.security-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
    padding: 15px;
    background: #f0fff4;
    border-radius: 10px;
    font-size: 0.85rem;
    color: #22543d;
}

.security-note i {
    color: #48bb78;
}

@media (max-width: 968px) {
    .checkout-layout {
        grid-template-columns: 1fr;
    }

    .order-summary {
        position: static;
    }

    .checkout-steps {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout</h1>

        <div class="checkout-steps">
            <div class="step">
                <span class="step-number">✓</span>
                <span>Keranjang</span>
            </div>
            <div class="step active">
                <span class="step-number">2</span>
                <span>Informasi</span>
            </div>
            <div class="step">
                <span class="step-number">3</span>
                <span>Pembayaran</span>
            </div>
        </div>
    </div>

    <form action="{{ route('digital.checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="checkout-layout">
            <!-- Checkout Form -->
            <div class="checkout-form">
                <div class="form-section">
                    <h3>
                        <i class="fas fa-user"></i>
                        Informasi Pembeli
                    </h3>

                    <div class="form-group">
                        <label for="customer_email">Email <span>*</span></label>
                        <input type="email" id="customer_email" name="customer_email"
                            class="form-control @error('customer_email') error @enderror"
                            value="{{ old('customer_email') }}" required placeholder="email@example.com">
                        @error('customer_email')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <small style="color: #718096; font-size: 0.85rem; margin-top: 5px; display: block;">
                            Hasil akan dikirim ke email ini
                        </small>
                    </div>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Informasi Penting:</strong><br>
                        Pastikan email yang Anda masukkan benar. Hasil analisis dan invoice akan dikirim ke email
                        tersebut.
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="summary-title">Ringkasan Pesanan</h2>

                <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                    @foreach($cart as $item)
                    <div class="order-item">
                        <div class="item-thumb">
                            <i
                                class="fas {{ $item['type'] === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                        </div>
                        <div class="item-info">
                            <h4>{{ $item['name'] }}</h4>
                            <div class="price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="summary-row">
                    <span>Subtotal ({{ count($cart) }} item)</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row total">
                    <span>Total Pembayaran</span>
                    <span class="amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-lock"></i>
                    Lanjut ke Pembayaran
                </button>

                <div class="security-note">
                    <i class="fas fa-shield-alt"></i>
                    <span><strong>100% Aman</strong> - Powered by Midtrans</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
});
</script>
@endsection