<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease;
        }

        .success-icon i {
            font-size: 3.5rem;
            color: white;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
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

        .course-info {
            background: #f7fafc;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .course-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .info-text {
            color: #718096;
            line-height: 1.8;
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
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1>Pembayaran Berhasil! 🎉</h1>
        <p class="subtitle">Transaksi Anda telah berhasil diproses</p>

        <div class="course-info">
            <div class="course-title">{{ $enrollment->course->title }}</div>
            <p class="info-text">
                Selamat! Anda sekarang dapat mengakses semua materi pembelajaran di course ini. 
                Selamat belajar dan semoga sukses! 📚
            </p>
        </div>

        <div class="btn-container">
            <a href="{{ route('edutech.courses.learn', $enrollment->course->slug) }}" class="btn btn-primary">
                <i class="fas fa-play-circle"></i> Mulai Belajar
            </a>
            <a href="{{ route('edutech.student.my-courses') }}" class="btn btn-secondary">
                <i class="fas fa-book"></i> Lihat My Courses
            </a>
        </div>
    </div>
</body>
</html>