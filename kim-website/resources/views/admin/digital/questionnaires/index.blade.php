@extends('layouts.admin-digital')

@section('title', 'Daftar CEKMA - Admin Digital')
@section('page-title', 'Daftar CEKMA')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total'] }}</h4>
            <p>Total CEKMA</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['active'] }}</h4>
            <p>Aktif</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['inactive'] }}</h4>
            <p>Nonaktif</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_responses'] }}</h4>
            <p>Total Respons</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clipboard-list"></i> Daftar CEKMA</h3>
        <a href="{{ route('admin.digital.questionnaires.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah CEKMA
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-section" style="margin: 0 20px;">
        <form method="GET" action="{{ route('admin.digital.questionnaires.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau tipe..." value="{{ request('search') }}">
                </div>
                <div class="filter-group" style="flex: 0.5;">
                    <label>Tipe</label>
                    <select name="type" class="form-control">
                        <option value="">Semua Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex: 0.5;">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="filter-group" style="flex: 0; white-space: nowrap;">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.digital.questionnaires.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($questionnaires->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama CEKMA</th>
                        <th>Tipe</th>
                        <th>Dimensi</th>
                        <th>Pertanyaan</th>
                        <th>Respons</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questionnaires as $questionnaire)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $questionnaire->name }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ Str::limit($questionnaire->description, 50) }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($questionnaire->type) }}</span>
                        </td>
                        <td>
                            @if($questionnaire->has_dimensions)
                                <span class="badge badge-primary">{{ $questionnaire->dimensions_count }} dimensi</span>
                            @else
                                <span class="badge badge-secondary">Tanpa dimensi</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $questionnaire->questions_count }} soal</span>
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $questionnaire->responses_count }} respons</span>
                        </td>
                        <td>
                            @if($questionnaire->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.questionnaires.show', $questionnaire->id) }}" class="btn btn-sm btn-icon btn-secondary" title="Lihat & Kelola">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.digital.questionnaires.edit', $questionnaire->id) }}" class="btn btn-sm btn-icon btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.digital.questionnaires.destroy', $questionnaire->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('Hapus cekma ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Hapus">
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

        <!-- Pagination -->
        <div style="padding: 20px;">
            {{ $questionnaires->withQueryString()->links('vendor.pagination.admin') }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <h3>Belum Ada CEKMA</h3>
            <p>Mulai dengan membuat cekma pertama Anda</p>
            <a href="{{ route('admin.digital.questionnaires.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah CEKMA
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
