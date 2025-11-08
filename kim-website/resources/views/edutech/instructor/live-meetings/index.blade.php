@extends('layouts.instructor')

@section('title', 'Live Meetings')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .page-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header p {
        margin-top: 10px;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .stat-icon.scheduled { background: linear-gradient(135deg, #4299e1, #3182ce); color: white; }
    .stat-icon.ongoing { background: linear-gradient(135deg, #48bb78, #38a169); color: white; }
    .stat-icon.completed { background: linear-gradient(135deg, #a0aec0, #718096); color: white; }

    .stat-label {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .meetings-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .table-header {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 20px 30px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table {
        margin: 0;
    }

    .table thead th {
        background: #f8f9fa;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 15px 20px;
        border: none;
    }

    .table tbody td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .meeting-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 1.05rem;
        margin-bottom: 5px;
    }

    .meeting-desc {
        color: #718096;
        font-size: 0.9rem;
    }

    .course-badge {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .schedule-info {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .schedule-date {
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .schedule-time {
        color: #718096;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .duration-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        background: #edf2f7;
        border-radius: 10px;
        font-weight: 500;
        color: #4a5568;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge.scheduled {
        background: linear-gradient(135deg, #bee3f8, #90cdf4);
        color: #2c5282;
    }

    .status-badge.ongoing {
        background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
        color: #22543d;
        animation: pulse 2s infinite;
    }

    .status-badge.completed {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
        color: #4a5568;
    }

    .status-badge.cancelled {
        background: linear-gradient(135deg, #fed7d7, #fc8181);
        color: #742a2a;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-join {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }

    .btn-join:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
    }

    .btn-edit {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
    }

    .btn-edit:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(237, 137, 54, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .btn-delete:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(245, 101, 101, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #718096;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 14px 32px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        font-size: 1rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .alert {
        padding: 18px 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
        color: #22543d;
        border-left: 4px solid #38a169;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="page-header">
            <h2>
                <i class="fas fa-video"></i>
                Live Meetings
            </h2>
            <p>Kelola jadwal live meeting untuk kursus Anda</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon scheduled">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-label">Terjadwal</div>
                <div class="stat-value">{{ $sessions->where('status', 'scheduled')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon ongoing">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-label">Sedang Berlangsung</div>
                <div class="stat-value">{{ $sessions->where('status', 'ongoing')->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $sessions->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        <!-- Meetings Table -->
        @if($sessions->count() > 0)
            <div class="meetings-table">
                <div class="table-header">
                    <h3>
                        <i class="fas fa-list"></i>
                        Daftar Live Meetings
                    </h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Meeting</th>
                                <th style="width: 20%;">Kursus</th>
                                <th style="width: 20%;">Jadwal</th>
                                <th style="width: 10%;">Durasi</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                            <tr>
                                <td>
                                    <div class="meeting-title">{{ $session->title }}</div>
                                    @if($session->description)
                                        <div class="meeting-desc">{{ Str::limit($session->description, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="course-badge">{{ $session->course->title }}</span>
                                </td>
                                <td>
                                    <div class="schedule-info">
                                        <span class="schedule-date">
                                            <i class="fas fa-calendar"></i>
                                            {{ $session->scheduled_at->format('d M Y') }}
                                        </span>
                                        <span class="schedule-time">
                                            <i class="fas fa-clock"></i>
                                            {{ $session->scheduled_at->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="duration-badge">
                                        <i class="fas fa-hourglass-half"></i>
                                        {{ $session->duration_minutes }} mnt
                                    </span>
                                </td>
                                <td>
                                    @if($session->status === 'scheduled')
                                        <span class="status-badge scheduled">
                                            <i class="fas fa-clock"></i> Terjadwal
                                        </span>
                                    @elseif($session->status === 'ongoing')
                                        <span class="status-badge ongoing">
                                            <i class="fas fa-circle"></i> Live
                                        </span>
                                    @elseif($session->status === 'completed')
                                        <span class="status-badge completed">
                                            <i class="fas fa-check"></i> Selesai
                                        </span>
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="fas fa-times"></i> Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ $session->meeting_url }}" target="_blank" class="btn-action btn-join" title="Join Meeting">
                                            <i class="fas fa-video"></i>
                                        </a>
                                        <a href="{{ route('edutech.instructor.live-meetings.edit', $session->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('edutech.instructor.live-meetings.destroy', $session->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus live meeting ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-video"></i>
                </div>
                <h3>Belum Ada Live Meeting</h3>
                <p>Mulai jadwalkan live meeting pertama Anda dan berinteraksi langsung dengan siswa</p>
                <a href="{{ route('edutech.instructor.live-meetings.create') }}" class="btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Buat Live Meeting Pertama
                </a>
            </div>
        @endif

        <!-- Floating Action Button -->
        @if($sessions->count() > 0)
            <a href="{{ route('edutech.instructor.live-meetings.create') }}" class="btn-primary" style="position: fixed; bottom: 30px; right: 30px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);">
                <i class="fas fa-plus-circle"></i>
                Jadwalkan Meeting Baru
            </a>
        @endif
    </div>
</div>
@endsection