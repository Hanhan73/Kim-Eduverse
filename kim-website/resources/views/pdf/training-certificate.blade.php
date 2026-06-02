<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Pelatihan</title>
    <style>
    @page { size: A4 landscape; margin: 0; }
    body { margin: 0; padding: 0; font-family: 'Times New Roman', serif; }

    .certificate {
        background: #fff;
        border: 10px double #667eea;
        padding: 30px 50px;
        box-sizing: border-box;
        position: relative;
        margin: 15mm auto;
        width: 267mm;
    }

    .certificate-inner {
        border: 2px solid #d4af37;
        padding: 25px;
        text-align: center;
        position: relative;
    }

    .logo { font-size: 42px; color: #667eea; margin-bottom: 8px; }
    .company-name {
        font-size: 16px; font-weight: bold; color: #1e293b;
        letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5px;
    }

    .cert-type {
        font-size: 13px; color: #6b7280; font-style: italic;
        letter-spacing: 1px; margin-bottom: 10px;
    }

    .title {
        font-size: 36px; color: #1F2937; font-weight: bold;
        text-transform: uppercase; letter-spacing: 3px; margin-bottom: 4px;
    }

    .subtitle {
        font-size: 13px; color: #6B7280; margin-bottom: 18px; font-style: italic;
    }

    .presented-text {
        font-size: 12px; color: #64748b; text-transform: uppercase;
        letter-spacing: 1px; margin-bottom: 8px;
    }

    .name {
        font-size: 30px; color: #667eea; font-weight: bold;
        border-bottom: 3px solid #667eea;
        display: inline-block; padding: 5px 30px 8px; margin: 8px 0 16px;
    }

    .completion-text { font-size: 13px; color: #4B5563; margin-bottom: 6px; }

    .course-name {
        font-size: 16px; color: #1e293b; font-weight: bold;
        font-style: italic; margin: 12px 40px; line-height: 1.5;
    }

    .details { display: inline-block; margin-bottom: 18px; text-align: left; }
    .detail-row { font-size: 11px; color: #374151; margin-bottom: 4px; }
    .detail-label { font-weight: bold; color: #6B7280; display: inline-block; width: 120px; }
    .detail-value { display: inline-block; color: #1e293b; font-weight: 600; }

    .signatures { margin-top: 24px; padding: 0 40px; }
    .signature-table { width: 100%; border-collapse: collapse; }
    .signature-table td { text-align: center; vertical-align: top; width: 50%; padding: 0 20px; }
    .signature-line { border-top: 2px solid #1F2937; width: 180px; margin: 0 auto 6px; }
    .signature-name { font-size: 13px; font-weight: bold; color: #1F2937; margin-bottom: 2px; }
    .signature-title { font-size: 11px; color: #6B7280; }

    .cert-number {
        position: absolute; bottom: 12px; right: 20px;
        font-size: 10px; color: #9ca3af;
    }

    .watermark-left { position: absolute; left: -8px; top: 50%; transform: translateY(-50%) rotate(-90deg); font-size: 10px; color: #d4af37; letter-spacing: 3px; }
    </style>
</head>
<body>
<div class="certificate">
    <div class="certificate-inner">
        <div class="watermark-left">KIM EDUVERSE ★ KIM EDUVERSE</div>

        <div class="logo">🎓</div>
        <div class="company-name">PT KIM Eduverse</div>
        <div class="cert-type">Menyatakan bahwa</div>

        <div class="title">Sertifikat</div>
        <div class="subtitle">Pelatihan Pendidik dan Tenaga Kependidikan</div>

        <div class="presented-text">Diberikan kepada</div>

        <div class="name">{{ strtoupper($participant->name) }}</div>

        @if($participant->nip)
        <div style="font-size:12px; color:#6b7280; margin-bottom:10px;">NIP: {{ $participant->nip }}</div>
        @endif

        <div class="completion-text">Telah mengikuti dan menyelesaikan</div>

        <div class="course-name">"{{ $training->title }}"</div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Tanggal</span>
                <span class="detail-value">: {{ $training->training_date->translatedFormat('d F Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tempat</span>
                <span class="detail-value">: {{ $training->location }}</span>
            </div>
            @if($training->organizer)
            <div class="detail-row">
                <span class="detail-label">Penyelenggara</span>
                <span class="detail-value">: {{ $training->organizer }}</span>
            </div>
            @endif
            @if($participant->enrollment && $participant->enrollment->post_test_score)
            <div class="detail-row">
                <span class="detail-label">Nilai Post-Test</span>
                <span class="detail-value">: {{ round($participant->enrollment->post_test_score) }}%</span>
            </div>
            @endif
        </div>

        <div class="signatures">
            <table class="signature-table">
                <tr>
                    <td>
                        <div style="height:50px;"></div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $training->trainer_name ?? 'Narasumber' }}</div>
                        <div class="signature-title">Narasumber/Fasilitator</div>
                    </td>
                    <td>
                        <div style="height:50px;"></div>
                        <div class="signature-line"></div>
                        <div class="signature-name">Direktur PT KIM Eduverse</div>
                        <div class="signature-title">Penyelenggara</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cert-number">No: {{ $participant->certificate_number }}</div>
    </div>
</div>
</body>
</html>