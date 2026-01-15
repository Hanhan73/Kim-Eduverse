@extends('layouts.instructor')

@section('title', 'Edit Attendance - ' . $course->title)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);
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

    .edit-form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        margin: 0 0 20px 0;
        color: #2d3748;
        font-size: 1.2rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
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
        border-color: #ed8936;
        box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.1);
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .attendance-table thead th {
        background: #f8f9fa;
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.85rem;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
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
        background: linear-gradient(135deg, #ed8936, #dd6b20);
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

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-update {
        flex: 1;
        padding: 15px 30px;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(237, 137, 54, 0.4);
    }

    .btn-cancel {
        padding: 15px 30px;
        background: #e2e8f0;
        color: #4a5568;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #cbd5e0;
        color: #2d3748;
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

    .container-fluid{
        margin-left: 300px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <a href="{{ route('edutech.instructor.students.attendance', [$course->id, $batch->id]) }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="page-header">
        <div>
            <h2><i class="fas fa-edit"></i> Edit Attendance - Pertemuan {{ $meetingNumber }}</h2>
            <p style="margin: 0; opacity: 0.9;">{{ $course->title }} - {{ $batch->batch_name }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('edutech.instructor.students.attendance.update', [$course->id, $batch->id, $meetingNumber]) }}">
        @csrf
        @method('PUT')
        
        <div class="edit-form-card">
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Informasi Pertemuan</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-book"></i> Topik/Materi</label>
                        <input type="text" name="meeting_topic" class="form-control" 
                               value="{{ $meeting->meeting_topic }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Tanggal Pertemuan</label>
                        <input type="date" name="attendance_date" class="form-control" 
                               value="{{ \Carbon\Carbon::parse($meeting->attendance_date)->format('d M Y') }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-chalkboard"></i> Jenis Kelas</label>
                        <select name="type" class="form-select" required>
                            <option value="offline" {{ $meeting->type == 'offline' ? 'selected' : '' }}>Tatap Muka</option>
                            <option value="online" {{ $meeting->type == 'online' ? 'selected' : '' }}>Online/Live</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-users"></i> Daftar Kehadiran Siswa</h3>
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
                            @foreach($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            {{ strtoupper(substr($attendance->student->name, 0, 1)) }}
                                        </div>
                                        <div class="student-details">
                                            <strong>{{ $attendance->student->name }}</strong>
                                            <small>{{ $attendance->student->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <select name="students[{{ $attendance->student_id }}][status]" 
                                            class="status-select status-{{ $attendance->status }}" 
                                            onchange="this.className='status-select status-' + this.value" required>
                                        <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>✓ Hadir</option>
                                        <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>⏰ Terlambat</option>
                                        <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>✗ Tidak Hadir</option>
                                        <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>📝 Izin</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $attendance->student_id }}][notes]" 
                                           class="notes-input" value="{{ $attendance->notes }}" placeholder="Catatan (opsional)">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Update Attendance
                </button>
                <a href="{{ route('edutech.instructor.students.attendance', [$course->id, $batch->id]) }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection