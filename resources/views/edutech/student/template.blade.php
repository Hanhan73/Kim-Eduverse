<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Pelatihan</title>

    <style>
    @page {
        size: A4 landscape;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: "Times New Roman", serif;
        color: #000000;
    }

    .page {
        position: relative;
        width: 297mm;
        height: 210mm;
        padding: 0;
        box-sizing: border-box;
        page-break-after: always;
    }

    /* ===== HALAMAN 1 ===== */
    /* Header */
    .header {
        position: absolute;
        top: 15mm;
        right: 30mm;
        font-size: 14pt;
    }

    /* Title */
    .title {
        position: absolute;
        top: 50mm;
        width: 100%;
        text-align: center;
    }

    .title h1 {
        font-size: 28pt;
        font-weight: bold;
        font-style: italic;
        margin: 0;
        padding: 0;
    }

    /* Certificate Number */
    .certificate-number {
        position: absolute;
        top: 65mm;
        width: 100%;
        text-align: center;
        font-size: 12pt;
    }

    /* Content */
    .given-to {
        position: absolute;
        top: 95mm;
        width: 100%;
        text-align: center;
        font-size: 12pt;
    }

    .recipient {
        position: absolute;
        top: 105mm;
        width: 100%;
        text-align: center;
        font-size: 20pt;
        font-weight: bold;
    }

    .description {
        position: absolute;
        top: 120mm;
        left: 30mm;
        right: 30mm;
        text-align: center;
        font-size: 11pt;
        line-height: 1.5;
    }

    .course-title {
        font-weight: normal;
        text-transform: uppercase;
    }

    /* Footer Page 1 */
    .footer-page1 {
        position: absolute;
        bottom: 40mm;
        width: 100%;
        text-align: center;
    }

    .director-title {
        font-size: 11pt;
        margin-bottom: 20mm;
    }

    .director-name {
        font-weight: bold;
        font-size: 12pt;
    }

    /* ===== HALAMAN 2 ===== */
    .page2-title {
        position: absolute;
        top: 40mm;
        width: 100%;
        text-align: center;
    }

    .page2-title .main-title {
        font-size: 12pt;
        font-weight: normal;
        margin: 0 0 5mm 0;
    }

    .page2-title .course-name {
        font-size: 14pt;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
    }

    /* Table */
    .table-wrapper {
        position: absolute;
        top: 70mm;
        left: 40mm;
        right: 40mm;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11pt;
    }

    table th {
        background-color: #ffffff;
        font-weight: bold;
        padding: 8px;
        border: 1px solid #000000;
    }

    table td {
        padding: 8px;
        border: 1px solid #000000;
    }

    .col-no {
        width: 20mm;
        text-align: center;
    }

    .col-materi {
        text-align: left;
    }

    .col-jp {
        width: 30mm;
        text-align: center;
    }

    /* Footer Page 2 */
    .footer-page2 {
        position: absolute;
        bottom: 40mm;
        width: 100%;
        text-align: center;
    }

    .footer-page2 .director-title {
        font-size: 11pt;
        margin-bottom: 20mm;
    }

    .footer-page2 .director-name {
        font-weight: bold;
        font-size: 12pt;
    }
    </style>
</head>

<body>
    <!-- ==================== HALAMAN 1 ==================== -->
    <div class="page">
        <div class="header">
            KIM Eduverseeeee
        </div>

        <div class="title">
            <h1>Sertifikat Pelatihan</h1>
        </div>

        <div class="certificate-number">
            Nomor: {{ $enrollment->certificate_number }}
        </div>

        <div class="given-to">
            Diberikan kepada:
        </div>

        <div class="recipient">
            {{ strtoupper($enrollment->student->name) }}
        </div>

        <div class="description">
            <p style="margin: 0 0 6mm 0;">
                sebagai peserta dalam kegiatan Pelatihan <span
                    class="course-title">"{{ strtoupper($enrollment->course->title) }}"</span>, yang
            </p>
            <p style="margin: 0 0 6mm 0;">
                diselenggarakan oleh PT Kompetensi Mandiri Indonesia. Dilaksanakan secara <i>online</i> selama
                {{ $totalJP }}
            </p>
            <p style="margin: 0;">
                Jam Pelajaran (JP) pada tanggal {{ $enrollment->created_at->translatedFormat('d F Y') }} sampai dengan
                {{ $enrollment->certificate_issued_at->translatedFormat('d F Y') }}.
            </p>
        </div>

        <div class="footer-page1">
            <div class="director-title">Direktur,</div>
            <div class="director-name">Yosep Hernawan, S.T., M.M., IPM.</div>
        </div>
    </div>

    <!-- ==================== HALAMAN 2 ==================== -->
    <div class="page">
        <div class="page2-title">
            <div class="main-title">MATERI PELATIHAN</div>
            <div class="course-name">{{ strtoupper($enrollment->course->title) }}</div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-materi">Materi</th>
                        <th class="col-jp">JP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollment->course->modules as $i => $module)
                    <tr>
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="col-materi">{{ $module->title }}</td>
                        <td class="col-jp">6</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer-page2">
            <div class="director-title">Direktur,</div>
            <div class="director-name">Yosep Hernawan, S.T., M.M., IPM.</div>
        </div>
    </div>

</body>

</html>