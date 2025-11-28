@extends('admin.digital.layouts.app')

@section('title', 'Edit Angket - Admin Digital')
@section('page-title', 'Edit Angket')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Main Form -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-edit"></i> Edit Angket</h3>
            <a href="{{ route('admin.digital.questionnaires.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.digital.questionnaires.update', $questionnaire->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Angket <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $questionnaire->name) }}" required>
                    @error('name')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi <span style="color: var(--danger);">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $questionnaire->description) }}</textarea>
                    @error('description')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="instructions">Petunjuk Pengisian</label>
                    <textarea name="instructions" id="instructions" class="form-control" rows="3">{{ old('instructions', $questionnaire->instructions) }}</textarea>
                    @error('instructions')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="type">Tipe Angket <span style="color: var(--danger);">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $questionnaire->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duration_minutes">Durasi (menit)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', $questionnaire->duration_minutes) }}" min="1">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="has_dimensions" id="has_dimensions" value="1" {{ old('has_dimensions', $questionnaire->has_dimensions) ? 'checked' : '' }}>
                            <label for="has_dimensions">Memiliki Dimensi Pengukuran</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div>
        <!-- Quick Stats -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Info Angket</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Slug</small>
                    <div style="font-weight: 600;">{{ $questionnaire->slug }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Dibuat</small>
                    <div>{{ $questionnaire->created_at->format('d M Y H:i') }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Terakhir diubah</small>
                    <div>{{ $questionnaire->updated_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Dimensions -->
        @if($questionnaire->has_dimensions)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-layer-group"></i> Dimensi ({{ $questionnaire->dimensions->count() }})</h3>
                <a href="{{ route('admin.digital.dimensions.create', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($questionnaire->dimensions->count() > 0)
                    @foreach($questionnaire->dimensions as $dimension)
                    <div style="padding: 15px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $dimension->name }}</strong>
                            <br>
                            <small style="color: var(--gray);">
                                {{ $dimension->questions->count() }} pertanyaan
                            </small>
                        </div>
                        <a href="{{ route('admin.digital.dimensions.edit', $dimension->id) }}" class="btn btn-sm btn-icon btn-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @endforeach
                @else
                <div style="padding: 20px; text-align: center; color: var(--gray);">
                    Belum ada dimensi
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Questions -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-question-circle"></i> Pertanyaan ({{ $questionnaire->questions->count() }})</h3>
                <a href="{{ route('admin.digital.questions.create', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($questionnaire->questions->count() > 0)
                    @foreach($questionnaire->questions->take(5) as $question)
                    <div style="padding: 15px; border-bottom: 1px solid #edf2f7;">
                        <div style="display: flex; gap: 10px; align-items: start;">
                            <span class="badge badge-info">{{ $question->order }}</span>
                            <div style="flex: 1;">
                                <div>{{ Str::limit($question->question_text, 60) }}</div>
                                @if($question->dimension)
                                <small style="color: var(--primary);">{{ $question->dimension->name }}</small>
                                @endif
                                @if($question->is_reverse_scored)
                                <span class="badge badge-warning" style="font-size: 0.7rem;">Reverse</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($questionnaire->questions->count() > 5)
                    <div style="padding: 15px; text-align: center;">
                        <a href="{{ route('admin.digital.questions.index', ['questionnaire_id' => $questionnaire->id]) }}" style="color: var(--primary);">
                            Lihat semua {{ $questionnaire->questions->count() }} pertanyaan →
                        </a>
                    </div>
                    @endif
                @else
                <div style="padding: 20px; text-align: center; color: var(--gray);">
                    Belum ada pertanyaan
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-cog"></i> Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.digital.questions.index', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                    <i class="fas fa-question-circle"></i> Kelola Pertanyaan
                </a>
                @if($questionnaire->has_dimensions)
                <a href="{{ route('admin.digital.dimensions.index', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-secondary" style="width: 100%; margin-bottom: 10px;">
                    <i class="fas fa-layer-group"></i> Kelola Dimensi
                </a>
                @endif
                <a href="{{ route('admin.digital.responses.index', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-chart-bar"></i> Lihat Respons
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
