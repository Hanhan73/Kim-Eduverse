@extends('layouts.admin-digital')

@section('title', 'Pertanyaan CEKMA - Admin Digital')
@section('page-title', 'Pertanyaan CEKMA')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-question-circle"></i> Daftar Pertanyaan</h3>
        <a href="{{ route('admin.digital.questions.create', request()->only(['questionnaire_id', 'dimension_id'])) }}"
            class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pertanyaan
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-section" style="margin: 0 20px;">
        <form method="GET" action="{{ route('admin.digital.questions.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Teks pertanyaan..."
                        value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>CEKMA</label>
                    <select name="questionnaire_id" id="questionnaire_filter" class="form-control"
                        onchange="loadDimensions()">
                        <option value="">Semua CEKMA</option>
                        @foreach($questionnaires as $questionnaire)
                        <option value="{{ $questionnaire->id }}"
                            {{ request('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                            {{ $questionnaire->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Dimensi</label>
                    <select name="dimension_id" id="dimension_filter" class="form-control">
                        <option value="">Semua Dimensi</option>
                        @foreach($dimensions as $dimension)
                        <option value="{{ $dimension->id }}"
                            {{ request('dimension_id') == $dimension->id ? 'selected' : '' }}>
                            {{ $dimension->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex: 0; white-space: nowrap;">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.digital.questions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($questions->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Pertanyaan</th>
                        <th>CEKMA</th>
                        <th>Dimensi</th>
                        <th>Reverse</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                    <tr>
                        <td>
                            <span class="badge badge-info">{{ $question->order }}</span>
                        </td>
                        <td>
                            <div style="max-width: 400px;">
                                {{ $question->question_text }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.digital.questionnaires.show', $question->questionnaire_id) }}"
                                style="color: var(--primary); text-decoration: none;">
                                {{ Str::limit($question->questionnaire->name ?? '-', 20) }}
                            </a>
                        </td>
                        <td>
                            @if($question->dimension)
                            <span class="badge badge-primary">{{ $question->dimension->name }}</span>
                            @else
                            <span style="color: var(--gray);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($question->is_reverse_scored)
                            <span class="badge badge-warning">Ya</span>
                            @else
                            <span class="badge badge-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.questions.edit', $question->id) }}"
                                    class="btn btn-sm btn-icon btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.digital.questions.destroy', $question->id) }}"
                                    method="POST" style="display: inline;"
                                    onsubmit="return confirmDelete('Hapus pertanyaan ini?')">
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
            {{ $questions->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-question-circle"></i>
            <h3>Belum Ada Pertanyaan</h3>
            <p>Mulai dengan menambahkan pertanyaan untuk cekma Anda</p>
            <a href="{{ route('admin.digital.questions.create', request()->only(['questionnaire_id', 'dimension_id'])) }}"
                class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pertanyaan
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Quick Add Modal -->
<div id="quickAddModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Pertanyaan Cepat</h3>
            <button class="modal-close" onclick="closeModal('quickAddModal')">&times;</button>
        </div>
        <form action="{{ route('admin.digital.questions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>CEKMA</label>
                    <select name="questionnaire_id" class="form-control" required>
                        <option value="">Pilih CEKMA</option>
                        @foreach($questionnaires as $questionnaire)
                        <option value="{{ $questionnaire->id }}"
                            {{ request('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                            {{ $questionnaire->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Dimensi (opsional)</label>
                    <select name="dimension_id" class="form-control">
                        <option value="">Tanpa Dimensi</option>
                        @foreach($dimensions as $dimension)
                        <option value="{{ $dimension->id }}">{{ $dimension->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <textarea name="question_text" class="form-control" rows="3" required
                        placeholder="Tulis pertanyaan..."></textarea>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_reverse_scored" id="modal_reverse" value="1">
                    <label for="modal_reverse">Reverse Scored</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('quickAddModal')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadDimensions() {
    const questionnaireId = document.getElementById('questionnaire_filter').value;
    const dimensionSelect = document.getElementById('dimension_filter');

    if (!questionnaireId) {
        dimensionSelect.innerHTML = '<option value="">Semua Dimensi</option>';
        return;
    }

    fetch(`/admin/digital/questions/dimensions/${questionnaireId}`)
        .then(response => response.json())
        .then(dimensions => {
            let options = '<option value="">Semua Dimensi</option>';
            dimensions.forEach(dim => {
                options += `<option value="${dim.id}">${dim.name}</option>`;
            });
            dimensionSelect.innerHTML = options;
        });
}
</script>
@endsection