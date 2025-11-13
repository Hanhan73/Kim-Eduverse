@extends('layouts.instructor')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4>Tandai Attendance - {{ $batch->name }}</h4>
            <p class="mb-0 text-muted">{{ $batch->course->title }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('instructor.attendance.store', $batch->id) }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Pertemuan Ke-</label>
                        <input type="number" name="meeting_number" class="form-control" 
                               value="{{ $nextMeeting }}" required min="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pertemuan</label>
                        <input type="date" name="meeting_date" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Topik/Materi</label>
                        <input type="text" name="meeting_topic" class="form-control" 
                               placeholder="Contoh: Pengenalan Laravel" required>
                    </div>
                </div>

                <h5 class="mb-3">Daftar Siswa ({{ $batch->students->count() }})</h5>
                
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
                            @foreach($batch->students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <select name="attendance[{{ $student->id }}]" class="form-select" required>
                                        <option value="present">Hadir</option>
                                        <option value="late">Terlambat</option>
                                        <option value="absent">Tidak Hadir</option>
                                        <option value="excused">Izin</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="notes[{{ $student->id }}]" class="form-control" placeholder="Opsional">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Attendance
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