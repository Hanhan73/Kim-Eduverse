@extends('layouts.bendahara')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Detail Penarikan Dana</h1>
        <div class="user-info">
            <a href="{{ route('edutech.bendahara.withdrawals.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
        <!-- Withdrawal Details -->
        <div class="content-section">
            <div class="section-header">
                <h2>Informasi Penarikan</h2>
                @if($withdrawal->status == 'pending')
                <span class="badge warning">Pending</span>
                @elseif($withdrawal->status == 'approved')
                <span class="badge success">Approved</span>
                @else
                <span class="badge danger">Rejected</span>
                @endif
            </div>

            <table style="width: 100%;">
                <tr>
                    <td style="padding: 12px 0; width: 40%; color: var(--dark); font-weight: 600;">Tanggal Pengajuan
                    </td>
                    <td style="padding: 12px 0;">{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Jumlah</td>
                    <td style="padding: 12px 0; font-size: 1.5rem; font-weight: 700; color: var(--success);">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Nama Bank</td>
                    <td style="padding: 12px 0;">{{ $withdrawal->bank_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Nomor Rekening</td>
                    <td style="padding: 12px 0;">{{ $withdrawal->account_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Nama Pemilik</td>
                    <td style="padding: 12px 0;">{{ $withdrawal->account_name }}</td>
                </tr>
                @if($withdrawal->notes)
                <tr>
                    <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Catatan</td>
                    <td style="padding: 12px 0;">{{ $withdrawal->notes }}</td>
                </tr>
                @endif
            </table>

            @if($withdrawal->status == 'approved')
            <div
                style="background: #c6f6d5; color: #22543d; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #48bb78;">
                <i class="fas fa-check-circle"></i>
                Disetujui oleh <strong>{{ $withdrawal->approver->name }}</strong><br>
                pada {{ $withdrawal->approved_at->format('d/m/Y H:i') }}
            </div>
            @elseif($withdrawal->status == 'rejected')
            <div
                style="background: #fed7d7; color: #742a2a; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #f56565;">
                <i class="fas fa-times-circle"></i>
                Ditolak oleh <strong>{{ $withdrawal->approver->name }}</strong><br>
                pada {{ $withdrawal->approved_at->format('d/m/Y H:i') }}<br>
                <strong>Alasan:</strong> {{ $withdrawal->rejection_reason }}
            </div>
            @endif
        </div>

        <!-- Instructor Info -->
        <div>
            <div class="content-section">
                <div class="section-header">
                    <h2>Informasi Instructor</h2>
                </div>

                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 12px 0; width: 40%; color: var(--dark); font-weight: 600;">Nama</td>
                        <td style="padding: 12px 0;">{{ $withdrawal->instructor->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Email</td>
                        <td style="padding: 12px 0;">{{ $withdrawal->instructor->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Saldo Tersedia</td>
                        <td style="padding: 12px 0; font-size: 1.5rem; font-weight: 700; color: var(--info);">
                            Rp {{ number_format($availableRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                @if($withdrawal->amount > $availableRevenue)
                <div
                    style="background: #fed7d7; color: #742a2a; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #f56565;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Peringatan:</strong> Jumlah penarikan melebihi saldo tersedia!
                </div>
                @endif
            </div>

            @if($withdrawal->status == 'pending')
            <div class="content-section" style="margin-top: 20px;">
                <div class="section-header">
                    <h2>Aksi</h2>
                </div>

                <form action="{{ route('edutech.bendahara.withdrawals.approve', $withdrawal) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menyetujui penarikan ini?')" style="margin-bottom: 15px;">
                    @csrf
                    <button type="submit" class="btn-primary"
                        style="width: 100%; {{ $withdrawal->amount > $availableRevenue ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"
                        {{ $withdrawal->amount > $availableRevenue ? 'disabled' : '' }}>
                        <i class="fas fa-check"></i> Setujui Penarikan
                    </button>
                </form>

                <button type="button" onclick="document.getElementById('rejectModal').style.display='block'"
                    style="width: 100%; padding: 12px 24px; background: linear-gradient(135deg, #fc8181, #f56565); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times"></i> Tolak Penarikan
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Revenues -->
    <div class="content-section">
        <div class="section-header">
            <h2>10 Pendapatan Terakhir Instructor</h2>
        </div>

        @if($recentRevenues->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Course</th>
                        <th>Harga</th>
                        <th>Share (70%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRevenues as $revenue)
                    <tr>
                        <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                        <td><strong>{{ $revenue->course->title }}</strong></td>
                        <td>Rp {{ number_format($revenue->course_price, 0, ',', '.') }}</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($revenue->instructor_share, 0, ',', '.') }}
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
            <h3>Belum ada pendapatan</h3>
            <p>Pendapatan instructor akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal"
    style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div
        style="background: white; margin: 100px auto; padding: 0; width: 500px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
        <form action="{{ route('edutech.bendahara.withdrawals.reject', $withdrawal) }}" method="POST">
            @csrf
            <div style="padding: 20px 30px; border-bottom: 2px solid var(--light);">
                <h2 style="margin: 0; font-size: 1.3rem;">Tolak Penarikan</h2>
            </div>
            <div style="padding: 30px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Alasan Penolakan <span style="color: #f56565;">*</span>
                </label>
                <textarea name="rejection_reason"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; resize: vertical;"
                    rows="4" required></textarea>
                <small style="color: var(--gray); display: block; margin-top: 5px;">Jelaskan alasan penolakan kepada
                    instructor</small>
            </div>
            <div
                style="padding: 20px 30px; border-top: 2px solid var(--light); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('rejectModal').style.display='none'"
                    class="btn-secondary">
                    Batal
                </button>
                <button type="submit"
                    style="padding: 10px 20px; background: linear-gradient(135deg, #fc8181, #f56565); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Tolak Penarikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection