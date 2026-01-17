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

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
                <small>All time earnings</small>
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

        <div style="padding: 20px; background: #f7fafc; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 10px 0; color: var(--dark);">Saldo Tersedia: Rp
                {{ number_format($availableRevenue, 0, ',', '.') }}</h3>
            <p style="margin: 0; color: var(--gray);">Minimum penarikan: Rp 50.000</p>
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

        <!-- Filter Section -->
        <div class="filter-section"
            style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <form method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end;">
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
                @endif

                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia
                        </option>
                        <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Ditarik
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                @if(request()->hasAny(['period', 'status', 'start_date']))
                <div>
                    <a href="{{ route('edutech.instructor.revenue.index') }}" class="btn-secondary"
                        style="padding: 10px 20px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
                @endif
            </form>
        </div>

        <!-- Summary for filtered period -->
        @if(isset($periodStats))
        <div class="stats-grid" style="margin-bottom: 20px;">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="stat-content">
                    <h3 style="color: white;">Rp {{ number_format($periodStats['total_revenue'], 0, ',', '.') }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Total Revenue (Periode)</p>
                    <small style="color: rgba(255,255,255,0.8);">{{ $periodStats['transaction_count'] }}
                        penjualan</small>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="stat-content">
                    <h3 style="color: white;">Rp {{ number_format($periodStats['your_share'], 0, ',', '.') }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Your Share (70%)</p>
                    <small style="color: rgba(255,255,255,0.8);">Setelah platform fee</small>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="stat-content">
                    <h3 style="color: white;">Rp {{ number_format($periodStats['platform_fee'], 0, ',', '.') }}</h3>
                    <p style="color: rgba(255,255,255,0.9);">Platform Fee</p>
                    <small style="color: rgba(255,255,255,0.8);">Rp 5000/transaksi</small>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <div class="stat-content">
                    <h3 style="color: white;">Rp {{ number_format($periodStats['avg_per_transaction'], 0, ',', '.') }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.9);">Rata-rata per Transaksi</p>
                    <small style="color: rgba(255,255,255,0.8);">Your share average</small>
                </div>
            </div>
        </div>
        @endif

        @if($revenues->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Course</th>
                        <th>Harga Course</th>
                        <th>Platform Fee</th>
                        <th>Sisa</th>
                        <th>Pendapatan (70%)</th>
                        <th>Platform (30%)</th>
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
                            <strong>{{ $revenue->course->title ?? 'N/A' }}</strong><br>
                            <small style="color: var(--gray);">Order #{{ $revenue->order->order_number }}</small>
                        </td>
                        <td style="font-weight: 700;">Rp {{ number_format($revenue->course_price, 0, ',', '.') }}</td>
                        <td style="color: var(--warning);">
                            - Rp 500
                        </td>
                        <td>
                            Rp {{ number_format(max(0, $revenue->course_price - 5000), 0, ',', '.') }}
                        </td>
                        <td style="color: #48bb78; font-weight: 700;">
                            Rp {{ number_format($revenue->instructor_share, 0, ',', '.') }}
                            <br><small style="color: var(--gray);">70% dari sisa</small>
                        </td>
                        <td style="color: var(--info); font-weight: 600;">
                            Rp {{ number_format($revenue->platform_share, 0, ',', '.') }}
                            <br><small style="color: var(--gray);">Fee + 30%</small>
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
                <tfoot>
                    <tr style="background: #f7fafc; font-weight: 700;">
                        <td colspan="2">TOTAL ({{ $revenues->total() }} transaksi)</td>
                        <td>Rp {{ number_format($revenues->sum('course_price'), 0, ',', '.') }}</td>
                        <td style="color: var(--warning);">- Rp
                            {{ number_format($revenues->count() * 5000, 0, ',', '.') }}</td>
                        <td>Rp
                            {{ number_format($revenues->sum('course_price') - ($revenues->count() * 5000), 0, ',', '.') }}
                        </td>
                        <td style="color: #48bb78;">Rp
                            {{ number_format($revenues->sum('instructor_share'), 0, ',', '.') }}</td>
                        <td style="color: var(--info);">Rp
                            {{ number_format($revenues->sum('platform_share'), 0, ',', '.') }}</td>
                        <td>-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $revenues->appends(request()->query())->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>Tidak ada pendapatan</h3>
            <p>{{ request()->hasAny(['period', 'status']) ? 'Tidak ada data untuk filter yang dipilih' : 'Pendapatan dari penjualan course akan muncul di sini' }}
            </p>
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

.filter-section .form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
}

.btn-secondary {
    background: #e2e8f0;
    color: var(--dark);
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
@endsection