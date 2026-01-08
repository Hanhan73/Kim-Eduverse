@extends('layouts.instructor')

@section('title', 'My Earnings')

@section('page-title', 'My Earnings')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Earnings</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Period Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
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
        </form>
    </div>
</div>

<!-- Current Balance -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Available Balance</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}</h2>
                <a href="{{ route('instructor.withdrawals') }}" class="btn btn-sm btn-success mt-3">
                    <i class="fas fa-arrow-right me-2"></i>Tarik Saldo
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Penghasilan</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->total_earned ?? 0, 0, ',', '.') }}</h2>
                <small class="text-muted">Dari {{ $earning->total_sales ?? 0 }} penjualan</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Sudah Ditarik</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->withdrawn ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Statistik Periode Ini</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h4>{{ $revenueStats['total_sales'] ?? 0 }}</h4>
                <p class="text-muted mb-0">Total Penjualan</p>
            </div>
            <div class="col-md-3">
                <h4>Rp {{ number_format($revenueStats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                <p class="text-muted mb-0">Total Revenue</p>
            </div>
            <div class="col-md-3">
                <h4>{{ $revenueStats['edutech_sales'] ?? 0 }}</h4>
                <p class="text-muted mb-0">Edutech Sales</p>
            </div>
            <div class="col-md-3">
                <h4>{{ $revenueStats['kim_digital_sales'] ?? 0 }}</h4>
                <p class="text-muted mb-0">Kim Digital Sales</p>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Detail -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detail Penghasilan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="earningsTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Platform</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Bagian Anda (70%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues ?? [] as $rev)
                    <tr>
                        <td>{{ $rev->paid_at->format('d M Y H:i') }}</td>
                        <td><code>{{ $rev->transaction_code }}</code></td>
                        <td>
                            <span class="badge bg-{{ $rev->course_type == 'edutech' ? 'primary' : 'success' }}">
                                {{ strtoupper($rev->course_type) }}
                            </span>
                        </td>
                        <td class="text-end">Rp {{ number_format($rev->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp
                            {{ number_format($rev->instructor_share, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada penghasilan untuk periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($revenues) && $revenues->hasPages())
        <div class="mt-3">
            {{ $revenues->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#earningsTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush