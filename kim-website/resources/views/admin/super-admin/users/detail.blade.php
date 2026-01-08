@extends('layouts.admin')

@section('title', 'Instructor Detail')


@section('page-title', 'Instructor Detail')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.instructors') }}">Instructors</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Instructor Info -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Instructor Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                    {{ strtoupper(substr($instructor->name, 0, 1)) }}
                </div>
            </div>
            <div class="col-md-5">
                <p><strong>Nama:</strong> {{ $instructor->name }}</p>
                <p><strong>Email:</strong> {{ $instructor->email }}</p>
                <p><strong>Bergabung:</strong> {{ $instructor->created_at->format('d M Y') }}</p>
            </div>
            <div class="col-md-5">
                <p><strong>Edutech Courses:</strong> {{ $instructor->edutechCourses->count() }}</p>
                <p><strong>Kim Digital Courses:</strong> {{ $instructor->kimDigitalCourses->count() }}</p>
                <p><strong>Bank Accounts:</strong> {{ $instructor->bankAccounts->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Earnings Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Earned</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->total_earned ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Available Balance</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Withdrawn</h6>
                <h2 class="mb-0">Rp {{ number_format($earning->withdrawn ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Sales</h6>
                <h2 class="mb-0">{{ $earning->total_sales ?? 0 }}</h2>
                <small class="text-muted">
                    E: {{ $earning->edutech_sales ?? 0 }} | K: {{ $earning->kim_digital_sales ?? 0 }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Revenue History -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Revenue History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Platform</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Bagian Instruktor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues ?? [] as $rev)
                    <tr>
                        <td>{{ $rev->paid_at->format('d M Y H:i') }}</td>
                        <td><code>{{ $rev->transaction_code }}</code></td>
                        <td>
                            <span class="badge bg-{{ $rev->course_type == 'edutech' ? 'primary' : 'success' }}">
                                {{ strtoupper($rev->course_type) }}
                            </span>
                        </td>
                        <td class="text-end">Rp {{ number_format($rev->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp
                            {{ number_format($rev->instructor_share, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada revenue</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Withdrawals History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Withdrawal History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th class="text-end">Jumlah</th>
                        <th>Bank</th>
                        <th>Status</th>
                        <th>Approved By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals ?? [] as $wd)
                    <tr>
                        <td>{{ $wd->created_at->format('d M Y H:i') }}</td>
                        <td><code>{{ $wd->request_code }}</code></td>
                        <td class="text-end fw-bold">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                        <td>
                            {{ $wd->bank_name }}<br>
                            <small class="text-muted">{{ $wd->account_number }}</small>
                        </td>
                        <td>
                            @if($wd->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                            @elseif($wd->status == 'approved')
                            <span class="badge bg-info">Approved</span>
                            @elseif($wd->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                            @elseif($wd->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($wd->approvedBy)
                            {{ $wd->approvedBy->name }}<br>
                            <small class="text-muted">{{ $wd->approved_at?->format('d M Y') }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada withdrawal</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection