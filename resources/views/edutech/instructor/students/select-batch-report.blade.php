@extends('layouts.instructor')

@section('title', 'Pilih Batch - Laporan')

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

    .batches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .batch-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .batch-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .batch-header {
        background: linear-gradient(135deg, #fff5f5, #fed7d7);
        padding: 25px;
        border-bottom: 2px solid #fc8181;
    }

    .batch-code {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
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

    .batch-body {
        padding: 25px;
    }

    .batch-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .info-label {
        color: #718096;
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .btn-select {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-select:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(237, 137, 54, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
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

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h2><i class="fas fa-chart-bar"></i> Pilih Batch untuk Laporan</h2>
            <p style="margin: 0; opacity: 0.9;">{{ $course->title }}</p>
        </div>

        <a href="{{ route('edutech.instructor.students') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Batches Grid -->
        @if($batches->count() > 0)
            <div class="batches-grid">
                @foreach($batches as $batch)
                <div class="batch-card">
                    <div class="batch-header">
                        <div class="batch-code">{{ $batch->batch_code }}</div>
                        <div class="batch-name">{{ $batch->batch_name }}</div>
                    </div>
                    <div class="batch-body">
                        <div class="batch-info">
                            <span class="info-label"><i class="fas fa-users"></i> Jumlah Siswa:</span>
                            <span class="info-value">{{ $batch->enrollments_count }} siswa</span>
                        </div>
                        <div class="batch-info">
                            <span class="info-label"><i class="fas fa-calendar"></i> Periode:</span>
                            <span class="info-value">{{ $batch->start_date->format('d M') }} - {{ $batch->end_date->format('d M Y') }}</span>
                        </div>
                        
                        <a href="{{ route('edutech.instructor.students.attendance.report', [$course->id, $batch->id]) }}" class="btn-select">
                            <i class="fas fa-file-chart-line"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>Belum Ada Batch</h3>
                <p style="color: #718096;">Buat batch terlebih dahulu sebelum melihat laporan</p>
                <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="btn-select" style="max-width: 300px; margin: 0 auto;">
                    <i class="fas fa-plus-circle"></i>
                    Kelola Batch
                </a>
            </div>
        @endif
    </div>
</div>
@endsection