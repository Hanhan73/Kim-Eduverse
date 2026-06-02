@extends('layouts.admin-digital')
@section('title', 'Pelatihan')
@section('page-title', 'Pelatihan')

@section('content')
<div class="page-header">
    <div>
        <h1>Pelatihan</h1>
        <p>Kelola pelatihan luring yang terintegrasi dengan sistem digital</p>
    </div>
    <a href="{{ route('admin.digital.trainings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pelatihan
    </a>
</div>

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($trainings->count() > 0)
        <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f9fa;">
                        <th style="padding:14px 16px; text-align:left; font-size:0.85rem; color:#6b7280;">JUDUL</th>
                        <th style="padding:14px 16px; text-align:left; font-size:0.85rem; color:#6b7280;">TANGGAL</th>
                        <th style="padding:14px 16px; text-align:left; font-size:0.85rem; color:#6b7280;">TEMPAT</th>
                        <th style="padding:14px 16px; text-align:center; font-size:0.85rem; color:#6b7280;">PESERTA</th>
                        <th style="padding:14px 16px; text-align:left; font-size:0.85rem; color:#6b7280;">SEMINAR LINK</th>
                        <th style="padding:14px 16px; text-align:center; font-size:0.85rem; color:#6b7280;">STATUS</th>
                        <th style="padding:14px 16px; text-align:center; font-size:0.85rem; color:#6b7280;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $training)
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:14px 16px;">
                            <div style="font-weight:600; color:#1e293b;">{{ $training->title }}</div>
                            @if($training->trainer_name)
                            <small style="color:#6b7280;">{{ $training->trainer_name }}</small>
                            @endif
                        </td>
                        <td style="padding:14px 16px;">
                            <div>{{ $training->training_date->format('d M Y') }}</div>
                            @if($training->start_time)
                            <small style="color:#6b7280;">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</small>
                            @endif
                        </td>
                        <td style="padding:14px 16px; color:#374151;">{{ $training->location }}</td>
                        <td style="padding:14px 16px; text-align:center;">
                            <span style="background:#ede9fe; color:#7c3aed; padding:4px 10px; border-radius:20px; font-weight:600; font-size:0.85rem;">
                                {{ $training->participants_count }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            @if($training->seminar)
                            <span style="background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:20px; font-size:0.8rem;">
                                <i class="fas fa-link"></i> {{ Str::limit($training->seminar->title, 30) }}
                            </span>
                            @else
                            <span style="color:#9ca3af; font-size:0.85rem;">Tanpa seminar</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            @if($training->is_active)
                            <span class="badge badge-success">Aktif</span>
                            @else
                            <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding:14px 16px; text-align:center;">
                            <div style="display:flex; gap:6px; justify-content:center;">
                                <a href="{{ route('admin.digital.trainings.show', $training) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.digital.trainings.edit', $training) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.digital.trainings.destroy', $training) }}" onsubmit="return confirm('Hapus pelatihan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:20px;">{{ $trainings->links() }}</div>
        @else
        <div class="empty-state">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Belum Ada Pelatihan</h3>
            <p>Mulai buat pelatihan pertama Anda</p>
            <a href="{{ route('admin.digital.trainings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pelatihan
            </a>
        </div>
        @endif
    </div>
</div>
@endsection