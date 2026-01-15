@extends('layouts.instructor')

@section('title', 'Edit Live Meeting')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .page-header .back-btn {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .page-header .back-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-5px);
    }

    .page-header-content h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .page-header-content p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .form-card-header {
        background: linear-gradient(135deg, #fff5f5, #fed7d7);
        padding: 25px 35px;
        border-bottom: 2px solid #fc8181;
    }

    .form-card-header h4 {
        margin: 0;
        font-size: 1.3rem;
        color: #742a2a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-body {
        padding: 35px;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: #ed8936;
    }

    .required-mark {
        color: #f56565;
        font-weight: 700;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #ed8936;
        box-shadow: 0 0 0 4px rgba(237, 137, 54, 0.1);
    }

    .form-control.is-invalid {
        border-color: #f56565;
    }

    .invalid-feedback {
        color: #f56565;
        font-size: 0.875rem;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
        padding: 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(237, 137, 54, 0.4);
    }

    .alert-box {
        background: linear-gradient(135deg, #feebc8, #fbd38d);
        border-left: 4px solid #ed8936;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .alert-box-title {
        font-weight: 600;
        color: #7c2d12;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-box-content {
        color: #2d3748;
        line-height: 1.6;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 20px;
    }

    .status-selector {
        position: relative;
    }

    .status-option {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-card-body {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <a href="{{ route('edutech.instructor.live-meetings.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="page-header-content">
                <h2><i class="fas fa-edit"></i> Edit Live Meeting</h2>
                <p>Perbarui detail meeting yang sudah dijadwalkan</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Alert Box -->
                <div class="alert-box">
                    <div class="alert-box-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Perhatian
                    </div>
                    <div class="alert-box-content">
                        Jika Anda mengubah jadwal atau link meeting, pastikan untuk memberitahu siswa yang sudah mendaftar.
                    </div>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h4>
                            <i class="fas fa-pencil-alt"></i>
                            Detail Live Meeting
                        </h4>
                    </div>
                    <div class="form-card-body">
                        <form action="{{ route('edutech.instructor.live-meetings.update', $session->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-book"></i>
                                    Pilih Kursus <span class="required-mark">*</span>
                                </label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ $session->course_id == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Judul Meeting <span class="required-mark">*</span>
                                </label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title', $session->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i>
                                    Deskripsi Meeting
                                </label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="4">{{ old('description', $session->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-link"></i>
                                    Link Google Meet <span class="required-mark">*</span>
                                </label>
                                <input type="url" name="meeting_url" class="form-control @error('meeting_url') is-invalid @enderror" 
                                       value="{{ old('meeting_url', $session->meeting_url) }}" required>
                                @error('meeting_url')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-row mb-4">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal & Waktu <span class="required-mark">*</span>
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           value="{{ old('scheduled_at', $session->scheduled_at->format('Y-m-d\TH:i')) }}" required>
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-hourglass-half"></i>
                                        Durasi (menit) <span class="required-mark">*</span>
                                    </label>
                                    <input type="number" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                           value="{{ old('duration_minutes', $session->duration_minutes) }}" min="15" max="300" required>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="status-selector">
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on"></i>
                                        Status <span class="required-mark">*</span>
                                    </label>
                                    <select name="status" class="form-select status-option @error('status') is-invalid @enderror" required>
                                        <option value="scheduled" {{ $session->status === 'scheduled' ? 'selected' : '' }}>
                                            🕐 Terjadwal
                                        </option>
                                        <option value="ongoing" {{ $session->status === 'ongoing' ? 'selected' : '' }}>
                                            🔴 Berlangsung
                                        </option>
                                        <option value="completed" {{ $session->status === 'completed' ? 'selected' : '' }}>
                                            ✅ Selesai
                                        </option>
                                        <option value="cancelled" {{ $session->status === 'cancelled' ? 'selected' : '' }}>
                                            ❌ Dibatalkan
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i>
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection