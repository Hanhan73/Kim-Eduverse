@extends('layouts.admin-digital')

@section('title', 'Manage Products - Admin KIM Digital')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Manage Products</h1>
            <p>Kelola produk digital Anda</p>
        </div>
        <a href="{{ route('admin.digital.products.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.digital.products.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="category">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="type">
                    <option value="">Semua Tipe</option>
                    <option value="questionnaire" {{ request('type') == 'questionnaire' ? 'selected' : '' }}>
                        Questionnaire</option>
                    <option value="module" {{ request('type') == 'module' ? 'selected' : '' }}>Module</option>
                    <option value="ebook" {{ request('type') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="template" {{ request('type') == 'template' ? 'selected' : '' }}>Template</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.digital.products.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <!-- Products Table -->
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Collaborator</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Terjual</th>
                    <th>Status</th>
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
                        @if($product->collaborator)
                        <span class="badge badge-info">{{ $product->collaborator->name }}</span>
                        @else
                        <span class="badge badge-secondary">Internal</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $product->category->name }}</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($product->type) }}</span>
                    </td>
                    <td>
                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                    </td>
                    <td>{{ $product->sold_count }}</td>
                    <td>
                        @if($product->is_active)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.products.edit', $product->id) }}" class="btn-icon btn-edit"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.digital.products.destroy', $product->id) }}"
                                style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $products->links('vendor.pagination.admin') }}
        </div>
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

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.filters-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filters-form {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
}

.btn-filter {
    background: #667eea;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-reset {
    background: #f7fafc;
    color: #4a5568;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
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
.product-info .no-image {
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
    font-size: 1.5rem;
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
}

.badge-success {
    background: #c6f6d5;
    color: #22543d;
}

.badge-danger {
    background: #fed7d7;
    color: #742a2a;
}

.badge-info {
    background: #bee3f8;
    color: #2c5282;
}

.badge-secondary {
    background: #e2e8f0;
    color: #4a5568;
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
}

.btn-edit {
    background: #bee3f8;
    color: #2c5282;
}

.btn-delete {
    background: #fed7d7;
    color: #742a2a;
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