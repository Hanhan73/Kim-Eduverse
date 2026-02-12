<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>KIM Edutech - Sedang Diperbarui</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;

        background: linear-gradient(rgba(102, 126, 234, .85), rgba(118, 75, 162, .85)),
        url('{{ asset("images/bg-office2.jpg") }}') center/cover no-repeat;
    }

    .card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 50px 40px;
        max-width: 640px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
    }

    .logo {
        width: 220px;
        margin-bottom: 80px;
    }

    h1 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 16px;
    }

    p {
        font-size: 1.1rem;
        line-height: 1.7;
        opacity: .95;
        margin-bottom: 30px;
    }

    .badge {
        display: inline-block;
        background: rgba(255, 255, 255, .2);
        padding: 8px 18px;
        border-radius: 999px;
        font-size: .9rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .btn {
        display: inline-block;
        padding: 14px 26px;
        border-radius: 50px;
        background: white;
        color: #667eea;
        font-weight: 700;
        text-decoration: none;
        transition: .25s;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 255, 255, .35);
    }

    .sub {
        font-size: .9rem;
        opacity: .85;
        margin-top: 18px;
    }
    </style>
</head>

<body>

    <div class="card">
        <img src="{{ asset('images/logo.png') }}" class="logo" alt="KIM">

        <div class="badge">Sedang Pengembangan</div>

        <h1>KIM Edutech Sementara Tidak Tersedia</h1>

        <p>
            Fitur pembelajaran <strong>KIM Edutech</strong> saat ini sedang dalam proses
            peningkatan sistem dan penyempurnaan layanan agar pengalaman belajar menjadi
            lebih baik.
        </p>

        <p>
            Selama masa pembaruan, akses pengguna untuk sementara dinonaktifkan.
            Terima kasih atas pengertiannya.
        </p>

        <a href="{{ route('home') }}" class="btn">
            ← Kembali ke Beranda
        </a>

        <div class="sub">
            Kami akan segera kembali dengan versi yang lebih optimal.
        </div>
    </div>

</body>

</html>