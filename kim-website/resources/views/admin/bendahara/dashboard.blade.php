@extends('layouts.admin')

@section('title', 'Bendahara Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Bendahara</h1>
        <div class="text-muted">
            <i class="fas fa-user"></i> {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Alert for Pending Withdrawals -->
    @if($stats['pending_withdrawals'] > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Perhatian!</strong> Ada {{ $stats['pending_withdrawals'] }} penarikan menunggu approval (Total: Rp
        {{ number_format($stats['pending_amount'], 0, ',', '.') }})
        <a href="{{ route('admin.bendahara.withdrawals') }}" class="alert-link">Lihat sekarang</a>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Perusahaan (30%)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['company_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructor Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Instruktor (70%)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['instructor_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Revenue Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Platform Breakdown (Bulan Ini)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="text-center">
                                <h5 class="text-primary">Edutech</h5>
                                <p class="mb-1">{{ $breakdown['edutech']['sales'] }} sales</p>
                                <h4>Rp {{ number_format($breakdown['edutech']['revenue'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-success">Kim Digital</h5>
                                <p class="mb-1">{{ $breakdown['kim_digital']['sales'] }} sales</p>
                                <h4>Rp {{ number_format($breakdown['kim_digital']['revenue'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Statistics (Bulan Ini)</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Total Penjualan</td>
                            <td class="text-right font-weight-bold">{{ $revenueStats['sales_count'] }}</td>
                        </tr>
                        <tr>
                            <td>Rata-rata per Penjualan</td>
                            <td class="text-right font-weight-bold">Rp
                                {{ number_format($revenueStats['average_sale'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td>Total Revenue</td>
                            <td class="text-right font-weight-bold text-primary">Rp
                                {{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Bagian Perusahaan (30%)</td>
                            <td class="text-right font-weight-bold text-success">Rp
                                {{ number_format($revenueStats['company_share'], 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Bagian Instruktor (70%)</td>
                            <td class="text-right font-weight-bold text-info">Rp
                                {{ number_format($revenueStats['instructor_share'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Withdrawals -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Penarikan Menunggu Approval</h6>
                    <a href="{{ route('admin.bendahara.withdrawals') }}" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($pendingWithdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Instruktor</th>
                                    <th>Jumlah</th>
                                    <th>Bank</th>
                                    <th>Tanggal Request</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->request_code }}</td>
                                    <td>{{ $withdrawal->instructor->name }}</td>
                                    <td class="font-weight-bold">Rp
                                        {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ $withdrawal->bank_name }}<br>
                                        <small class="text-muted">{{ $withdrawal->account_number }}</small>
                                    </td>
                                    <td>{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.bendahara.withdrawals.detail', $withdrawal->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p class="mb-0">Tidak ada penarikan yang menunggu approval</p>
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
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bendahara.revenue') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-line"></i><br>
                                Lihat Revenue
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bendahara.instructor-earnings') }}" class="btn btn-info btn-block">
                                <i class="fas fa-users"></i><br>
                                Earnings Instruktor
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bendahara.withdrawals') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-money-bill-wave"></i><br>
                                Kelola Penarikan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bendahara.reports') }}" class="btn btn-success btn-block">
                                <i class="fas fa-file-alt"></i><br>
                                Laporan Keuangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection