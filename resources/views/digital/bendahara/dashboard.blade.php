@extends('layouts.bendahara_digital')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Dashboard Bendahara Digital</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('digital_admin_name'), 0, 1) }}</div>
            <span>{{ session('digital_admin_name') }}</span>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending_count'] }}</h3>
                <p>Pending Withdrawals</p>
                <small>Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['approved_today'] }}</h3>
                <p>Approved Today</p>
                <small>Rp {{ number_format($stats['approved_amount_today'], 0, ',', '.') }}</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['total_revenue_month'], 0, ',', '.') }}</h3>
                <p>Total Revenue Bulan Ini</p>
                <small>{{ $stats['transactions_month'] }} transaksi</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active_collaborators'] }}</h3>
                <p>Active Collaborators</p>
                <small>Yang memiliki revenue</small>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="content-section">
        <div class="section-header">
            <h2>Aktivitas Terbaru</h2>
        </div>

        @if($recentWithdrawals->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Collaborator</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentWithdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $withdrawal->collaborator->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $withdrawal->collaborator->email }}</small>
                        </td>
                        <td><strong>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($withdrawal->status == 'pending')
                            <span class="badge warning">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="badge success">Approved</span>
                            @else
                            <span class="badge danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('digital.bendahara.withdrawals.show', $withdrawal) }}"
                                style="color: var(--info); text-decoration: none; font-weight: 600;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum ada aktivitas</h3>
            <p>Aktivitas withdrawal akan muncul di sini</p>
        </div>
        @endif
    </div>

    <!-- Top Earning Collaborators -->
    <div class="content-section">
        <div class="section-header">
            <h2>Top Earning Collaborators Bulan Ini</h2>
        </div>

        @if($topCollaborators->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Collaborator</th>
                        <th>Total Revenue</th>
                        <th>Available Balance</th>
                        <th>Total Withdrawn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCollaborators as $index => $data)
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
                        <td>
                            <strong>{{ $data['collaborator']->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $data['collaborator']->email }}</small>
                        </td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}
                        </td>
                        <td>Rp {{ number_format($data['available'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($data['withdrawn'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-bar"></i>
            <h3>Belum ada data</h3>
            <p>Data collaborator akan muncul setelah ada transaksi</p>
        </div>
        @endif
    </div>
</div>
@endsection