@php
use Illuminate\Support\Str;

$title = strtoupper($seminar->title);
$words = explode(' ', $title);

$totalWords = count($words);
$half = ceil($totalWords / 2);

$line1 = implode(' ', array_slice($words, 0, $half));
$line2 = implode(' ', array_slice($words, $half));
$totalJP = $seminar->materials->sum('jp');
if ($totalJP == 0) {
$totalJP = ceil($seminar->duration_minutes / 60);
}
@endphp
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
        right: 30mm;
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
        margin-bottom: 16px;
    }

    .body-text {
        font-size: 12px;
        line-height: 1.7;
        margin-bottom: 12px;
    }

    .details {
        font-size: 11px;
        line-height: 1.6;
        margin-top: 10px;
    }

    .signature {
        margin-top: 35px;
    }

    .sign-name {
        font-weight: bold;
        font-size: 12px;
        color: #0B4DBA;
    }

    .page-2 .content {
        top: 35mm;
    }

    .page-2 h2 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
    }

    th {
        background: #0B4DBA;
        color: #fff;
    }

    td:nth-child(1),
    td:nth-child(3) {
        text-align: center;
        width: 60px;
    }
    </style>
</head>

<body>

    {{-- ================= PAGE 1 ================= --}}
    <div class="page">
        <div class="sidebar">
            <div class="sidebar-text">
                On-Demand Seminar<br>
                Certificate
            </div>
        </div>

        <div class="content">
            <img src="{{ public_path('images/logo.png') }}" class="logo">

            <div class="title-small">This is to certify that</div>

            <div class="participant-name">
                {{ $enrollment->participant_name }}
            </div>

            <div class="body-text">
                has completed the on-demand seminar entitled<br>
                <i><b>{{ $seminar->title }}</b></i>
            </div>

            <div class="body-text">
                This certificate is granted upon completion of an asynchronous learning module designed to provide
                targeted insights and specialized knowledge within the selected field.
            </div>

            <div class="details">
                <b>Total Contact Hours:</b>
                {{ $seminar->materials->sum('jp') ?? ceil($seminar->duration_minutes / 60) }} Hours<br>

                <b>Certified on:</b>
                {{ $enrollment->completed_at?->format('d F Y') ?? now()->format('d F Y') }}<br>

                <b>Certificate Number:</b>
                {{ $enrollment->certificate_number }}
            </div>

            <div class="signature">
                <img src="{{ public_path('images/ttd.png') }}" width="120"><br>
                <div class="sign-name">
                    Yosep Hernawan, S.T., M.M., IPM., CBTS.
                </div>
                Director
            </div>
        </div>
    </div>

    {{-- ================= PAGE 2 ================= --}}
    <div class="page page-2">
        <div class="sidebar">
            <div class="sidebar-text">
                {{ $line1 }}<br>
                {{ $line2 }}
            </div>
        </div>

        <div class="content">
            <h2>MATERI ON-DEMAND SEMINAR</h2>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>JP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seminar->materials as $i => $material)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $material->title }}</td>

                        @if($i === 0)
                        <td rowspan="{{ $seminar->materials->count() }}" style="
                        text-align: center;
                        vertical-align: middle;
                        font-weight: bold;
                        font-size: 14px;
                    ">
                            {{ $totalJP }}
                        </td>
                        @endif
                    </tr>
                    @empty
                    @php
                    $defaultMaterials = [
                    'Pengenalan dan Konsep Dasar',
                    'Teori dan Metodologi',
                    'Teknik dan Implementasi Praktis',
                    'Studi Kasus dan Analisis',
                    'Evaluasi dan Tindak Lanjut'
                    ];
                    $totalJP = ceil($seminar->duration_minutes / 60);
                    @endphp

                    @foreach($defaultMaterials as $i => $m)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $m }}</td>

                        @if($i === 0)
                        <td rowspan="{{ count($defaultMaterials) }}" style="
                            text-align: center;
                            vertical-align: middle;
                            font-weight: bold;
                            font-size: 14px;
                        ">
                            {{ $totalJP }}
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    @endforelse
                </tbody>
            </table>

            <div style="text-align:center; margin-top:40px">
                <img src="{{ public_path('images/ttd.png') }}" width="120"><br>
                <b>Yosep Hernawan, S.T., M.M., IPM., CBTS.</b>
            </div>
        </div>
    </div>

</body>

</html>