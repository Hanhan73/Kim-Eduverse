@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Tambah Produk Baru</h1>
        <div class="user-info">
            <a href="{{ route('digital.collaborator.products.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="content-section">
        <div class="section-header">
            <h2>Form Produk</h2>
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

        <form action="{{ route('digital.collaborator.products.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nama Produk <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="name"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    placeholder="Contoh: CEKMA Minat Bakat Siswa" value="{{ old('name') }}" required>
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <option value="">Pilih Tipe</option>
                        <option value="questionnaire" {{ old('type') == 'questionnaire' ? 'selected' : '' }}>
                            CEKMA/Questionnaire</option>
                        <option value="ebook" {{ old('type') == 'ebook' ? 'selected' : '' }}>E-Book (PDF)</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="template" {{ old('type') == 'template' ? 'selected' : '' }}>Template</option>
                        <option value="module" {{ old('type') == 'module' ? 'selected' : '' }}>Modul</option>
                        <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>On-demand seminar
                        </option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Deskripsi Singkat (Opsional)
                </label>
                <input type="text" name="short_description"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    placeholder="Deskripsi singkat untuk tampilan card" value="{{ old('short_description') }}"
                    maxlength="500">
                <small style="color: var(--gray); display: block; margin-top: 5px;">Max 500 karakter</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Deskripsi Lengkap <span style="color: var(--danger);">*</span>
                </label>
                <textarea name="description" rows="5"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                    placeholder="Jelaskan detail produk Anda..." required>{{ old('description') }}</textarea>
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
                            placeholder="75000" min="0" value="{{ old('price') }}" required>
                    </div>
                    <small style="color: var(--gray); display: block; margin-top: 5px;">
                        Anda akan mendapat 70% dari harga jual
                    </small>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                        Durasi (Menit) - Opsional
                    </label>
                    <input type="number" name="duration_minutes"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                        placeholder="60" min="1" value="{{ old('duration_minutes') }}">
                    <small style="color: var(--gray); display: block; margin-top: 5px;">
                        Untuk video/seminar
                    </small>
                </div>
            </div>

            <!-- Questionnaire Field (conditional) -->
            <div id="questionnaireField" style="margin-bottom: 20px; display: none;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Pilih CEKMA
                </label>
                <select name="questionnaire_id"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    <option value="">Pilih CEKMA (Opsional)</option>
                    @foreach($questionnaires as $questionnaire)
                    <option value="{{ $questionnaire->id }}"
                        {{ old('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                        {{ $questionnaire->name }}
                    </option>
                    @endforeach
                </select>
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Hubungkan produk dengan cekma yang sudah ada
                </small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    URL File (Google Drive / YouTube)
                </label>
                <input type="url" name="file_url"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    placeholder="https://drive.google.com/... atau https://youtube.com/..."
                    value="{{ old('file_url') }}">
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Upload file ke Google Drive (untuk PDF/Ebook) atau YouTube (untuk Video)
                </small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Upload Thumbnail
                </label>
                <input type="file" name="thumbnail" accept="image/*"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Format: JPG, PNG. Max 2MB
                </small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span style="font-weight: 600;">Aktifkan produk setelah dibuat</span>
                </label>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Simpan Produk
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
    toggleQuestionnaireField(); // Initial check
});
</script>
@endpush