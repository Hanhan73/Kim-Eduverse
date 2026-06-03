@php
$materials = $training->certificateMaterials;
$totalJP = $training->total_jp ?? 0;
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
    }

    .content {
        position: absolute;
        top: 30mm;
        left: 65mm;
        right: 20mm;
    }

    .logo {
        width: 90px;
        margin-bottom: 18px;
    }

    .title-small {
        font-size: 14px;
        margin-bottom: 10px;
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
        margin-bottom: 12px;
    }

    .body-text {
        font-size: 12px;
        line-height: 1.7;
        margin-bottom: 10px;
    }

    .details {
        font-size: 11px;
        line-height: 1.9;
        margin-top: 10px;
    }

    .details b {
        display: inline-block;
        width: 130px;
    }

    .signature {
        margin-top: 28px;
    }

    .sign-name {
        font-weight: bold;
        font-size: 12px;
        color: #0B4DBA;
    }

    .page-2 .content {
        top: 28mm;
    }

    .page-2 h2 {
        text-align: center;
        font-size: 17px;
        margin-bottom: 4px;
        color: #0B4DBA;
    }

    .page-2 .sub-title {
        text-align: center;
        font-size: 11px;
        margin-bottom: 16px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px 10px;
    }

    th {
        background: #0B4DBA;
        color: #fff;
        text-align: center;
    }

    td:first-child {
        text-align: center;
        width: 40px;
    }

    td:last-child {
        text-align: center;
        width: 80px;
    }

    .jp-total-row td {
        font-weight: bold;
        background: #f0f4ff;
    }
    </style>
</head>

<body>

    <div class="page">
        <div class="sidebar">
            <div class="sidebar-text">Pelatihan<br>Sertifikat</div>
        </div>
        <div class="content">
            <img src="{{ storage_path('app/private-assets/logo.png') }}" class="logo">
            <div class="title-small">Diberikan kepada</div>
            <div class="participant-name">{{ strtoupper($participant->name) }}</div>
            @if($participant->nip)<div class="participant-nip">NIP: {{ $participant->nip }}</div>@endif
            <div class="body-text">
                telah mengikuti dan menyelesaikan pelatihan<br>
                <i><b>{{ $training->title }}</b></i>
            </div>
            <div class="body-text">
                Sertifikat ini diberikan sebagai bukti keikutsertaan dan penyelesaian program pelatihan
                yang diselenggarakan oleh {{ $training->organizer ?? 'PT KIM Eduverse' }}.
            </div>
            <div class="details">
                <b>Tanggal Pelatihan :</b> {{ $training->training_date->translatedFormat('d F Y') }}<br>
                <b>Tempat :</b> {{ $training->location }}<br>
                @if($training->trainer_name)<b>Narasumber :</b> {{ $training->trainer_name }}<br>@endif
                <b>Total JP :</b> {{ $totalJP }} Jam Pelajaran<br>
                @if($participant->post_test_score)<b>Nilai Post-Test :</b>
                {{ round($participant->post_test_score) }}%<br>@endif
                <b>No. Sertifikat :</b> {{ $participant->certificate_number }}
            </div>
            <div class="signature">
                <img src="{{ storage_path('app/private-assets/ttd.jpeg') }}" width="110"><br>
                <div class="sign-name">Yosep Hernawan, S.T., M.M., IPM., CBTS.</div>
                Direktur PT KIM Eduverse
            </div>
        </div>
    </div>

    <div class="page page-2">
        <div class="sidebar">
            <div class="sidebar-text">Pelatihan<br>Sertifikat</div>
        </div>
        <div class="content">
            <h2>MATERI PELATIHAN</h2>
            <div class="sub-title">{{ $training->title }}</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th style="width:70px;">Total JP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $i => $m)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="text-align:left;">{{ $m->title }}</td>
                        @if($i === 0)
                        {{-- JP hanya di baris pertama, span ke semua baris materi --}}
                        <td rowspan="{{ $materials->count() }}"
                            style="text-align:center; vertical-align:middle; font-weight:bold; font-size:14px; color:#0B4DBA;">
                            {{ $totalJP }} JP
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; color:#6b7280;">-</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="text-align:center; margin-top:30px;">
                <img src="{{ storage_path('app/private-assets/ttd.jpeg') }}" width="110"><br>
                <b>Yosep Hernawan, S.T., M.M., IPM., CBTS.</b><br>
                <span style="font-size:10px;">Direktur PT KIM Eduverse</span>
            </div>
        </div>
    </div>

</body>

</html>