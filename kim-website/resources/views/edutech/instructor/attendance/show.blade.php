@extends('layouts.instructor')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Attendance - {{ $batch->name }}</h4>
                    <p class="mb-0 text-muted">{{ $batch->course->title }}</p>
                </div>
                <div>
                    <a href="{{ route('instructor.attendance.create', $batch->id) }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Pertemuan
                    </a>
                    <a href="{{ route('instructor.attendance.download', $batch->id) }}" class="btn btn-info">
                        <i class="fas fa-download"></i> Download Laporan Lengkap
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($meetings->isEmpty())
                <div class="alert alert-info">
                    Belum ada data attendance. Silakan tambah pertemuan baru.
                </div>
            @else
                @foreach($meetings as $meeting)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Pertemuan {{ $meeting->meeting_number }}</h5>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $meeting->meeting_date->format('d M Y') }} | 
                                    <i class="fas fa-book"></i> {{ $meeting->meeting_topic }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('instructor.attendance.edit', [$batch->id, $meeting->meeting_number]) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('instructor.attendance.download', [$batch->id, $meeting->meeting_number]) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendanceData[$meeting->meeting_number] as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->student->name }}</td>
                                        <td>
                                            @if($attendance->status == 'present')
                                                <span class="badge bg-success">Hadir</span>
                                            @elseif($attendance->status == 'late')
                                                <span class="badge bg-warning">Terlambat</span>
                                            @elseif($attendance->status == 'absent')
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                            @else
                                                <span class="badge bg-info">Izin</span>
                                            @endif
                                        </td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection