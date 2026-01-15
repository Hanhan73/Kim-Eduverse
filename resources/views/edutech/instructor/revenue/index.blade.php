@extends('layouts.instructor')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Pendapatan Saya</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('edutech_user_name'), 0, 1) }}</div>
            <span>{{ session('edutech_user_name') }}</span>
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

    <!-- Revenue Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($availableRevenue, 0, ',', '.') }}</h3>
                <p>Saldo Tersedia</p>
                <small>Siap untuk ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</h3>
                <p>Dalam Proses</p>
                <small>Menunggu konfirmasi</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($withdrawnRevenue, 0, ',', '.') }}</h3>
                <p>Total Ditarik</p>
                <small>Riwayat penarikan</small>
            </div>
        </div>
    </div>

    <!-- Withdrawal Section -->
    <div class="content-section">
        <div class="section-header">
            <h2>Penarikan Dana</h2>
            @if($availableRevenue >= 50000)
            <a href="{{ route('edutech.instructor.revenue.withdrawal') }}" class="btn-primary">
                <i class="fas fa-money-bill-wave"></i> Tarik Dana
            </a>
            @else
            <button class="btn-disabled" disabled>
                <i class="fas fa-lock"></i> Minimal Rp 50.000
            </button>
            @endif
        </div>

        @if($withdrawals->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Bank</th>
                        <th>No. Rekening</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</strong></td>
                        <td>{{ $withdrawal->bank_name }}</td>
                        <td>{{ $withdrawal->account_number }}</td>
                        <td>
                            @if($withdrawal->status == 'pending')
                            <span class="badge warning">Menunggu Persetujuan</span>
                            @elseif($withdrawal->status == 'approved')
                            <span class="badge success">Disetujui</span>
                            @else
                            <span class="badge draft">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @if($withdrawal->status == 'rejected' && $withdrawal->rejection_reason)
                    <tr>
                        <td colspan="5" style="background: #fff5f5; padding: 10px 15px;">
                            <small style="color: #c53030;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Alasan penolakan:</strong> {{ $withdrawal->rejection_reason }}
                            </small>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $withdrawals->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-money-bill-wave"></i>
            <h3>Belum ada riwayat penarikan</h3>
            <p>Penarikan dana Anda akan muncul di sini</p>
        </div>
        @endif
    </div>

    <!-- Revenue History -->
    <div class="content-section">
        <div class="section-header">
            <h2>Riwayat Pendapatan</h2>
        </div>

        @if($revenues->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Course</th>
                        <th>Harga Course</th>
                        <th>Pendapatan (70%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $revenue->course->title ?? 'N/A' }}</strong>
                        </td>
                        <td>Rp {{ number_format($revenue->course_price, 0, ',', '.') }}</td>
                        <td style="color: #48bb78; font-weight: 700;">
                            Rp {{ number_format($revenue->instructor_share, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($revenue->status == 'pending')
                            <span class="badge warning">Pending</span>
                            @elseif($revenue->status == 'available')
                            <span class="badge success">Tersedia</span>
                            @else
                            <span class="badge info">Ditarik</span>
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
            <h3>Belum ada pendapatan</h3>
            <p>Pendapatan dari penjualan course akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

<style>
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border-left: 4px solid #48bb78;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border-left: 4px solid #f56565;
}

.btn-disabled {
    background: #e2e8f0;
    color: #a0aec0;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: not-allowed;
}
</style>
@endsection