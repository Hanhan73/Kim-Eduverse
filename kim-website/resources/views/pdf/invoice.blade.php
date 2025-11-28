<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #2d3748;
            line-height: 1.5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        /* Header */
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }

        .company-info {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 11px;
            color: #718096;
        }

        .invoice-title {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 40%;
        }

        .invoice-title h1 {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            color: #4a5568;
        }

        /* Info Section */
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .info-box p {
            font-size: 12px;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .info-box .name {
            font-weight: bold;
            font-size: 14px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #667eea;
            color: white;
        }

        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table th:last-child {
            text-align: right;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table td:last-child {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            color: #2d3748;
        }

        .product-type {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
        }

        /* Totals */
        .totals-section {
            width: 100%;
            display: table;
        }

        .totals-spacer {
            display: table-cell;
            width: 60%;
        }

        .totals-box {
            display: table-cell;
            width: 40%;
        }

        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .totals-label {
            display: table-cell;
            padding: 8px 15px;
            color: #718096;
        }

        .totals-value {
            display: table-cell;
            padding: 8px 15px;
            text-align: right;
            color: #2d3748;
        }

        .totals-row.total {
            background: #f8f9fa;
            border-radius: 8px;
        }

        .totals-row.total .totals-label {
            font-weight: bold;
            color: #2d3748;
        }

        .totals-row.total .totals-value {
            font-weight: bold;
            font-size: 16px;
            color: #667eea;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-badge.paid {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }

        .invoice-footer p {
            color: #718096;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .thank-you {
            font-size: 14px;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Notes */
        .notes-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .notes-section h4 {
            font-size: 11px;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 10px;
        }

        .notes-section p {
            font-size: 11px;
            color: #4a5568;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">KIM Digital</div>
                <div class="company-details">
                    PT KIM Eduverse<br>
                    Jl. Example No. 123, Jakarta<br>
                    Email: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="7704020707180503371c1e1a591418591e13">[email&#160;protected]</a><br>
                    Telp: +62 812-3456-7890
                </div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-number">{{ $order->order_number }}</div>
                <div style="margin-top: 10px;">
                    <span class="status-badge {{ $order->payment_status }}">
                        {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h3>Dikirim Kepada</h3>
                <p class="name">{{ $order->customer_name }}</p>
                <p>{{ $order->customer_email }}</p>
                @if($order->customer_phone)
                <p>{{ $order->customer_phone }}</p>
                @endif
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Detail Invoice</h3>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y') }}</p>
                <p><strong>Waktu:</strong> {{ $order->created_at->format('H:i') }} WIB</p>
                @if($order->paid_at)
                <p><strong>Dibayar:</strong> {{ $order->paid_at->format('d F Y, H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Produk</th>
                    <th style="width: 15%;">Tipe</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 25%;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item->product_name }}</div>
                    </td>
                    <td>
                        <span class="product-type">{{ ucfirst($item->product_type) }}</span>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-spacer"></div>
            <div class="totals-box">
                <div class="totals-row">
                    <span class="totals-label">Subtotal</span>
                    <span class="totals-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->tax > 0)
                <div class="totals-row">
                    <span class="totals-label">Pajak</span>
                    <span class="totals-value">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="totals-row total">
                    <span class="totals-label">TOTAL</span>
                    <span class="totals-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="notes-section">
            <h4>Catatan</h4>
            <p>
                Invoice ini merupakan bukti pembelian yang sah untuk produk digital KIM. 
                Produk digital tidak dapat dikembalikan atau ditukar setelah pembelian berhasil.
                Jika ada pertanyaan, silakan hubungi customer service kami.
            </p>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="thank-you">Terima kasih atas kepercayaan Anda!</p>
            <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p