@extends('layouts.admin')

@section('title', 'Instructor Earnings')

@section('page-title', 'Instructor Earnings')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Instructor Earnings</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Instructors</h6>
                <h2 class="mb-0">{{ $instructors->count() ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Earned</h6>
                <h2 class="mb-0">Rp {{ number_format($totalEarned ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Available Balance</h6>
                <h2 class="mb-0">Rp {{ number_format($totalBalance ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Withdrawn</h6>
                <h2 class="mb-0">Rp {{ number_format($totalWithdrawn ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Earnings Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Earnings Semua Instruktor</h5>
            <button type="button" class="btn btn-sm btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Instruktor</th>
                        <th class="text-end">Total Earned</th>
                        <th class="text-end">Available</th>
                        <th class="text-end">Withdrawn</th>
                        <th class="text-end">Pending</th>
                        <th class="text-center">Total Sales</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings ?? [] as $earning)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 14px;">
                                    {{ strtoupper(substr($earning->instructor->name ?? 'N', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $earning->instructor->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $earning->instructor->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-success">Rp
                                {{ number_format($earning->total_earned, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-primary">Rp
                                {{ number_format($earning->available_balance, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-muted">Rp {{ number_format($earning->withdrawn, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-warning">Rp
                                {{ number_format($earning->pending_withdrawal, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $earning->total_sales }}</span>
                            <br>
                            <small class="text-muted">
                                E: {{ $earning->edutech_sales }} | K: {{ $earning->kim_digital_sales }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.bendahara.instructor-earnings.detail', $earning->instructor_id) }}"
                                class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data earnings</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>TOTAL</th>
                        <th class="text-end">Rp {{ number_format($totalEarned ?? 0, 0, ',', '.') }}</th>
                        <th class="text-end">Rp {{ number_format($totalBalance ?? 0, 0, ',', '.') }}</th>
                        <th class="text-end">Rp {{ number_format($totalWithdrawn ?? 0, 0, ',', '.') }}</th>
                        <th class="text-end">Rp {{ number_format($totalPending ?? 0, 0, ',', '.') }}</th>
                        <th class="text-center">{{ $totalSales ?? 0 }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(isset($earnings) && $earnings->hasPages())
        <div class="mt-3">
            {{ $earnings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection