@extends('layouts.admin')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Period Selection -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label">Periode Laporan</label>
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('period', 'month') == 'month' ? 'selected' : '' }}>Bulan Ini
                    </option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
                <button type="button" class="btn btn-primary" onclick="exportExcel()">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Revenue Summary -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Revenue Summary</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="300">Total Revenue</th>
                        <td class="text-end fs-5 fw-bold">Rp
                            {{ number_format($revenueStats['total_revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Sales</th>
                        <td class="text-end">{{ $revenueStats['sales_count'] ?? 0 }} transaksi</td>
                    </tr>
                    <tr>
                        <th>Average Sale Value</th>
                        <td class="text-end">Rp {{ number_format($revenueStats['average_sale'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Company Share (30%)</th>
                        <td class="text-end text-success fw-bold">Rp
                            {{ number_format($revenueStats['company_share'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Instructor Share (70%)</th>
                        <td class="text-end">Rp {{ number_format($revenueStats['instructor_share'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Platform Breakdown -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Edutech Platform</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Total Sales</th>
                        <td class="text-end">{{ $breakdown['edutech']['sales'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Total Revenue</th>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($breakdown['edutech']['revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Company Share</th>
                        <td class="text-end text-success">Rp
                            {{ number_format(($breakdown['edutech']['revenue'] ?? 0) * 0.3, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">Kim Digital Platform</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Total Sales</th>
                        <td class="text-end">{{ $breakdown['kim_digital']['sales'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Total Revenue</th>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($breakdown['kim_digital']['revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Company Share</th>
                        <td class="text-end text-success">Rp
                            {{ number_format(($breakdown['kim_digital']['revenue'] ?? 0) * 0.3, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Summary -->
<div class="card mb-4">
    <div class="card-header bg-warning">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Withdrawal Summary</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="300">Pending Withdrawals</th>
                        <td class="text-end">{{ $withdrawalStats['pending_count'] ?? 0 }} requests (Rp
                            {{ number_format($withdrawalStats['pending_amount'] ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <th>Approved Withdrawals</th>
                        <td class="text-end">{{ $withdrawalStats['approved_count'] ?? 0 }} requests (Rp
                            {{ number_format($withdrawalStats['approved_amount'] ?? 0, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <th>Completed Withdrawals</th>
                        <td class="text-end text-success fw-bold">{{ $withdrawalStats['completed_count'] ?? 0 }}
                            requests (Rp {{ number_format($withdrawalStats['completed_amount'] ?? 0, 0, ',', '.') }})
                        </td>
                    </tr>
                    <tr>
                        <th>Rejected Withdrawals</th>
                        <td class="text-end text-danger">{{ $withdrawalStats['rejected_count'] ?? 0 }} requests (Rp
                            {{ number_format($withdrawalStats['rejected_amount'] ?? 0, 0, ',', '.') }})</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Top 10 Instructors -->
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 10 Instructors</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">Rank</th>
                        <th>Instruktor</th>
                        <th class="text-end">Total Earned</th>
                        <th class="text-center">Total Sales</th>
                        <th class="text-center">Courses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topInstructors ?? [] as $index => $item)
                    <tr>
                        <td class="text-center">
                            @if($index == 0)
                            <i class="fas fa-trophy text-warning fa-lg"></i>
                            @elseif($index == 1)
                            <i class="fas fa-medal text-secondary fa-lg"></i>
                            @elseif($index == 2)
                            <i class="fas fa-medal text-danger fa-lg"></i>
                            @else
                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item['instructor']->name }}</strong><br>
                            <small class="text-muted">{{ $item['instructor']->email }}</small>
                        </td>
                        <td class="text-end fw-bold text-success">Rp
                            {{ number_format($item['total_earned'], 0, ',', '.') }}</td>
                        <td class="text-center"><span class="badge bg-primary">{{ $item['total_sales'] }}</span></td>
                        <td class="text-center">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportExcel() {
    alert('Export Excel functionality - Coming soon!');
    // Implement export functionality here
}
</script>
@endpush