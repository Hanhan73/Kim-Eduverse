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
        font-family: "Times New Roman", serif;
        background: #ffffff;
    }

    .page {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 60px 80px;
        box-sizing: border-box;
    }

    /* Header */
    .brand {
        text-align: right;
        font-size: 18px;
        font-weight: bold;
        color: #1f2937;
    }

    .title {
        text-align: center;
        margin-top: 20px;
    }

    .title h1 {
        font-size: 32px;
        margin: 0;
        font-style: italic;
    }

    .cert-number {
        text-align: center;
        margin-top: 6px;
        font-size: 14px;
    }

    /* Content */
    .content {
        margin-top: 40px;
        text-align: center;
        font-size: 16px;
        line-height: 1.6;
    }

    .participant-name {
        margin: 20px 0;
        font-size: 26px;
        font-weight: bold;
    }

    .seminar-title {
        font-weight: bold;
    }

    .italic {
        font-style: italic;
    }

    /* Footer */
    .footer {
        position: absolute;
        bottom: 80px;
        width: calc(100% - 160px);
        text-align: center;
    }

    .director-title {
        margin-bottom: 60px;
    }

    .director-name {
        font-weight: bold;
        text-decoration: underline;
    }

    /* Decorative corners (optional, simple) */
    .corner {
        position: absolute;
        width: 120px;
        height: 120px;
        border: 8px solid #0f766e;
    }

    .corner.top-left {
        top: 0;
        left: 0;
        border-right: none;
        border-bottom: none;
    }

    .corner.bottom-right {
        bottom: 0;
        right: 0;
        border-left: none;
        border-top: none;
    }

    .page-2 {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 60px 80px;
        box-sizing: border-box;
        font-family: "Times New Roman", serif;
    }

    .page-2 h2 {
        text-align: center;
        font-size: 22px;
        margin-bottom: 10px;
    }

    .page-2 h3 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 30px;
        font-weight: bold;
    }

    table.materi-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.materi-table th,
    table.materi-table td {
        border: 1px solid #000;
        padding: 8px;
    }

    table.materi-table th {
        text-align: center;
        font-weight: bold;
    }

    table.materi-table td:nth-child(1),
    table.materi-table td:nth-child(3) {
        text-align: center;
        width: 60px;
    }

    .footer-2 {
        position: absolute;
        bottom: 80px;
        width: calc(100% - 160px);
        text-align: center;
    }

    .footer-2 .director-name {
        font-weight: bold;
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="page">
        <!-- Decorative Corners -->
        <div class="corner top-left"></div>
        <div class="corner bottom-right"></div>

        <!-- Header -->
        <div class="brand">
            KIM Eduvers
        </div>

        <div class="title">
            <h1>Sertifikat Seminar</h1>
            <div class="cert-number">
                Nomor: {{ $enrollment->certificate_number }}
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <p>Diberikan kepada:</p>

            <div class="participant-name">
                {{ $enrollment->participant_name }}
            </div>

            <p>
                sebagai peserta dalam kegiatan
                <span class="italic">On-Demand Seminar</span>
                <span class="seminar-title">
                    “{{ strtoupper($seminar->title) }}”
                </span>,
                yang diselenggarakan oleh
                <strong>PT Kompetensi Mandiri Indonesia</strong>.
            </p>

            <p>
                Dilaksanakan secara <span class="italic">online</span>
                selama {{ $seminar->total_jp ?? 6 }} Jam Pelajaran (JP)
                pada tanggal
                {{ $enrollment->completed_at?->format('d F Y') }}.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="director-title">Direktur,</div>

            <div class="director-name">
                Yosep Hernawan, S.T., M.M., IPM.
            </div>
        </div>
    </div>

    <div style="page-break-after: always;"></div>

    <div class="page-2">

        <h2>MATERI <i>ON-DEMAND SEMINAR</i></h2>
        <h3>{{ strtoupper($seminar->title) }}</h3>

        <table class="materi-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Materi</th>
                    <th>JP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seminar->materials as $index => $material)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $material->title }}</td>

                    @if ($index === 0)
                    <td rowspan="{{ $seminar->materials->count() }}" style="vertical-align: middle; font-weight: bold;">
                        6
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="footer-2">
            <div>Direktur,</div>
            <br><br>
            <div class="director-name">
                Yosep Hernawan, S.T., M.M., IPM
            </div>
        </div>

    </div>



</body>

</html>