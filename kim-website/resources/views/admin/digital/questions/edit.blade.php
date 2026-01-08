@extends('layouts.admin-digital')

@section('title', 'Edit Pertanyaan - Admin Digital')
@section('page-title', 'Edit Pertanyaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit Pertanyaan</h3>
        <a href="{{ route('admin.digital.questions.index', ['questionnaire_id' => $question->questionnaire_id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="questionnaire_id">Angket <span style="color: var(--danger);">*</span></label>
                    <select name="questionnaire_id" id="questionnaire_id" class="form-control" required onchange="loadDimensions()">
                        @foreach($questionnaires as $questionnaire)
                            <option value="{{ $questionnaire->id }}" data-has-dimensions="{{ $questionnaire->has_dimensions ? '1' : '0' }}" {{ old('questionnaire_id', $question->questionnaire_id) == $questionnaire->id ? 'selected' : '' }}>
                                {{ $questionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="dimension_id">Dimensi</label>
                    <select name="dimension_id" id="dimension_id" class="form-control">
                        <option value="">Tanpa Dimensi</option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension->id }}" {{ old('dimension_id', $question->dimension_id) == $dimension->id ? 'selected' : '' }}>
                                {{ $dimension->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="question_text">Teks Pertanyaan <span style="color: var(--danger);">*</span></label>
                <textarea name="question_text" id="question_text" class="form-control" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="order">Urutan</label>
                    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $question->order) }}" min="0">
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check" style="margin-top: 10px;">
                        <input type="checkbox" name="is_reverse_scored" id="is_reverse_scored" value="1" {{ old('is_reverse_scored', $question->is_reverse_scored) ? 'checked' : '' }}>
                        <label for="is_reverse_scored">Reverse Scored</label>
                    </div>
                    <small style="color: var(--gray);">Centang jika skor dibalik (5 menjadi 1, dst.)</small>
                </div>
            </div>

            <!-- Current Options -->
            <div style="background: #f7fafc; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="margin-bottom: 15px; color: var(--dark);">
                    <i class="fas fa-list-ol"></i> Opsi Jawaban (Skala Likert)
                </h4>
                @if($question->options)
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @foreach($question->options as $value => $label)
                            <span class="badge badge-primary" style="padding: 8px 15px;">{{ $value }} - {{ $label }}</span>
                        @endforeach
                    </div>
                @else
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <span class="badge badge-primary" style="padding: 8px 15px;">1 - Sangat Tidak Setuju</span>
                        <span class="badge badge-primary" style="padding: 8px 15px;">2 - Tidak Setuju</span>
                        <span class="badge badge-primary" style="padding: 8px 15px;">3 - Netral</span>
                        <span class="badge badge-primary" style="padding: 8px 15px;">4 - Setuju</span>
                        <span class="badge badge-primary" style="padding: 8px 15px;">5 - Sangat Setuju</span>
                    </div>
                @endif
            </div>

            <!-- Scoring Info -->
            @if($question->is_reverse_scored)
            <div style="background: #fefcbf; border: 1px solid #f6e05e; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                <p style="color: #744210; margin: 0;">
                    <i class="fas fa-exchange-alt"></i>
                    <strong>Reverse Scored:</strong> Jawaban 1 akan dihitung sebagai 5, jawaban 2 sebagai 4, dst.
                </p>
            </div>
            @endif

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.digital.questions.index', ['questionnaire_id' => $question->questionnaire_id]) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Meta Info -->
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3><i class="fas fa-info-circle"></i> Informasi</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <div>
                <small style="color: var(--gray);">ID</small>
                <div style="font-weight: 600;">{{ $question->id }}</div>
            </div>
            <div>
                <small style="color: var(--gray);">Dibuat</small>
                <div>{{ $question->created_at->format('d M Y H:i') }}</div>
            </div>
            <div>
                <small style="color: var(--gray);">Terakhir diubah</small>
                <div>{{ $question->updated_at->format('d M Y H:i') }}</div>
            </div>
            <div>
                <small style="color: var(--gray);">Angket</small>
                <div>
                    <a href="{{ route('admin.digital.questionnaires.show', $question->questionnaire_id) }}" style="color: var(--primary);">
                        {{ $question->questionnaire->name ?? '-' }}
                    </a>
                </div>
            </div>
        </div>
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
                options += `<option value="${dim.id}">${dim.name}</option>`;
            });
            dimensionSelect.innerHTML = options;
        });
}
</script>
@endsection
