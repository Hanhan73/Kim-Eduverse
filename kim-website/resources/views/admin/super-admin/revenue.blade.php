@extends('layouts.admin')

@section('title', 'Revenue Overview')

@section('page-title', 'Revenue Overview')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Revenue</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Period Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label">Period</label>
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('period', 'month') == 'month' ? 'selected' : '' }}>Bulan Ini
                    </option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h3 class="mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                        <small class="text-muted">{{ $stats['sales_count'] ?? 0 }} penjualan</small>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Company Share (30%)</h6>
                        <h3 class="mb-0">Rp {{ number_format($stats['company_share'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Instructor Share (70%)</h6>
                        <h3 class="mb-0">Rp {{ number_format($stats['instructor_share'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Rata-rata</h6>
                        <h3 class="mb-0">Rp {{ number_format($stats['average_sale'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Platform Breakdown -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Edutech Platform</h5>
            </div>
            <div class="card-body text-center py-4">
                <h1 class="display-4 text-primary">{{ $breakdown['edutech']['sales'] ?? 0 }}</h1>
                <p class="text-muted mb-3">Total Penjualan</p>
                <h3 class="text-dark">Rp {{ number_format($breakdown['edutech']['revenue'] ?? 0, 0, ',', '.') }}</h3>
                <small class="text-muted">Total Revenue</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Kim Digital Platform</h5>
            </div>
            <div class="card-body text-center py-4">
                <h1 class="display-4 text-success">{{ $breakdown['kim_digital']['sales'] ?? 0 }}</h1>
                <p class="text-muted mb-3">Total Penjualan</p>
                <h3 class="text-dark">Rp {{ number_format($breakdown['kim_digital']['revenue'] ?? 0, 0, ',', '.') }}
                </h3>
                <small class="text-muted">Total Revenue</small>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Detail Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detail Revenue</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Instruktor</th>
                        <th>Platform</th>
                        <th>Course</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Company</th>
                        <th class="text-end">Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues ?? [] as $rev)
                    <tr>
                        <td>{{ $rev->paid_at->format('d M Y H:i') }}</td>
                        <td><code>{{ $rev->transaction_code }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                    {{ strtoupper(substr($rev->instructor->name ?? 'N', 0, 1)) }}
                                </div>
                                {{ $rev->instructor->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $rev->course_type == 'edutech' ? 'primary' : 'success' }}">
                                {{ strtoupper($rev->course_type) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($rev->course_name ?? 'N/A', 30) }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($rev->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end text-success">Rp {{ number_format($rev->company_share, 0, ',', '.') }}</td>
                        <td class="text-end text-primary">Rp {{ number_format($rev->instructor_share, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data revenue untuk periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Destroy existing instance
    if ($.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable().destroy();
    }

    // Re-initialize
    $('.datatable').DataTable({
        "pageLength": 10,
        "destroy": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
@endsection