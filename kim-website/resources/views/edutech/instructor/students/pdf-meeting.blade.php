<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Attendance - Pertemuan {{ $meeting->meeting_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #ed8936;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2d3748;
            font-weight: 700;
        }
        .header h2 {
            margin: 10px 0 5px 0;
            font-size: 18px;
            color: #4a5568;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0;
            color: #718096;
            font-size: 13px;
        }
        .info-section {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #ed8936;
        }
        .info-row {
            display: flex;
            margin: 8px 0;
        }
        .info-label {
            width: 180px;
            font-weight: 600;
            color: #2d3748;
        }
        .info-value {
            color: #4a5568;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #ed8936;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 12px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-present {
            background: #c6f6d5;
            color: #22543d;
        }
        .badge-late {
            background: #feebc8;
            color: #7c2d12;
        }
        .badge-absent {
            background: #fed7d7;
            color: #742a2a;
        }
        .badge-excused {
            background: #bee3f8;
            color: #2c5282;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #edf2f7;
            border-radius: 8px;
        }
        .summary h3 {
            margin: 0 0 15px 0;
            color: #2d3748;
            font-size: 14px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .summary-item {
            text-align: center;
            padding: 12px;
            background: white;
            border-radius: 6px;
        }
        .summary-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .summary-label {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }
        .signature-section {
            margin-top: 50px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-line {
            border-top: 1px solid #2d3748;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ATTENDANCE</h1>
        <h2>{{ $course->title }}</h2>
        <p>{{ $batch->batch_name }} ({{ $batch->batch_code }})</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Pertemuan Ke</span>
            <span class="info-value">: {{ $meeting->meeting_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Topik/Materi</span>
            <span class="info-value">: {{ $meeting->meeting_topic }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pertemuan</span>
            <span class="info-value">: {{ \Carbon\Carbon::parse($meeting->attendance_date)->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jenis Kelas</span>
            <span class="info-value">: {{ $meeting->type == 'offline' ? 'Tatap Muka' : 'Online/Live Session' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Instructor</span>
            <span class="info-value">: {{ $course->instructor->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Siswa</span>
            <span class="info-value">: {{ $attendances->count() }} siswa</span>
        </div>
    </div>

    <h3 style="color: #2d3748; margin: 25px 0 15px 0;">Daftar Kehadiran Siswa</h3>

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
                <td><strong>{{ $attendance->student->name }}</strong></td>
                <td>{{ $attendance->student->email }}</td>
                <td>
                    @if($attendance->status == 'present')
                        <span class="status-badge badge-present">✓ Hadir</span>
                    @elseif($attendance->status == 'late')
                        <span class="status-badge badge-late">⏰ Terlambat</span>
                    @elseif($attendance->status == 'absent')
                        <span class="status-badge badge-absent">✗ Tidak Hadir</span>
                    @else
                        <span class="status-badge badge-excused">📝 Izin</span>
                    @endif
                </td>
                <td>{{ $attendance->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Kehadiran</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number" style="color: #48bb78;">{{ $attendances->where('status', 'present')->count() }}</div>
                <div class="summary-label">Hadir</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #ed8936;">{{ $attendances->where('status', 'late')->count() }}</div>
                <div class="summary-label">Terlambat</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #f56565;">{{ $attendances->where('status', 'absent')->count() }}</div>
                <div class="summary-label">Tidak Hadir</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #4299e1;">{{ $attendances->where('status', 'excused')->count() }}</div>
                <div class="summary-label">Izin</div>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin: 0;">Bandung, {{ now()->format('d F Y') }}</p>
            <p style="margin: 5px 0;">Instructor,</p>
            <div class="signature-line">
                <strong>{{ $course->instructor->name }}</strong>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} WIB</p>
        <p>© {{ date('Y') }} KIM Eduverse - Attendance Management System</p>
    </div>
</body>
</html>