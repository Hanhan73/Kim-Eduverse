@extends('layouts.admin-digital')

@section('title', isset($category) ? 'Edit Category' : 'Create Category')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>{{ isset($category) ? 'Edit Category' : 'Tambah Category Baru' }}</h1>
            <p>{{ isset($category) ? 'Update informasi kategori' : 'Buat kategori produk baru' }}</p>
        </div>
        <a href="{{ route('admin.digital.categories.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ isset($category) ? route('admin.digital.categories.update', $category->id) : route('admin.digital.categories.store') }}">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="form-card">
                <h3>Informasi Kategori</h3>

                <div class="form-group">
                    <label for="name">Nama Kategori <span class="required">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name ?? '') }}" 
                           required
                           placeholder="e.g. Tes Kepribadian">
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" hidden>
                    <label for="slug">Slug</label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $category->slug ?? '') }}" 
                           placeholder="Auto-generated dari nama">
                    @error('slug')<span class="error">{{ $message }}</span>@enderror
                    <small>Kosongkan untuk auto-generate (URL-friendly)</small>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Deskripsi singkat kategori...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')<span class="error">{{ $message }}</span>@enderror>
                </div>

                <div class="form-group">
                    <label for="icon">Icon (Font Awesome)</label>
                    <input type="text" 
                           id="icon" 
                           name="icon" 
                           value="{{ old('icon', $category->icon ?? '') }}" 
                           placeholder="e.g. fas fa-brain">
                    @error('icon')<span class="error">{{ $message }}</span>@enderror>
                    <small>
                        Lihat icon di 
                        <a href="https://fontawesome.com/icons" target="_blank" style="color: #667eea;">
                            Font Awesome Icons
                        </a>
                    </small>
                </div>

                <div class="form-check">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active">Active (tampil di katalog)</label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> {{ isset($category) ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>

        <!-- Icon Preview -->
        <div class="form-card preview-card">
            <h3>Preview Icon</h3>
            <div class="icon-preview">
                <div class="preview-box" id="iconPreview">
                    <i class="fas fa-folder" id="previewIcon"></i>
                </div>
                <p id="iconClass">fas fa-folder</p>
            </div>
            <small style="color: #718096; display: block; margin-top: 15px;">
                Preview akan muncul saat Anda mengetik class icon
            </small>
        </div>
    </div>
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

    .form-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
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

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
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

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .preview-card {
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    .icon-preview {
        text-align: center;
        padding: 20px;
    }

    .preview-box {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .preview-box i {
        font-size: 4rem;
        color: white;
    }

    #iconClass {
        color: #667eea;
        font-weight: 600;
        font-family: monospace;
        background: #f7fafc;
        padding: 8px 15px;
        border-radius: 8px;
        display: inline-block;
    }

    @media (max-width: 1024px) {
        .form-container {
            grid-template-columns: 1fr;
        }
        
        .preview-card {
            position: static;
        }
    }
</style>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });

    // Icon preview
    document.getElementById('icon').addEventListener('input', function() {
        const iconClass = this.value.trim();
        const previewIcon = document.getElementById('previewIcon');
        const iconClassDisplay = document.getElementById('iconClass');
        
        if (iconClass) {
            previewIcon.className = iconClass;
            iconClassDisplay.textContent = iconClass;
        } else {
            previewIcon.className = 'fas fa-folder';
            iconClassDisplay.textContent = 'fas fa-folder';
        }
    });

    // Trigger on page load if editing
    window.addEventListener('DOMContentLoaded', function() {
        const iconInput = document.getElementById('icon');
        if (iconInput.value) {
            iconInput.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection
