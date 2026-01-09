@extends('layouts.collaborator')
@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>My Questionnaires</h1>
        <a href="{{ route('digital.collaborator.questionnaires.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Angket
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-content">
                <h4>{{ $stats['total'] }}</h4>
                <p>Total Angket</p>
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
            <div class="stat-icon info"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-content">
                <h4>{{ $stats['with_dimensions'] }}</h4>
                <p>Dengan Dimensi</p>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Angket</h2>
        </div>
        @if($questionnaires->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Pertanyaan</th>
                    <th>Respons</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questionnaires as $q)
                <tr>
                    <td><strong>{{ $q->name }}</strong><br><small
                            style="color: var(--gray);">{{ Str::limit($q->description, 50) }}</small></td>
                    <td><span class="badge badge-info">{{ ucfirst($q->type) }}</span></td>
                    <td><span class="badge badge-info">{{ $q->questions_count }} soal</span></td>
                    <td><span class="badge badge-success">{{ $q->responses_count }} respons</span></td>
                    <td><span
                            class="badge {{ $q->is_active ? 'badge-success' : 'badge-danger' }}">{{ $q->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('digital.collaborator.questionnaires.builder', $q) }}"
                                class="btn btn-sm btn-primary" title="Builder"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('digital.collaborator.questionnaires.destroy', $q) }}" method="POST"
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
        <div style="padding: 20px;">{{ $questionnaires->links() }}</div>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <h3>Belum Ada Angket</h3>
            <p>Mulai dengan membuat angket pertama Anda</p>
            <a href="{{ route('digital.collaborator.questionnaires.create') }}" class="btn-primary"><i
                    class="fas fa-plus"></i> Tambah Angket</a>
        </div>
        @endif
    </div>
</div>
@endsection