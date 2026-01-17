@extends('layouts.admin-digital')

@section('title', 'Manage Landing Pages')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Landing Pages</h1>
            <p>Kelola landing page promosi untuk setiap produk</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="info-card">
        <i class="fas fa-info-circle"></i>
        <div>
            <strong>Tentang Landing Page</strong>
            <p>Landing page adalah halaman promosi terpisah dengan URL <code>/promo/[slug]</code>. Halaman detail produk
                (<code>/digital/[slug]</code>) tetap ada. Navbar di landing page berisi tombol "Lihat Detail" dan "Beli
                Sekarang".</p>
        </div>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Landing Page</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div class="product-info">
                            @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                            @else
                            <div class="no-image"><i class="fas fa-image"></i></div>
                            @endif
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <span class="product-slug">{{ $product->slug }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $product->formatted_price }}</strong>
                    </td>
                    <td>
                        @if($product->landingPage && $product->landingPage->is_active)
                        <span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>
                        @elseif($product->landingPage)
                        <span class="badge badge-warning"><i class="fas fa-pause"></i> Nonaktif</span>
                        @else
                        <span class="badge badge-secondary"><i class="fas fa-minus"></i> Belum Ada</span>
                        @endif
                    </td>
                    <td>
                        @if($product->landingPage && $product->landingPage->is_active)
                        <code class="url-code">/promo/{{ $product->slug }}</code>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.landing-pages.edit', $product->id) }}"
                                class="btn-icon btn-edit" title="{{ $product->landingPage ? 'Edit' : 'Buat' }}">
                                <i class="fas {{ $product->landingPage ? 'fa-edit' : 'fa-plus' }}"></i>
                            </a>
                            @if($product->landingPage)
                            <a href="{{ route('admin.digital.landing-pages.preview', $product->id) }}"
                                class="btn-icon btn-preview" title="Preview" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST"
                                action="{{ route('admin.digital.landing-pages.destroy', $product->id) }}"
                                style="display: inline;" onsubmit="return confirm('Yakin hapus landing page ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('digital.show', $product->slug) }}" class="btn-icon btn-view"
                                title="Halaman Detail" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-wrapper">{{ $products->links('vendor.pagination.admin') }}</div>
    </div>
</div>

<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 2rem;
    color: #2d3748;
    margin-bottom: 5px;
}

.page-header p {
    color: #718096;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
}

.info-card {
    background: #ebf8ff;
    border: 2px solid #90cdf4;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
}

.info-card i {
    color: #3182ce;
    font-size: 1.5rem;
}

.info-card strong {
    display: block;
    color: #2c5282;
    margin-bottom: 5px;
}

.info-card p {
    color: #4a5568;
    margin: 0;
    font-size: 0.95rem;
}

.info-card code {
    background: #bee3f8;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    background: #f7fafc;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-info img,
.no-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.no-image {
    background: #f7fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e0;
}

.product-slug {
    display: block;
    color: #a0aec0;
    font-size: 0.85rem;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-success {
    background: #c6f6d5;
    color: #22543d;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.url-code {
    background: #f0fff4;
    color: #22543d;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
}

.text-muted {
    color: #a0aec0;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-edit {
    background: #bee3f8;
    color: #2c5282;
}

.btn-preview {
    background: #c6f6d5;
    color: #22543d;
}

.btn-delete {
    background: #fed7d7;
    color: #742a2a;
}

.btn-view {
    background: #e9d8fd;
    color: #553c9a;
}

.btn-icon:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.text-center {
    text-align: center;
    padding: 40px !important;
    color: #a0aec0;
}

.pagination-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
@endsection