@extends('layouts.bendahara')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Detail Instructor: {{ $instructor->name }}</h1>
        <div class="user-info">
            <a href="{{ route('edutech.bendahara.instructors.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Instructor Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p>Total Revenue</p>
                <small>All time earnings</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['available'], 0, ',', '.') }}</h3>
                <p>Available Balance</p>
                <small>Siap ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['withdrawn'], 0, ',', '.') }}</h3>
                <p>Total Withdrawn</p>
                <small>Sudah ditarik</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Rp {{ number_format($stats['pending'], 0, ',', '.') }}</h3>
                <p>Pending</p>
                <small>Dalam proses</small>
            </div>
        </div>
    </div>

    <!-- Instructor Info -->
    <div class="content-section" style="margin-bottom: 25px;">
        <div class="section-header">
            <h2>Informasi Instructor</h2>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 12px 0; width: 40%; color: var(--dark); font-weight: 600;">Nama Lengkap</td>
                        <td style="padding: 12px 0;">{{ $instructor->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Email</td>
                        <td style="padding: 12px 0;">{{ $instructor->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Phone</td>
                        <td style="padding: 12px 0;">{{ $instructor->phone ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 12px 0; width: 40%; color: var(--dark); font-weight: 600;">Status</td>
                        <td style="padding: 12px 0;">
                            @if($instructor->is_active)
                            <span class="badge success">Active</span>
                            @else
                            <span class="badge danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Member Since</td>
                        <td style="padding: 12px 0;">{{ $instructor->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Email Verified</td>
                        <td style="padding: 12px 0;">
                            @if($instructor->email_verified_at)
                            <span class="badge success">Verified</span>
                            @else
                            <span class="badge warning">Not Verified</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Courses -->
    <div class="content-section" style="margin-bottom: 25px;">
        <div class="section-header">
            <h2>Courses</h2>
        </div>

        @if($courses->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Price</th>
                        <th>Enrollments</th>
                        <th>Sales</th>
                        <th>Revenue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $data)
                    <tr>
                        <td><strong>{{ $data['course']->title }}</strong></td>
                        <td>Rp {{ number_format($data['course']->price, 0, ',', '.') }}</td>
                        <td>{{ $data['course']->enrollments_count }} students</td>
                        <td>{{ $data['sales'] }} sales</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                        </td>
                        <td>
                            @if($data['course']->is_published)
                            <span class="badge success">Published</span>
                            @else
                            <span class="badge warning">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-book"></i>
            <h3>Belum ada course</h3>
            <p>Instructor belum membuat course</p>
        </div>
        @endif
    </div>

    <!-- Withdrawal History -->
    <div class="content-section" style="margin-bottom: 25px;">
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
                        <th>Aksi</th>
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
                            @else
                            <span class="badge danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('edutech.bendahara.withdrawals.show', $withdrawal) }}"
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
            <i class="fas fa-money-bill-wave"></i>
            <h3>Belum ada penarikan</h3>
            <p>Instructor belum melakukan penarikan dana</p>
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
                        <th>Course</th>
                        <th>Harga</th>
                        <th>Instructor Share (70%)</th>
                        <th>Platform Share (30%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                        <td><strong>{{ $revenue->course->title }}</strong></td>
                        <td>Rp {{ number_format($revenue->course_price, 0, ',', '.') }}</td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($revenue->instructor_share, 0, ',', '.') }}
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
            <p>Revenue instructor akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection