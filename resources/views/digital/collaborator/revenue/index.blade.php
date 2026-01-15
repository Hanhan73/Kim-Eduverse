@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Revenue & Withdrawal</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('digital_admin_name'), 0, 1) }}</div>
            <span>{{ session('digital_admin_name') }}</span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Revenue Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($availableRevenue, 0, ',', '.') }}</h3>
                <p>Available Balance</p>
                <small>Dapat ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</h3>
                <p>Pending Revenue</p>
                <small>Dalam proses</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($withdrawnRevenue, 0, ',', '.') }}</h3>
                <p>Total Withdrawn</p>
                <small>Sudah dicairkan</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($availableRevenue + $withdrawnRevenue, 0, ',', '.') }}</h3>
                <p>Total Revenue</p>
                <small>All time</small>
            </div>
        </div>
    </div>

    <!-- Withdrawal Button -->
    <div class="content-section">
        <div class="section-header">
            <h2>Penarikan Dana</h2>
        </div>

        <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: #f7fafc; border-radius: 8px; margin-bottom: 20px;">
            <div>
                <h3 style="margin: 0; color: var(--dark);">Saldo Tersedia: Rp
                    {{ number_format($availableRevenue, 0, ',', '.') }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--gray);">Minimum penarikan: Rp 50.000</p>
            </div>
            <a href="{{ route('digital.collaborator.revenue.withdrawal') }}" class="btn-primary"
                style="{{ $availableRevenue < 50000 ? 'opacity: 0.5; cursor: not-allowed; pointer-events: none;' : '' }}">
                <i class="fas fa-money-bill-wave"></i> Ajukan Penarikan
            </a>
        </div>

        @if($availableRevenue < 50000) <div class="alert alert-warning">
            <i class="fas fa-info-circle"></i>
            Saldo Anda belum mencapai minimum penarikan Rp 50.000
    </div>
    @endif
</div>

<!-- Withdrawal History -->
<div class="content-section">
    <div class="section-header">
        <h2>Riwayat Penarikan</h2>
    </div>

    @if($withdrawals->count() > 0)
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Bank</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-weight: 700;">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                    <td>
                        {{ $withdrawal->bank_name }}<br>
                        <small style="color: var(--gray);">{{ $withdrawal->account_number }}</small>
                    </td>
                    <td>
                        @if($withdrawal->status == 'pending')
                        <span class="badge warning">Pending</span>
                        @elseif($withdrawal->status == 'approved')
                        <span class="badge success">Approved</span>
                        <br><small style="color: var(--gray);">{{ $withdrawal->approved_at->format('d/m/Y') }}</small>
                        @else
                        <span class="badge danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($withdrawal->status == 'rejected')
                        <small style="color: var(--danger);">{{ $withdrawal->rejection_reason }}</small>
                        @else
                        {{ $withdrawal->notes ?? '-' }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        {{ $withdrawals->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>Belum ada penarikan</h3>
        <p>Riwayat penarikan akan muncul di sini</p>
    </div>
    @endif
</div>

<!-- Revenue History -->
<div class="content-section">
    <div class="section-header">
        <h2>Riwayat Revenue</h2>
    </div>

    @if($revenues->count() > 0)
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Product</th>
                    <th>Harga</th>
                    <th>Your Share (70%)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenues as $revenue)
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
    <div style="margin-top: 20px;">
        {{ $revenues->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-chart-line"></i>
        <h3>Belum ada revenue</h3>
        <p>Revenue akan muncul setelah ada penjualan produk</p>
    </div>
    @endif
</div>
</div>
@endsection