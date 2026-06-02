@php
$title = strtoupper($training->title);
$words = explode(' ', $title);
$totalWords = count($words);
$half = ceil($totalWords / 2);
$line1 = implode(' ', array_slice($words, 0, $half));
$line2 = implode(' ', array_slice($words, $half));

// Ambil materi dari seminar jika ada, fallback ke default
$seminar = $training->seminar;
$materials = $seminar ? $seminar->materials : collect([]);
$totalJP = $materials->sum('jp');
if ($totalJP == 0) {
    $totalJP = $seminar ? ceil($seminar->duration_minutes / 60) : 8;
}

// Nilai post-test
$enrollment = $participant->enrollment;
$postScore  = $enrollment ? round($enrollment->post_test_score ?? 0) : null;
@endphp
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
        font-family: "Times New Roman", serif;
        color: #222;
    }

    .page {
        position: relative;
        width: 297mm;
        height: 210mm;
        background: url('{{ public_path("images/paper-bg.jpg") }}') center / cover no-repeat;
    }

    .page:not(:last-child) {
        page-break-after: always;
    }

    .sidebar {
        position: absolute;
        top: 25mm;
        left: 20mm;
        width: 28mm;
        height: 160mm;
        background: #0B4DBA;
    }

    .sidebar-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-90deg);
        width: 150mm;
        text-align: center;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 2px;
        line-height: 1.3;
        white-space: normal;
    }

    .content {
        position: absolute;
        top: 30mm;
        left: 65mm;
        right: 20mm;
    }

    .logo {
        width: 90px;
        margin-bottom: 20px;
    }

    .title-small {
        font-size: 14px;
        margin-bottom: 12px;
    }

    .participant-name {
        font-size: 26px;
        font-weight: bold;
        color: #0B4DBA;
        margin-bottom: 6px;
    }

    .participant-nip {
        font-size: 12px;
        color: #555;
        margin-bottom: 14px;
    }

    .body-text {
        font-size: 12px;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .details {
        font-size: 11px;
        line-height: 1.8;
        margin-top: 10px;
    }

    .details b {
        display: inline-block;
        width: 130px;
    }

    .signature {
        margin-top: 30px;
    }

    .sign-name {
        font-weight: bold;
        font-size: 12px;
        color: #0B4DBA;
    }

    /* Page 2 */
    .page-2 .content {
        top: 35mm;
    }

    .page-2 h2 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 8px;
        color: #0B4DBA;
    }

    .page-2 .sub-title {
        text-align: center;
        font-size: 12px;
        margin-bottom: 20px;
        color: #444;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #000;
        padding: 7px 10px;
    }

    th {
        background: #0B4DBA;
        color: #fff;
    }

    td:nth-child(1),
    td:nth-child(3) {
        text-align: center;
        width: 50px;
    }
    </style>
</head>
<body>

{{-- ================= PAGE 1 ================= --}}
<div class="page">
    <div class="sidebar">
        <div class="sidebar-text">
            Pelatihan<br>
            Sertifikat
        </div>
    </div>

    <div class="content">
        <img src="{{ storage_path('app/private-assets/logo.png') }}" class="logo">

        <div class="title-small">Diberikan kepada</div>

        <div class="participant-name">
            {{ strtoupper($participant->name) }}
        </div>

        @if($participant->nip)
        <div class="participant-nip">NIP: {{ $participant->nip }}</div>
        @endif

        <div class="body-text">
            telah mengikuti dan menyelesaikan pelatihan<br>
            <i><b>{{ $training->title }}</b></i>
        </div>

        <div class="body-text">
            Sertifikat ini diberikan sebagai bukti keikutsertaan dan penyelesaian program pelatihan
            yang diselenggarakan oleh {{ $training->organizer ?? 'PT KIM Eduverse' }}.
        </div>

        <div class="details">
            <b>Tanggal Pelatihan :</b>
            {{ $training->training_date->translatedFormat('d F Y') }}<br>

            <b>Tempat :</b>
            {{ $training->location }}<br>

            @if($training->trainer_name)
            <b>Narasumber :</b>
            {{ $training->trainer_name }}<br>
            @endif

            @if($postScore)
            <b>Nilai Post-Test :</b>
            {{ $postScore }}%<br>
            @endif

            <b>No. Sertifikat :</b>
            {{ $participant->certificate_number }}
        </div>

        <div class="signature">
            <img src="{{ storage_path('app/private-assets/ttd.jpeg') }}" width="120"><br>
            <div class="sign-name">
                Yosep Hernawan, S.T., M.M., IPM., CBTS.
            </div>
            Direktur PT KIM Eduverse
        </div>
    </div>
</div>

{{-- ================= PAGE 2 ================= --}}
<div class="page page-2">
    <div class="sidebar">
        <div class="sidebar-text">
            Pelatihan<br>
            Sertifikat
        </div>
    </div>

    <div class="content">
        <h2>MATERI PELATIHAN</h2>
        <div class="sub-title">{{ $training->title }}</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Materi</th>
                    <th>JP</th>
                </tr>
            </thead>
            <tbody>
                @if($materials->count() > 0)
                    @foreach($materials as $i => $material)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $material->title }}</td>
                        @if($i === 0)
                        <td rowspan="{{ $materials->count() }}" style="text-align:center; vertical-align:middle; font-weight:bold; font-size:14px;">
                            {{ $totalJP }}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                @else
                    {{-- Fallback: materi default --}}
                    @php
                    $defaultMaterials = [
                        'Konsep Dasar dan Pengantar Materi',
                        'Teori, Metodologi, dan Kerangka Kerja',
                        'Teknik dan Implementasi Praktis',
                        'Studi Kasus dan Analisis',
                        'Evaluasi, Refleksi, dan Tindak Lanjut',
                    ];
                    @endphp
                    @foreach($defaultMaterials as $i => $m)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $m }}</td>
                        @if($i === 0)
                        <td rowspan="{{ count($defaultMaterials) }}" style="text-align:center; vertical-align:middle; font-weight:bold; font-size:14px;">
                            {{ $totalJP }}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div style="text-align:center; margin-top:35px;">
            <img src="{{ storage_path('app/private-assets/ttd.jpeg') }}" width="120"><br>
            <b>Yosep Hernawan, S.T., M.M., IPM., CBTS.</b><br>
            <span style="font-size:11px;">Direktur PT KIM Eduverse</span>
        </div>
    </div>
</div>

</body>
</html>