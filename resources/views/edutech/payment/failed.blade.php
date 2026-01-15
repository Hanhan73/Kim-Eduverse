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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            {{-- Alert jika sebelumnya gagal --}}
            @if(isset($failedPayment) && $failedPayment)
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex">
                    <div class="me-2">⚠️</div>
                    <div>
                        <strong>Pembayaran Sebelumnya Gagal</strong>
                        <p class="mb-0 small mt-1">
                            Transaksi dengan ID <code>{{ $failedPayment->transaction_id }}</code> tidak berhasil. 
                            Silakan coba lagi dengan metode pembayaran yang sama atau pilih metode lain.
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Pembayaran Kursus</h4>

                    <!-- Detail Kursus -->
                    <div class="border rounded p-3 mb-4">
                        <div class="d-flex">
                            @if($enrollment->course->thumbnail)
                            <img src="{{ Storage::url($enrollment->course->thumbnail) }}" 
                                 alt="{{ $enrollment->course->title }}"
                                 class="rounded me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $enrollment->course->title }}</h6>
                                <p class="text-muted small mb-2">{{ $enrollment->course->instructor->name }}</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2">{{ $enrollment->course->level }}</span>
                                    <small class="text-muted">{{ $enrollment->course->duration }} jam</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pembayaran -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Harga Kursus</span>
                            <span class="fw-bold">Rp {{ number_format($enrollment->payment_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Admin</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total Pembayaran</span>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($enrollment->payment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Transaction ID -->
                    <div class="alert alert-light mb-4">
                        <small class="text-muted d-block">ID Transaksi</small>
                        <code>{{ $payment->transaction_id }}</code>
                    </div>

                    <!-- Tombol Bayar -->
                    <div class="d-grid gap-2">
                        <button id="pay-button" class="btn btn-primary btn-lg">
                            <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                            </svg>
                            Bayar Sekarang
                        </button>
                        <a href="{{ route('edutech.courses.show', $enrollment->course->slug) }}" 
                           class="btn btn-outline-secondary">
                            Batalkan
                        </a>
                    </div>

                    <!-- Info Keamanan -->
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            🔒 Pembayaran aman dengan Midtrans
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        const button = this;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        fetch('{{ route("edutech.payment.process", $enrollment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("edutech.payment.success", $enrollment->id) }}';
                    },
                    onPending: function(result) {
                        window.location.href = '{{ route("edutech.payment.show", $enrollment->id) }}';
                    },
                    onError: function(result) {
                        window.location.href = '{{ route("edutech.payment.failed", $enrollment->id) }}';
                    },
                    onClose: function() {
                        button.disabled = false;
                        button.innerHTML = '<svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/><path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/></svg>Bayar Sekarang';
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            button.disabled = false;
            button.innerHTML = '<svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/><path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/></svg>Bayar Sekarang';
        });
    });
</script>
</body>
</html>