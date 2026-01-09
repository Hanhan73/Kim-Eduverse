@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Edit Produk</h1>
        <div class="user-info">
            <a href="{{ route('digital.collaborator.products.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Form Edit Produk</h2>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Ada kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('digital.collaborator.products.update', $product) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nama Produk <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="name"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    value="{{ old('name', $product->name) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                        Kategori <span style="color: var(--danger);">*</span>
                    </label>
                    <select name="category_id"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                        required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                        Tipe Produk <span style="color: var(--danger);">*</span>
                    </label>
                    <select name="type" id="productType"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                        required>
                        <option value="questionnaire"
                            {{ old('type', $product->type) == 'questionnaire' ? 'selected' : '' }}>Angket/Questionnaire
                        </option>
                        <option value="ebook" {{ old('type', $product->type) == 'ebook' ? 'selected' : '' }}>E-Book
                            (PDF)</option>
                        <option value="video" {{ old('type', $product->type) == 'video' ? 'selected' : '' }}>Video
                        </option>
                        <option value="template" {{ old('type', $product->type) == 'template' ? 'selected' : '' }}>
                            Template</option>
                        <option value="module" {{ old('type', $product->type) == 'module' ? 'selected' : '' }}>Modul
                        </option>
                        <option value="seminar" {{ old('type', $product->type) == 'seminar' ? 'selected' : '' }}>Seminar
                            On-Demand</option>
                        <option value="other" {{ old('type', $product->type) == 'other' ? 'selected' : '' }}>Lainnya
                        </option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Deskripsi Singkat (Opsional)
                </label>
                <input type="text" name="short_description"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    value="{{ old('short_description', $product->short_description) }}" maxlength="500">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Deskripsi Lengkap <span style="color: var(--danger);">*</span>
                </label>
                <textarea name="description" rows="5"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                    required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                        Harga <span style="color: var(--danger);">*</span>
                    </label>
                    <div style="position: relative;">
                        <span
                            style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--gray); font-weight: 600;">Rp</span>
                        <input type="number" name="price"
                            style="width: 100%; padding: 12px 12px 12px 45px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                            min="0" value="{{ old('price', $product->price) }}" required>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                        Durasi (Menit)
                    </label>
                    <input type="number" name="duration_minutes"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                        min="1" value="{{ old('duration_minutes', $product->duration_minutes) }}">
                </div>
            </div>

            <!-- Questionnaire Field -->
            <div id="questionnaireField" style="margin-bottom: 20px; display: none;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Pilih Angket
                </label>
                <select name="questionnaire_id"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    <option value="">Pilih Angket (Opsional)</option>
                    @foreach($questionnaires as $questionnaire)
                    <option value="{{ $questionnaire->id }}"
                        {{ old('questionnaire_id', $product->questionnaire_id) == $questionnaire->id ? 'selected' : '' }}>
                        {{ $questionnaire->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    URL File (Google Drive / YouTube)
                </label>
                <input type="url" name="file_url"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    value="{{ old('file_url', $product->file_url) }}">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Upload Thumbnail Baru (Opsional)
                </label>
                @if($product->thumbnail)
                <div style="margin-bottom: 10px;">
                    <img src="{{ Storage::url($product->thumbnail) }}"
                        style="max-width: 200px; border-radius: 8px; border: 2px solid #e2e8f0;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-top: 5px;">Thumbnail saat ini</p>
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Upload gambar baru untuk mengganti thumbnail lama
                </small>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Update Produk
                </button>
                <a href="{{ route('digital.collaborator.products.index') }}" class="btn-secondary"
                    style="flex: 0.3; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('productType');
    const questionnaireField = document.getElementById('questionnaireField');

    function toggleQuestionnaireField() {
        if (typeSelect.value === 'questionnaire') {
            questionnaireField.style.display = 'block';
        } else {
            questionnaireField.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleQuestionnaireField);
    toggleQuestionnaireField();
});
</script>
@endpush