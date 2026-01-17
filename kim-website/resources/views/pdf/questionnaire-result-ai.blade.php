<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Analisis - {{ $response->questionnaire->name }}</title>
    <style>
    @page {
        margin: 0;
        padding: 0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10pt;
        line-height: 1.5;
        color: #1a202c;
        background: #ffffff;
    }

    .page {
        padding: 20px 25px;
        page-break-after: always;
        position: relative;
    }

    .page:last-child {
        page-break-after: auto;
    }

    /* Modern Header - Compact */
    .header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        margin: -20px -25px 20px -25px;
        padding: 0;
        overflow: hidden;
        border-radius: 0 0 20px 20px;
    }

    .header-top {
        padding: 18px 25px 10px 25px;
        background: rgba(255, 255, 255, 0.1);
    }

    .header-main {
        padding: 14px 25px 18px 25px;
    }

    .company-name {
        font-size: 8.5pt;
        font-weight: 600;
        color: #1a202c;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .header-title {
        font-size: 24pt;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 6px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-subtitle {
        font-size: 11pt;
        color: #1a202c;
        font-weight: 400;
    }

    .ai-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        padding: 5px 14px;
        border-radius: 18px;
        font-size: 7.5pt;
        font-weight: 600;
        margin-top: 10px;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Info Grid - Compact */
    .info-grid {
        display: table;
        width: 100%;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 18px;
        border: 2px solid #e2e8f0;
    }

    .info-item {
        display: table-row;
    }

    .info-label {
        display: table-cell;
        width: 100px;
        padding: 4px 0;
        font-size: 8pt;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        display: table-cell;
        padding: 4px 0;
        font-size: 9pt;
        color: #1e293b;
        font-weight: 500;
    }

    /* Section Headers - Compact */
    .section-header {
        margin: 12px 0 8px 0;
        padding-bottom: 5px;
        border-bottom: 3px solid #4f46e5;
    }

    .section-title {
        font-size: 13pt;
        font-weight: 800;
        color: #1e293b;
    }

    /* Overall Summary - Compact */
    .summary-card {
        background: white;
        border: 2px solid #e0e7ff;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 14px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .summary-card p {
        color: #334155;
        font-size: 9pt;
        line-height: 1.55;
        margin-bottom: 9px;
        text-align: justify;
    }

    .summary-card p:last-child {
        margin-bottom: 0;
    }

    /* Chart - Compact */
    .chart-wrapper {
        background: white;
        border-radius: 12px;
        padding: 14px;
        margin: 14px 0;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .chart-item {
        margin-bottom: 10px;
    }

    .chart-item:last-child {
        margin-bottom: 0;
    }

    .chart-label-row {
        display: table;
        width: 100%;
        margin-bottom: 5px;
    }

    .chart-label {
        display: table-cell;
        font-size: 9pt;
        font-weight: 700;
        color: #1e293b;
    }

    .chart-score {
        display: table-cell;
        text-align: right;
        font-size: 8pt;
        font-weight: 700;
        color: #64748b;
    }

    .chart-bar-bg {
        background: #f1f5f9;
        border-radius: 8px;
        height: 22px;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .chart-bar {
        height: 100%;
        border-radius: 8px;
        position: relative;
    }

    .chart-bar-text {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 8.5pt;
        font-weight: 800;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* Dimension Cards - SUPER Compact */
    .dimension-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        border: 2px solid #e2e8f0;
        page-break-inside: auto;
    }

    .dimension-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 10px 14px;
        color: white;
    }

    .dimension-title-row {
        display: table;
        width: 100%;
    }

    .dimension-name {
        display: table-cell;
        font-size: 12pt;
        font-weight: 800;
        color: #1a202c;
        vertical-align: middle;
    }

    .dimension-score {
        display: table-cell;
        text-align: right;
        vertical-align: middle;
    }

    .score-badge {
        background: rgba(255, 255, 255, 0.25);
        padding: 4px 12px;
        border-radius: 14px;
        font-size: 8.5pt;
        font-weight: 700;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .dimension-body {
        padding: 12px;
    }

    /* Level Badge - Compact */
    .level-indicator {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 14px;
        font-size: 8pt;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .level-sangat-rendah {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 2px solid #6ee7b7;
    }

    .level-rendah {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #047857;
        border: 2px solid #6ee7b7;
    }

    .level-sedang {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 2px solid #fbbf24;
    }

    .level-tinggi {
        background: linear-gradient(135deg, #fed7d7 0%, #fca5a5 100%);
        color: #991b1b;
        border: 2px solid #f87171;
    }

    .level-sangat-tinggi {
        background: linear-gradient(135deg, #fecaca 0%, #f87171 100%);
        color: #7f1d1d;
        border: 2px solid #ef4444;
    }

    /* Content Sections - Compact */
    .content-section {
        margin-bottom: 10px;
    }

    .content-section:last-child {
        margin-bottom: 0;
    }

    .content-title {
        font-size: 10pt;
        font-weight: 800;
        color: #4f46e5;
        margin-bottom: 6px;
    }

    .content-text {
        font-size: 8.5pt;
        color: #334155;
        line-height: 1.5;
        text-align: justify;
    }

    /* Recommendations - Compact */
    .recommendations-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 10px;
        padding: 10px;
        border: 2px solid #bfdbfe;
    }

    .recommendation-item {
        display: table;
        width: 100%;
        margin-bottom: 6px;
        font-size: 8pt;
        page-break-inside: avoid;
    }

    .recommendation-item:last-child {
        margin-bottom: 0;
    }

    .rec-bullet {
        display: table-cell;
        width: 22px;
        vertical-align: top;
        font-weight: 800;
        color: #4f46e5;
        font-size: 9pt;
    }

    .rec-text {
        display: table-cell;
        vertical-align: top;
        color: #1e293b;
        line-height: 1.45;
    }

    /* Motivational Section - Compact */
    .motivation-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 3px solid #f59e0b;
        border-radius: 12px;
        padding: 16px;
        margin: 14px 0;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        page-break-inside: avoid;
    }

    .motivation-text {
        font-size: 9.5pt;
        color: #78350f;
        font-style: italic;
        line-height: 1.55;
        font-weight: 500;
    }

    .motivation-title {
        font-size: 10pt;
        font-weight: 800;
        color: #b45309;
        margin-bottom: 8px;
    }

    /* Professional Note - Compact */
    .professional-box {
        background: #fafafa;
        border-left: 4px solid #4f46e5;
        border-radius: 0 10px 10px 0;
        padding: 10px 14px;
        margin: 14px 0;
        page-break-inside: avoid;
    }

    .professional-title {
        font-size: 8pt;
        font-weight: 800;
        color: #4f46e5;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .professional-text {
        font-size: 8.5pt;
        color: #334155;
        line-height: 1.5;
    }

    /* Disclaimer - Compact */
    .disclaimer-box {
        background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%);
        border: 2px solid #fca5a5;
        border-radius: 10px;
        padding: 10px 14px;
        margin: 14px 0;
        page-break-inside: avoid;
    }

    .disclaimer-title {
        font-size: 8pt;
        font-weight: 800;
        color: #991b1b;
        margin-bottom: 4px;
    }

    .disclaimer-text {
        font-size: 7.5pt;
        color: #7f1d1d;
        line-height: 1.4;
    }

    /* Footer - Compact */
    .footer {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 2px solid #e2e8f0;
    }

    .footer-grid {
        display: table;
        width: 100%;
    }

    .footer-left {
        display: table-cell;
        width: 60%;
        font-size: 7pt;
        color: #94a3b8;
        line-height: 1.4;
    }

    .footer-right {
        display: table-cell;
        width: 40%;
        text-align: right;
        font-size: 7pt;
        color: #94a3b8;
        line-height: 1.4;
    }

    .footer-brand {
        font-weight: 700;
        color: #64748b;
    }

    /* Page Number */
    .page-number {
        position: absolute;
        bottom: 10px;
        right: 25px;
        font-size: 7pt;
        color: #94a3b8;
    }

    /* Flexible page breaks for content */
    .content-section,
    .summary-card,
    .recommendations-box {
        page-break-inside: auto;
    }

    /* Keep these elements together */
    .motivation-box,
    .professional-box,
    .disclaimer-box {
        page-break-inside: avoid;
    }

    /* Orphans/widows control */
    p,
    .content-text,
    .rec-text {
        orphans: 2;
        widows: 2;
    }

    /* Chart page specific styles */
    .chart-page .chart-wrapper {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .chart-page .section-header {
        margin-top: 30px;
    }
    </style>
</head>

<body>
    <!-- COVER PAGE -->
    <div class="page">
        <div class="header">
            <div class="header-top">
                <div class="company-name">KIM Eduverse - Digital Assessment</div>
            </div>
            <div class="header-main">
                <div class="header-title">Hasil Analisis</div>
                <div class="header-subtitle">{{ $response->questionnaire->name }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-value">{{ $response->respondent_email }}</div>
            </div>
            <div class="info-item">
                <div class="info-value">
                    {{ $response->completed_at ? $response->completed_at->format('d F Y, H:i') : now()->format('d F Y, H:i') }}
                    WIB</div>
            </div>
            <div class="info-item">
                <div class="info-value">{{ $response->order->order_number ?? '-' }}</div>
            </div>
        </div>

        @if($response->getOverallSummary())
        <div class="section-header">
            <div class="section-title">
                Ringkasan Hasil Keseluruhan
            </div>
        </div>
        <div class="summary-card">
            @foreach(explode("\n\n", $response->getOverallSummary()) as $paragraph)
            @if(trim($paragraph))
            <p>{{ trim($paragraph) }}</p>
            @endif
            @endforeach
        </div>
        @endif

        <div class="page-number">Halaman 1</div>
    </div>

    <!-- CHART PAGE -->
    @if($response->chart_data && isset($response->chart_data['bar']))
    <div class="page chart-page">
        <div class="header">
            <div class="header-top">
                <div class="company-name">KIM Eduverse - Digital Assessment</div>
            </div>
            <div class="header-main">
                <div class="header-title">Visualisasi Skor</div>
                <div class="header-subtitle">{{ $response->questionnaire->name }}</div>
            </div>
        </div>

        <div class="section-header">
            <div class="section-title">
                Visualisasi Skor per Dimensi
            </div>
        </div>
        <div class="chart-wrapper">
            @foreach($response->chart_data['bar'] as $item)
            <div class="chart-item">
                <div class="chart-label-row">
                    <div class="chart-label">{{ $item['name'] }}</div>
                    <div class="chart-score">{{ $item['score'] }} poin</div>
                </div>
                <div class="chart-bar-bg">
                    <div class="chart-bar"
                        style="width: {{ max($item['percentage'], 10) }}%; background: {{ $item['color'] }};">
                        <div class="chart-bar-text">{{ $item['percentage'] }}%</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="page-number">Halaman 2</div>
    </div>
    @endif

    <!-- DIMENSION PAGES -->
    @php
    $results = $response->ai_analysis['dimension_results'] ?? $response->result_summary ?? [];
    $pageNum = $response->chart_data && isset($response->chart_data['bar']) ? 3 : 2;
    @endphp

    @foreach($results as $dimensionCode => $result)
    <div class="page">
        <div class="dimension-card">
            <div class="dimension-header">
                <div class="dimension-title-row">
                    <div class="dimension-name">{{ $result['dimension_name'] ?? $dimensionCode }}</div>
                    <div class="dimension-score">
                        <span class="score-badge">{{ $result['score'] ?? 0 }} poin</span>
                    </div>
                </div>
            </div>

            <div class="dimension-body">
                @php
                $level = $result['interpretation']['level'] ?? 'Sedang';
                $levelClass = 'level-' . strtolower(str_replace(' ', '-', $level));
                @endphp

                <!-- Score Info Box -->
                <div
                    style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px 16px; margin-bottom: 12px; display: table; width: 100%;">
                    <div style="display: table-cell; width: 50%; vertical-align: middle;">
                        <div style="font-size: 8pt; color: #64748b; font-weight: 600; margin-bottom: 2px;">SKOR KAMU
                        </div>
                        <div style="font-size: 16pt; color: #1e293b; font-weight: 800;">{{ $result['score'] ?? 0 }}
                            <span style="font-size: 9pt; color: #94a3b8;">poin</span>
                        </div>
                    </div>
                    <div style="display: table-cell; width: 50%; vertical-align: middle; text-align: right;">
                        <div class="level-indicator {{ $levelClass }}" style="margin-bottom: 0;">
                            {{ $level }}
                        </div>
                    </div>
                </div>

                <div class="content-section">
                    <div class="content-title">
                        <span>Apa Artinya Hasil Ini Bagi Kamu</span>
                    </div>
                    <div class="content-text">
                        @if(isset($result['ai_interpretation']) && $result['ai_interpretation'])
                        {{ $result['ai_interpretation'] }}
                        @else
                        {{ $result['interpretation']['description'] ?? 'Interpretasi tidak tersedia.' }}
                        @endif
                    </div>
                </div>

                @php
                $recommendations = $result['ai_recommendations'] ?? $result['interpretation']['suggestions'] ?? [];
                @endphp

                @if(count($recommendations) > 0)
                <div class="content-section">
                    <div class="content-title">
                        <span>Rekomendasi &amp; Saran</span>
                    </div>
                    <div class="recommendations-box">
                        @foreach($recommendations as $index => $recommendation)
                        <div class="recommendation-item">
                            <div class="rec-bullet">{{ $index + 1 }}.</div>
                            <div class="rec-text">{{ $recommendation }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="page-number">Halaman {{ $pageNum++ }}</div>
    </div>
    @endforeach

    <!-- CLOSING PAGE -->
    <div class="page">
        @if($response->getMotivationalMessage())
        <div class="motivation-box">
            <div class="motivation-title">Pesan Motivasi</div>
            <div class="motivation-text">
                "{{ $response->getMotivationalMessage() }}"
            </div>
        </div>
        @endif

        @if($response->ai_analysis['professional_note'] ?? null)
        <div class="professional-box">
            <div class="professional-title">Catatan Refleksi</div>
            <div class="professional-text">
                {{ $response->ai_analysis['professional_note'] }}
            </div>
        </div>
        @endif

        <div class="disclaimer-box">
            <div class="disclaimer-title">Disclaimer</div>
            <div class="disclaimer-text">
                Hasil analisis ini bersifat informatif dan edukatif, bukan merupakan diagnosis medis atau psikologis.
                Untuk penanganan lebih lanjut atau jika Anda mengalami kesulitan yang signifikan, sangat disarankan
                untuk berkonsultasi dengan psikolog atau profesional kesehatan mental yang berkualifikasi.
            </div>
        </div>

        <div class="footer">
            <div class="footer-grid">
                <div class="footer-left">
                    <div>Dicetak pada {{ now()->format('d F Y, H:i') }} WIB</div>
                    @if($response->hasAIAnalysis())
                    <div>Analisis menggunakan teknologi AI (Claude by Anthropic)</div>
                    @endif
                    <div style="margin-top: 5px;">
                        Dokumen ini bersifat rahasia dan hanya ditujukan untuk penerima yang bersangkutan.
                    </div>
                </div>
                <div class="footer-right">
                    <div class="footer-brand">KIM Eduverse</div>
                    <div>Psikologi • Pendidikan • Digital Assessment</div>
                    <div style="margin-top: 5px;">© {{ date('Y') }} All Rights Reserved</div>
                </div>
            </div>
        </div>

        <div class="page-number">Halaman {{ $pageNum }}</div>
    </div>
</body>

</html>