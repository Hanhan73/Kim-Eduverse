@extends('layouts.admin-digital')

@section('title', 'Manage Categories - Admin KIM Digital')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Manage Categories</h1>
            <p>Kelola kategori produk digital</p>
        </div>
        <a href="{{ route('admin.digital.categories.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <!-- Categories Table -->
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Jumlah Produk</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        @if($category->icon)
                        <div class="category-icon">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                        @else
                        <div class="category-icon no-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $category->name }}</strong>
                        @if($category->description)
                        <p class="category-desc">{{ Str::limit($category->description, 60) }}</p>
                        @endif
                    </td>
                    <td>
                        <code>{{ $category->slug }}</code>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $category->products_count }} produk</span>
                    </td>
                    <td>
                        @if($category->is_active)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.categories.edit', $category->id) }}" 
                               class="btn-icon btn-edit" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" 
                                  action="{{ route('admin.digital.categories.destroy', $category->id) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn-icon btn-delete" 
                                        title="Delete"
                                        {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e0; margin-bottom: 10px;"></i>
                        <p>Belum ada kategori</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $categories->links() }}
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

    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .category-icon.no-icon {
        background: #f7fafc;
        color: #cbd5e0;
    }

    .category-desc {
        color: #a0aec0;
        font-size: 0.85rem;
        margin: 5px 0 0 0;
    }

    code {
        background: #f7fafc;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #667eea;
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

    .btn-icon:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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
