@extends('layouts.admin')

@section('title', 'Revenue Management')

@section('page-title', 'Revenue Management')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.bendahara.dashboard') }}">Dashboard</a></li>
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
                <label class="form-label">Instructor</label>
                <select name="instructor" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Instruktor</option>
                    @foreach($instructors ?? [] as $inst)
                    <option value="{{ $inst->id }}" {{ request('instructor') == $inst->id ? 'selected' : '' }}>
                        {{ $inst->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
                <a href="{{ route('admin.bendahara.revenue') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Revenue</h6>
                <h3 class="mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ $stats['sales_count'] ?? 0 }} penjualan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Company (30%)</h6>
                <h3 class="mb-0 text-success">Rp {{ number_format($stats['company_share'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Instructor (70%)</h6>
                <h3 class="mb-0">Rp {{ number_format($stats['instructor_share'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Rata-rata</h6>
                <h3 class="mb-0">Rp {{ number_format($stats['average_sale'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Platform Stats -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Edutech</h5>
            </div>
            <div class="card-body text-center py-4">
                <h1 class="display-4 text-primary">{{ $breakdown['edutech']['sales'] ?? 0 }}</h1>
                <p class="text-muted mb-2">Penjualan</p>
                <h3>Rp {{ number_format($breakdown['edutech']['revenue'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Kim Digital</h5>
            </div>
            <div class="card-body text-center py-4">
                <h1 class="display-4 text-success">{{ $breakdown['kim_digital']['sales'] ?? 0 }}</h1>
                <p class="text-muted mb-2">Penjualan</p>
                <h3>Rp {{ number_format($breakdown['kim_digital']['revenue'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Table -->
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
                        <th>Kode</th>
                        <th>Instruktor</th>
                        <th>Platform</th>
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
                        <td>{{ $rev->instructor->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $rev->course_type == 'edutech' ? 'primary' : 'success' }}">
                                {{ strtoupper($rev->course_type) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">Rp {{ number_format($rev->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end text-success fw-bold">Rp
                            {{ number_format($rev->company_share, 0, ',', '.') }}</td>
                        <td class="text-end text-primary">Rp {{ number_format($rev->instructor_share, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data revenue</p>
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