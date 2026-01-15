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

    <!-- Filter Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p>Total Revenue (All Time)</p>
                <small>Collaborator share: 70%</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['platform_share'], 0, ',', '.') }}</h3>
                <p>Platform Revenue (30%)</p>
                <small>Total platform earnings</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_transactions'] }}</h3>
                <p>Total Transaksi</p>
                <small>Semua product sales</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</h3>
                <p>Rata-rata Transaksi</p>
                <small>Per product sold</small>
            </div>
        </div>
    </div>

    <!-- Revenue List -->
    <div class="content-section">
        <div class="section-header">
            <h2>Detail Revenue</h2>
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
                        <th>Collaborator (70%)</th>
                        <th>Platform (30%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $revenue->collaborator->name }}</strong>
                        </td>
                        <td>{{ $revenue->product->name }}</td>
                        <td>Rp {{ number_format($revenue->product_price, 0, ',', '.') }}</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($revenue->collaborator_share, 0, ',', '.') }}
                        </td>
                        <td style="color: var(--warning); font-weight: 700;">
                            Rp {{ number_format($revenue->platform_share, 0, ',', '.') }}
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
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $revenues->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>Belum ada revenue</h3>
            <p>Revenue dari transaksi akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection