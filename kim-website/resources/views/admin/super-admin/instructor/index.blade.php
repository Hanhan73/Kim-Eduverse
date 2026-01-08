@extends('layouts.admin')

@section('title', 'Instructors Management')

@section('page-title', 'Instructors Management')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Instructors</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Instructors</h6>
                <h2 class="mb-0">{{ $instructors->total() ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Active Courses</h6>
                <h2 class="mb-0">{{ $totalCourses ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Earnings</h6>
                <h2 class="mb-0">Rp {{ number_format($totalEarnings ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending Balance</h6>
                <h2 class="mb-0">Rp {{ number_format($totalBalance ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Instructors Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Instruktor</h5>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#filterModal">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Instruktor</th>
                        <th>Email</th>
                        <th class="text-end">Total Earned</th>
                        <th class="text-end">Available Balance</th>
                        <th class="text-center">Total Sales</th>
                        <th class="text-center">Courses</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructors ?? [] as $instructor)
                    @php
                    $earning = $instructor->instructorEarning;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $instructor->name }}</div>
                                    <small class="text-muted">Joined
                                        {{ $instructor->created_at->format('M Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $instructor->email }}</td>
                        <td class="text-end">
                            <span class="fw-bold text-success">
                                Rp {{ number_format($earning->total_earned ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold text-primary">
                                Rp {{ number_format($earning->available_balance ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $earning->total_sales ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <small>
                                Edutech: {{ $instructor->edutechCourses->count() }}<br>
                                Kim Digital: {{ $instructor->kimDigitalCourses->count() }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.super-admin.instructors.detail', $instructor->id) }}"
                                class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data instruktor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($instructors) && $instructors->hasPages())
        <div class="mt-3">
            {{ $instructors->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Instructors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Minimum Earnings</label>
                        <input type="number" name="min_earnings" class="form-control"
                            value="{{ request('min_earnings') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Sales</label>
                        <input type="number" name="min_sales" class="form-control" value="{{ request('min_sales') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="earnings" {{ request('sort') == 'earnings' ? 'selected' : '' }}>Earnings
                                (High to Low)</option>
                            <option value="sales" {{ request('sort') == 'sales' ? 'selected' : '' }}>Sales (High to Low)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.super-admin.instructors') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection