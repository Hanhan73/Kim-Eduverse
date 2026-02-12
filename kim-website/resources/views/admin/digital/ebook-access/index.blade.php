@extends('layouts.admin-digital')

@section('title', 'Manage E-book Access - Admin KIM Digital')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Manage E-book Access</h1>
            <p>Kelola akses e-book pelanggan</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.digital.ebook-access.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Cari email..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="product_id">
                    <option value="">Semua E-book</option>
                    @foreach($products as $prod)
                    <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                        {{ $prod->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa
                    </option>
                    <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Dicabut</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.digital.ebook-access.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <!-- Accesses Table -->
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>E-book</th>
                    <th>Email Pelanggan</th>
                    <th>Order</th>
                    <th>Tanggal Beli</th>
                    <th>Kadaluarsa</th>
                    <th>Sisa Hari</th>
                    <th>Status</th>
                    <th>Dibuka</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accesses as $access)
                <tr>
                    <td>
                        <strong>{{ $access->product->name }}</strong>
                    </td>
                    <td>{{ $access->order->customer_email }}</td>
                    <td>
                        <a href="{{ route('admin.digital.orders.show', $access->order->id) }}"
                            style="color: #667eea; text-decoration: none;">
                            {{ $access->order->order_number }}
                        </a>
                    </td>
                    <td>{{ $access->created_at->format('d M Y') }}</td>
                    <td>{{ $access->expires_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($access->isExpired())
                        <span style="color: #e53e3e; font-weight: 600;">Kadaluarsa</span>
                        @else
                        <span style="color: #48bb78; font-weight: 600;">{{ $access->days_remaining }} hari</span>
                        @endif
                    </td>
                    <td>
                        @if($access->isValid())
                        <span class="badge badge-success">Aktif</span>
                        @elseif($access->isExpired())
                        <span class="badge badge-danger">Kadaluarsa</span>
                        @else
                        <span class="badge badge-secondary">Dicabut</span>
                        @endif
                    </td>
                    <td>{{ $access->view_count }}x</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.ebook-access.show', $access->id) }}"
                                class="btn-icon btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($access->isValid())
                            <button type="button" class="btn-icon btn-warning" title="Perpanjang"
                                onclick="showExtendModal({{ $access->id }}, '{{ $access->product->name }}')">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.digital.ebook-access.revoke', $access->id) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-icon btn-delete" title="Cabut Akses"
                                    onclick="return confirm('Yakin ingin mencabut akses ini?')">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @else
                            <form method="POST"
                                action="{{ route('admin.digital.ebook-access.reactivate', $access->id) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-icon btn-success" title="Aktifkan Kembali">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data akses e-book</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $accesses->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>

<!-- Modal Perpanjang Akses -->
<div class="modal-overlay" id="extendModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Perpanjang Akses E-book</h2>
            <button type="button" class="modal-close" onclick="closeExtendModal()">&times;</button>
        </div>
        <form method="POST" id="extendForm">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom: 20px;">E-book: <strong id="ebookName"></strong></p>

                <div class="form-group">
                    <label for="days">Tambah Durasi (Hari) <span class="required">*</span></label>
                    <input type="number" id="days" name="days" class="form-control" min="1" max="365" value="30"
                        required>
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                    <button type="button" class="btn-preset" onclick="setExtendDays(30)">+1 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(60)">+2 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(90)">+3 Bulan</button>
                    <button type="button" class="btn-preset" onclick="setExtendDays(180)">+6 Bulan</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeExtendModal()">Batal</button>
                <button type="submit" class="btn-primary">Perpanjang Akses</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 30px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 2rem;
    color: #2d3748;
    margin-bottom: 5px;
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
}

.btn-filter,
.btn-reset {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
}

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-reset {
    background: #f7fafc;
    color: #4a5568;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
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
}

.btn-info {
    background: #bee3f8;
    color: #2c5282;
}

.btn-warning {
    background: #fef3c7;
    color: #92400e;
}

.btn-delete {
    background: #fed7d7;
    color: #742a2a;
}

.btn-success {
    background: #c6f6d5;
    color: #22543d;
}

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal-overlay.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 500px;
    width: 100%;
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

.modal-body {
    padding: 30px;
}

.modal-footer {
    padding: 20px 30px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1px solid #e2e8f0;
}

.btn-primary,
.btn-secondary {
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-preset {
    padding: 6px 12px;
    background: #e2e8f0;
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    font-size: 0.85rem;
    cursor: pointer;
}
</style>

<script>
function showExtendModal(accessId, ebookName) {
    document.getElementById('ebookName').textContent = ebookName;
    document.getElementById('extendForm').action = '/admin/digital/ebook-access/' + accessId + '/extend';
    document.getElementById('extendModal').classList.add('show');
}

function closeExtendModal() {
    document.getElementById('extendModal').classList.remove('show');
}

function setExtendDays(days) {
    document.getElementById('days').value = days;
}
</script>
@endsection