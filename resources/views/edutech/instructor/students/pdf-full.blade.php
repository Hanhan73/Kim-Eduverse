<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Attendance Lengkap - {{ $batch->batch_name }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #ed8936;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #2d3748;
            font-weight: 700;
        }
        .header h2 {
            margin: 8px 0 0 0;
            font-size: 16px;
            color: #4a5568;
        }
        .header p {
            margin: 5px 0;
            color: #718096;
            font-size: 11px;
        }
        .info-section {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            background: #f7fafc;
            padding: 12px;
            border-radius: 6px;
        }
        .info-item {
            padding: 5px;
        }
        .info-label {
            font-weight: 600;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        th {
            background: #ed8936;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #dd6b20;
        }
        td {
            padding: 6px 4px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .student-name {
            text-align: left;
            font-weight: 600;
            color: #2d3748;
        }
        .meeting-col {
            font-size: 7px;
            min-width: 50px;
        }
        .status-icon {
            font-weight: 700;
            font-size: 11px;
        }
        .status-present { color: #48bb78; }
        .status-late { color: #ed8936; }
        .status-absent { color: #f56565; }
        .status-excused { color: #4299e1; }
        .legend {
            margin-top: 15px;
            padding: 10px;
            background: #edf2f7;
            border-radius: 6px;
            font-size: 9px;
        }
        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }
        .summary-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .summary-table {
            width: 100%;
            margin-top: 10px;
        }
        .summary-table th {
            background: #2d3748;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #718096;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ATTENDANCE LENGKAP</h1>
        <h2>{{ $course->title }}</h2>
        <p>{{ $batch->batch_name }} ({{ $batch->batch_code }})</p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Instructor:</span> {{ $course->instructor->name }}
            </div>
            <div class="info-item">
                <span class="info-label">Total Pertemuan:</span> {{ $meetings->count() }}
            </div>
            <div class="info-item">
                <span class="info-label">Total Siswa:</span> {{ count($studentsData) }}
            </div>
            <div class="info-item">
                <span class="info-label">Periode:</span>
                
                {{ \Carbon\Carbon::parse($meetings->first()->attendance_date)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($meetings->last()->attendance_date)->format('d M Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Jadwal:</span> {{ $batch->getScheduleDaysFormatted()}}            </div>
            <div class="info-item">
                <span class="info-label">Waktu:</span> 
                {{ $batch->start_time ? date('H:i', strtotime($batch->start_time)) : '-' }} - 
                {{ $batch->end_time ? date('H:i', strtotime($batch->end_time)) : '-' }} WIB
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="22%">Nama Siswa</th>
                @foreach($meetings as $meeting)
                <th class="meeting-col">
                    P{{ $meeting->meeting_number }}<br>
                    <span style="font-size: 6px;">{{ \Carbon\Carbon::parse($meeting->attendance_date)->format('d/m') }}</span>
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach($meetings as $meeting)
                <th class="meeting-col" style="font-size: 6px; padding: 3px 2px;">
                    {{ \Illuminate\Support\Str::limit($meeting->meeting_topic, 12) }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($studentsData as $index => $studentData)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="student-name">{{ $studentData['name'] }}</td>
                @foreach($meetings as $meeting)
                    @php
                        $record = $studentData['attendance_records'][$meeting->meeting_number] ?? null;
                    @endphp
                    <td>
                        @if($record)
                            @if($record->status == 'present')
                                <span class="status-icon status-present">✓</span>
                            @elseif($record->status == 'late')
                                <span class="status-icon status-late">△</span>
                            @elseif($record->status == 'absent')
                                <span class="status-icon status-absent">✗</span>
                            @else
                                <span class="status-icon status-excused">I</span>
                            @endif
                        @else
                            <span style="color: #cbd5e0;">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan:</strong>
        <span class="legend-item">
            <span class="status-icon status-present">✓</span> = Hadir
        </span>
        <span class="legend-item">
            <span class="status-icon status-late">△</span> = Terlambat
        </span>
        <span class="legend-item">
            <span class="status-icon status-absent">✗</span> = Tidak Hadir
        </span>
        <span class="legend-item">
            <span class="status-icon status-excused">I</span> = Izin
        </span>
    </div>

    <div class="summary-section">
        <h3 style="margin: 0 0 10px 0; font-size: 12px; color: #2d3748;">Ringkasan Per Pertemuan</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th width="10%">Pertemuan</th>
                    <th width="12%">Tanggal</th>
                    <th width="30%">Topik</th>
                    <th width="12%">Hadir</th>
                    <th width="12%">Terlambat</th>
                    <th width="12%">Tidak Hadir</th>
                    <th width="12%">Izin</th>
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
                    <td><strong>P{{ $meeting->meeting_number }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($meeting->attendance_date)->format('d M Y') }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $meeting->meeting_topic }}</td>
                    <td class="status-present"><strong>{{ $meetingAttendances->where('status', 'present')->count() }}</strong></td>
                    <td class="status-late"><strong>{{ $meetingAttendances->where('status', 'late')->count() }}</strong></td>
                    <td class="status-absent"><strong>{{ $meetingAttendances->where('status', 'absent')->count() }}</strong></td>
                    <td class="status-excused"><strong>{{ $meetingAttendances->where('status', 'excused')->count() }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} WIB</p>
        <p>© {{ date('Y') }} KIM Eduverse - Attendance Management System</p>
    </div>
</body>
</html>