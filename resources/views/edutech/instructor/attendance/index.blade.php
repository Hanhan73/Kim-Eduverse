@extends('layouts.instructor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Attendance</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($batches as $batch)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $batch->name }}</h5>
                    <p class="card-text">
                        <strong>Course:</strong> {{ $batch->course->title }}<br>
                        <strong>Students:</strong> {{ $batch->students->count() }} siswa<br>
                        <strong>Schedule:</strong> {{ $batch->schedule }}
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('instructor.attendance.show', $batch->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Lihat Attendance
                        </a>
                        <a href="{{ route('instructor.attendance.create', $batch->id) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Pertemuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Belum ada batch yang tersedia.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection