@extends('layouts.admin-digital')

@section('title', 'Dimensi Angket - Admin Digital')
@section('page-title', 'Dimensi Angket')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-layer-group"></i> Daftar Dimensi</h3>
        <a href="{{ route('admin.digital.dimensions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Dimensi
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-section" style="margin: 0 20px;">
        <form method="GET" action="{{ route('admin.digital.dimensions.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau kode..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>Angket</label>
                    <select name="questionnaire_id" class="form-control">
                        <option value="">Semua Angket</option>
                        @foreach($questionnaires as $questionnaire)
                            <option value="{{ $questionnaire->id }}" {{ request('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                                {{ $questionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex: 0; white-space: nowrap;">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.digital.dimensions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($dimensions->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Dimensi</th>
                        <th>Kode</th>
                        <th>Angket</th>
                        <th>Pertanyaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dimensions as $dimension)
                    <tr>
                        <td>
                            <span class="badge badge-info">{{ $dimension->order }}</span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $dimension->name }}</strong>
                                @if($dimension->description)
                                <br>
                                <small style="color: var(--gray);">{{ Str::limit($dimension->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <code style="background: #f7fafc; padding: 3px 8px; border-radius: 5px;">{{ $dimension->code }}</code>
                        </td>
                        <td>
                            <a href="{{ route('admin.digital.questionnaires.show', $dimension->questionnaire_id) }}" style="color: var(--primary); text-decoration: none;">
                                {{ Str::limit($dimension->questionnaire->name ?? '-', 25) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $dimension->questions_count }} soal</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.dimensions.show', $dimension->id) }}" class="btn btn-sm btn-icon btn-secondary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.digital.dimensions.edit', $dimension->id) }}" class="btn btn-sm btn-icon btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.digital.dimensions.destroy', $dimension->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete('Hapus dimensi ini?')">
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
            {{ $dimensions->withQueryString()->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-layer-group"></i>
            <h3>Belum Ada Dimensi</h3>
            <p>Dimensi digunakan untuk mengelompokkan pertanyaan dalam angket</p>
            <a href="{{ route('admin.digital.dimensions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Dimensi
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
