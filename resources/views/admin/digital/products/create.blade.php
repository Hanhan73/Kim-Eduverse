@extends('layouts.admin-digital')

@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>{{ isset($product) ? 'Edit Product' : 'Tambah Product Baru' }}</h1>
            <p>{{ isset($product) ? 'Update informasi produk' : 'Buat produk digital baru' }}</p>
        </div>
        <a href="{{ route('admin.digital.products.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ isset($product) ? route('admin.digital.products.update', $product->id) : route('admin.digital.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-card">
                <h3>Informasi Produk</h3>

                <div class="form-group">
                    <label for="name">Nama Produk <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="Auto-generated dari nama">
                    @error('slug')<span class="error">{{ $message }}</span>@enderror
                    <small>Kosongkan untuk auto-generate</small>
                </div>

                <div class="form-group">
                    <label for="short_description">Deskripsi Singkat</label>
                    <textarea id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    @error('short_description')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Lengkap <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="8" required>{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-sidebar">
                <div class="form-card">
                    <h3>Kategori & Tipe</h3>

                    <div class="form-group">
                        <label for="category_id">Kategori <span class="required">*</span></label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="type">Tipe Produk <span class="required">*</span></label>
                        <select id="type" name="type" required onchange="toggleQuestionnaireField()">
                            <option value="">Pilih Tipe</option>
                            <option value="questionnaire" {{ old('type', $product->type ?? '') == 'questionnaire' ? 'selected' : '' }}>Questionnaire</option>
                            <option value="module" {{ old('type', $product->type ?? '') == 'module' ? 'selected' : '' }}>Learning Module</option>
                            <option value="ebook" {{ old('type', $product->type ?? '') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                            <option value="video" {{ old('type', $product->type ?? '') == 'video' ? 'selected' : '' }}>Video Course</option>
                            <option value="template" {{ old('type', $product->type ?? '') == 'template' ? 'selected' : '' }}>Template</option>
                            <option value="other" {{ old('type', $product->type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" id="questionnaire-field" style="display: none;">
                        <label for="questionnaire_id">Questionnaire</label>
                        <select id="questionnaire_id" name="questionnaire_id">
                            <option value="">Pilih Questionnaire</option>
                            @foreach($questionnaires as $q)
                            <option value="{{ $q->id }}" {{ old('questionnaire_id', $product->questionnaire_id ?? '') == $q->id ? 'selected' : '' }}>
                                {{ $q->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga (Rp) <span class="required">*</span></label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="1000" required>
                        @error('price')<span class="error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-card">
                    <h3>Media</h3>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        @if(isset($product) && $product->thumbnail)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Current thumbnail">
                        </div>
                        @endif
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')<span class="error">{{ $message }}</span>@enderror
                        <small>Max 2MB. Format: JPG, PNG</small>
                    </div>

                    <div class="form-group">
                        <label for="file_url">File URL / Download Link</label>
                        <input type="url" id="file_url" name="file_url" value="{{ old('file_url', $product->file_url ?? '') }}" placeholder="https://">
                        @error('file_url')<span class="error">{{ $message }}</span>@enderror
                        <small>Link Google Drive, Dropbox, dll</small>
                    </div>
                </div>

                <div class="form-card">
                    <h3>Status</h3>

                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Active (tampil di katalog)</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                        <label for="is_featured">Featured (tampil di homepage)</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 2rem;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        border: 2px solid #667eea;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-card h3 {
        font-size: 1.2rem;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .required {
        color: #e53e3e;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="url"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
    }

    .form-group input[type="file"] {
        border: 2px dashed #cbd5e0;
        padding: 15px;
        border-radius: 8px;
        width: 100%;
    }

    .form-group small {
        display: block;
        color: #a0aec0;
        margin-top: 5px;
        font-size: 0.85rem;
    }

    .error {
        color: #e53e3e;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .current-image {
        margin-bottom: 15px;
    }

    .current-image img {
        width: 200px;
        height: auto;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .form-sidebar {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-sidebar {
            position: static;
        }
    }
</style>

<script>
    function toggleQuestionnaireField() {
        const typeSelect = document.getElementById('type');
        const questionnaireField = document.getElementById('questionnaire-field');
        
        if (typeSelect.value === 'questionnaire') {
            questionnaireField.style.display = 'block';
        } else {
            questionnaireField.style.display = 'none';
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', toggleQuestionnaireField);

    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection
