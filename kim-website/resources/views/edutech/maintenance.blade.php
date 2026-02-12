<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>KIM Edutech - Sedang Diperbarui</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            /* WARNA BARU — BIRU TEAL */
            background:
                linear-gradient(rgba(0, 102, 153, .88), rgba(0, 150, 136, .88)),
                url('{{ asset("images/bg-office2.jpg") }}') center/cover no-repeat;
        }

        .card {
            background: rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(12px);
            border-radius: 22px;
            padding: 50px 45px;
            max-width: 640px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .25);

            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            width: 200px;
            margin-bottom: 25px;
            display: block;
        }

        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, .25);
            padding: 10px 20px;
            border-radius: 999px;
            font-size: .95rem;
            font-weight: 600;
            margin-bottom: 22px;
        }

        h1 {
            font-size: 2.1rem;
            font-weight: 800;
            margin-bottom: 16px;
        }

        p {
            font-size: 1.08rem;
            line-height: 1.7;
            opacity: .95;
            margin-bottom: 24px;
        }

        .btn {
            display: inline-block;
            padding: 14px 26px;
            border-radius: 50px;
            background: white;
            color: #006699;
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
            opacity: .9;
            margin-top: 18px;
        }
    </style>
</head>

<body>

<div class="card">

    <img src="{{ asset('images/logo.png') }}" class="logo" alt="KIM">

    <div class="badge">
        🚧 Sedang Pengembangan
    </div>

    <h1>KIM Edutech Sementara Tidak Tersedia</h1>

    <p>
        Fitur pembelajaran <strong>KIM Edutech</strong> sedang dalam proses
        peningkatan sistem dan penyempurnaan layanan agar pengalaman belajar
        menjadi lebih baik.
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
