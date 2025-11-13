<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        .info-section {
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .info-row {
            margin: 8px 0;
        }
        .info-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background-color: #48bb78;
            color: white;
        }
        .badge-warning {
            background-color: #ed8936;
            color: white;
        }
        .badge-danger {
            background-color: #f56565;
            color: white;
        }
        .badge-info {
            background-color: #4299e1;
            color: white;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background: #edf2f7;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 25px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11px;
        }
        .signature {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ATTENDANCE</h1>
        <h2>{{ $batch->course->title }}</h2>
        <p style="margin: 5px 0;">{{ $batch->name }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Pertemuan Ke</span>: {{ $meeting->meeting_number }}
        </div>
        <div class="info-row">
            <span class="info-label">Topik/Materi</span>: {{ $meeting->meeting_topic }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal</span>: {{ $meeting->meeting_date->format('d F Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Instructor</span>: {{ $batch->instructor->name }}
        </div>
        <div class="info-row">
            <span class="info-label">Total Siswa</span>: {{ $attendances->count() }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Siswa</th>
                <th width="25%">Email</th>
                <th width="15%">Status</th>
                <th width="25%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $attendance->student->name }}</td>
                <td>{{ $attendance->student->email }}</td>
                <td>
                    @if($attendance->status == 'present')
                        <span class="badge badge-success">Hadir</span>
                    @elseif($attendance->status == 'late')
                        <span class="badge badge-warning">Terlambat</span>
                    @elseif($attendance->status == 'absent')
                        <span class="badge badge-danger">Tidak Hadir</span>
                    @else
                        <span class="badge badge-info">Izin</span>
                    @endif
                </td>
                <td>{{ $attendance->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <span style="color: #48bb78;">● Hadir:</span> {{ $attendances->where('status', 'present')->count() }}
        </div>
        <div class="summary-item">
            <span style="color: #ed8936;">● Terlambat:</span> {{ $attendances->where('status', 'late')->count() }}
        </div>
        <div class="summary-item">
            <span style="color: #f56565;">● Tidak Hadir:</span> {{ $attendances->where('status', 'absent')->count() }}
        </div>
        <div class="summary-item">
            <span style="color: #4299e1;">● Izin:</span> {{ $attendances->where('status', 'excused')->count() }}
        </div>
    </div>

    <div class="signature">
        <p>Bandung, {{ now()->format('d F Y') }}</p>
        <p>Instructor,</p>
        <br><br><br>
        <p><strong>{{ $batch->instructor->name }}</strong></p>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>© {{ date('Y') }} KIM Eduverse - Attendance Management System</p>
    </div>
</body>
</html>