@extends('layouts.admin')

@section('title', 'System Settings')

@section('page-title', 'System Settings')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <!-- Revenue Sharing Settings -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-percent me-2"></i>Revenue Sharing</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.super-admin.settings.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Instructor Percentage</label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-lg" value="70" readonly>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Bagian yang diterima instruktor dari setiap penjualan course
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Company Percentage</label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-lg" value="30" readonly>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Bagian yang diterima perusahaan dari setiap penjualan course
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Revenue Sharing Model:</strong> 70% untuk instruktor, 30% untuk perusahaan.
                        Setting ini hard-coded di sistem untuk konsistensi.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Withdrawal Settings -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Withdrawal Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.super-admin.settings.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Minimum Withdrawal Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control form-control-lg" value="50000" readonly>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Jumlah minimum yang dapat ditarik oleh instruktor
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Withdrawal Processing Time</label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-lg" value="1-3" readonly>
                            <span class="input-group-text">hari kerja</span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Estimasi waktu proses penarikan dana
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Withdrawal hanya diproses pada hari kerja (Senin-Jumat).
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-server me-2"></i>System Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">System Version</h6>
                        <h4>v1.0.0</h4>
                        <small class="text-muted">Unified Admin System</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Laravel Version</h6>
                        <h4>{{ app()->version() }}</h4>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">PHP Version</h6>
                        <h4>{{ phpversion() }}</h4>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Total Users</h6>
                        <h4>{{ \App\Models\User::count() }}</h4>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Total Instructors</h6>
                        <h4>{{ \App\Models\User::instructors()->count() }}</h4>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted">Total Revenue Shares</h6>
                        <h4>{{ \App\Models\RevenueShare::count() }}</h4>
                    </div>
                </div>

                <div class="alert alert-secondary mt-3">
                    <i class="fas fa-code me-2"></i>
                    <strong>Developer Note:</strong> Untuk mengubah revenue sharing percentage atau minimum withdrawal
                    amount,
                    silakan update di kode (Models & Services). Settings ini dibuat read-only untuk konsistensi sistem.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection