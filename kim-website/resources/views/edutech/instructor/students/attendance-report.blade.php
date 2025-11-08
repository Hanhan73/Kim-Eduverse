@extends('layouts.instructor')

@section('title', 'Laporan Presensi - ' . $course->title)

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
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        margin: 0 0 5px 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .back-btn {
        padding: 12px 20px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        color: white;
        text-decoration: none;
        font-weight: 600;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .report-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .report-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 20px 30px;
        border-bottom: 2px solid #e2e8f0;
    }

    .report-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #2d3748;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table thead th {
        background: #f8f9fa;
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .report-table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .report-table tbody tr:hover {
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
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .student-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 1.05rem;
    }

    .attendance-stats {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .stat-badge {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .stat-badge.present {
        background: #c6f6d5;
        color: #22543d;
    }

    .stat-badge.absent {
        background: #fed7d7;
        color: #742a2a;
    }

    .stat-badge.late {
        background: #feebc8;
        color: #7c2d12;
    }

    .percentage-bar {
        width: 100%;
        height: 30px;
        background: #e2e8f0;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }

    .percentage-fill {
        height: 100%;
        background: linear-gradient(90deg, #48bb78, #38a169);
        transition: width 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .percentage-fill.low {
        background: linear-gradient(90deg, #f56565, #e53e3e);
    }

    .percentage-fill.medium {
        background: linear-gradient(90deg, #ed8936, #dd6b20);
    }

    .btn-export {
        padding: 12px 24px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2><i class="fas fa-chart-bar"></i> Laporan Presensi</h2>
                <p style="margin: 0; opacity: 0.9;">{{ $course->title }} - {{ $batch->batch_name }}</p>
            </div>
            <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Export Button -->
        <button class="btn-export" onclick="window.print()">
            <i class="fas fa-file-export"></i>
            Export ke PDF
        </button>

        <!-- Report Card -->
        <div class="report-card">
            <div class="report-header">
                <h3>Rekap Kehadiran Siswa</h3>
            </div>
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 30%;">Nama Siswa</th>
                            <th style="width: 15%;">Total Sesi</th>
                            <th style="width: 25%;">Statistik</th>
                            <th style="width: 25%;">Persentase Kehadiran</th>
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
                                    <div class="student-name">{{ $student->name }}</div>
                                </div>
                            </td>
                            <td>
                                <strong style="font-size: 1.3rem; color: #2d3748;">{{ $student->total_sessions }}</strong>
                                <span style="color: #718096;"> sesi</span>
                            </td>
                            <td>
                                <div class="attendance-stats">
                                    <span class="stat-badge present">
                                        <i class="fas fa-check"></i>
                                        {{ $student->present_count }} Hadir
                                    </span>
                                    <span class="stat-badge absent">
                                        <i class="fas fa-times"></i>
                                        {{ $student->absent_count }} Absen
                                    </span>
                                    <span class="stat-badge late">
                                        <i class="fas fa-clock"></i>
                                        {{ $student->late_count }} Terlambat
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="percentage-bar">
                                    <div class="percentage-fill {{ $student->attendance_percentage < 60 ? 'low' : ($student->attendance_percentage < 80 ? 'medium' : '') }}" 
                                         style="width: {{ $student->attendance_percentage }}%">
                                        {{ $student->attendance_percentage }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection