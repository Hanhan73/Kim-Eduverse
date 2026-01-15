<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Seminar</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background-color: #4F46E5;
        color: white;
        padding: 30px;
        text-align: center;
        border-radius: 8px 8px 0 0;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
    }

    .content {
        background-color: #f9fafb;
        padding: 30px;
        border: 1px solid #e5e7eb;
    }

    .congrats {
        background-color: #10b981;
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .info-box {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        border-left: 4px solid #4F46E5;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: bold;
        color: #6b7280;
    }

    .info-value {
        color: #111827;
    }

    .footer {
        background-color: #1f2937;
        color: #9ca3af;
        padding: 20px;
        text-align: center;
        border-radius: 0 0 8px 8px;
        font-size: 14px;
    }

    .button {
        display: inline-block;
        background-color: #4F46E5;
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 6px;
        margin: 10px 5px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎓 Selamat!</h1>
        <p>Anda Telah Menyelesaikan Seminar</p>
    </div>

    <div class="content">
        <div class="congrats">
            <h2 style="margin: 0;">Seminar Berhasil Diselesaikan!</h2>
            <p style="margin: 10px 0 0 0;">Sertifikat Anda terlampir pada email ini</p>
        </div>

        <p>Dear Peserta,</p>

        <p>Selamat! Anda telah berhasil menyelesaikan seminar <strong>"{{ $seminar->title }}"</strong> dengan baik.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #4F46E5;">📋 Detail Seminar</h3>

            <div class="info-row">
                <span class="info-label">Judul Seminar:</span>
                <span class="info-value">{{ $seminar->title }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Instruktur:</span>
                <span class="info-value">{{ $seminar->instructor_name }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Tanggal Selesai:</span>
                <span class="info-value">{{ $enrollment->completed_at->format('d F Y') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Nomor Sertifikat:</span>
                <span class="info-value"><strong>{{ $enrollment->certificate_number }}</strong></span>
            </div>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #10b981;">📊 Hasil Evaluasi</h3>

            <div class="info-row">
                <span class="info-label">Nilai Pre-Test:</span>
                <span class="info-value">{{ round($enrollment->pre_test_score) }}%</span>
            </div>

            <div class="info-row">
                <span class="info-label">Nilai Post-Test:</span>
                <span class="info-value">{{ round($enrollment->post_test_score) }}%</span>
            </div>
        </div>

        <p><strong>📎 Lampiran:</strong></p>
        <ul>
            <li>Sertifikat Digital (PDF) - <em>sertifikat_{{ $enrollment->certificate_number }}.pdf</em></li>
        </ul>

        <p>Sertifikat ini dapat Anda gunakan sebagai bukti telah mengikuti dan menyelesaikan seminar dengan baik.</p>

        <p>Terima kasih atas partisipasi Anda dalam seminar ini. Kami berharap ilmu yang didapat dapat bermanfaat untuk
            pengembangan diri Anda.</p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Catatan:</strong><br>
            - Simpan sertifikat ini dengan baik<br>
            - Sertifikat ini dapat diverifikasi menggunakan nomor sertifikat<br>
            - Jika ada pertanyaan, silakan hubungi tim kami
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0 0 10px 0;"><strong>KIM Eduverse</strong></p>
        <p style="margin: 0;">© {{ date('Y') }} KIM Eduverse. All rights reserved.</p>
        <p style="margin: 10px 0 0 0; font-size: 12px;">
            Email ini dikirim secara otomatis, mohon tidak membalas email ini.
        </p>
    </div>
</body>

</html>