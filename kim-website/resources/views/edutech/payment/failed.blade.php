<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Gagal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f56565 0%, #c53030 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .failed-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .failed-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f56565, #c53030);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: shake 0.5s ease;
        }

        .failed-icon i {
            font-size: 3.5rem;
            color: white;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        h1 {
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 15px;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #718096;
            margin-bottom: 30px;
        }

        .error-info {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }

        .error-info h3 {
            color: #c53030;
            margin-bottom: 10px;
        }

        .error-info ul {
            list-style: none;
            padding: 0;
        }

        .error-info li {
            padding: 5px 0;
            color: #718096;
        }

        .error-info li i {
            color: #f56565;
            margin-right: 10px;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 18px 40px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #718096;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            border-color: #667eea;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="failed-card">
        <div class="failed-icon">
            <i class="fas fa-times"></i>
        </div>

        <h1>Pembayaran Gagal</h1>
        <p class="subtitle">Transaksi tidak dapat diselesaikan</p>

        <div class="error-info">
            <h3><i class="fas fa-exclamation-triangle"></i> Kemungkinan Penyebab:</h3>
            <ul>
                <li><i class="fas fa-dot-circle"></i> Pembayaran dibatalkan oleh pengguna</li>
                <li><i class="fas fa-dot-circle"></i> Saldo atau limit kartu tidak mencukupi</li>
                <li><i class="fas fa-dot-circle"></i> Terjadi kesalahan pada sistem pembayaran</li>
                <li><i class="fas fa-dot-circle"></i> Waktu pembayaran telah habis</li>
            </ul>
        </div>

        <div class="btn-container">
            <a href="{{ route('edutech.payment.show', $enrollment->id) }}" class="btn btn-primary">
                <i class="fas fa-redo"></i> Coba Bayar Lagi
            </a>
            <a href="{{ route('edutech.courses.detail', $enrollment->course->slug) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Course
            </a>
        </div>

        <p style="color: #a0aec0; font-size: 0.9rem; margin-top: 30px;">
            Jika masalah berlanjut, hubungi customer support kami
        </p>
    </div>
</body>
</html>