@extends('layouts.app')

@section('title', 'Pembayaran - KIM Digital')

@push('styles')
<style>
    .payment-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .payment-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .payment-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.5rem;
        color: white;
    }

    .payment-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .payment-header p {
        color: #718096;
        font-size: 1rem;
    }

    .order-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .order-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 1rem;
    }

    .order-row.label {
        color: #718096;
    }

    .order-row.value {
        font-weight: 600;
        color: #2d3748;
    }

    .order-row.total {
        font-size: 1.5rem;
        font-weight: 700;
        padding-top: 20px;
        border-top: 2px solid #e2e8f0;
        margin-top: 15px;
    }

    .order-row.total .amount {
        color: #667eea;
    }

    .payment-methods {
        margin-bottom: 30px;
    }

    .payment-methods h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .method-item {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        font-size: 0.85rem;
        color: #718096;
    }

    .method-item i {
        display: block;
        font-size: 1.8rem;
        margin-bottom: 8px;
        color: #667eea;
    }

    .info-alert {
        background: #e0e7ff;
        border: 2px solid #c7d2fe;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: start;
        gap: 12px;
        margin-bottom: 25px;
        font-size: 0.9rem;
        color: #4338ca;
    }

    .info-alert i {
        font-size: 1.3rem;
        margin-top: 2px;
    }

    .btn-pay {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .btn-pay:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-pay:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-pay .spinner {
        display: none;
    }

    .btn-pay.loading .spinner {
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .security-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
        padding: 20px;
        background: #f0fff4;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #22543d;
    }

    .security-footer i {
        color: #48bb78;
        font-size: 1.5rem;
    }

    .cancel-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #718096;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .cancel-link:hover {
        color: #2d3748;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h1>Pembayaran</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <!-- Order Info -->
        <div class="order-info">
            <div class="order-row">
                <span class="label">Nama</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            <div class="order-row">
                <span class="label">Email</span>
                <span class="value">{{ $order->customer_email }}</span>
            </div>
            <div class="order-row">
                <span class="label">Jumlah Item</span>
                <span class="value">{{ $order->items->count() }} produk</span>
            </div>
            <div class="order-row total">
                <span>Total Pembayaran</span>
                <span class="amount">{{ $order->formatted_total }}</span>
            </div>
        </div>

        <!-- Payment Methods Info -->
        <div class="payment-methods">
            <h3>Metode Pembayaran Tersedia</h3>
            <div class="methods-grid">
                <div class="method-item">
                    <i class="fas fa-university"></i>
                    Bank Transfer
                </div>
                <div class="method-item">
                    <i class="fas fa-credit-card"></i>
                    Credit Card
                </div>
                <div class="method-item">
                    <i class="fas fa-wallet"></i>
                    GoPay
                </div>
                <div class="method-item">
                    <i class="fas fa-mobile-alt"></i>
                    OVO
                </div>
                <div class="method-item">
                    <i class="fas fa-money-bill-wave"></i>
                    DANA
                </div>
                <div class="method-item">
                    <i class="fas fa-shopping-bag"></i>
                    ShopeePay
                </div>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="info-alert">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Informasi Penting:</strong><br>
                Setelah klik tombol "Bayar Sekarang", Anda akan diarahkan ke halaman pembayaran Midtrans yang aman untuk menyelesaikan transaksi.
            </div>
        </div>

        <!-- Pay Button -->
        <button type="button" id="payButton" class="btn-pay">
            <i class="fas fa-lock"></i>
            <span class="text">Bayar Sekarang</span>
            <i class="fas fa-spinner spinner"></i>
        </button>

        <!-- Security Footer -->
        <div class="security-footer">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>Pembayaran 100% Aman & Terenkripsi</strong><br>
                Powered by Midtrans - Payment Gateway Terpercaya
            </div>
        </div>

        <a href="{{ route('digital.cart') }}" class="cancel-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
        </a>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
const payButton = document.getElementById('payButton');
const snapToken = @json($snapToken);

payButton.addEventListener('click', function() {
    // Disable button and show loading
    payButton.disabled = true;
    payButton.classList.add('loading');
    payButton.querySelector('.text').textContent = 'Memproses...';

    // Open Midtrans Snap
    snap.pay(snapToken, {
        onSuccess: function(result) {
            console.log('Payment success:', result);
            window.location.href = '{{ route("digital.payment.success", $order->order_number) }}';
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            alert('Menunggu pembayaran. Silakan selesaikan pembayaran Anda.');
            payButton.disabled = false;
            payButton.classList.remove('loading');
            payButton.querySelector('.text').textContent = 'Bayar Sekarang';
        },
        onError: function(result) {
            console.log('Payment error:', result);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            payButton.disabled = false;
            payButton.classList.remove('loading');
            payButton.querySelector('.text').textContent = 'Bayar Sekarang';
        },
        onClose: function() {
            console.log('Payment popup closed');
            payButton.disabled = false;
            payButton.classList.remove('loading');
            payButton.querySelector('.text').textContent = 'Bayar Sekarang';
        }
    });
});
</script>
@endsection
