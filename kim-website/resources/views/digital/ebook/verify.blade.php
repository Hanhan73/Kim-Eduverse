<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akses - {{ $access->product->name }}</title>
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .verify-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        max-width: 450px;
        width: 100%;
        padding: 45px 35px;
        text-align: center;
    }

    .verify-icon { font-size: 60px; margin-bottom: 15px; }

    .verify-card h1 {
        font-size: 22px;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .verify-card p {
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .product-name {
        font-weight: 700;
        color: #667eea;
    }

    .form-group {
        text-align: left;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }

    input[type="email"] {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
    }

    input[type="email"]:focus {
        border-color: #667eea;
    }

    .error-message {
        background: #fff5f5;
        border-left: 4px solid #e53e3e;
        color: #c53030;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        text-align: left;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
    }

    .hint {
        margin-top: 20px;
        font-size: 12px;
        color: #a0aec0;
    }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="verify-icon">🔐</div>
        <h1>Verifikasi Akses</h1>
        <p>
            Masukkan email yang Anda gunakan saat membeli
            <span class="product-name">{{ $access->product->name }}</span>
            untuk melanjutkan.
        </p>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('ebook.verify.submit', $token) }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Pembelian</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com"
                    value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit" class="btn-submit">Verifikasi & Lanjutkan</button>
        </form>

        <p class="hint">
            Gunakan email yang sama dengan saat checkout. Jika lupa, cek email konfirmasi pembelian Anda.
        </p>
    </div>
</body>
</html>