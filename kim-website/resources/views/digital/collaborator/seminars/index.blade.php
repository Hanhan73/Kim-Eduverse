@extends('layouts.collaborator')
@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>My Seminars</h1>
        <a href="{{ route('digital.collaborator.seminars.create') }}" class="btn-primary"><i class="fas fa-plus"></i>
            Tambah Seminar</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-content">
                <h4>{{ $stats['total'] }}</h4>
                <p>Total Seminar</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <h4>{{ $stats['active'] }}</h4>
                <p>Aktif</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <h4>{{ $stats['total_enrollments'] }}</h4>
                <p>Total Peserta</p>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Seminar</h2>
        </div>
        @if($seminars->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Seminar</th>
                    <th>Instruktur</th>
                    <th>Harga</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seminars as $s)
                <tr>
                    <td>
                        <div style="display: flex; gap: 15px; align-items: center;">
                            @if($s->thumbnail)
                            <img src="{{ Storage::url($s->thumbnail) }}"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                            <div
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chalkboard-teacher" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            @endif
                            <div><strong>{{ $s->title }}</strong><br><small
                                    style="color: var(--gray);">{{ $s->duration_minutes }} menit</small></div>
                        </div>
                    </td>
                    <td><strong>{{ $s->instructor_name }}</strong></td>
                    <td><strong style="color: var(--primary);">Rp {{ number_format($s->price, 0, ',', '.') }}</strong>
                    </td>
                    <td><span class="badge badge-info"><i class="fas fa-users"></i> {{ $s->enrollments_count }}</span>
                    </td>
                    <td><span
                            class="badge {{ $s->is_active ? 'badge-success' : 'badge-danger' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('digital.collaborator.seminars.edit', $s) }}"
                                class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('digital.collaborator.seminars.destroy', $s) }}" method="POST"
                                style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 20px;">{{ $seminars->links() }}</div>
        @else
        <div class="empty-state">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Belum Ada Seminar</h3>
            <p>Mulai tambahkan seminar on demand pertama Anda</p>
            <a href="{{ route('digital.collaborator.seminars.create') }}" class="btn-primary"><i
                    class="fas fa-plus"></i> Tambah Seminar</a>
        </div>
        @endif
    </div>
</div>
@endsection