<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px 0;
        }
        .invoice-info .label {
            font-weight: bold;
            width: 150px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals table {
            margin-left: auto;
            width: 300px;
        }
        .totals td {
            padding: 8px 0;
        }
        .totals .total-row {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #667eea;
            color: #667eea;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-paid {
            background: #c6f6d5;
            color: #22543d;
        }
        .status-pending {
            background: #feebc8;
            color: #7c2d12;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>KIM Digital - Platform Produk Digital Pendidikan</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td class="label">Invoice Number:</td>
                <td><strong>{{ $order->order_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Invoice Date:</td>
                <td>{{ $order->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Payment Status:</td>
                <td>
                    <span class="status-badge status-{{ $order->payment_status === 'paid' ? 'paid' : 'pending' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </td>
            </tr>
            @if($order->paid_at)
            <tr>
                <td class="label">Paid Date:</td>
                <td>{{ $order->paid_at->format('d F Y, H:i') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="customer-info">
        <h3 style="color: #667eea; margin-bottom: 10px;">Customer Information</h3>
        <table>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $order->customer_email }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Type</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td><strong>{{ $item->product_name }}</strong></td>
                <td>{{ ucfirst($item->product_type) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tax (1%):</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($order->tax, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td style="text-align: right;"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>KIM Digital</strong></p>
        <p>Platform Produk Digital Pendidikan</p>
        <p>Email: support@kimdigital.com | Website: www.kimdigital.com</p>
        <p style="margin-top: 15px;">Terima kasih atas pembelian Anda!</p>
    </div>
</body>
</html>
