<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Seminar</title>
    <style>
    @page {
        size: A4 landscape;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        background: #f9fafb;
        font-family: 'Times New Roman', serif;
    }

    .certificate {
        background: #fff;
        border: 10px double #667eea;
        padding: 30px 50px;
        box-sizing: border-box;
        position: relative;
        margin: 15mm auto;
    }

    .certificate-inner {
        border: 2px solid #d4af37;
        padding: 25px;
        text-align: center;
        position: relative;
    }

    .logo {
        font-size: 42px;
        color: #667eea;
        margin-bottom: 8px;
    }

    .company-name {
        font-size: 16px;
        font-weight: bold;
        color: #1e293b;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .title {
        font-size: 40px;
        color: #1F2937;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 8px;
    }

    .subtitle {
        font-size: 14px;
        color: #6B7280;
        margin-bottom: 20px;
        font-style: italic;
    }

    .presented-text {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .name {
        font-size: 32px;
        color: #667eea;
        font-weight: bold;
        border-bottom: 3px solid #667eea;
        display: inline-block;
        padding: 5px 30px 8px;
        margin: 8px 0 20px;
    }

    .completion-text {
        font-size: 14px;
        color: #4B5563;
        margin-bottom: 8px;
    }

    .course-name {
        font-size: 18px;
        color: #1e293b;
        font-weight: bold;
        font-style: italic;
        margin: 15px 40px;
    }

    .details {
        display: inline-block;
        margin-bottom: 20px;
        text-align: left;
    }

    .detail-row {
        font-size: 12px;
        color: #374151;
        margin-bottom: 5px;
        padding: 3px 0;
    }

    .detail-label {
        font-weight: bold;
        color: #6B7280;
        display: inline-block;
        width: 130px;
    }

    .detail-value {
        display: inline-block;
        color: #1e293b;
        font-weight: 600;
    }

    .signatures {
        margin-top: 30px;
        padding: 0 40px;
    }

    .signature-table {
        width: 100%;
        border-collapse: collapse;
    }

    .signature-table td {
        text-align: center;
        vertical-align: top;
        width: 50%;
        padding: 0 20px;
    }

    .signature-line {
        border-top: 2px solid #1F2937;
        width: 180px;
        margin: 0 auto 8px;
    }

    .signature-name {
        font-size: 14px;
        font-weight: bold;
        color: #1F2937;
        margin-bottom: 3px;
    }

    .signature-title {
        font-size: 11px;
        color: #6B7280;
        font-style: italic;
    }

    .certificate-number {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: #9CA3AF;
        font-family: 'Courier New', monospace;
    }

    .seal {
        position: absolute;
        top: 50%;
        right: 40px;
        transform: translateY(-50%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #667eea;
        color: #667eea;
        font-weight: bold;
        text-align: center;
        font-size: 11px;
        padding-top: 42px;
        box-sizing: border-box;
        line-height: 1.2;
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 90px;
        color: rgba(102, 126, 234, 0.12);
        font-weight: bold;
        letter-spacing: 8px;
        z-index: 0;
    }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="certificate-inner">
            <div class="watermark">CERTIFIED</div>

            <div class="company-name">PT KIM</div>

            <div class="title">SERTIFIKAT</div>
            <div class="subtitle">Bukti Keikutsertaan dan Kelulusan</div>

            <div class="presented-text">Diberikan Kepada</div>
            <div class="name">{{ $enrollment->participant_name ?? $enrollment->customer_email }}</div>

            <div class="completion-text">Telah berhasil menyelesaikan seminar</div>

            <div class="course-name">"{{ $seminar->title }}"</div>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Tanggal Selesai:</span>
                    <span class="detail-value">{{ $enrollment->completed_at->format('d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Instruktur:</span>
                    <span class="detail-value">{{ $seminar->instructor_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Skor Pre-Test:</span>
                    <span class="detail-value">{{ round($enrollment->pre_test_score) }}%</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Skor Post-Test:</span>
                    <span class="detail-value">{{ round($enrollment->post_test_score) }}%</span>
                </div>
            </div>

            <div class="signatures">
                <table class="signature-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="signature-line"></div>
                            <div class="signature-name">{{ $seminar->instructor_name }}</div>
                            <div class="signature-title">Instruktur Seminar</div>
                        </td>
                        <td>
                            <div class="signature-line"></div>
                            <div class="signature-name">Direktur</div>
                            <div class="signature-title">PT KIM</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="seal">VERIFIED<br>CERTIFICATE</div>

            <div class="certificate-number">
                Nomor Sertifikat: {{ $enrollment->certificate_number }}
            </div>
        </div>
    </div>
</body>

</html>