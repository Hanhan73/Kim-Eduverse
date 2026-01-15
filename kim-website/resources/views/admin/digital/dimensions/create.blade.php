@extends('layouts.admin-digital')

@section('title', 'Tambah Dimensi - Admin Digital')
@section('page-title', 'Tambah Dimensi Baru')

@section('styles')
<style>
    .interpretation-card {
        background: #f7fafc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
    }
    .interpretation-card.low {
        border-left: 4px solid #48bb78;
    }
    .interpretation-card.medium {
        border-left: 4px solid #ed8936;
    }
    .interpretation-card.high {
        border-left: 4px solid #f56565;
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
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Dimensi Baru</h3>
        <a href="{{ route('admin.digital.dimensions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.dimensions.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="form-group">
                    <label for="questionnaire_id">CEKMA <span style="color: var(--danger);">*</span></label>
                    <select name="questionnaire_id" id="questionnaire_id" class="form-control" required>
                        <option value="">Pilih CEKMA</option>
                        @foreach($questionnaires as $questionnaire)
                            <option value="{{ $questionnaire->id }}" {{ (old('questionnaire_id') ?? $selectedQuestionnaireId) == $questionnaire->id ? 'selected' : '' }}>
                                {{ $questionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('questionnaire_id')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order">Urutan</label>
                    <input type="number" name="order" id="order" class="form-control" value="{{ old('order') }}" min="0" placeholder="Auto">
                    <small style="color: var(--gray);">Kosongkan untuk urutan otomatis</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="form-group">
                    <label for="name">Nama Dimensi <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Kelelahan Emosional">
                    @error('name')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="code">Kode <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required placeholder="Contoh: exhaustion">
                    <small style="color: var(--gray);">Gunakan huruf kecil tanpa spasi</small>
                    @error('code')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="2" placeholder="Jelaskan apa yang diukur oleh dimensi ini...">{{ old('description') }}</textarea>
            </div>

            <!-- Interpretations -->
            <h4 style="margin-bottom: 20px; color: var(--dark);">
                <i class="fas fa-chart-pie"></i> Interpretasi Skor
            </h4>
            <p style="color: var(--gray); margin-bottom: 20px;">
                Atur interpretasi untuk setiap tingkat skor. Interpretasi akan ditampilkan pada hasil cekma.
            </p>

            <!-- Low -->
            <div class="interpretation-card low">
                <h5 style="color: #22543d; margin-bottom: 15px;">
                    <i class="fas fa-smile"></i> Tingkat RENDAH
                </h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Label</label>
                        <input type="text" name="interpretations[low][level]" class="form-control" value="{{ old('interpretations.low.level', 'RENDAH') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[low][class]" class="form-control" value="{{ old('interpretations.low.class', 'level-rendah') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[low][description]" class="form-control" rows="2" required placeholder="Jelaskan kondisi ketika skor rendah...">{{ old('interpretations.low.description') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran (opsional)</label>
                    <div id="suggestions-low">
                        <div class="suggestion-item">
                            <input type="text" name="interpretations[low][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                            <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-add-suggestion" onclick="addSuggestion('low')">
                        <i class="fas fa-plus"></i> Tambah Saran
                    </button>
                </div>
            </div>

            <!-- Medium -->
            <div class="interpretation-card medium">
                <h5 style="color: #744210; margin-bottom: 15px;">
                    <i class="fas fa-meh"></i> Tingkat SEDANG
                </h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Label</label>
                        <input type="text" name="interpretations[medium][level]" class="form-control" value="{{ old('interpretations.medium.level', 'SEDANG') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[medium][class]" class="form-control" value="{{ old('interpretations.medium.class', 'level-sedang') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[medium][description]" class="form-control" rows="2" required placeholder="Jelaskan kondisi ketika skor sedang...">{{ old('interpretations.medium.description') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran (opsional)</label>
                    <div id="suggestions-medium">
                        <div class="suggestion-item">
                            <input type="text" name="interpretations[medium][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                            <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-add-suggestion" onclick="addSuggestion('medium')">
                        <i class="fas fa-plus"></i> Tambah Saran
                    </button>
                </div>
            </div>

            <!-- High -->
            <div class="interpretation-card high">
                <h5 style="color: #742a2a; margin-bottom: 15px;">
                    <i class="fas fa-frown"></i> Tingkat TINGGI
                </h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Label</label>
                        <input type="text" name="interpretations[high][level]" class="form-control" value="{{ old('interpretations.high.level', 'TINGGI') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[high][class]" class="form-control" value="{{ old('interpretations.high.class', 'level-tinggi') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[high][description]" class="form-control" rows="2" required placeholder="Jelaskan kondisi ketika skor tinggi...">{{ old('interpretations.high.description') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran (opsional)</label>
                    <div id="suggestions-high">
                        <div class="suggestion-item">
                            <input type="text" name="interpretations[high][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                            <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-add-suggestion" onclick="addSuggestion('high')">
                        <i class="fas fa-plus"></i> Tambah Saran
                    </button>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Dimensi
                </button>
                <a href="{{ route('admin.digital.dimensions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addSuggestion(level) {
    const container = document.getElementById('suggestions-' + level);
    const item = document.createElement('div');
    item.className = 'suggestion-item';
    item.innerHTML = `
        <input type="text" name="interpretations[${level}][suggestions][]" class="form-control" placeholder="Saran tindakan...">
        <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(item);
}

// Auto-generate code from name
document.getElementById('name').addEventListener('input', function() {
    const code = this.value.toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '_')
        .substring(0, 50);
    document.getElementById('code').value = code;
});
</script>
@endsection
