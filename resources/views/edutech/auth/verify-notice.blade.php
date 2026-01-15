<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - KIM Edutech</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 16px;
            padding: 50px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }

        .icon-wrapper i {
            font-size: 3rem;
            color: white;
        }

        h1 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 15px;
        }

        p {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .alert-info {
            background: #bee3f8;
            color: #2c5282;
        }

        .resend-form {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }

        .form-label {
            display: block;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .btn-resend {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-resend:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .back-link {
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            color: #764ba2;
        }

        .steps {
            background: #f7fafc;
            padding: 20px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: left;
        }

        .steps h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .steps ol {
            margin-left: 20px;
            color: #4a5568;
        }

        .steps li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="verify-container">
        <div class="icon-wrapper">
            <i class="fas fa-envelope"></i>
        </div>

        <h1>Verifikasi Email Anda</h1>
        <p>Kami telah mengirimkan link verifikasi ke email Anda. Silakan cek inbox atau folder spam.</p>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
        </div>
        @endif

        <div class="steps">
            <h3>Langkah Verifikasi:</h3>
            <ol>
                <li>Buka email Anda</li>
                <li>Cari email dari KIM Edutech</li>
                <li>Klik link verifikasi di dalam email</li>
                <li>Login dengan akun Anda</li>
            </ol>
        </div>

        <div class="resend-form">
            <p style="margin-bottom: 15px;">Tidak menerima email?</p>
            <form action="{{ route('edutech.verification.resend') }}" method="POST">
                @csrf
                <label class="form-label">Email Anda</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                <button type="submit" class="btn-resend">
                    <i class="fas fa-paper-plane"></i> Kirim Ulang Email
                </button>
            </form>
        </div>

        <div class="back-link">
            <a href="{{ route('edutech.login') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>
</body>

</html>