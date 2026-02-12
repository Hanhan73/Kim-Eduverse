<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Kadaluarsa - KIM Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .expired-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
        overflow: hidden;
    }

    .expired-header {
        background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        padding: 50px 30px;
        text-align: center;
        color: white;
    }

    .expired-icon {
        font-size: 80px;
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .expired-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .expired-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .expired-body {
        padding: 40px 30px;
    }

    .info-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #718096;
        font-size: 14px;
    }

    .info-value {
        color: #2d3748;
        font-weight: 600;
        font-size: 14px;
    }

    .message-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .message-box h3 {
        color: #856404;
        font-size: 16px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .message-box p {
        color: #856404;
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
    }

    .cta-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .btn {
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        font-size: 16px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #667eea;
        color: white;
    }

    .support-info {
        background: #f0f9ff;
        border: 2px solid #bae6fd;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-top: 25px;
    }

    .support-info i {
        color: #0284c7;
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
    }

    .support-info p {
        color: #0c4a6e;
        margin: 0;
        font-size: 14px;
    }

    .support-info strong {
        color: #0284c7;
    }
    </style>
</head>

<body>
    <div class="expired-container">
        <div class="expired-header">
            <div class="expired-icon">🔒</div>
            <h1>Akses Telah Kadaluarsa</h1>
            <p>Masa akses e-book Anda telah berakhir</p>
        </div>

        <div class="expired-body">
            <!-- Product Info -->
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">E-Book:</span>
                    <span class="info-value">{{ $access->product->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $access->order->customer_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pembelian:</span>
                    <span class="info-value">{{ $access->created_at->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kadaluarsa:</span>
                    <span class="info-value" style="color: #e53e3e;">{{ $access->expires_at->format('d F Y, H:i') }}
                        WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Dibuka:</span>
                    <span class="info-value">{{ $access->view_count }} kali</span>
                </div>
            </div>

            <!-- Message -->
            <div class="message-box">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    Apa yang Bisa Dilakukan?
                </h3>
                <p>
                    Masa akses e-book Anda telah berakhir. Jika Anda ingin melanjutkan membaca, silakan hubungi
                    customer service kami untuk perpanjangan akses atau beli ulang produk ini.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="cta-buttons">
                <a href="{{ route('digital.catalog') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i>
                    Lihat Produk Lainnya
                </a>
                <a href="mailto:support@kimeduverse.com" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i>
                    Hubungi Customer Service
                </a>
            </div>

            <!-- Support Info -->
            <div class="support-info">
                <i class="fas fa-headset"></i>
                <p>
                    <strong>Butuh Bantuan?</strong><br>
                    Email: support@kimeduverse.com | WhatsApp: 081234567890
                </p>
            </div>
        </div>
    </div>
</body>

</html>