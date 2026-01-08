@extends('layouts.admin')

@section('title', 'Withdrawal Management')

@section('page-title', 'Withdrawal Management')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Withdrawals</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending</h6>
                <h2 class="mb-0">{{ $stats['pending_count'] ?? 0 }}</h2>
                <small class="text-muted">Rp {{ number_format($stats['pending_amount'] ?? 0, 0, ',', '.') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Approved</h6>
                <h2 class="mb-0">{{ $stats['approved_count'] ?? 0 }}</h2>
                <small class="text-muted">Rp {{ number_format($stats['approved_amount'] ?? 0, 0, ',', '.') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Completed</h6>
                <h2 class="mb-0">{{ $stats['completed_count'] ?? 0 }}</h2>
                <small class="text-muted">Rp {{ number_format($stats['completed_amount'] ?? 0, 0, ',', '.') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <h6 class="text-muted mb-2">Rejected</h6>
                <h2 class="mb-0">{{ $stats['rejected_count'] ?? 0 }}</h2>
                <small class="text-muted">Rp {{ number_format($stats['rejected_amount'] ?? 0, 0, ',', '.') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-auto">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <a href="{{ route('admin.bendahara.withdrawals') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Pending Alert -->
@if(($stats['pending_count'] ?? 0) > 0)
<div class="alert alert-warning alert-dismissible fade show">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Perhatian!</strong> Ada {{ $stats['pending_count'] }} withdrawal request yang menunggu approval.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Withdrawals Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Withdrawal</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Instruktor</th>
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
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                    {{ strtoupper(substr($wd->instructor->name ?? 'N', 0, 1)) }}
                                </div>
                                {{ $wd->instructor->name ?? 'N/A' }}
                            </div>
                        </td>
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
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.bendahara.withdrawals.detail', $wd->id) }}"
                                class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada withdrawal request</p>
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
@endsection