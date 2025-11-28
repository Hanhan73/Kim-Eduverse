<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hasil {{ $response->questionnaire->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #667eea;
        }

        .info-value {
            color: #495057;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }

        .dimension-result {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .dimension-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .dimension-subtitle {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .score-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .score-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .score-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }

        .level-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .level-rendah {
            background: #d4edda;
            color: #155724;
        }

        .level-sedang {
            background: #fff3cd;
            color: #856404;
        }

        .level-tinggi {
            background: #f8d7da;
            color: #721c24;
        }

        .description-box {
            background: #f8f9ff;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
        }

        .description-box p {
            margin: 0;
            color: #495057;
        }

        .suggestions-box {
            background: #fff;
            border: 2px solid #e9ecef;
            padding: 15px;
            border-radius: 8px;
        }

        .suggestions-title {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .suggestions-box ul {
            margin-left: 20px;
        }

        .suggestions-box li {
            margin-bottom: 10px;
            color: #495057;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
        }

        .footer strong {
            color: #667eea;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $response->questionnaire->name }}</h1>
        <p>Hasil Analisis & Interpretasi</p>
    </div>

    <!-- Participant Info -->
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $response->respondent_email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span class="info-value">{{ $response->completed_at->format('d F Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Order ID:</span>
            <span class="info-value">{{ $response->order->order_number }}</span>
        </div>
    </div>

    <!-- Introduction -->
    <div class="section">
        <div class="section-title">Pengantar</div>
        <p>{{ $response->questionnaire->description }}</p>
    </div>

    <!-- Results -->
    <div class="section">
        <div class="section-title">Hasil Analisis</div>

        @foreach($response->result_summary as $dimensionCode => $result)
        <div class="dimension-result">
            <div class="dimension-name">{{ $result['dimension_name'] }}</div>
            <div class="dimension-subtitle">
                {{ $response->questionnaire->dimensions->where('code', $dimensionCode)->first()->description ?? '' }}
            </div>

            <div class="score-display">
                <div class="score-label">Skor Anda</div>
                <div class="score-value">{{ $result['score'] }} poin</div>
            </div>

            <div class="level-badge {{ $result['interpretation']['class'] ?? 'level-sedang' }}">
                Tingkat: {{ $result['interpretation']['level'] ?? 'Sedang' }}
            </div>

            <div class="description-box">
                <p><strong>Deskripsi:</strong> {{ $result['interpretation']['description'] ?? '' }}</p>
            </div>

            @if(isset($result['interpretation']['suggestions']) && count($result['interpretation']['suggestions']) > 0)
            <div class="suggestions-box">
                <div class="suggestions-title">💡 Saran Tindakan:</div>
                <ul>
                    @foreach($result['interpretation']['suggestions'] as $suggestion)
                    <li>{{ $suggestion }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Conclusion -->
    <div class="section">
        <div class="section-title">Catatan Penting</div>
        <div class="description-box">
            <p>
                Hasil ini merupakan gambaran kondisi Anda saat ini berdasarkan jawaban yang diberikan. 
                Interpretasi ini bersifat informatif dan edukatif. Jika Anda merasa membutuhkan bantuan 
                lebih lanjut, sangat disarankan untuk berkonsultasi dengan profesional di bidang terkait.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>PT KIM Eduverse - KIM Digital</strong><br>
            Dokumen ini dibuat secara otomatis pada {{ now()->format('d F Y, H:i') }}<br>
            © {{ date('Y') }} PT KIM. All Rights Reserved.
        </p>
    </div>
</body>
</html>
