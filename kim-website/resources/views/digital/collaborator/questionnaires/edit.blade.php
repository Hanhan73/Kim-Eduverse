@extends('layouts.collaborator')

@section('title', 'Edit Angket - ' . $questionnaire->name)
@section('page-title', 'Edit Angket')

@section('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
}

.form-switch .form-check-input {
    width: 50px;
    height: 26px;
    cursor: pointer;
}

.form-switch .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: var(--gray);
}

.form-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-check-label {
    cursor: pointer;
}

.invalid-feedback {
    display: block;
    color: var(--danger);
    font-size: 0.85rem;
    margin-top: 5px;
}

.text-danger {
    color: var(--danger);
}

.text-muted {
    color: var(--gray) !important;
}

.text-white {
    color: white !important;
}

.mb-0 {
    margin-bottom: 0 !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

.mb-3 {
    margin-bottom: 1rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.me-2 {
    margin-right: 0.5rem !important;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.col-lg-8 {
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
    padding: 0 15px;
}

.col-lg-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding: 0 15px;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 15px;
}

@media (max-width: 992px) {

    .col-lg-8,
    .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.w-100 {
    width: 100% !important;
}

.d-flex {
    display: flex !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.align-items-center {
    align-items: center !important;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
    font-size: 0.9rem;
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: white;
}

.btn-outline-info {
    background: transparent;
    border: 2px solid var(--info);
    color: var(--info);
}

.btn-outline-info:hover {
    background: var(--info);
    color: white;
}

.btn-outline-success {
    background: transparent;
    border: 2px solid var(--success);
    color: var(--success);
}

.btn-outline-success:hover {
    background: var(--success);
    color: white;
}
</style>
@endsection

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="display: flex; gap: 8px; list-style: none; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('digital.collaborator.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item" style="color: var(--gray);">/</li>
                <li class="breadcrumb-item"><a href="{{ route('digital.collaborator.questionnaires.index') }}">Angket</a></li>
                <li class="breadcrumb-item" style="color: var(--gray);">/</li>
                <li class="breadcrumb-item active" style="color: var(--gray);">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('digital.collaborator.questionnaires.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('digital.collaborator.questionnaires.update', $questionnaire->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nama Angket <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $questionnaire->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            value="{{ old('slug', $questionnaire->slug) }}">
                        <small class="text-muted">Kosongkan untuk generate otomatis dari nama</small>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $questionnaire->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instruksi Pengisian</label>
                        <textarea name="instructions" rows="4"
                            class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions', $questionnaire->instructions) }}</textarea>
                        @error('instructions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tipe Angket</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="burnout"
                                        {{ old('type', $questionnaire->type) == 'burnout' ? 'selected' : '' }}>Burnout
                                    </option>
                                    <option value="stress"
                                        {{ old('type', $questionnaire->type) == 'stress' ? 'selected' : '' }}>Stress
                                    </option>
                                    <option value="anxiety"
                                        {{ old('type', $questionnaire->type) == 'anxiety' ? 'selected' : '' }}>Anxiety
                                    </option>
                                    <option value="depression"
                                        {{ old('type', $questionnaire->type) == 'depression' ? 'selected' : '' }}>
                                        Depression</option>
                                    <option value="personality"
                                        {{ old('type', $questionnaire->type) == 'personality' ? 'selected' : '' }}>
                                        Personality</option>
                                    <option value="general"
                                        {{ old('type', $questionnaire->type) == 'general' ? 'selected' : '' }}>General
                                    </option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Estimasi Waktu (menit)</label>
                                <input type="number" name="duration_minutes"
                                    class="form-control @error('duration_minutes') is-invalid @enderror"
                                    value="{{ old('duration_minutes', $questionnaire->duration_minutes) }}" min="1">
                                @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-check" style="margin-top: 10px;">
                        <input type="checkbox" name="has_dimensions" value="1" class="form-check-input"
                            id="hasDimensions"
                            {{ old('has_dimensions', $questionnaire->has_dimensions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasDimensions">
                            Angket memiliki dimensi/subskala
                        </label>
                    </div>
                </div>
            </div>

            <!-- AI Configuration -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 style="color: white;"><i class="fas fa-robot me-2"></i>Konfigurasi AI Analysis</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        AI Analysis menggunakan Claude AI untuk memberikan interpretasi mendalam, rekomendasi personal,
                        dan rencana aksi berdasarkan hasil angket.
                    </div>

                    <div class="form-check form-switch mb-4" style="display: flex; align-items: flex-start; gap: 15px;">
                        <input type="checkbox" name="ai_enabled" value="1" class="form-check-input" id="aiEnabled"
                            style="width: 50px; height: 26px;"
                            {{ old('ai_enabled', $questionnaire->ai_enabled ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aiEnabled" style="flex: 1;">
                            <strong>Aktifkan AI Analysis</strong>
                            <br><small class="text-muted">Hasil angket akan dianalisis oleh AI untuk memberikan
                                interpretasi yang lebih personal</small>
                        </label>
                    </div>

                    <div id="aiSettings"
                        style="{{ old('ai_enabled', $questionnaire->ai_enabled ?? true) ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label class="form-label">Persona AI</label>
                            <select name="ai_persona" class="form-select @error('ai_persona') is-invalid @enderror">
                                <option value="psikolog"
                                    {{ old('ai_persona', $questionnaire->ai_persona ?? 'psikolog') == 'psikolog' ? 'selected' : '' }}>
                                    🧠 Psikolog Profesional
                                </option>
                                <option value="konselor"
                                    {{ old('ai_persona', $questionnaire->ai_persona) == 'konselor' ? 'selected' : '' }}>
                                    💬 Konselor
                                </option>
                                <option value="coach"
                                    {{ old('ai_persona', $questionnaire->ai_persona) == 'coach' ? 'selected' : '' }}>
                                    🎯 Life Coach
                                </option>
                                <option value="mentor"
                                    {{ old('ai_persona', $questionnaire->ai_persona) == 'mentor' ? 'selected' : '' }}>
                                    👨‍🏫 Mentor Akademik
                                </option>
                                <option value="hr"
                                    {{ old('ai_persona', $questionnaire->ai_persona) == 'hr' ? 'selected' : '' }}>
                                    👔 HR Professional
                                </option>
                            </select>
                            <small class="text-muted">Persona menentukan gaya bahasa dan fokus analisis AI</small>
                            @error('ai_persona')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Context Tambahan untuk AI</label>
                            <textarea name="ai_context" rows="5"
                                class="form-control @error('ai_context') is-invalid @enderror"
                                placeholder="Berikan instruksi atau konteks tambahan untuk AI...">{{ old('ai_context', $questionnaire->ai_context) }}</textarea>
                            <small class="text-muted">
                                Contoh: "Fokus pada aspek akademik mahasiswa", "Berikan rekomendasi yang bisa dilakukan
                                sendiri di rumah", "Gunakan bahasa yang mudah dipahami remaja"
                            </small>
                            @error('ai_context')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning" style="margin-bottom: 0;">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Catatan Biaya:</strong>
                            <br>Setiap analisis AI menggunakan API Claude yang memiliki biaya. Estimasi ~Rp 500-1500 per
                            analisis tergantung kompleksitas.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3><i class="fas fa-toggle-on me-2"></i>Status</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch" style="display: flex; align-items: center; gap: 15px;">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                            style="width: 50px; height: 26px;"
                            {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Aktif</label>
                    </div>
                    <small class="text-muted" style="display: block; margin-top: 10px;">Angket yang tidak aktif tidak
                        akan muncul di katalog</small>
                </div>
            </div>

            <!-- Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3><i class="fas fa-chart-bar me-2"></i>Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Dimensi:</span>
                        <strong>{{ $questionnaire->dimensions->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Pertanyaan:</span>
                        <strong>{{ $questionnaire->questions->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Response:</span>
                        <strong>{{ $questionnaire->responses->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Response Selesai:</span>
                        <strong>{{ $questionnaire->responses->where('is_completed', true)->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3><i class="fas fa-link me-2"></i>Quick Links</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('digital.collaborator.questions.index', ['questionnaire_id' => $questionnaire->id]) }}"
                        class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-question-circle"></i> Kelola Pertanyaan
                    </a>
                    <a href="{{ route('digital.collaborator.dimensions.index', ['questionnaire_id' => $questionnaire->id]) }}"
                        class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-layer-group"></i> Kelola Dimensi
                    </a>
                    <a href="{{ route('digital.collaborator.responses.index', ['questionnaire_id' => $questionnaire->id]) }}"
                        class="btn btn-outline-success w-100">
                        <i class="fas fa-poll"></i> Lihat Response
                    </a>
                </div>
            </div>

            <!-- Submit -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('aiEnabled').addEventListener('change', function() {
    document.getElementById('aiSettings').style.display = this.checked ? '' : 'none';
});
</script>
@endsection