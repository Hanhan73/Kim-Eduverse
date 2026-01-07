@extends('layouts.instructor')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Instruktor</h1>
        <div class="text-muted">
            <i class="fas fa-user"></i> {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Earnings Summary -->
    <div class="row mb-4">
        <!-- Available Balance -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Saldo Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-muted mt-2">
                                <a href="{{ route('instructor.withdrawals') }}" class="text-success">
                                    <i class="fas fa-arrow-right"></i> Tarik Saldo
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Earned -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penghasilan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_earned'], 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-muted mt-2">
                                Dari {{ $stats['total_sales'] }} penjualan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawn -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sudah Ditarik
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['withdrawn'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawal -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Penarikan Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['pending_withdrawal'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Penjualan</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-right">
                            <h5 class="text-primary">Total Penjualan</h5>
                            <h2 class="font-weight-bold">{{ $stats['total_sales'] }}</h2>
                            <p class="text-muted mb-0">courses terjual</p>
                        </div>
                        <div class="col-md-4 border-right">
                            <h5 class="text-info">Edutech</h5>
                            <h2 class="font-weight-bold">{{ $stats['edutech_sales'] }}</h2>
                            <p class="text-muted mb-0">courses terjual</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-success">Kim Digital</h5>
                            <h2 class="font-weight-bold">{{ $stats['kim_digital_sales'] }}</h2>
                            <p class="text-muted mb-0">courses terjual</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Revenues -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Pemasukan Terakhir</h6>
                    <a href="{{ route('instructor.earnings') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentRevenues->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Course</th>
                                    <th>Bagian Anda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRevenues as $revenue)
                                <tr>
                                    <td>{{ $revenue->paid_at->format('d M Y') }}</td>
                                    <td>
                                        <small
                                            class="badge badge-{{ $revenue->course_type == 'edutech' ? 'primary' : 'success' }}">
                                            {{ strtoupper($revenue->course_type) }}
                                        </small>
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        Rp {{ number_format($revenue->instructor_share, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada pemasukan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Penarikan</h6>
                    <a href="{{ route('instructor.withdrawals') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentWithdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                                    <td class="font-weight-bold">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </td>
                                    <td>{!! $withdrawal->status_badge !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada riwayat penarikan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Menu Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('instructor.earnings') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-line"></i><br>
                                Detail Penghasilan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('instructor.withdrawals') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill-wave"></i><br>
                                Penarikan Saldo
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('instructor.bank-accounts') }}" class="btn btn-info btn-block">
                                <i class="fas fa-university"></i><br>
                                Rekening Bank
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('instructor.courses') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-book"></i><br>
                                Kursus Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Info:</strong> Anda mendapatkan 70% dari setiap penjualan course.
                Minimum penarikan adalah Rp 50.000.
                Penarikan akan diproses dalam 1-3 hari kerja setelah approval.
            </div>
        </div>
    </div>
</div>
@endsection