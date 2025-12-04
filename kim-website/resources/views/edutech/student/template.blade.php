<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan</title>
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
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .certificate {
        background: #fff;
        border: 10px double #667eea;
        padding: 40px 60px;
        box-sizing: border-box;
        position: relative;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
    }

    .certificate-inner {
        border: 2px solid #d4af37;
        padding: 40px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }

    .company-name {
        font-size: 16px;
        font-weight: bold;
        color: #1e293b;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .title {
        font-size: 46px;
        color: #1F2937;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 8px;
        text-shadow: 0 2px 3px rgba(0, 0, 0, 0.15);
    }

    .subtitle {
        font-size: 18px;
        color: #6B7280;
        margin-bottom: 30px;
        font-style: italic;
    }

    .name {
        font-size: 40px;
        color: #667eea;
        font-weight: bold;
        border-bottom: 3px solid #667eea;
        display: inline-block;
        padding: 5px 40px 10px;
        margin: 10px 0 25px 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .completion-text {
        font-size: 18px;
        color: #4B5563;
        margin-bottom: 10px;
    }

    .course-name {
        font-size: 30px;
        color: #667eea;
        font-weight: bold;
        margin: 20px 0 30px 0;
    }

    .details {
        display: inline-block;
        margin-bottom: 25px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #374151;
        margin-bottom: 6px;
    }

    .detail-label {
        font-weight: bold;
        color: #6B7280;
        min-width: 130px;
    }

    .signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding: 0 60px;
    }

    .signature {
        text-align: center;
        width: 250px;
    }

    .signature-line {
        border-top: 2px solid #1F2937;
        width: 200px;
        margin: 0 auto 10px auto;
    }

    .signature-name {
        font-size: 16px;
        font-weight: bold;
        color: #1F2937;
    }

    .signature-title {
        font-size: 12px;
        color: #6B7280;
        font-style: italic;
    }

    .certificate-number {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #9CA3AF;
        font-family: 'Courier New', monospace;
    }

    .seal {
        position: absolute;
        top: 50%;
        right: 70px;
        transform: translateY(-50%);
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid #6D28D9;
        color: #6D28D9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        text-align: center;
        font-size: 14px;
        line-height: 1.2;
        box-shadow: 0 0 10px rgba(109, 40, 217, 0.3);
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 90px;
        color: rgba(102, 126, 234, 0.2);
        font-weight: bold;
        letter-spacing: 5px;
    }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="certificate-inner">
            <div class="watermark">SERTIFIKAT</div>

            <div class="company-name">PT KIM</div>

            <div class="title">Sertifikat Kelulusan</div>
            <div class="subtitle">Dengan ini menyatakan bahwa</div>

            <div class="name">{{ $enrollment->participant_name ?? $enrollment->customer_email }}</div>

            <div class="completion-text">telah berhasil menyelesaikan kursus</div>

            <div class="course-name">{{ $seminar->title }}</div>

            <div class="details">
                <div class="detail-row">
                    <div class="detail-label">Tanggal Kelulusan:</div>
                    <div class="detail-value">{{ $enrollment->completed_at->format('d F Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Kategori Kursus:</div>
                    <div class="detail-value">Seminar</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Instruktur:</div>
                    <div class="detail-value">{{ $seminar->instructor_name }}</div>
                </div>
            </div>

            <div class="signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $seminar->instructor_name }}</div>
                    <div class="signature-title">Instruktur Kursus</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">Direktur</div>
                    <div class="signature-title">PT KIM</div>
                </div>
            </div>

            <div class="seal">SERTIFIKAT</div>

            <div class="certificate-number">
                Nomor Sertifikat: {{ $enrollment->certificate_number }}
            </div>
        </div>
    </div>
</body>

</html>