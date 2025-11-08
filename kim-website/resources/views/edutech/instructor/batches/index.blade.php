@extends('layouts.instructor')

@section('title', 'Kelola Batch - ' . $course->title)

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
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        margin: 0 0 5px 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-back, .btn-create {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .btn-create {
        background: white;
        color: #667eea;
        border: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,255,255,0.4);
    }

    .batches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 25px;
    }

    .batch-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .batch-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .batch-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 25px;
        border-bottom: 2px solid #e2e8f0;
    }

    .batch-code {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .batch-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .batch-period {
        color: #718096;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .batch-body {
        padding: 25px;
    }

    .batch-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-item {
        padding: 15px;
        background: #f7fafc;
        border-radius: 12px;
    }

    .info-label {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .schedule-info {
        background: #ebf8ff;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #4299e1;
    }

    .schedule-info strong {
        color: #2c5282;
        display: block;
        margin-bottom: 8px;
    }

    .schedule-details {
        color: #2d3748;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .status-upcoming { background: #bee3f8; color: #2c5282; }
    .status-ongoing { background: #c6f6d5; color: #22543d; animation: pulse 2s infinite; }
    .status-completed { background: #e2e8f0; color: #4a5568; }
    .status-cancelled { background: #fed7d7; color: #742a2a; }

    .batch-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-students {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-attendance {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
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

    .alert-error {
        background: linear-gradient(135deg, #fed7d7, #fc8181);
        color: #742a2a;
        border-left: 4px solid #e53e3e;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2><i class="fas fa-layer-group"></i> Kelola Batch / Kelas</h2>
                <p style="margin: 0; opacity: 0.9;">{{ $course->title }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('edutech.instructor.students') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('edutech.instructor.batches.create', $course->id) }}" class="btn-create">
                    <i class="fas fa-plus-circle"></i> Buat Batch Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Batches Grid -->
        @if($batches->count() > 0)
            <div class="batches-grid">
                @foreach($batches as $batch)
                <div class="batch-card">
                    <div class="batch-header">
                        <div class="batch-code">{{ $batch->batch_code }}</div>
                        <div class="batch-name">{{ $batch->batch_name }}</div>
                        <div class="batch-period">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $batch->start_date->format('d M Y') }} - {{ $batch->end_date->format('d M Y') }}
                        </div>
                    </div>
                    <div class="batch-body">
                        <span class="status-badge status-{{ $batch->status }}">
                            @if($batch->status === 'upcoming')
                                <i class="fas fa-clock"></i> Akan Datang
                            @elseif($batch->status === 'ongoing')
                                <i class="fas fa-circle"></i> Sedang Berjalan
                            @elseif($batch->status === 'completed')
                                <i class="fas fa-check"></i> Selesai
                            @else
                                <i class="fas fa-times"></i> Dibatalkan
                            @endif
                        </span>

                        <div class="batch-info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-users"></i> Siswa Terdaftar
                                </div>
                                <div class="info-value">{{ $batch->enrollments_count }}/{{ $batch->max_students }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user-check"></i> Kuota Tersisa
                                </div>
                                <div class="info-value">{{ $batch->max_students - $batch->enrollments_count }}</div>
                            </div>
                        </div>

                        @if($batch->schedule_days)
                        <div class="schedule-info">
                            <strong><i class="fas fa-clock"></i> Jadwal Kelas:</strong>
                            <div class="schedule-details">
                                📅 {{ $batch->getScheduleDaysFormatted() }}<br>
                                ⏰ {{ date('H:i', strtotime($batch->start_time)) }} - {{ date('H:i', strtotime($batch->end_time)) }} WIB
                            </div>
                        </div>
                        @endif

                        <div class="batch-actions">
                            <a href="{{ route('edutech.instructor.students.course', $course->id) }}?batch={{ $batch->id }}" class="btn-action btn-students">
                                <i class="fas fa-list"></i> Siswa
                            </a>
                            <a href="{{ route('edutech.instructor.students.attendance', [$course->id, $batch->id]) }}" class="btn-action btn-attendance">
                                <i class="fas fa-clipboard-check"></i> Presensi
                            </a>
                            <a href="{{ route('edutech.instructor.batches.edit', [$course->id, $batch->id]) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="background: white; padding: 80px; text-align: center; border-radius: 20px;">
                <div style="width: 100px; height: 100px; margin: 0 auto 25px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>Belum Ada Batch</h3>
                <p style="color: #718096; margin: 15px 0 30px;">Buat batch/kelas pertama untuk mengatur jadwal dan siswa</p>
                <a href="{{ route('edutech.instructor.batches.create', $course->id) }}" class="btn-create" style="display: inline-flex;">
                    <i class="fas fa-plus-circle"></i> Buat Batch Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection