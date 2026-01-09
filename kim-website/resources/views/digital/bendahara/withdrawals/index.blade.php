@extends('layouts.bendahara_digital')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Penarikan Dana Collaborator</h1>
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

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['pending'] }}</h3>
                <p>Pending Requests</p>
                <small>Menunggu persetujuan</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</h3>
                <p>Total Pending</p>
                <small>Jumlah yang ditunda</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['approved_today'] }}</h3>
                <p>Approved Hari Ini</p>
                <small>{{ date('d/m/Y') }}</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['approved_amount_today'], 0, ',', '.') }}</h3>
                <p>Total Approved Hari Ini</p>
                <small>Dana yang disetujui</small>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Penarikan Dana</h2>
        </div>

        @if($withdrawals->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Collaborator</th>
                        <th>Jumlah</th>
                        <th>Bank</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $withdrawal->collaborator->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $withdrawal->collaborator->email }}</small>
                        </td>
                        <td style="font-weight: 700;">
                            Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                        </td>
                        <td>
                            {{ $withdrawal->bank_name }}<br>
                            <small style="color: var(--gray);">{{ $withdrawal->account_number }}</small><br>
                            <small style="color: var(--gray);">a.n. {{ $withdrawal->account_name }}</small>
                        </td>
                        <td>
                            @if($withdrawal->status == 'pending')
                            <span class="badge warning">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="badge success">Approved</span>
                            <br><small
                                style="color: var(--gray);">{{ $withdrawal->approved_at->format('d/m/Y H:i') }}</small>
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
        <div style="margin-top: 20px;">
            {{ $withdrawals->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum ada pengajuan penarikan</h3>
            <p>Pengajuan penarikan dana akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection