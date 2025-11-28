@extends('layouts.admin-digital')

@section('title', 'Edit Dimensi - Admin Digital')
@section('page-title', 'Edit Dimensi')

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
        <h3><i class="fas fa-edit"></i> Edit Dimensi</h3>
        <a href="{{ route('admin.digital.dimensions.index', ['questionnaire_id' => $dimension->questionnaire_id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.dimensions.update', $dimension->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
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

                <div class="form-group">
                    <label for="order">Urutan</label>
                    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $dimension->order) }}" min="0">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="form-group">
                    <label for="name">Nama Dimensi <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $dimension->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="code">Kode <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $dimension->code) }}" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $dimension->description) }}</textarea>
            </div>

            <!-- Interpretations -->
            <h4 style="margin-bottom: 20px; color: var(--dark);">
                <i class="fas fa-chart-pie"></i> Interpretasi Skor
            </h4>

            @php
                $interpretations = $dimension->interpretations ?? [];
            @endphp

            <!-- Low -->
            <div class="interpretation-card low">
                <h5 style="color: #22543d; margin-bottom: 15px;">
                    <i class="fas fa-smile"></i> Tingkat RENDAH
                </h5>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Label</label>
                        <input type="text" name="interpretations[low][level]" class="form-control" value="{{ old('interpretations.low.level', $interpretations['low']['level'] ?? 'RENDAH') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[low][class]" class="form-control" value="{{ old('interpretations.low.class', $interpretations['low']['class'] ?? 'level-rendah') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[low][description]" class="form-control" rows="2" required>{{ old('interpretations.low.description', $interpretations['low']['description'] ?? '') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran</label>
                    <div id="suggestions-low">
                        @if(isset($interpretations['low']['suggestions']) && count($interpretations['low']['suggestions']) > 0)
                            @foreach($interpretations['low']['suggestions'] as $suggestion)
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[low][suggestions][]" class="form-control" value="{{ $suggestion }}">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[low][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
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
                        <input type="text" name="interpretations[medium][level]" class="form-control" value="{{ old('interpretations.medium.level', $interpretations['medium']['level'] ?? 'SEDANG') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[medium][class]" class="form-control" value="{{ old('interpretations.medium.class', $interpretations['medium']['class'] ?? 'level-sedang') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[medium][description]" class="form-control" rows="2" required>{{ old('interpretations.medium.description', $interpretations['medium']['description'] ?? '') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran</label>
                    <div id="suggestions-medium">
                        @if(isset($interpretations['medium']['suggestions']) && count($interpretations['medium']['suggestions']) > 0)
                            @foreach($interpretations['medium']['suggestions'] as $suggestion)
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[medium][suggestions][]" class="form-control" value="{{ $suggestion }}">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[medium][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
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
                        <input type="text" name="interpretations[high][level]" class="form-control" value="{{ old('interpretations.high.level', $interpretations['high']['level'] ?? 'TINGGI') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>CSS Class</label>
                        <input type="text" name="interpretations[high][class]" class="form-control" value="{{ old('interpretations.high.class', $interpretations['high']['class'] ?? 'level-tinggi') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Deskripsi</label>
                    <textarea name="interpretations[high][description]" class="form-control" rows="2" required>{{ old('interpretations.high.description', $interpretations['high']['description'] ?? '') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Saran</label>
                    <div id="suggestions-high">
                        @if(isset($interpretations['high']['suggestions']) && count($interpretations['high']['suggestions']) > 0)
                            @foreach($interpretations['high']['suggestions'] as $suggestion)
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[high][suggestions][]" class="form-control" value="{{ $suggestion }}">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="suggestion-item">
                                <input type="text" name="interpretations[high][suggestions][]" class="form-control" placeholder="Saran tindakan...">
                                <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="this.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-add-suggestion" onclick="addSuggestion('high')">
                        <i class="fas fa-plus"></i> Tambah Saran
                    </button>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.digital.dimensions.index', ['questionnaire_id' => $dimension->questionnaire_id]) }}" class="btn btn-secondary">
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
</script>
@endsection
