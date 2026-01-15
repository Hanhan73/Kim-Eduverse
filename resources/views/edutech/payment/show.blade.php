<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - {{ $enrollment->course->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #ed8936;
            --info: #4299e1;
            --dark: #2d3748;
            --gray: #718096;
            --light: #f7fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 40px;
            color: white;
            text-align: center;
        }

        .payment-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .payment-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .payment-body {
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .course-summary {
            padding: 30px;
            background: var(--light);
            border-radius: 12px;
        }

        .course-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .course-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .course-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray);
        }

        .info-item i {
            color: var(--primary);
            width: 20px;
        }

        .price-summary {
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .price-row.total {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
        }

        .payment-info {
            background: #fff5e6;
            border-left: 4px solid var(--warning);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .payment-info p {
            color: var(--dark);
            line-height: 1.6;
        }

        .btn-pay {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--success), #38a169);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(72, 187, 120, 0.4);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-back {
            width: 100%;
            padding: 15px;
            background: white;
            color: var(--gray);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 20px;
        }

        .secure-badge i {
            color: var(--success);
        }

        .payment-logos {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .payment-logo {
            height: 30px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .payment-logo:hover {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .payment-body {
                grid-template-columns: 1fr;
            }

            .payment-header h1 {
                font-size: 1.5rem;
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <!-- Header -->
            <div class="payment-header">
                <h1><i class="fas fa-credit-card"></i> Pembayaran Course</h1>
                <p>Selesaikan pembayaran untuk mulai belajar</p>
            </div>

            <!-- Body -->
            <div class="payment-body">
                <!-- Course Summary -->
                <div class="course-summary">
                    @if($enrollment->course->thumbnail)
                        <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" 
                             alt="{{ $enrollment->course->title }}" 
                             class="course-thumbnail">
                    @else
                        <div class="course-thumbnail"></div>
                    @endif

                    <h2 class="course-title">{{ $enrollment->course->title }}</h2>

                    <div class="course-info">
                        <div class="info-item">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $enrollment->course->instructor->name }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $enrollment->course->duration_hours }} jam</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-signal"></i>
                            <span>{{ ucfirst($enrollment->course->level) }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-tag"></i>
                            <span>{{ $enrollment->course->category }}</span>
                        </div>
                    </div>

                    <div class="price-summary">
                        <div class="price-row">
                            <span>Harga Course</span>
                            <span>Rp {{ number_format($enrollment->payment_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row">
                            <span>Biaya Admin</span>
                            <span>Gratis</span>
                        </div>
                        <div class="price-row total">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($enrollment->payment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h3 class="section-title">Metode Pembayaran</h3>

                    <div class="payment-info">
                        <p><strong><i class="fas fa-info-circle"></i> Informasi Penting:</strong></p>
                        <p>Setelah klik tombol "Bayar Sekarang", Anda akan diarahkan ke halaman pembayaran yang aman dari Midtrans untuk menyelesaikan transaksi.</p>
                    </div>

                    <button type="button" id="pay-button" class="btn-pay">
                        <i class="fas fa-lock"></i>
                        <span>Bayar Sekarang</span>
                    </button>

                    <a href="{{ route('edutech.courses.detail', $enrollment->course->slug) }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Course
                    </a>

                    <div class="secure-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>Pembayaran 100% Aman & Terenkripsi</span>
                    </div>

                    <div class="payment-logos">
                        <small style="color: var(--gray); width: 100%; margin-bottom: 10px;">Metode Pembayaran yang Tersedia:</small>
                        <span style="color: var(--gray); font-size: 0.9rem;">Bank Transfer, Credit Card, GoPay, OVO, Dana, ShopeePay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <script>
        const payButton = document.getElementById('pay-button');
        const enrollmentId = {{ $enrollment->id }};

        payButton.addEventListener('click', function() {
            payButton.disabled = true;
            payButton.innerHTML = '<span class="loading"></span> Memproses...';

            // Request snap token dari server
            fetch(`/edutech/payment/${enrollmentId}/process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    // Open Midtrans Snap
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = `/edutech/payment/${enrollmentId}/success`;
                        },
                        onPending: function(result) {
                            window.location.href = `/edutech/payment/${enrollmentId}`;
                        },
                        onError: function(result) {
                            window.location.href = `/edutech/payment/${enrollmentId}/failed`;
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '<i class="fas fa-lock"></i> <span>Bayar Sekarang</span>';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-lock"></i> <span>Bayar Sekarang</span>';
            });
        });
    </script>
</body>
</html>