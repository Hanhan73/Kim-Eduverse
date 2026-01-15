@extends('layouts.instructor')

@section('title', 'Edit Batch')

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

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ed8936;
        box-shadow: 0 0 0 4px rgba(237, 137, 54, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 25px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(237, 137, 54, 0.4);
    }

    .back-btn {
        padding: 12px 24px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <div class="page-header">
            <h2><i class="fas fa-edit"></i> Edit Batch</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">{{ $course->title }}</p>
        </div>

        <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">
                    <form action="{{ route('edutech.instructor.batches.update', [$course->id, $batch->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-tag"></i> Nama Batch *</label>
                            <input type="text" name="batch_name" class="form-control" value="{{ old('batch_name', $batch->batch_name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-code"></i> Kode Batch</label>
                            <input type="text" class="form-control" value="{{ $batch->batch_code }}" readonly style="background: #f7fafc;">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-calendar"></i> Tanggal Mulai *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $batch->start_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-calendar-check"></i> Tanggal Selesai *</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $batch->end_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-clock"></i> Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $batch->start_time) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-clock"></i> Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $batch->end_time) }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-users"></i> Maksimal Siswa *</label>
                                <input type="number" name="max_students" class="form-control" value="{{ old('max_students', $batch->max_students) }}" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-toggle-on"></i> Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="upcoming" {{ $batch->status === 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                                    <option value="ongoing" {{ $batch->status === 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                                    <option value="completed" {{ $batch->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $batch->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-sticky-note"></i> Catatan</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $batch->notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Batch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection