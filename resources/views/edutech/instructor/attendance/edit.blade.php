@extends('layouts.instructor')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit Attendance - Pertemuan {{ $meetingNumber }}</h4>
            <p class="mb-0 text-muted">{{ $batch->name }} - {{ $batch->course->title }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('instructor.attendance.update', [$batch->id, $meetingNumber]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pertemuan</label>
                        <input type="date" name="meeting_date" class="form-control" 
                               value="{{ $meeting->meeting_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Topik/Materi</label>
                        <input type="text" name="meeting_topic" class="form-control" 
                               value="{{ $meeting->meeting_topic }}" required>
                    </div>
                </div>

                <h5 class="mb-3">Daftar Siswa</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Status Kehadiran</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->student->name }}</td>
                                <td>{{ $attendance->student->email }}</td>
                                <td>
                                    <select name="attendance[{{ $attendance->student_id }}]" class="form-select" required>
                                        <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Hadir</option>
                                        <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Terlambat</option>
                                        <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                                        <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>Izin</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="notes[{{ $attendance->student_id }}]" 
                                           class="form-control" value="{{ $attendance->notes }}" placeholder="Opsional">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Attendance
                    </button>
                    <a href="{{ route('instructor.attendance.show', $batch->id) }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection