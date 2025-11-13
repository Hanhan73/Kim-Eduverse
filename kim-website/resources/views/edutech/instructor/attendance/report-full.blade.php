<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Attendance Lengkap</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }
        .info-section {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #333;
        }
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .student-name {
            text-align: left;
            font-weight: 500;
        }
        .status-present {
            color: #48bb78;
            font-weight: bold;
        }
        .status-late {
            color: #ed8936;
            font-weight: bold;
        }
        .status-absent {
            color: #f56565;
            font-weight: bold;
        }
        .status-excused {
            color: #4299e1;
            font-weight: bold;
        }
        .summary-section {
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .summary-table {
            margin-top: 10px;
        }
        .summary-table th {
            background-color: #2d3748;
        }
        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: center;
        }
        .meeting-header {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            font-size: 8px;
        }
        .stats-cell {
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ATTENDANCE LENGKAP</h1>
        <h2>{{ $batch->course->title }}</h2>
        <p style="margin: 3px 0;">{{ $batch->name }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Instructor</span>: {{ $batch->instructor->name }}
        </div>
        <div class="info-row">
            <span class="info-label">Schedule</span>: {{ $batch->schedule }}
        </div>
        <div class="info-row">
            <span class="info-label">Total Pertemuan</span>: {{ $meetings->count() }}
        </div>
        <div class="info-row">
            <span class="info-label">Total Siswa</span>: {{ count($attendanceData) }}
        </div>
        <div class="info-row">
            <span class="info-label">Periode</span>: 
            {{ $meetings->first()->meeting_date->format('d M Y') }} - 
            {{ $meetings->last()->meeting_date->format('d M Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="20%">Nama Siswa</th>
                @foreach($meetings as $meeting)
                <th width="{{ 77 / $meetings->count() }}%">
                    <div class="meeting-header">
                        P{{ $meeting->meeting_number }} - {{ $meeting->meeting_date->format('d/m') }}
                    </div>
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach($meetings as $meeting)
                <th style="font-size: 7px; padding: 4px 2px;">
                    {{ \Illuminate\Support\Str::limit($meeting->meeting_topic, 15) }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $studentId => $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="student-name">{{ $data['student']->name }}</td>
                @foreach($meetings as $meeting)
                    @php
                        $record = $data['records'][$meeting->meeting_number] ?? null;
                    @endphp
                    <td>
                        @if($record)
                            @if($record->status == 'present')
                                <span class="status-present">✓</span>
                            @elseif($record->status == 'late')
                                <span class="status-late">△</span>
                            @elseif($record->status == 'absent')
                                <span class="status-absent">✗</span>
                            @else
                                <span class="status-excused">I</span>
                            @endif
                        @else
                            <span style="color: #ccc;">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <h3 style="margin-bottom: 10px;">Ringkasan Kehadiran Per Pertemuan</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th width="15%">Pertemuan</th>
                    <th width="15%">Tanggal</th>
                    <th width="25%">Topik</th>
                    <th width="11.25%">Hadir</th>
                    <th width="11.25%">Terlambat</th>
                    <th width="11.25%">Tidak Hadir</th>
                    <th width="11.25%">Izin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                @php
                    $meetingAttendances = \App\Models\Attendance::where('batch_id', $batch->id)
                        ->where('meeting_number', $meeting->meeting_number)
                        ->get();
                @endphp
                <tr>
                    <td class="stats-cell">Pertemuan {{ $meeting->meeting_number }}</td>
                    <td>{{ $meeting->meeting_date->format('d M Y') }}</td>
                    <td style="text-align: left;">{{ $meeting->meeting_topic }}</td>
                    <td class="status-present stats-cell">{{ $meetingAttendances->where('status', 'present')->count() }}</td>
                    <td class="status-late stats-cell">{{ $meetingAttendances->where('status', 'late')->count() }}</td>
                    <td class="status-absent stats-cell">{{ $meetingAttendances->where('status', 'absent')->count() }}</td>
                    <td class="status-excused stats-cell">{{ $meetingAttendances->where('status', 'excused')->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px; padding: 10px; background: #edf2f7; border-radius: 5px;">
        <strong>Keterangan:</strong><br>
        <span class="status-present">✓</span> = Hadir &nbsp;|&nbsp;
        <span class="status-late">△</span> = Terlambat &nbsp;|&nbsp;
        <span class="status-absent">✗</span> = Tidak Hadir &nbsp;|&nbsp;
        <span class="status-excused">I</span> = Izin
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>© {{ date('Y') }} KIM Eduverse - Attendance Management System</p>
    </div>
</body>
</html>