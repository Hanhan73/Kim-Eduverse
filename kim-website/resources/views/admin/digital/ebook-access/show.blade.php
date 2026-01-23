@extends('layouts.admin-digital')

@section('title', 'Detail Akses E-book - Admin KIM Digital')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Detail Akses E-book</h1>
            <p>Informasi lengkap akses e-book pelanggan</p>
        </div>
        <a href="{{ route('admin.digital.ebook-access.index') }}" class="btn-secondary">
            ← Kembali
        </a>
    </div>

    <div class="detail-grid">
        <!-- Left -->
        <div class="detail-card">
            <h3>Informasi E-book</h3>

            <div class="info-row">
                <span class="label">Nama E-book</span>
                <span class="value">{{ $access->product->name }}</span>
            </div>

            <div class="info-row">
                <span class="label">Order</span>
                <span class="value">
                    <a href="{{ route('admin.digital.orders.show', $access->order->id) }}">
                        {{ $access->order->order_number }}
                    </a>
                </span>
            </div>

            <div class="info-row">
                <span class="label">Email Pelanggan</span>
                <span class="value">{{ $access->order->customer_email }}</span>
            </div>

            <div class="info-row">
                <span class="label">Tanggal Beli</span>
                <span class="value">{{ $access->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <!-- Right -->
        <div class="detail-card">
            <h3>Status Akses</h3>

            <div class="info-row">
                <span class="label">Status</span>
                <span class="value">
                    @if($access->isValid())
                    <span class="badge badge-success">Aktif</span>
                    @elseif($access->isExpired())
                    <span class="badge badge-danger">Kadaluarsa</span>
                    @else
                    <span class="badge badge-secondary">Dicabut</span>
                    @endif
                </span>
            </div>

            <div class="info-row">
                <span class="label">Kadaluarsa</span>
                <span class="value">
                    {{ $access->expires_at->format('d M Y, H:i') }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">Sisa Hari</span>
                <span class="value">
                    @if($access->isExpired())
                    <span style="color:#e53e3e;font-weight:600;">Kadaluarsa</span>
                    @else
                    <span style="color:#48bb78;font-weight:600;">
                        {{ $access->days_remaining }} hari
                    </span>
                    @endif
                </span>
            </div>

            <div class="info-row">
                <span class="label">Jumlah Dibuka</span>
                <span class="value">{{ $access->view_count }} kali</span>
            </div>

            <div class="info-row">
                <span class="label">Terakhir Diakses</span>
                <span class="value">
                    {{ $access->last_accessed_at ? $access->last_accessed_at->format('d M Y, H:i') : '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">IP Terakhir</span>
                <span class="value">{{ $access->last_ip ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions-card">
        <h3>Tindakan</h3>

        <div class="action-buttons">
            @if($access->isValid())
            <button class="btn-warning" onclick="showExtendModal({{ $access->id }}, '{{ $access->product->name }}')">
                Perpanjang Akses
            </button>

            <form method="POST" action="{{ route('admin.digital.ebook-access.revoke', $access->id) }}">
                @csrf
                <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin mencabut akses ini?')">
                    Cabut Akses
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.digital.ebook-access.reactivate', $access->id) }}">
                @csrf
                <button type="submit" class="btn-success">
                    Aktifkan Kembali
                </button>
            </form>
            @endif
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
                <p style="margin-bottom: 20px;">
                    E-book: <strong id="ebookName"></strong>
                </p>

                <div class="form-group">
                    <label for="days">
                        Tambah Durasi (Hari) <span class="required">*</span>
                    </label>
                    <input type="number" id="days" name="days" class="form-control" min="1" max="365" value="30"
                        required>
                </div>

                <!-- Preset -->
                <div class="preset-buttons">
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.detail-card,
.actions-card {
    background: white;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.detail-card h3,
.actions-card h3 {
    font-size: 1.2rem;
    margin-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #e2e8f0;
}

.label {
    color: #718096;
    font-weight: 600;
}

.value a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
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
    gap: 15px;
    flex-wrap: wrap;
}

.btn-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-warning:hover {
    background: #fde68a;
}

.btn-danger {
    background: #fed7d7;
    color: #742a2a;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #feb2b2;
}

.btn-success {
    background: #c6f6d5;
    color: #22543d;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-success:hover {
    background: #9ae6b4;
}

.btn-secondary {
    background: #e2e8f0;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    color: #4a5568;
    font-weight: 600;
    display: inline-block;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.btn-primary {
    background: #667eea;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #5a67d8;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.3rem;
    color: #2d3748;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #718096;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f7fafc;
    color: #2d3748;
}

.modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #4a5568;
}

.required {
    color: #e53e3e;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.preset-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.btn-preset {
    background: #f7fafc;
    color: #4a5568;
    padding: 8px 15px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-preset:hover {
    background: #edf2f7;
    border-color: #cbd5e0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 25px;
    border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .modal-content {
        width: 95%;
    }

    .preset-buttons {
        justify-content: space-between;
    }

    .btn-preset {
        flex: 1;
        text-align: center;
    }
}
</style>

<script>
function showExtendModal(accessId, ebookName) {
    document.getElementById('ebookName').textContent = ebookName;
    document.getElementById('extendForm').action = `/admin/digital/ebook-access/${accessId}/extend`;
    document.getElementById('extendModal').classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
}

function closeExtendModal() {
    document.getElementById('extendModal').classList.remove('show');
    document.body.style.overflow = ''; // Restore scrolling
}

function setExtendDays(days) {
    document.getElementById('days').value = days;
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('extendModal');
    if (event.target == modal) {
        closeExtendModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeExtendModal();
    }
});
</script>
@endsection