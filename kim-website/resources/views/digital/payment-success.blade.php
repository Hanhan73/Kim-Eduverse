<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->payment_status === 'paid' ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran' }} - KIM Digital</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            color: #2d3748;
            line-height: 1.6;
        }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
            text-align: center;
        }

        .page-header a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .success-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
        }

        .success-header.pending {
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 50px;
        }

        .success-header h1 {
            margin: 0 0 10px;
            font-size: 2rem;
            font-weight: 700;
        }

        .success-header p {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .success-body {
            padding: 40px 30px;
        }

        /* Order Info */
        .order-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .order-info-row:last-child {
            border-bottom: none;
        }

        .order-info-label {
            color: #6c757d;
        }

        .order-info-value {
            font-weight: 600;
            color: #2d3748;
        }

        .order-info-value.total {
            color: #667eea;
            font-size: 1.25rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-badge.paid {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        /* Order Items */
        .order-items {
            margin-bottom: 30px;
        }

        .order-items h3 {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .order-item-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .order-item-info {
            flex: 1;
        }

        .order-item-info h4 {
            margin: 0 0 5px;
            font-size: 1rem;
            color: #2d3748;
        }

        .type-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            background: #e9ecef;
            color: #6c757d;
        }

        .type-badge.questionnaire {
            background: #e0e7ff;
            color: #4338ca;
        }

        .type-badge.ebook {
            background: #dcfce7;
            color: #166534;
        }

        .type-badge.template {
            background: #fef3c7;
            color: #92400e;
        }

        .order-item-price {
            font-weight: 600;
            color: #667eea;
            font-size: 1rem;
        }

        /* Action Sections */
        .action-section {
            background: #f0fff4;
            border: 2px solid #9ae6b4;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .action-section.download {
            background: #ebf8ff;
            border-color: #90cdf4;
        }

        .action-section.questionnaire {
            background: #fef3c7;
            border-color: #fcd34d;
        }

        .action-section h3 {
            margin: 0 0 15px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-section p {
            margin: 0 0 20px;
            color: #4a5568;
            line-height: 1.6;
        }

        /* Download List */
        .download-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .download-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .download-item-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .download-item-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .download-item-name {
            font-weight: 600;
            color: #2d3748;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102,126,234,0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(72,187,120,0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(237,137,54,0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }

        /* Info Box */
        .info-box {
            background: #f0f9ff;
            border: 2px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            gap: 15px;
            align-items: start;
            margin-top: 30px;
        }

        .info-box i {
            color: #0284c7;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .info-box strong {
            color: #0c4a6e;
        }

        /* Pending Payment Notice */
        .pending-notice {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
        }

        .pending-notice i {
            font-size: 3rem;
            color: #ffc107;
            margin-bottom: 15px;
            display: block;
        }

        .pending-notice h3 {
            margin: 0 0 10px;
            color: #856404;
        }

        .pending-notice p {
            margin: 0;
            color: #856404;
        }

        /* Steps */
        .steps-section {
            margin-bottom: 30px;
        }

        .steps-section h3 {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .step-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-content strong {
            display: block;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .step-content span {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Footer */
        .page-footer {
            text-align: center;
            padding: 30px 20px;
            color: #718096;
            font-size: 0.9rem;
        }

        .page-footer a {
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .success-container {
                margin: 20px auto;
            }

            .success-header {
                padding: 30px 20px;
            }

            .success-header h1 {
                font-size: 1.5rem;
            }

            .success-body {
                padding: 25px 20px;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .order-item-info {
                text-align: center;
            }

            .download-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .download-item-info {
                flex-direction: column;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .order-info-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('digital.catalog') }}">KIM Digital</a>
    </div>

    <div class="success-container">
        <div class="success-card">
            @if($order->payment_status === 'paid')
            <!-- Success Header -->
            <div class="success-header">
                <div class="success-icon">✓</div>
                <h1>Pembayaran Berhasil!</h1>
                <p>Terima kasih atas pembelian Anda</p>
            </div>
            @else
            <!-- Pending Header -->
            <div class="success-header pending">
                <div class="success-icon">⏳</div>
                <h1>Menunggu Pembayaran</h1>
                <p>Silakan selesaikan pembayaran Anda</p>
            </div>
            @endif

            <div class="success-body">
                @if($order->payment_status !== 'paid')
                <!-- Pending Payment Notice -->
                <div class="pending-notice">
                    <i class="fas fa-clock"></i>
                    <h3>Pembayaran Belum Dikonfirmasi</h3>
                    <p>Jika Anda sudah melakukan pembayaran, silakan refresh halaman ini atau tunggu beberapa saat.</p>
                    <a href="{{ route('digital.payment.success', $order->order_number) }}" class="btn btn-warning" style="margin-top: 15px;">
                        <i class="fas fa-sync-alt"></i> Refresh Status
                    </a>
                </div>
                @endif

                <!-- Order Info -->
                <div class="order-info">
                    <div class="order-info-row">
                        <span class="order-info-label">No. Pesanan</span>
                        <span class="order-info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="order-info-row">
                        <span class="order-info-label">Nama</span>
                        <span class="order-info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="order-info-row">
                        <span class="order-info-label">Email</span>
                        <span class="order-info-value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="order-info-row">
                        <span class="order-info-label">Status</span>
                        <span class="status-badge {{ $order->payment_status }}">
                            {{ $order->payment_status === 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}
                        </span>
                    </div>
                    <div class="order-info-row">
                        <span class="order-info-label">Total Pembayaran</span>
                        <span class="order-info-value total">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items">
                    <h3><i class="fas fa-shopping-bag"></i> Produk yang Dibeli</h3>
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="order-item-icon">
                            @if($item->product_type === 'questionnaire')
                                <i class="fas fa-clipboard-list"></i>
                            @elseif($item->product_type === 'ebook')
                                <i class="fas fa-book"></i>
                            @elseif($item->product_type === 'template')
                                <i class="fas fa-file-alt"></i>
                            @elseif($item->product_type === 'worksheet')
                                <i class="fas fa-tasks"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>
                        <div class="order-item-info">
                            <h4>{{ $item->product_name }}</h4>
                            <span class="type-badge {{ $item->product_type }}">
                                {{ ucfirst($item->product_type) }}
                            </span>
                        </div>
                        <div class="order-item-price">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($order->payment_status === 'paid')
                    {{-- ========================================
                        SECTION: DOWNLOADABLE PRODUCTS
                    ======================================== --}}
                    @if($hasDownloadable && $downloadableProducts->count() > 0)
                    <div class="action-section download">
                        <h3><i class="fas fa-download" style="color: #0284c7;"></i> Download Produk Digital</h3>
                        <p>Produk digital Anda siap didownload. Klik tombol di bawah untuk mengunduh file.</p>
                        
                        <div class="download-list">
                            @foreach($downloadableProducts as $item)
                            <div class="download-item">
                                <div class="download-item-info">
                                    <div class="download-item-icon">
                                        @if($item->product_type === 'ebook')
                                            <i class="fas fa-book"></i>
                                        @elseif($item->product_type === 'template')
                                            <i class="fas fa-file-alt"></i>
                                        @else
                                            <i class="fas fa-file-download"></i>
                                        @endif
                                    </div>
                                    <span class="download-item-name">{{ $item->product_name }}</span>
                                </div>
                                <a href="{{ route('digital.product.download', [$order->order_number, $item->product_id]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- ========================================
                        SECTION: QUESTIONNAIRE
                    ======================================== --}}
                    @if($hasQuestionnaire && $incompleteQuestionnaires->count() > 0)
                    <div class="action-section questionnaire">
                        <h3><i class="fas fa-clipboard-list" style="color: #d97706;"></i> Isi Angket</h3>
                        <p>Anda memiliki {{ $incompleteQuestionnaires->count() }} angket yang perlu diisi. Hasil analisis akan dikirim ke email Anda setelah selesai mengisi.</p>
                        
                        <a href="{{ route('digital.questionnaire.show', $order->order_number) }}" class="btn btn-warning">
                            <i class="fas fa-pencil-alt"></i> Isi Angket Sekarang
                        </a>
                    </div>
                    @endif

                    {{-- ========================================
                        SECTION: COMPLETED QUESTIONNAIRES
                    ======================================== --}}
                    @if($hasQuestionnaire && $order->responses->where('is_completed', true)->count() > 0)
                    <div class="action-section" style="background: #f0fff4; border-color: #9ae6b4;">
                        <h3><i class="fas fa-check-circle" style="color: #22543d;"></i> Angket Selesai</h3>
                        <p>Hasil analisis telah dikirim ke email Anda.</p>
                        
                        @foreach($order->responses->where('is_completed', true) as $response)
                        <div class="download-item" style="margin-top: 10px;">
                            <div class="download-item-info">
                                <div class="download-item-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <span class="download-item-name">{{ $response->questionnaire->name ?? 'Hasil Angket' }}</span>
                            </div>
                            @if($response->result_pdf_path)
                            <a href="{{ route('digital.questionnaire.download', $response->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Download Hasil
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- ========================================
                        SECTION: NEXT STEPS (if no special action needed)
                    ======================================== --}}
                    @if(!$hasQuestionnaire && !$hasDownloadable)
                    <div class="steps-section">
                        <h3><i class="fas fa-list-ol"></i> Langkah Selanjutnya</h3>
                        <ul class="steps-list">
                            <li class="step-item">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <strong>Cek Email</strong>
                                    <span>Kami telah mengirim email konfirmasi ke {{ $order->customer_email }}</span>
                                </div>
                            </li>
                            <li class="step-item">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <strong>Butuh Bantuan?</strong>
                                    <span>Hubungi customer service kami jika ada pertanyaan</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endif
                @endif

                <!-- CTA Buttons -->
                <div class="cta-buttons">
                    @if($order->payment_status === 'paid')
                    <a href="{{ route('digital.invoice.download', $order->order_number) }}" class="btn btn-secondary">
                        <i class="fas fa-file-invoice"></i> Download Invoice
                    </a>
                    @endif
                    <a href="{{ route('digital.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Belanja Lagi
                    </a>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email Konfirmasi Dikirim</strong><br>
                        Kami telah mengirim email konfirmasi ke <strong>{{ $order->customer_email }}</strong>. 
                        Jika tidak menemukan email, cek folder spam/junk.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="page-footer">
        <p>&copy; {{ date('Y') }} PT KIM Eduverse. All Rights Reserved.</p>
        <p>
            <a href="{{ route('digital.catalog') }}">Katalog Produk</a> · 
            <a href="/cdn-cgi/l/email-protection#a6d5d3d6d6c9d4d2e6cdcfcb88c5c988cfc2">Hubungi Kami</a>
