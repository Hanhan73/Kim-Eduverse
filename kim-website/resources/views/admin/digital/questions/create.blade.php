@extends('admin.digital.layouts.app')

@section('title', 'Tambah Pertanyaan - Admin Digital')
@section('page-title', 'Tambah Pertanyaan Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Pertanyaan Baru</h3>
        <a href="{{ route('admin.digital.questions.index', request()->only(['questionnaire_id', 'dimension_id'])) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.questions.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="questionnaire_id">Angket <span style="color: var(--danger);">*</span></label>
                    <select name="questionnaire_id" id="questionnaire_id" class="form-control" required onchange="loadDimensions()">
                        <option value="">Pilih Angket</option>
                        @foreach($questionnaires as $questionnaire)
                            <option value="{{ $questionnaire->id }}" data-has-dimensions="{{ $questionnaire->has_dimensions ? '1' : '0' }}" {{ (old('questionnaire_id') ?? $selectedQuestionnaireId) == $questionnaire->id ? 'selected' : '' }}>
                                {{ $questionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('questionnaire_id')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group" id="dimension-group">
                    <label for="dimension_id">Dimensi</label>
                    <select name="dimension_id" id="dimension_id" class="form-control">
                        <option value="">Tanpa Dimensi</option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension->id }}" {{ (old('dimension_id') ?? $selectedDimensionId) == $dimension->id ? 'selected' : '' }}>
                                {{ $dimension->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('dimension_id')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="question_text">Teks Pertanyaan <span style="color: var(--danger);">*</span></label>
                <textarea name="question_text" id="question_text" class="form-control" rows="3" required placeholder="Tulis pertanyaan angket...">{{ old('question_text') }}</textarea>
                @error('question_text')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="order">Urutan</label>
                    <input type="number" name="order" id="order" class="form-control" value="{{ old('order') }}" min="0" placeholder="Auto">
                    <small style="color: var(--gray);">Kosongkan untuk urutan otomatis</small>
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check" style="margin-top: 10px;">
                        <input type="checkbox" name="is_reverse_scored" id="is_reverse_scored" value="1" {{ old('is_reverse_scored') ? 'checked' : '' }}>
                        <label for="is_reverse_scored">Reverse Scored</label>
                    </div>
                    <small style="color: var(--gray);">Centang jika skor dibalik (5 menjadi 1, dst.)</small>
                </div>
            </div>

            <!-- Options Preview -->
            <div style="background: #f7fafc; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="margin-bottom: 15px; color: var(--dark);">
                    <i class="fas fa-list-ol"></i> Opsi Jawaban (Skala Likert)
                </h4>
                <p style="color: var(--gray); margin-bottom: 15px;">
                    Secara default, pertanyaan menggunakan skala Likert 1-5:
                </p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <span class="badge badge-primary" style="padding: 8px 15px;">1 - Sangat Tidak Setuju</span>
                    <span class="badge badge-primary" style="padding: 8px 15px;">2 - Tidak Setuju</span>
                    <span class="badge badge-primary" style="padding: 8px 15px;">3 - Netral</span>
                    <span class="badge badge-primary" style="padding: 8px 15px;">4 - Setuju</span>
                    <span class="badge badge-primary" style="padding: 8px 15px;">5 - Sangat Setuju</span>
                </div>
            </div>

            <!-- Info -->
            <div style="background: #ebf8ff; border: 1px solid #90cdf4; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                <p style="color: #2b6cb0; margin: 0;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Tips:</strong> Gunakan pertanyaan yang jelas dan mudah dipahami. Hindari pertanyaan ganda (double-barreled questions).
                </p>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pertanyaan
                </button>
                <button type="submit" name="save_and_add" value="1" class="btn btn-success">
                    <i class="fas fa-plus"></i> Simpan & Tambah Lagi
                </button>
                <a href="{{ route('admin.digital.questions.index', request()->only(['questionnaire_id', 'dimension_id'])) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function loadDimensions() {
    const questionnaireId = document.getElementById('questionnaire_id').value;
    const dimensionSelect = document.getElementById('dimension_id');
    const selectedOption = document.querySelector(`#questionnaire_id option[value="${questionnaireId}"]`);
    const hasDimensions = selectedOption ? selectedOption.dataset.hasDimensions === '1' : false;
    
    if (!questionnaireId || !hasDimensions) {
        dimensionSelect.innerHTML = '<option value="">Tanpa Dimensi</option>';
        return;
    }
    
    fetch(`/admin/digital/questions/dimensions/${questionnaireId}`)
        .then(response => response.json())
        .then(dimensions => {
            let options = '<option value="">Tanpa Dimensi</option>';
            dimensions.forEach(dim => {
                options += `<option value="${dim.id}">${dim.name} (${dim.code})</option>`;
            });
            dimensionSelect.innerHTML = options;
        })
        .catch(err => {
            console.error('Error loading dimensions:', err);
        });
}

// Load dimensions on page load if questionnaire is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const questionnaireId = document.getElementById('questionnaire_id').value;
    if (questionnaireId) {
        // Keep current selection
    }
});
</script>
@endsection
