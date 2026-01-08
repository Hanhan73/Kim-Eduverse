@extends('layouts.admin')

@section('title', 'Withdrawal Detail')


@section('page-title', 'Withdrawal Detail')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.withdrawals') }}">Withdrawals</a></li>
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

    <!-- Info Sidebar -->
    <div class="col-md-4">
        @if($withdrawal->status == 'completed')
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Status</h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>Withdrawal Completed</h5>
                <p class="text-muted mb-0">Transfer telah selesai</p>
            </div>
        </div>
        @elseif($withdrawal->status == 'rejected')
        <div class="card border-danger mb-3">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-times-circle me-2"></i>Status</h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                <h5>Withdrawal Rejected</h5>
                <p class="text-muted mb-0">Request telah ditolak</p>
            </div>
        </div>
        @elseif($withdrawal->status == 'pending')
        <div class="alert alert-warning">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Info:</strong> Menunggu approval dari Bendahara
        </div>
        @elseif($withdrawal->status == 'approved')
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Info:</strong> Menunggu Bendahara upload bukti transfer
        </div>
        @endif

        <!-- Timeline -->
        <div class="card">
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
                            <small>{{ $withdrawal->approved_at->format('d M Y H:i') }}</small><br>
                            <small class="text-muted">by {{ $withdrawal->approvedBy->name }}</small>
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

        <!-- Instructor Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Instructor Info</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="user-avatar mx-auto" style="width: 60px; height: 60px; font-size: 24px;">
                        {{ strtoupper(substr($withdrawal->instructor->name, 0, 1)) }}
                    </div>
                </div>
                <p class="mb-1"><strong>{{ $withdrawal->instructor->name }}</strong></p>
                <p class="mb-1"><small class="text-muted">{{ $withdrawal->instructor->email }}</small></p>
                <hr>
                <a href="{{ route('admin.super-admin.instructors.detail', $withdrawal->instructor->id) }}"
                    class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-eye me-2"></i>View Profile
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.super-admin.withdrawals') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke List
    </a>
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