@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Super Admin Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-user"></i> {{ auth()->user()->name }}
        </div>
    </div>

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

        <!-- Company Share -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Company Revenue (30%)
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

        <!-- Instructor Share -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Instructor Revenue (70%)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['instructor_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Withdrawals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_withdrawals'] }} requests
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Rp {{ number_format($stats['pending_withdrawal_amount'], 0, ',', '.') }}
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

    <!-- Secondary Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                    <div class="h5 mb-0">{{ $stats['total_users'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Instructors</div>
                    <div class="h5 mb-0">{{ $stats['total_instructors'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                    <div class="h5 mb-0">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Bendahara</div>
                    <div class="h5 mb-0">{{ $stats['total_bendaharas'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Instructors -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Top Instructors</h6>
                    <a href="{{ route('admin.super-admin.instructors') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Instructor</th>
                                    <th>Sales</th>
                                    <th>Total Earned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topInstructors as $item)
                                <tr>
                                    <td>{{ $item['instructor']->name }}</td>
                                    <td>{{ $item['total_sales'] }}</td>
                                    <td>Rp {{ number_format($item['total_earned'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Revenues -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Revenues</h6>
                    <a href="{{ route('admin.super-admin.revenue') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Instructor</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRevenues as $revenue)
                                <tr>
                                    <td>{{ $revenue->paid_at->format('d M Y') }}</td>
                                    <td>{{ $revenue->instructor->name }}</td>
                                    <td>Rp {{ number_format($revenue->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Stats -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Revenue Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center border-right">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-3 text-center border-right">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Sales Count</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $revenueStats['sales_count'] }}</div>
                        </div>
                        <div class="col-md-3 text-center border-right">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Average Sale</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($revenueStats['average_sale'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Split</div>
                            <div class="small">
                                <div>Company: Rp {{ number_format($revenueStats['company_share'], 0, ',', '.') }}</div>
                                <div>Instructor: Rp {{ number_format($revenueStats['instructor_share'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection