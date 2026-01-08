@extends('layouts.admin')

@section('title', 'Withdrawal Detail')

@section('sidebar-menu')
<div class="menu-title">Menu Utama</div>
<a href="{{ route('admin.bendahara.dashboard') }}" class="menu-item">
    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
</a>
<a href="{{ route('admin.bendahara.revenue') }}" class="menu-item">
    <i class="fas fa-chart-line"></i><span>Revenue</span>
</a>
<a href="{{ route('admin.bendahara.instructor-earnings') }}" class="menu-item">
    <i class="fas fa-users"></i><span>Instructor Earnings</span>
</a>
<a href="{{ route('admin.bendahara.withdrawals') }}" class="menu-item active">
    <i class="fas fa-money-bill-wave"></i><span>Withdrawals</span>
</a>
<a href="{{ route('admin.bendahara.reports') }}" class="menu-item">
    <i class="fas fa-file-alt"></i><span>Reports</span>
</a>
@endsection

@section('page-title', 'Withdrawal Detail')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.withdrawals') }}">Withdrawals</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <!-- Withdrawal Info -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $withdrawal->request_code }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Instruktor</th>
                        <td>
                            <strong>{{ $withdrawal->instructor->name }}</strong><br>
                            <small class="text-muted">{{ $withdrawal->instructor->email }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Penarikan</th>
                        <td class="fs-3 fw-bold text-success">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Balance Sebelum</th>
                        <td>Rp {{ number_format($withdrawal->balance_before ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Balance Sesudah</th>
                        <td>Rp {{ number_format($withdrawal->balance_after ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Bank Tujuan</th>
                        <td>
                            <strong>{{ $withdrawal->bank_name }}</strong><br>
                            <code class="fs-6">{{ $withdrawal->account_number }}</code><br>
                            <small>{{ $withdrawal->account_holder_name }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($withdrawal->status == 'pending')
                            <span class="badge bg-warning fs-6">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="badge bg-info fs-6">Approved</span>
                            @elseif($withdrawal->status == 'completed')
                            <span class="badge bg-success fs-6">Completed</span>
                            @elseif($withdrawal->status == 'rejected')
                            <span class="badge bg-danger fs-6">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Request</th>
                        <td>{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($withdrawal->approved_by)
                    <tr>
                        <th>Approved By</th>
                        <td>
                            {{ $withdrawal->approvedBy->name }}<br>
                            <small class="text-muted">{{ $withdrawal->approved_at?->format('d M Y H:i') }}</small>
                        </td>
                    </tr>
                    @endif
                    @if($withdrawal->notes)
                    <tr>
                        <th>Notes</th>
                        <td>
                            <div class="alert alert-info mb-0">{{ $withdrawal->notes }}</div>
                        </td>
                    </tr>
                    @endif
                    @if($withdrawal->rejection_reason)
                    <tr>
                        <th>Alasan Ditolak</th>
                        <td>
                            <div class="alert alert-danger mb-0">{{ $withdrawal->rejection_reason }}</div>
                        </td>
                    </tr>
                    @endif
                    @if($withdrawal->completed_at)
                    <tr>
                        <th>Completed At</th>
                        <td>{{ $withdrawal->completed_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Transfer Proof -->
        @if($withdrawal->transfer_proof && $withdrawal->status == 'completed')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Bukti Transfer</h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $withdrawal->transfer_proof) }}" class="img-fluid rounded"
                    alt="Bukti Transfer" style="max-height: 500px;">
            </div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="col-md-4">
        @if($withdrawal->status == 'pending')
        <!-- Approve Form -->
        <div class="card mb-3 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Approve Withdrawal</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bendahara.withdrawals.approve', $withdrawal) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Catatan untuk instruktor..."></textarea>
                        <small class="text-muted">Catatan ini akan terlihat oleh instruktor</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100"
                        onclick="return confirm('Approve withdrawal request ini?')">
                        <i class="fas fa-check me-2"></i>Approve Request
                    </button>
                </form>
            </div>
        </div>

        <!-- Reject Form -->
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-times-circle me-2"></i>Reject Withdrawal</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bendahara.withdrawals.reject', $withdrawal) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3"
                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                        <small class="text-muted">Wajib diisi</small>
                    </div>
                    <button type="submit" class="btn btn-danger w-100"
                        onclick="return confirm('Reject withdrawal request ini?')">
                        <i class="fas fa-times me-2"></i>Reject Request
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($withdrawal->status == 'approved')
        <!-- Complete Form -->
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-check-double me-2"></i>Complete Withdrawal</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Upload bukti transfer untuk menyelesaikan withdrawal
                </div>
                <form method="POST" action="{{ route('admin.bendahara.withdrawals.complete', $withdrawal) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="transfer_proof" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"
                        onclick="return confirm('Complete withdrawal ini?')">
                        <i class="fas fa-check-double me-2"></i>Complete & Upload
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($withdrawal->status == 'completed')
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Status</h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>Withdrawal Completed</h5>
                <p class="text-muted mb-0">Transfer telah selesai</p>
            </div>
        </div>
        @endif

        @if($withdrawal->status == 'rejected')
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-times-circle me-2"></i>Status</h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                <h5>Withdrawal Rejected</h5>
                <p class="text-muted mb-0">Request telah ditolak</p>
            </div>
        </div>
        @endif

        <!-- History Timeline -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <i class="fas fa-circle text-primary"></i>
                        <div>
                            <strong>Request Created</strong><br>
                            <small>{{ $withdrawal->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @if($withdrawal->approved_at)
                    <div class="timeline-item">
                        <i class="fas fa-circle text-success"></i>
                        <div>
                            <strong>Approved</strong><br>
                            <small>{{ $withdrawal->approved_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                    @if($withdrawal->completed_at)
                    <div class="timeline-item">
                        <i class="fas fa-circle text-info"></i>
                        <div>
                            <strong>Completed</strong><br>
                            <small>{{ $withdrawal->completed_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item i {
    position: absolute;
    left: -30px;
    top: 5px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -26px;
    top: 15px;
    width: 2px;
    height: calc(100% - 10px);
    background: #dee2e6;
}
</style>
@endpush