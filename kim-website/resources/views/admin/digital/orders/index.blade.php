@extends('layouts.admin-digital')

@section('title', 'Manage Orders - Admin KIM Digital')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Manage Orders</h1>
            <p>Kelola pesanan pelanggan</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.digital.orders.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Cari order number atau email..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="payment_status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="filter-group">
                <input type="date" name="date_from" placeholder="Dari Tanggal" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <input type="date" name="date_to" placeholder="Sampai Tanggal" value="{{ request('date_to') }}">
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.digital.orders.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_number }}</strong>
                    </td>
                    <td>
                        <div class="customer-info">
                            <strong>{{ $order->customer_email }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $order->items->count() }} item(s)</span>
                    </td>
                    <td>
                        <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        @if($order->payment_status === 'paid')
                        <span class="badge badge-success">Paid</span>
                        @elseif($order->payment_status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @else
                        <span class="badge badge-danger">Failed</span>
                        @endif
                    </td>
                    <td>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.orders.show', $order->id) }}" class="btn-icon btn-view" title="View Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->payment_status === 'paid')
                            <a href="{{ route('admin.digital.orders.invoice', $order->id) }}" class="btn-icon btn-download" title="Download Invoice">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada orders</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $orders->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .page-header p {
        color: #718096;
    }

    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .filters-form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .btn-filter {
        background: #667eea;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reset {
        background: #f7fafc;
        color: #4a5568;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table th {
        background: #f7fafc;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .customer-info strong {
        display: block;
        color: #2d3748;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge-warning {
        background: #feebc8;
        color: #7c2d12;
    }

    .badge-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .badge-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-view {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-download {
        background: #c6f6d5;
        color: #22543d;
    }

    .text-center {
        text-align: center;
        padding: 40px !important;
        color: #a0aec0;
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>
@endsection
