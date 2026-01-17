@extends('layouts.bendahara_digital')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Revenue Report</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('digital_admin_name'), 0, 1) }}</div>
            <span>{{ session('digital_admin_name') }}</span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="content-section">
        <form method="GET"
            style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end; background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Periode</label>
                <select name="period" class="form-control" onchange="this.form.submit()">
                    <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini
                    </option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini
                    </option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Bulan Lalu
                    </option>
                    <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun Ini
                    </option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            @if(request('period') == 'custom')
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div>
                <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            @endif

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Collaborator</label>
                <select name="collaborator_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Collaborator</option>
                    @foreach($collaborators as $collab)
                    <option value="{{ $collab->id }}" {{ request('collaborator_id') == $collab->id ? 'selected' : '' }}>
                        {{ $collab->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            @if(request()->hasAny(['period', 'collaborator_id', 'start_date']))
            <div>
                <a href="{{ route('digital.bendahara.revenue.index') }}" class="btn-secondary"
                    style="padding: 10px 20px;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Filter Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p>Total Revenue {{ request('period') && request('period') != 'all' ? '(Filtered)' : '(All Time)' }}</p>
                <small>{{ $stats['total_transactions'] }} transaksi</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['collaborator_total'], 0, ',', '.') }}</h3>
                <p>Total Collaborator Share (70%)</p>
                <small>Setelah platform fee</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['platform_share'], 0, ',', '.') }}</h3>
                <p>Platform Revenue</p>
                <small>Fee Rp 5000 + 30% share</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['platform_fee_total'], 0, ',', '.') }}</h3>
                <p>Total Platform Fee</p>
                <small>Rp 5000 x {{ $stats['total_transactions'] }} transaksi</small>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown Chart -->
    <div class="content-section">
        <div class="section-header">
            <h2>Revenue Breakdown</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 10px; color: white;">
                <h4 style="margin: 0 0 10px 0; color: white;">Platform Fee (Rp 5000/transaksi)</h4>
                <h2 style="margin: 0; color: white;">Rp {{ number_format($stats['platform_fee_total'], 0, ',', '.') }}
                </h2>
                <small
                    style="opacity: 0.9;">{{ number_format(($stats['platform_fee_total'] / max($stats['total_revenue'], 1)) * 100, 1) }}%
                    dari total</small>
            </div>
            <div
                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 10px; color: white;">
                <h4 style="margin: 0 0 10px 0; color: white;">Remaining Amount</h4>
                <h2 style="margin: 0; color: white;">Rp
                    {{ number_format($stats['total_revenue'] - $stats['platform_fee_total'], 0, ',', '.') }}</h2>
                <small style="opacity: 0.9;">Setelah dikurangi platform fee</small>
            </div>
            <div
                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 10px; color: white;">
                <h4 style="margin: 0 0 10px 0; color: white;">Collaborator (70% dari sisa)</h4>
                <h2 style="margin: 0; color: white;">Rp {{ number_format($stats['collaborator_total'], 0, ',', '.') }}
                </h2>
                <small
                    style="opacity: 0.9;">{{ number_format(($stats['collaborator_total'] / max($stats['total_revenue'], 1)) * 100, 1) }}%
                    dari total</small>
            </div>
            <div
                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 25px; border-radius: 10px; color: white;">
                <h4 style="margin: 0 0 10px 0; color: white;">Platform Additional (30% dari sisa)</h4>
                <h2 style="margin: 0; color: white;">Rp
                    {{ number_format($stats['platform_share'] - $stats['platform_fee_total'], 0, ',', '.') }}</h2>
                <small
                    style="opacity: 0.9;">{{ number_format((($stats['platform_share'] - $stats['platform_fee_total']) / max($stats['total_revenue'], 1)) * 100, 1) }}%
                    dari total</small>
            </div>
        </div>
    </div>

    <!-- Revenue List -->
    <div class="content-section">
        <div class="section-header">
            <h2>Detail Revenue</h2>
            <button onclick="exportRevenue()" class="btn-primary">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>

        @if($revenues->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Collaborator</th>
                        <th>Product</th>
                        <th>Harga</th>
                        <th>Platform Fee</th>
                        <th>Sisa</th>
                        <th>Collab (70%)</th>
                        <th>Platform (30%)</th>
                        <th>Total Platform</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                    <tr>
                        <td>
                            {{ $revenue->created_at->format('d/m/Y') }}<br>
                            <small style="color: var(--gray);">{{ $revenue->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong>{{ $revenue->collaborator->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $revenue->collaborator->email }}</small>
                        </td>
                        <td>
                            {{ $revenue->product->name }}<br>
                            <small style="color: var(--gray);">Order #{{ $revenue->order->order_number }}</small>
                        </td>
                        <td style="font-weight: 700;">Rp {{ number_format($revenue->product_price, 0, ',', '.') }}</td>
                        <td style="color: var(--warning); font-weight: 600;">
                            - Rp 5000
                        </td>
                        <td>
                            Rp {{ number_format(max(0, $revenue->product_price - 5000), 0, ',', '.') }}
                        </td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($revenue->collaborator_share, 0, ',', '.') }}
                        </td>
                        <td style="color: var(--info); font-weight: 600;">
                            Rp {{ number_format($revenue->platform_share - 5000, 0, ',', '.') }}
                        </td>
                        <td style="color: var(--orange); font-weight: 700;">
                            Rp {{ number_format($revenue->platform_share, 0, ',', '.') }}
                            <br><small style="color: var(--gray);">5000 + 30%</small>
                        </td>
                        <td>
                            @if($revenue->status == 'available')
                            <span class="badge success">Available</span>
                            @elseif($revenue->status == 'withdrawn')
                            <span class="badge info">Withdrawn</span>
                            @else
                            <span class="badge warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f7fafc; font-weight: 700;">
                        <td colspan="3">TOTAL ({{ $revenues->total() }} transaksi)</td>
                        <td>Rp {{ number_format($revenues->sum('product_price'), 0, ',', '.') }}</td>
                        <td style="color: var(--warning);">- Rp
                            {{ number_format($revenues->count() * 5000, 0, ',', '.') }}</td>
                        <td>Rp
                            {{ number_format($revenues->sum('product_price') - ($revenues->count() * 5000), 0, ',', '.') }}
                        </td>
                        <td style="color: var(--success);">Rp
                            {{ number_format($revenues->sum('collaborator_share'), 0, ',', '.') }}</td>
                        <td style="color: var(--info);">Rp
                            {{ number_format($revenues->sum('platform_share') - ($revenues->count() * 5000), 0, ',', '.') }}
                        </td>
                        <td style="color: var(--orange);">Rp
                            {{ number_format($revenues->sum('platform_share'), 0, ',', '.') }}</td>
                        <td>-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $revenues->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>Tidak ada revenue</h3>
            <p>{{ request()->hasAny(['period', 'collaborator_id']) ? 'Tidak ada data untuk filter yang dipilih' : 'Revenue dari transaksi akan muncul di sini' }}
            </p>
        </div>
        @endif
    </div>
</div>

<style>
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
}

.btn-secondary {
    background: #e2e8f0;
    color: var(--dark);
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-secondary:hover {
    background: #cbd5e0;
}
</style>

<script>
function exportRevenue() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = '{{ route("digital.bendahara.revenues.index") }}?' + params.toString();
}
</script>
@endsection