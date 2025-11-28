@extends('layouts.admin-digital')

@section('title', 'Edit Dimensi - Admin Digital')
@section('page-title', 'Edit Dimensi')

@section('styles')
<style>
    .score-range-card {
        background: #f7fafc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #cbd5e0;
    }
    .score-range-card.level-sangat-rendah { border-left-color: #276749; background: #f0fff4; }
    .score-range-card.level-rendah { border-left-color: #48bb78; background: #f0fff4; }
    .score-range-card.level-sedang { border-left-color: #ed8936; background: #fffaf0; }
    .score-range-card.level-tinggi { border-left-color: #f56565; background: #fff5f5; }
    .score-range-card.level-sangat-tinggi { border-left-color: #742a2a; background: #fff5f5; }

    .score-range-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .score-range-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    .score-range-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .score-range-badge.level-sangat-rendah { background: #276749; color: white; }
    .score-range-badge.level-rendah { background: #48bb78; color: white; }
    .score-range-badge.level-sedang { background: #ed8936; color: white; }
    .score-range-badge.level-tinggi { background: #f56565; color: white; }
    .score-range-badge.level-sangat-tinggi { background: #742a2a; color: white; }

    .score-info {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    .score-info-item {
        background: white;
        padding: 10px 15px;
        border-radius: 8px;
        text-align: center;
    }
    .score-info-label {
        font-size: 0.75rem;
        color: var(--gray);
    }
    .score-info-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .suggestion-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    .suggestion-item input {
        flex: 1;
    }
    .btn-add-suggestion {
        background: none;
        border: 1px dashed #cbd5e0;
        color: var(--gray);
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s;
    }
    .btn-add-suggestion:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .bounds-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }
    .bounds-info h4 {
        margin: 0 0 15px;
    }
    .bounds-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    .bounds-item {
        background: rgba(255,255,255,0.2);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
    .bounds-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .bounds-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .add-range-form {
        background: white;
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.digital.dimensions.index', ['questionnaire_id' => $dimension->questionnaire_id]) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div>
        <a href="{{ route('admin.digital.questionnaires.show', $dimension->questionnaire_id) }}" class="btn btn-info">
            <i class="fas fa-clipboard-list"></i> Lihat Angket
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <!-- Left: Basic Info -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit"></i> Informasi Dimensi</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.digital.dimensions.update', $dimension->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="questionnaire_id">Angket <span style="color: var(--danger);">*</span></label>
                        <select name="questionnaire_id" id="questionnaire_id" class="form-control" required>
                            @foreach($questionnaires as $questionnaire)
                                <option value="{{ $questionnaire->id }}" {{ old('questionnaire_id', $dimension->questionnaire_id) == $questionnaire->id ? 'selected' : '' }}>
                                    {{ $questionnaire->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="name">Nama Dimensi <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $dimension->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="code">Kode <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $dimension->code) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $dimension->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="order">Urutan</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $dimension->order) }}" min="0">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Questions Summary -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-question-circle"></i> Pertanyaan ({{ $dimension->questions->count() }})</h3>
            </div>
            <div class="card-body" style="padding: 0; max-height: 300px; overflow-y: auto;">
                @forelse($dimension->questions as $question)
                <div style="padding: 12px 20px; border-bottom: 1px solid #edf2f7; display: flex; gap: 10px; align-items: start;">
                    <span class="badge badge-primary" style="flex-shrink: 0;">{{ $question->order }}</span>
                    <span style="flex: 1; font-size: 0.9rem;">{{ Str::limit($question->question_text, 80) }}</span>
                    @if($question->is_reverse_scored)
                        <span class="badge badge-warning" style="flex-shrink: 0;">R</span>
                    @endif
                </div>
                @empty
                <div style="padding: 30px; text-align: center; color: var(--gray);">
                    <i class="fas fa-info-circle"></i> Belum ada pertanyaan
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Score Ranges -->
    <div>
        <!-- Score Bounds Info -->
        <div class="bounds-info">
            <h4><i class="fas fa-calculator"></i> Kalkulasi Skor</h4>
            <div class="bounds-grid">
                <div class="bounds-item">
                    <div class="bounds-value">{{ $bounds['questions'] }}</div>
                    <div class="bounds-label">Pertanyaan</div>
                </div>
                <div class="bounds-item">
                    <div class="bounds-value">{{ $bounds['min'] }}</div>
                    <div class="bounds-label">Skor Min</div>
                </div>
                <div class="bounds-item">
                    <div class="bounds-value">{{ $bounds['max'] }}</div>
                    <div class="bounds-label">Skor Max</div>
                </div>
            </div>
            <p style="margin-top: 15px; font-size: 0.85rem; opacity: 0.9;">
                <i class="fas fa-info-circle"></i> 
                Dengan {{ $bounds['questions'] }} pertanyaan (skala 1-5), skor berkisar antara {{ $bounds['min'] }} - {{ $bounds['max'] }}.
            </p>
        </div>

        <!-- Score Ranges -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-sliders-h"></i> Score Ranges</h3>
                <div>
                    @if($bounds['questions'] > 0)
                    <form action="{{ route('admin.digital.dimensions.generate-ranges', $dimension->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Ini akan menghapus score ranges yang ada dan membuat baru. Lanjutkan?')">
                            <i class="fas fa-magic"></i> Auto Generate
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($bounds['questions'] == 0)
                    <div class="empty-state" style="padding: 30px;">
                        <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>
                        <h4>Belum Ada Pertanyaan</h4>
                        <p>Tambahkan pertanyaan ke dimensi ini terlebih dahulu untuk mengatur score ranges.</p>
                    </div>
                @else
                    <!-- Existing Score Ranges -->
                    @forelse($dimension->scoreRanges as $range)
                    <div class="score-range-card {{ $range->css_class }}" id="range-{{ $range->id }}">
                        <form action="{{ route('admin.digital.score-ranges.update', $range->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="score-range-header">
                                <span class="score-range-badge {{ $range->css_class }}">{{ $range->level }}</span>
                                <div>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteRange({{ $range->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Min Skor</label>
                                    <input type="number" name="min_score" class="form-control" value="{{ $range->min_score }}" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Max Skor</label>
                                    <input type="number" name="max_score" class="form-control" value="{{ $range->max_score }}" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Kategori</label>
                                    <select name="category" class="form-control">
                                        @foreach($categories as $val => $label)
                                            <option value="{{ $val }}" {{ $range->category == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Label Tampilan</label>
                                    <input type="text" name="level" class="form-control" value="{{ $range->level }}" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>CSS Class</label>
                                    <select name="css_class" class="form-control">
                                        @foreach($cssClasses as $val => $label)
                                            <option value="{{ $val }}" {{ $range->css_class == $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" rows="2" required>{{ $range->description }}</textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label>Saran</label>
                                <div id="suggestions-{{ $range->id }}">
                                    @if($range->suggestions && count($range->suggestions) > 0)
                                        @foreach($range->suggestions as $suggestion)
                                        <div class="suggestion-item">
                                            <input type="text" name="suggestions[]" class="form-control" value="{{ $suggestion }}">
                                            <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn-add-suggestion" onclick="addSuggestion('suggestions-{{ $range->id }}', 'suggestions[]')">
                                    <i class="fas fa-plus"></i> Tambah Saran
                                </button>
                            </div>
                        </form>
                    </div>
                    @empty
                    <div class="empty-state" style="padding: 30px;">
                        <i class="fas fa-sliders-h"></i>
                        <h4>Belum Ada Score Ranges</h4>
                        <p>Klik "Auto Generate" atau tambahkan manual di bawah.</p>
                    </div>
                    @endforelse

                    <!-- Add New Range Form -->
                    <div class="add-range-form">
                        <h4 style="margin-bottom: 20px; color: var(--dark);">
                            <i class="fas fa-plus-circle"></i> Tambah Score Range Baru
                        </h4>
                        <form action="{{ route('admin.digital.dimensions.add-range', $dimension->id) }}" method="POST">
                            @csrf
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Min Skor <span style="color: var(--danger);">*</span></label>
                                    <input type="number" name="min_score" class="form-control" required min="{{ $bounds['min'] }}" max="{{ $bounds['max'] }}">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Max Skor <span style="color: var(--danger);">*</span></label>
                                    <input type="number" name="max_score" class="form-control" required min="{{ $bounds['min'] }}" max="{{ $bounds['max'] }}">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Kategori <span style="color: var(--danger);">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Pilih...</option>
                                        @foreach($categories as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Label Tampilan <span style="color: var(--danger);">*</span></label>
                                    <input type="text" name="level" class="form-control" required placeholder="Contoh: SANGAT TINGGI">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>CSS Class <span style="color: var(--danger);">*</span></label>
                                    <select name="css_class" class="form-control" required>
                                        @foreach($cssClasses as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Deskripsi <span style="color: var(--danger);">*</span></label>
                                <textarea name="description" class="form-control" rows="2" required placeholder="Jelaskan kondisi pada level ini..."></textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Saran (opsional)</label>
                                <div id="suggestions-new">
                                    <div class="suggestion-item">
                                        <input type="text" name="suggestions[]" class="form-control" placeholder="Saran tindakan...">
                                        <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn-add-suggestion" onclick="addSuggestion('suggestions-new', 'suggestions[]')">
                                    <i class="fas fa-plus"></i> Tambah Saran
                                </button>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Score Range
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-range-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
function addSuggestion(containerId, inputName) {
    const container = document.getElementById(containerId);
    const item = document.createElement('div');
    item.className = 'suggestion-item';
    item.innerHTML = `
        <input type="text" name="${inputName}" class="form-control" placeholder="Saran tindakan...">
        <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(item);
}

function deleteRange(rangeId) {
    if (confirm('Hapus score range ini?')) {
        const form = document.getElementById('delete-range-form');
        form.action = '{{ url("admin/digital/score-ranges") }}/' + rangeId;
        form.submit();
    }
}
</script>
@endsection
