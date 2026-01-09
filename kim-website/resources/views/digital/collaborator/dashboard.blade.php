@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('digital_admin_name'), 0, 1) }}</div>
            <span>{{ session('digital_admin_name') }}</span>
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['available_revenue'], 0, ',', '.') }}</h3>
                <p>Available Revenue</p>
                <small>Siap ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['withdrawn_revenue'], 0, ',', '.') }}</h3>
                <p>Total Withdrawn</p>
                <small>Sudah ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_products'] }}</h3>
                <p>Total Products</p>
                <small>{{ $stats['total_sales'] }} sales</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending_withdrawals'] }}</h3>
                <p>Pending Withdrawals</p>
                <small>Menunggu approval</small>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="content-section">
        <div class="section-header">
            <h2>Top Selling Products</h2>
        </div>

        @if($topProducts->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Product</th>
                        <th>Total Sales</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $index => $data)
                    <tr>
                        <td>
                            @if($index == 0)
                            <span style="font-size: 1.5rem;">🥇</span>
                            @elseif($index == 1)
                            <span style="font-size: 1.5rem;">🥈</span>
                            @elseif($index == 2)
                            <span style="font-size: 1.5rem;">🥉</span>
                            @else
                            <strong>{{ $index + 1 }}</strong>
                            @endif
                        </td>
                        <td><strong>{{ $data->product->name }}</strong></td>
                        <td>{{ $data->sales }} sales</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($data->revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Belum ada produk</h3>
            <p>Mulai buat produk untuk mendapatkan revenue</p>
        </div>
        @endif
    </div>

    <!-- Recent Revenues -->
    <div class="content-section">
        <div class="section-header">
            <h2>Recent Revenues</h2>
            <a href="{{ route('digital.collaborator.revenue.index') }}" class="btn-secondary">View All</a>
        </div>

        @if($recentRevenues->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Your Share (70%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRevenues as $revenue)
                    <tr>
                        <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                        <td><strong>{{ $revenue->product->name }}</strong></td>
                        <td>Rp {{ number_format($revenue->product_price, 0, ',', '.') }}</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($revenue->collaborator_share, 0, ',', '.') }}
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
        @else
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>Belum ada revenue</h3>
            <p>Revenue akan muncul setelah ada penjualan</p>
        </div>
        @endif
    </div>
</div>
@endsection