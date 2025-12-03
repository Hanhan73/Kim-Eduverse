<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f4f5f7;
        margin: 0;
        padding: 0;
    }

    .email-container {
        max-width: 600px;
        margin: 30px auto;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 30px;
        text-align: center;
        color: white;
    }

    .header h1 {
        margin: 0 0 10px;
        font-size: 2rem;
    }

    .header p {
        margin: 0;
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .content {
        padding: 40px 30px;
    }

    .congratulations {
        text-align: center;
        margin-bottom: 30px;
    }

    .congratulations .icon {
        font-size: 4rem;
        margin-bottom: 15px;
    }

    .congratulations h2 {
        color: #2d3748;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .congratulations p {
        color: #718096;
        font-size: 1rem;
        line-height: 1.6;
    }

    .seminar-info {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 10px;
        margin: 30px 0;
    }

    .seminar-info h3 {
        margin: 0 0 15px;
        color: #2d3748;
        font-size: 1.3rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #718096;
        font-weight: 600;
    }

    .info-value {
        color: #2d3748;
        font-weight: 700;
    }

    .certificate-box {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8edff 100%);
        border: 2px solid #c7d2fe;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        margin: 30px 0;
    }

    .certificate-box h4 {
        margin: 0 0 10px;
        color: #5a67d8;
        font-size: 1.1rem;
    }

    .certificate-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin: 10px 0;
    }

    .btn {
        display: inline-block;
        padding: 15px 35px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        margin-top: 15px;
        transition: all 0.3s;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .attachments {
        margin: 30px 0;
    }

    .attachments h4 {
        color: #2d3748;
        margin-bottom: 15px;
    }

    .attachment-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .attachment-icon {
        width: 40px;
        height: 40px;
        background: #667eea;
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .attachment-name {
        flex: 1;
        font-weight: 600;
        color: #2d3748;
    }

    .footer {
        background: #f8f9fa;
        padding: 30px;
        text-align: center;
        color: #718096;
        font-size: 0.9rem;
    }

    .footer p {
        margin: 5px 0;
    }

    .social-links {
        margin: 20px 0;
    }

    .social-links a {
        display: inline-block;
        margin: 0 10px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Selamat!</h1>
            <p>Anda Telah Menyelesaikan Seminar</p>
        </div>

        <div class="content">
            <div class="congratulations">
                <div class="icon">🏆</div>
                <h2>Seminar Berhasil Diselesaikan!</h2>
                <p>
                    Selamat! Anda telah berhasil menyelesaikan seminar
                    <strong>{{ $seminar->title }}</strong> dengan baik.
                </p>
            </div>

            <div class="seminar-info">
                <h3>Detail Seminar</h3>
                <div class="info-row">
                    <span class="info-label">Judul Seminar</span>
                    <span class="info-value">{{ $seminar->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Instruktur</span>
                    <span class="info-value">{{ $seminar->instructor_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Selesai</span>
                    <span class="info-value">{{ $enrollment->completed_at->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pre-Test Score</span>
                    <span class="info-value">{{ round($enrollment->pre_test_score) }}%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Post-Test Score</span>
                    <span class="info-value">{{ round($enrollment->post_test_score) }}%</span>
                </div>
            </div>

            <div class="certificate-box">
                <h4>Nomor Sertifikat Anda</h4>
                <div class="certificate-number">{{ $enrollment->certificate_number }}</div>
                <p style="margin: 10px 0 0; color: #5a67d8;">
                    Sertifikat ini dapat diverifikasi di website kami
                </p>
            </div>

            <div class="attachments">
                <h4>📎 File Terlampir:</h4>
                <div class="attachment-item">
                    <div class="attachment-icon">📜</div>
                    <div class="attachment-name">Sertifikat_{{ $enrollment->certificate_number }}.pdf</div>
                </div>
                @if($seminar->material_pdf_path)
                <div class="attachment-item">
                    <div class="attachment-icon">📚</div>
                    <div class="attachment-name">Materi_{{ str_replace(' ', '_', $seminar->title) }}.pdf</div>
                </div>
                @endif
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <p style="color: #718096; margin-bottom: 15px;">
                    Ingin mengikuti seminar lainnya?
                </p>
                <a href="{{ route('digital.catalog', ['category' => 'seminar']) }}" class="btn">
                    Lihat Seminar Lainnya
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>PT KIM Eduverse</strong></p>
            <p>Psikologi • Pendidikan • Digital Learning</p>
            <div class="social-links">
                <a href="#">Website</a> |
                <a href="#">Instagram</a> |
                <a href="#">Email</a>
            </div>
            <p style="margin-top: 20px; font-size: 0.85rem;">
                Email ini dikirim otomatis, mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>

</html>