@extends('layouts.instructor')

@section('title', 'Jadwalkan Live Meeting')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 25px 35px;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-card-header h4 {
        margin: 0;
        font-size: 1.3rem;
        color: #2d3748;
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
        color: #667eea;
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
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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

    .form-text {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-text a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .form-text a:hover {
        text-decoration: underline;
    }

    .input-group-text {
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        border-right: none;
        color: #718096;
        border-radius: 12px 0 0 12px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .info-box {
        background: linear-gradient(135deg, #ebf8ff, #bee3f8);
        border-left: 4px solid #4299e1;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .info-box-title {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-box-content {
        color: #2d3748;
        line-height: 1.6;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
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
                <h2><i class="fas fa-video"></i> Jadwalkan Live Meeting</h2>
                <p>Buat jadwal meeting baru untuk kursus Anda</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Info Box -->
                <div class="info-box">
                    <div class="info-box-title">
                        <i class="fas fa-lightbulb"></i>
                        Tips untuk Live Meeting yang Efektif
                    </div>
                    <div class="info-box-content">
                        Pastikan Anda membuat link Google Meet terlebih dahulu, jadwalkan meeting setidaknya 1 hari sebelumnya, dan kirimkan notifikasi kepada siswa.
                    </div>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h4>
                            <i class="fas fa-edit"></i>
                            Detail Live Meeting
                        </h4>
                    </div>
                    <div class="form-card-body">
                        <form action="{{ route('edutech.instructor.live-meetings.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-book"></i>
                                    Pilih Kursus <span class="required-mark">*</span>
                                </label>
                                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kursus --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                       value="{{ old('title') }}" placeholder="Contoh: Live Session - Introduction to Laravel" required>
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
                                          rows="4" placeholder="Jelaskan topik yang akan dibahas dalam meeting ini...">{{ old('description') }}</textarea>
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
                                       value="{{ old('meeting_url') }}" placeholder="https://meet.google.com/xxx-yyyy-zzz" required>
                                @error('meeting_url')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Buat meeting di <a href="https://meet.google.com" target="_blank">Google Meet</a> lalu copy link-nya di sini
                                </small>
                            </div>

                            <div class="form-row mb-4">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        Tanggal & Waktu <span class="required-mark">*</span>
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           value="{{ old('scheduled_at') }}" required>
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
                                           value="{{ old('duration_minutes', 60) }}" min="15" max="300" required>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i>
                                Jadwalkan Meeting
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection