@extends('layouts.app')

@section('title', 'Keranjang Belanja - KIM Digital')

@push('styles')
<style>
.cart-container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
}

.cart-header {
    text-align: center;
    margin-bottom: 50px;
}

.cart-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
}

.cart-header p {
    color: #718096;
    font-size: 1.1rem;
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
}

.cart-items {
    background: white;
    border-radius: 20px;
    border: 2px solid #e2e8f0;
    overflow: hidden;
}

.cart-item {
    display: flex;
    gap: 20px;
    padding: 25px;
    border-bottom: 2px solid #f7fafc;
    transition: all 0.3s ease;
}

.cart-item:hover {
    background: #f8f9fa;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    flex-shrink: 0;
}

.item-details {
    flex: 1;
}

.item-category {
    color: #667eea;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.item-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.item-type {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.item-type i {
    color: #667eea;
}

.item-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.item-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn-remove {
    background: #fee;
    color: #f56565;
    border: 2px solid #feb2b2;
    padding: 10px 20px;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-remove:hover {
    background: #f56565;
    color: white;
    border-color: #f56565;
}

.cart-summary {
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

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 1rem;
    color: #4a5568;
}

.summary-row.total {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    padding-top: 20px;
    border-top: 2px solid #f7fafc;
    margin-top: 20px;
}

.summary-row.total .amount {
    color: #667eea;
    font-size: 1.8rem;
}

.btn-checkout {
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
    text-decoration: none;
}

.btn-checkout:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-continue {
    width: 100%;
    background: white;
    color: #667eea;
    padding: 15px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    border: 2px solid #667eea;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 15px;
    text-decoration: none;
    display: block;
    text-align: center;
}

.btn-continue:hover {
    background: #f0f4ff;
}

.security-badge {
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

.security-badge i {
    color: #48bb78;
    font-size: 1.2rem;
}

.empty-cart {
    text-align: center;
    padding: 100px 20px;
}

.empty-cart i {
    font-size: 6rem;
    color: #e2e8f0;
    margin-bottom: 25px;
}

.empty-cart h2 {
    font-size: 2rem;
    color: #2d3748;
    margin-bottom: 15px;
}

.empty-cart p {
    color: #718096;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.btn-browse {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 15px 35px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-browse:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

@media (max-width: 968px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }

    .cart-summary {
        position: static;
    }

    .cart-item {
        flex-direction: column;
    }

    .item-image {
        width: 100%;
        height: 200px;
    }
}
</style>
@endpush

@section('content')
<div class="cart-container">
    <div class="cart-header">
        <h1>Keranjang Belanja</h1>
        <p>Review produk yang akan Anda beli</p>
    </div>

    @if(count($cart) > 0)
    <div class="cart-layout">
        <!-- Cart Items -->
        <div class="cart-items">
            @foreach($cart as $item)
            <div class="cart-item">
                <div class="item-image">
                    @if(isset($item['thumbnail']))
                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="{{ $item['name'] }}"
                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                    @else
                    <i class="fas {{ $item['type'] === 'questionnaire' ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                    @endif
                </div>

                <div class="item-details">
                    <div class="item-category">Produk Digital</div>
                    <h3 class="item-name">{{ $item['name'] }}</h3>
                    <div class="item-type">
                        <i class="fas fa-tag"></i>
                        {{ ucfirst($item['type']) }}
                    </div>
                    <div class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>

                <div class="item-actions">
                    <form action="{{ route('digital.cart.remove', $item['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-remove"
                            onclick="return confirm('Hapus produk ini dari keranjang?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h2 class="summary-title">Ringkasan Belanja</h2>

            <div class="summary-row">
                <span>Subtotal ({{ count($cart) }} item)</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <span class="amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <a href="{{ route('digital.checkout') }}" class="btn-checkout">
                <i class="fas fa-lock"></i>
                Lanjut ke Pembayaran
            </a>

            <a href="{{ route('digital.catalog') }}" class="btn-continue">
                <i class="fas fa-arrow-left"></i> Lanjut Belanja
            </a>

            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span><strong>Pembayaran Aman</strong> dengan Midtrans</span>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h2>Keranjang Belanja Kosong</h2>
        <p>Belum ada produk di keranjang Anda. Yuk mulai belanja!</p>
        <a href="{{ route('digital.catalog') }}" class="btn-browse">
            <i class="fas fa-shopping-bag"></i>
            Jelajahi Produk
        </a>
    </div>
    @endif
</div>

@if(session('success'))
<script>
alert('{{ session('
    success ') }}');
</script>
@endif
@endsection