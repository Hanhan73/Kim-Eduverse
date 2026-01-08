<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Seminar Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .order-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .seminar-link {
            display: inline-block;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Akses Seminar Anda</h1>
        <p>Pembayaran Anda telah berhasil dikonfirmasi</p>
    </div>

    <div class="content">
        <div class="order-info">
            <h2>Detail Pesanan</h2>
            <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
            <p><strong>Tanggal:</strong> {{ $order->paid_at->format('d F Y H:i') }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <h3>Akses Langsung ke Seminar Anda</h3>
        <p>Klik tombol di bawah ini untuk langsung mengakses halaman seminar:</p>

        <div style="text-align: center;">
            <a href="{{ $seminarLink }}" class="seminar-link">
                <i class="fas fa-door-open"></i> Akses Sekarang
            </a>
        </div>

        <p>Link ini khusus untuk Anda dan dapat digunakan kapan saja. Simpan email ini untuk referensi di masa depan.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>

</html>