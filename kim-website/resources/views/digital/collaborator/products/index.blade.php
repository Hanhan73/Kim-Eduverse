@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>My Products</h1>
        <div class="user-info">
            <button onclick="document.getElementById('createProductModal').style.display='block'" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_products'] }}</h3>
                <p>Total Products</p>
                <small>Semua produk Anda</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active_products'] }}</h3>
                <p>Active Products</p>
                <small>Sedang dijual</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['inactive_products'] }}</h3>
                <p>Inactive Products</p>
                <small>Tidak dijual</small>
            </div>
        </div>
    </div>

    <!-- Create Product Type Selector Modal -->
    <div id="createProductModal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div
            style="background: white; margin: 100px auto; padding: 0; width: 600px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
            <div style="padding: 20px 30px; border-bottom: 2px solid var(--light);">
                <h2 style="margin: 0; font-size: 1.3rem;">Pilih Tipe Produk</h2>
            </div>
            <div style="padding: 30px;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <a href="{{ route('digital.collaborator.questionnaires.create') }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-clipboard-list"
                            style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">CEKMA/Questionnaire</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Buat cekma psikologi</p>
                    </a>

                    <a href="{{ route('digital.collaborator.seminars.create') }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-chalkboard-teacher"
                            style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">On-demand seminar</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Seminar + Pre/Post Test</p>
                    </a>

                    <a href="{{ route('digital.collaborator.products.create-simple', ['type' => 'ebook']) }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-book" style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">E-Book / PDF</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Upload ebook digital</p>
                    </a>

                    <a href="{{ route('digital.collaborator.products.create-simple', ['type' => 'video']) }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-video" style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">Video Course</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Upload video pembelajaran
                        </p>
                    </a>

                    <a href="{{ route('digital.collaborator.products.create-simple', ['type' => 'template']) }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-file-alt" style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">Template</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Template dokumen</p>
                    </a>

                    <a href="{{ route('digital.collaborator.products.create-simple', ['type' => 'module']) }}"
                        style="text-decoration: none; padding: 20px; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.3s; display: block;">
                        <i class="fas fa-box" style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;"></i>
                        <h3 style="margin: 0; font-size: 1rem; color: var(--dark);">Modul Pembelajaran</h3>
                        <p style="margin: 5px 0 0; font-size: 0.85rem; color: var(--gray);">Modul lengkap</p>
                    </a>
                </div>
            </div>
            <div
                style="padding: 20px 30px; border-top: 2px solid var(--light); display: flex; justify-content: flex-end;">
                <button onclick="document.getElementById('createProductModal').style.display='none'"
                    class="btn-secondary">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Produk</h2>
        </div>

        @if($products->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Type</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small style="color: var(--gray);">{{ Str::limit($product->description, 50) }}</small>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            @if($product->type == 'pdf')
                            <span class="badge info"><i class="fas fa-file-pdf"></i> PDF</span>
                            @elseif($product->type == 'video')
                            <span class="badge info"><i class="fas fa-video"></i> Video</span>
                            @else
                            <span class="badge info"><i class="fas fa-file-alt"></i>
                                {{ ucfirst($product->type) }}</span>
                            @endif
                        </td>
                        <td style="font-weight: 700;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->is_active)
                            <span class="badge success">Active</span>
                            @else
                            <span class="badge danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('digital.collaborator.products.edit', $product) }}"
                                    style="color: var(--info); text-decoration: none; padding: 5px 10px;">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('digital.collaborator.products.toggle', $product) }}"
                                    method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit"
                                        style="border: none; background: none; color: var(--warning); cursor: pointer; padding: 5px 10px;">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>

                                <form action="{{ route('digital.collaborator.products.destroy', $product) }}"
                                    method="POST" onsubmit="return confirm('Yakin hapus produk ini?')"
                                    style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="border: none; background: none; color: var(--danger); cursor: pointer; padding: 5px 10px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $products->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Belum ada produk</h3>
            <p>Mulai buat produk pertama Anda</p>
            <a href="{{ route('digital.collaborator.products.create') }}" class="btn-primary" style="margin-top: 20px;">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
        @endif
    </div>
</div>
@endsection