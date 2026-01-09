@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Tambah {{ ucfirst($type) }}</h1>
        <div class="user-info">
            <a href="{{ route('digital.collaborator.products.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="content-section">
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

        <form action="{{ route('digital.collaborator.products.store-simple') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nama {{ ucfirst($type) }} <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="name"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    value="{{ old('name') }}" required placeholder="Contoh: Panduan Lengkap Manajemen Stres">
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
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Deskripsi Singkat
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
                <textarea name="description" rows="6"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                    placeholder="Jelaskan detail produk Anda..." required>{{ old('description') }}</textarea>
            </div>

            @if($type === 'video')
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Durasi Video (menit)
                </label>
                <input type="number" name="duration_minutes"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    placeholder="60" min="1" value="{{ old('duration_minutes') }}">
            </div>
            @endif

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    URL File (Google Drive / YouTube) <span style="color: var(--danger);">*</span>
                </label>
                <input type="url" name="file_url"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                    placeholder="https://{{ $type === 'video' ? 'youtube.com/...' : 'drive.google.com/...' }}"
                    value="{{ old('file_url') }}" required>
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    @if($type === 'video')
                    Upload video ke YouTube dan paste link di sini
                    @else
                    Upload file ke Google Drive dan paste link di sini (pastikan akses public)
                    @endif
                </small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Upload Thumbnail
                </label>
                <input type="file" name="thumbnail" accept="image/*"
                    style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Format: JPG, PNG. Max 2MB. Rekomendasi ukuran: 800x600px
                </small>
            </div>

            <!-- Info Box -->
            <div
                style="background: #f0f4ff; border-left: 4px solid var(--primary); padding: 15px 20px; border-radius: 8px; margin-bottom: 30px;">
                <div style="display: flex; gap: 15px; align-items: start;">
                    <i class="fas fa-info-circle"
                        style="color: var(--primary); font-size: 1.5rem; margin-top: 2px;"></i>
                    <div>
                        <h4 style="margin: 0 0 8px; color: var(--primary);">Tips untuk {{ ucfirst($type) }}</h4>
                        @if($type === 'ebook')
                        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
                            <li>Pastikan PDF berkualitas tinggi dan mudah dibaca</li>
                            <li>Berikan deskripsi lengkap tentang isi e-book</li>
                            <li>Upload ke Google Drive dengan akses "Anyone with the link"</li>
                        </ul>
                        @elseif($type === 'video')
                        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
                            <li>Upload video ke YouTube (bisa unlisted)</li>
                            <li>Pastikan kualitas video HD (720p atau lebih)</li>
                            <li>Tambahkan subtitle jika memungkinkan</li>
                        </ul>
                        @elseif($type === 'template')
                        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
                            <li>Sediakan template yang siap pakai</li>
                            <li>Berikan instruksi penggunaan yang jelas</li>
                            <li>Format file: DOC, XLS, PPT, atau PDF</li>
                        </ul>
                        @else
                        <ul style="margin: 0; padding-left: 20px; color: var(--dark);">
                            <li>Berikan konten yang berkualitas dan bermanfaat</li>
                            <li>Pastikan file dapat diakses dengan mudah</li>
                            <li>Sertakan panduan penggunaan jika diperlukan</li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Simpan {{ ucfirst($type) }}
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