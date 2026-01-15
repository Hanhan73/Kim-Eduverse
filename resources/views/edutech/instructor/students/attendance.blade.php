@extends('layouts.instructor')

@section('title', 'Attendance - ' . $course->title)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .container-fluid{
        margin-left: 300px;
    }

    .page-header h2 {
        margin: 0 0 5px 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .back-btn {
        padding: 12px 20px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .meetings-history {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .meetings-history h3 {
        margin: 0 0 20px 0;
        font-size: 1.3rem;
        color: #2d3748;
    }

    .meeting-card {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border-left: 4px solid #667eea;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .meeting-info h4 {
        margin: 0 0 5px 0;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .meeting-info p {
        margin: 0;
        color: #718096;
        font-size: 0.9rem;
    }

    .meeting-actions {
        display: flex;
        gap: 10px;
    }

    .btn-edit, .btn-download {
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #ed8936;
        color: white;
    }

    .btn-edit:hover {
        background: #dd6b20;
        color: white;
        transform: translateY(-2px);
    }

    .btn-download {
        background: #4299e1;
        color: white;
    }

    .btn-download:hover {
        background: #3182ce;
        color: white;
        transform: translateY(-2px);
    }

    .attendance-controls {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .control-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-submit {
        padding: 12px 30px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
    }

    .attendance-table-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .table-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 20px 30px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #2d3748;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table thead th {
        background: #f8f9fa;
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .attendance-table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .attendance-table tbody tr:hover {
        background: #f8f9fa;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .student-details strong {
        display: block;
        color: #2d3748;
        font-size: 1.05rem;
    }

    .student-details small {
        color: #718096;
    }

    .status-select {
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .status-select:focus {
        outline: none;
        border-color: #4299e1;
    }

    .status-present { background: #c6f6d5; color: #22543d; }
    .status-absent { background: #fed7d7; color: #742a2a; }
    .status-late { background: #feebc8; color: #7c2d12; }
    .status-excused { background: #bee3f8; color: #2c5282; }

    .notes-input {
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        width: 100%;
        font-size: 0.9rem;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #48bb78;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #f56565;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #718096;
    }

    .type-display {
        padding: 6px 12px;
        background: #edf2f7;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali ke Batches
    </a>

    <div class="page-header">
        <div>
            <h2><i class="fas fa-clipboard-check"></i> Manajemen Attendance</h2>
            <p style="margin: 0; opacity: 0.9;">{{ $course->title }} - {{ $batch->batch_name }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- History Pertemuan -->
    @if($meetings->count() > 0)
    <div class="meetings-history">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3><i class="fas fa-history"></i> Riwayat Pertemuan</h3>
            <a href="{{ route('edutech.instructor.students.attendance.download', [$course->id, $batch->id]) }}" class="btn-download">
                <i class="fas fa-download"></i> Download Laporan Lengkap
            </a>
        </div>
        
        @foreach($meetings as $meeting)
        <div class="meeting-card">
            <div class="meeting-info">
                <h4>Pertemuan {{ $meeting->meeting_number }} - {{ $meeting->meeting_topic }}</h4>
                <p>
                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($meeting->attendance_date)->format('d M Y') }} |
                    <i class="fas fa-{{ $meeting->type == 'offline' ? 'chalkboard-teacher' : 'video' }}"></i> {{ $meeting->type == 'offline' ? 'Tatap Muka' : 'Online' }}
                </p>
            </div>
            <div class="meeting-actions">
                <a href="{{ route('edutech.instructor.students.attendance.edit', [$course->id, $batch->id, $meeting->meeting_number]) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('edutech.instructor.students.attendance.download', [$course->id, $batch->id, $meeting->meeting_number]) }}" class="btn-download">
                    <i class="fas fa-download"></i> PDF
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Form Attendance Baru -->
    <form method="POST" action="{{ route('edutech.instructor.students.attendance.store', [$course->id, $batch->id]) }}">
        @csrf
        
        <div class="attendance-controls">
            <h3 style="margin: 0 0 20px 0; color: #2d3748;">
                <i class="fas fa-plus-circle"></i> Tambah Pertemuan Baru
            </h3>
            <div class="control-row">
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> Pertemuan Ke-</label>
                    <input type="number" name="meeting_number" class="form-control" 
                           value="{{ $nextMeeting }}" min="1" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-book"></i> Topik/Materi</label>
                    <input type="text" name="meeting_topic" class="form-control" 
                           placeholder="Contoh: Pengenalan Laravel MVC" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Tanggal</label>
                    <input type="date" name="attendance_date" class="form-control" 
                           value="{{ $selectedDate }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chalkboard"></i> Jenis Kelas</label>
                    <select name="type" class="form-select" id="type-select" required>
                        <option value="offline">Tatap Muka</option>
                        <option value="online">Online/Live</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="attendance-table-container">
            <div class="table-header">
                <h3>Daftar Presensi Siswa ({{ $students->count() }})</h3>
            </div>
            <div class="table-responsive">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 35%;">Nama Siswa</th>
                            <th style="width: 20%;">Status Kehadiran</th>
                            <th style="width: 40%;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div class="student-details">
                                        <strong>{{ $student->name }}</strong>
                                        <small>{{ $student->email }}</small>
                                    </div>
                                </div>
                                <input type="hidden" name="students[{{ $index }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td>
                                <select name="students[{{ $index }}][status]" 
                                        class="status-select status-present" 
                                        onchange="this.className='status-select status-' + this.value" required>
                                    <option value="present">✓ Hadir</option>
                                    <option value="late">⏰ Terlambat</option>
                                    <option value="absent">✗ Tidak Hadir</option>
                                    <option value="excused">📝 Izin</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="students[{{ $index }}][notes]" 
                                       class="notes-input" placeholder="Catatan (opsional)">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p>Belum ada siswa di batch ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->count() > 0)
        <div style="margin-top: 20px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Attendance Pertemuan {{ $nextMeeting }}
            </button>
        </div>
        @endif
    </form>
</div>
@endsection