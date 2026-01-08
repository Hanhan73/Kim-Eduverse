@extends('layouts.admin-digital')

@section('title', 'Order Detail - ' . $order->order_number)

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Order #{{ $order->order_number }}</h1>
            <p>Detail pesanan pelanggan</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.digital.orders.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if($order->payment_status === 'paid')
            <a href="{{ route('admin.digital.orders.invoice', $order->id) }}" class="btn-primary" target="_blank">
                <i class="fas fa-file-pdf"></i> Download Invoice
            </a>
            @endif
        </div>
    </div>

    <div class="order-grid">
        <!-- Order Info -->
        <div class="info-card">
            <h3>Informasi Order</h3>
            <div class="info-row">
                <span class="label">Order Number:</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="info-row">
                <span class="label">Tanggal:</span>
                <span>{{ $order->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Payment Status:</span>
                @if($order->payment_status === 'paid')
                <span class="badge badge-success">Paid</span>
                @elseif($order->payment_status === 'pending')
                <span class="badge badge-warning">Pending</span>
                @else
                <span class="badge badge-danger">Failed</span>
                @endif
            </div>
            <div class="info-row">
                <span class="label">Order Status:</span>
                @if($order->order_status === 'completed')
                <span class="badge badge-success">Completed</span>
                @elseif($order->order_status === 'pending')
                <span class="badge badge-warning">Pending</span>
                @else
                <span class="badge badge-danger">Cancelled</span>
                @endif
            </div>
            @if($order->paid_at)
            <div class="info-row">
                <span class="label">Paid At:</span>
                <span>{{ $order->paid_at->format('d F Y, H:i') }}</span>
            </div>
            @endif
        </div>

        <!-- Customer Info -->
        <div class="info-card">
            <h3>Informasi Pelanggan</h3>
            <div class="info-row">
                <span class="label">Email:</span>
                <strong>{{ $order->customer_email }}</strong>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="table-card">
        <h3>Items</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($item->product_type) }}</span>
                    </td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                    <td><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Tax:</strong></td>
                    <td><strong>Rp {{ number_format($order->tax, 0, ',', '.') }}</strong></td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
                    <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Update Status Form -->
    @if($order->payment_status !== 'paid')
    <div class="info-card">
        <h3>Update Status</h3>
        <form method="POST" action="{{ route('admin.digital.orders.updateStatus', $order->id) }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Payment Status</label>
                    <select name="payment_status">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Order Status</label>
                    <select name="order_status">
                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Status
            </button>
        </form>
    </div>
    @endif
</div>

<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .order-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .info-card h3 {
        font-size: 1.2rem;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f7fafc;
    }

    .info-row .label {
        color: #718096;
        font-weight: 500;
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

    .badge-info {
        background: #bee3f8;
        color: #2c5282;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .table-card h3 {
        margin-bottom: 20px;
        color: #2d3748;
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
        font-size: 0.9rem;
    }

    .data-table tfoot td {
        padding-top: 15px;
        border-top: 2px solid #e2e8f0;
    }

    .total-row td {
        font-size: 1.1rem;
        color: #667eea;
    }

    .text-right {
        text-align: right;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2d3748;
    }

    .form-group select {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
    }
</style>
@endsection
