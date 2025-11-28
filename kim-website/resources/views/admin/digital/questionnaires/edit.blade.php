@extends('layouts.admin-digital')

@section('title', 'Edit Angket - Admin Digital')
@section('page-title', 'Edit Angket')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit Angket</h3>
        <a href="{{ route('admin.digital.questionnaires.show', $questionnaire->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.questionnaires.update', $questionnaire->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Left Column -->
                <div>
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
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="type">Tipe Angket <span style="color: var(--danger);">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $questionnaire->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration_minutes">Durasi Pengisian (menit)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', $questionnaire->duration_minutes) }}" min="1">
                        @error('duration_minutes')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="has_dimensions" id="has_dimensions" value="1" {{ old('has_dimensions', $questionnaire->has_dimensions) ? 'checked' : '' }}>
                            <label for="has_dimensions">Memiliki Dimensi Pengukuran</label>
                        </div>
                        <small style="color: var(--gray);">Centang jika angket memiliki beberapa dimensi/aspek yang diukur terpisah</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Aktif</label>
                        </div>
                        <small style="color: var(--gray);">Angket aktif dapat diakses oleh pengguna</small>
                    </div>

                    <!-- Info -->
                    <div style="background: #f7fafc; padding: 15px; border-radius: 10px; margin-top: 20px;">
                        <small style="color: var(--gray);">Slug</small>
                        <div style="font-family: monospace; background: white; padding: 8px 12px; border-radius: 5px; margin-top: 5px;">
                            {{ $questionnaire->slug }}
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.digital.questionnaires.show', $questionnaire->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quick Stats -->
<div class="card" style="margin-top: 25px;">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Statistik Angket</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 10px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);">{{ $questionnaire->dimensions->count() }}</div>
                <small style="color: var(--gray);">Dimensi</small>
            </div>
            <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 10px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--info);">{{ $questionnaire->questions->count() }}</div>
                <small style="color: var(--gray);">Pertanyaan</small>
            </div>
            <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 10px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success);">{{ $questionnaire->responses()->where('is_completed', true)->count() }}</div>
                <small style="color: var(--gray);">Respons Selesai</small>
            </div>
            <div style="text-align: center; padding: 20px; background: #f7fafc; border-radius: 10px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning);">{{ $questionnaire->responses()->where('is_completed', false)->count() }}</div>
                <small style="color: var(--gray);">Respons Pending</small>
            </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <a href="{{ route('admin.digital.questionnaires.show', $questionnaire->id) }}" class="btn btn-primary">
                <i class="fas fa-cog"></i> Kelola Dimensi & Pertanyaan
            </a>
        </div>
    </div>
</div>
@endsection
