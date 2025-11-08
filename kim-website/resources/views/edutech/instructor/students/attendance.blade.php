@extends('layouts.instructor')

@section('title', 'Presensi - ' . $course->title)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(66, 153, 225, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header-left h2 {
        margin: 0 0 5px 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .page-header-left p {
        margin: 0;
        opacity: 0.9;
    }

    .back-btn {
        padding: 12px 20px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .attendance-controls {
        background: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .control-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 20px;
        align-items: end;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        display: block;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .btn-load {
        padding: 12px 30px;
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-load:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(66, 153, 225, 0.4);
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
        letter-spacing: 0.5px;
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
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .notes-input:focus {
        outline: none;
        border-color: #4299e1;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 25px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(72, 187, 120, 0.4);
    }

    .alert {
        padding: 18px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .alert-success {
        background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
        color: #22543d;
        border-left: 4px solid #38a169;
    }

    @media (max-width: 968px) {
        .control-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h2><i class="fas fa-clipboard-check"></i> Presensi</h2>
                <p>{{ $course->title }} - {{ $batch->batch_name }}</p>
            </div>
            <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Attendance Controls -->
        <div class="attendance-controls">
            <form method="GET">
                <div class="control-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-info-circle"></i> Keterangan</label>
                        <input type="text" class="form-control" placeholder="Contoh: Pertemuan 1" >
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-users"></i> Total Siswa</label>
                        <input type="text" class="form-control" value="{{ $students->count() }} siswa" readonly>
                    </div>
                    <button type="submit" class="btn-load">
                        <i class="fas fa-sync"></i> Muat
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <form method="POST" action="{{ route('edutech.instructor.students.attendance.store', [$course->id, $batch->id]) }}">
            @csrf
            <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
            
            <div class="attendance-table-container">
                <div class="table-header">
                    <h3>Daftar Presensi Siswa</h3>
                </div>
                <div class="table-responsive">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 30%;">Nama Siswa</th>
                                <th style="width: 15%;">
                                    <select class="form-select" id="type-select" name="type" required>
                                        <option value="offline">Tatap Muka</option>
                                        <option value="online">Online/Live</option>
                                    </select>
                                </th>
                                <th style="width: 20%;">Status</th>
                                <th style="width: 30%;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
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
                                    <span id="type-display-{{ $index }}" class="type-display">Tatap Muka</span>
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][status]" class="status-select status-present" 
                                            onchange="this.className='status-select status-' + this.value" required>
                                        <option value="present" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'present' ? 'selected' : '' }}>
                                            ✓ Hadir
                                        </option>
                                        <option value="absent" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'absent' ? 'selected' : '' }}>
                                            ✗ Tidak Hadir
                                        </option>
                                        <option value="late" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'late' ? 'selected' : '' }}>
                                            ⏰ Terlambat
                                        </option>
                                        <option value="excused" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'excused' ? 'selected' : '' }}>
                                            📋 Izin
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][notes]" class="notes-input" 
                                           placeholder="Catatan tambahan..." 
                                           value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->notes : '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Presensi
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the type select element
    const typeSelect = document.getElementById('type-select');
    
    if (typeSelect) {
        // Update all type displays when select changes
        typeSelect.addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text;
            
            // Update all spans with class 'type-display'
            const typeDisplays = document.querySelectorAll('.type-display');
            typeDisplays.forEach(function(display) {
                display.textContent = selectedText;
            });
            
            console.log('Type changed to:', selectedText);
        });
        
        console.log('✅ Attendance type selector loaded');
    } else {
        console.error('❌ Type select not found!');
    }
});
</script>
@endsection