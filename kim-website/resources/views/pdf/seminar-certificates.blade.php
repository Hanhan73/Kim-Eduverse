<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sertifikat - {{ $seminar->title }}</title>
    <style>
    @page {
        margin: 0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Georgia', serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
    }

    .certificate {
        background: white;
        padding: 60px;
        border: 15px solid #d4af37;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        min-height: 700px;
        position: relative;
    }

    .certificate::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 3px solid #e5c77f;
        border-radius: 10px;
    }

    .header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        z-index: 1;
    }

    .logo {
        font-size: 2.5rem;
        color: #667eea;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .subtitle {
        font-size: 1rem;
        color: #666;
        font-style: italic;
    }

    .title {
        font-size: 2.8rem;
        color: #2d3748;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin: 40px 0 20px;
        text-align: center;
    }

    .presented-to {
        text-align: center;
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 20px;
    }

    .recipient-name {
        text-align: center;
        font-size: 2.5rem;
        color: #2d3748;
        font-weight: 700;
        border-bottom: 3px solid #667eea;
        padding-bottom: 10px;
        margin: 0 auto 30px;
        max-width: 500px;
    }

    .description {
        text-align: center;
        font-size: 1.1rem;
        color: #4a5568;
        line-height: 1.8;
        margin-bottom: 30px;
    }

    .seminar-title {
        text-align: center;
        font-size: 1.5rem;
        color: #667eea;
        font-weight: 700;
        margin-bottom: 40px;
    }

    .details {
        display: table;
        width: 100%;
        margin: 40px 0;
    }

    .detail-row {
        display: table-row;
    }

    .detail-label,
    .detail-value {
        display: table-cell;
        padding: 10px;
        font-size: 1rem;
    }

    .detail-label {
        color: #666;
        width: 200px;
    }

    .detail-value {
        color: #2d3748;
        font-weight: 600;
    }

    .signatures {
        display: table;
        width: 100%;
        margin-top: 80px;
    }

    .signature {
        display: table-cell;
        text-align: center;
        width: 50%;
    }

    .signature-line {
        border-top: 2px solid #2d3748;
        width: 250px;
        margin: 0 auto 10px;
        padding-top: 10px;
    }

    .signature-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3748;
    }

    .signature-title {
        font-size: 0.9rem;
        color: #666;
    }

    .certificate-number {
        text-align: center;
        margin-top: 40px;
        font-size: 0.9rem;
        color: #999;
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 8rem;
        color: rgba(102, 126, 234, 0.05);
        font-weight: 700;
        z-index: 0;
    }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="watermark">KIM</div>

        <div class="header">
            <div class="logo">PT KIM EDUVERSE</div>
            <div class="subtitle">Psikologi • Pendidikan • Digital Learning</div>
        </div>

        <div class="title">CERTIFICATE</div>
        <div class="presented-to">This certificate is proudly presented to</div>

        <div class="recipient-name">{{ $enrollment->customer_email }}</div>

        <div class="description">
            Has successfully completed the seminar on
        </div>

        <div class="seminar-title">"{{ $seminar->title }}"</div>

        <div class="details">
            <div class="detail-row">
                <div class="detail-label">Instructor:</div>
                <div class="detail-value">{{ $seminar->instructor_name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Completion Date:</div>
                <div class="detail-value">{{ $enrollment->completed_at->format('F d, Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Pre-Test Score:</div>
                <div class="detail-value">{{ round($enrollment->pre_test_score) }}%</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Post-Test Score:</div>
                <div class="detail-value">{{ round($enrollment->post_test_score) }}%</div>
            </div>
        </div>

        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $seminar->instructor_name }}</div>
                <div class="signature-title">Instructor</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Director</div>
                <div class="signature-title">PT KIM Eduverse</div>
            </div>
        </div>

        <div class="certificate-number">
            Certificate No: {{ $enrollment->certificate_number }}
        </div>
    </div>
</body>

</html>