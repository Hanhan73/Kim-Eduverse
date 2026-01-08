@extends('layouts.instructor')

@section('title', 'Withdrawals')

@section('page-title', 'Withdrawals')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Withdrawals</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Current Balance -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">Saldo Tersedia</h5>
                        <h1 class="mb-2">Rp {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}</h1>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Minimum penarikan: Rp 50.000
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal"
                            data-bs-target="#withdrawModal"
                            {{ ($earning->available_balance ?? 0) < 50000 ? 'disabled' : '' }}>
                            <i class="fas fa-money-bill-wave me-2"></i>Request Penarikan
                        </button>
                        @if(($earning->available_balance ?? 0) < 50000) <small class="d-block text-muted mt-2">Saldo
                            minimum belum tercapai</small>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Penarikan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th class="text-end">Jumlah</th>
                        <th>Bank</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals ?? [] as $wd)
                    <tr>
                        <td>{{ $wd->created_at->format('d M Y H:i') }}</td>
                        <td><code>{{ $wd->request_code }}</code></td>
                        <td class="text-end fw-bold">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                        <td>
                            <strong>{{ $wd->bank_name }}</strong><br>
                            <small class="text-muted">{{ $wd->account_number }}</small>
                        </td>
                        <td>
                            @if($wd->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                            @elseif($wd->status == 'approved')
                            <span class="badge bg-info">Approved</span>
                            @elseif($wd->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                            @elseif($wd->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                            @endif
                            @if($wd->status == 'rejected' && $wd->rejection_reason)
                            <br><small class="text-danger">{{ $wd->rejection_reason }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wd->status == 'pending')
                            <form method="POST" action="{{ route('instructor.withdrawals.cancel', $wd) }}"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin membatalkan?')">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </form>
                            @elseif($wd->status == 'completed' && $wd->transfer_proof)
                            <button type="button" class="btn btn-sm btn-info"
                                onclick="showProof('{{ asset('storage/' . $wd->transfer_proof) }}')">
                                <i class="fas fa-receipt"></i> Bukti
                            </button>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat penarikan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($withdrawals) && $withdrawals->hasPages())
        <div class="mt-3">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Request Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Penarikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('instructor.withdrawals.request') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Saldo Tersedia: <strong>Rp
                            {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Penarikan (Rp)</label>
                        <input type="number" name="amount" class="form-control" min="50000"
                            max="{{ $earning->available_balance ?? 0 }}" placeholder="Minimal Rp 50.000" required>
                        <small class="text-muted">Minimum: Rp 50.000 | Maximum: Rp
                            {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rekening Bank</label>
                        <select name="bank_account_id" class="form-select" required>
                            <option value="">Pilih Rekening</option>
                            @forelse($bankAccounts ?? [] as $bank)
                            <option value="{{ $bank->id }}" {{ $bank->is_primary ? 'selected' : '' }}>
                                {{ $bank->bank_name }} - {{ $bank->account_number }} ({{ $bank->account_holder_name }})
                                @if($bank->is_primary) ⭐ @endif
                            </option>
                            @empty
                            <option value="" disabled>Belum ada rekening bank</option>
                            @endforelse
                        </select>
                        @if(($bankAccounts ?? collect())->count() == 0)
                        <small class="text-danger">
                            Anda belum memiliki rekening bank.
                            <a href="{{ route('instructor.bank-accounts') }}">Tambah rekening</a>
                        </small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"
                        {{ ($bankAccounts ?? collect())->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="proofImage" src="" class="img-fluid" alt="Bukti Transfer">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showProof(imageUrl) {
    document.getElementById('proofImage').src = imageUrl;
    new bootstrap.Modal(document.getElementById('proofModal')).show();
}
</script>
@endpush