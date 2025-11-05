<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Same CSS as create.blade.php */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: #f8f9fa; color: #2d3748; }
        .admin-layout { display: grid; grid-template-columns: 250px 1fr; min-height: 100vh; }
        .sidebar { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 2rem 0; }
        .sidebar-brand { padding: 0 1.5rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 2rem; }
        .sidebar-brand h2 { font-size: 1.5rem; font-weight: 700; }
        .sidebar-brand p { font-size: 0.85rem; opacity: 0.8; margin-top: 0.5rem; }
        .sidebar-menu { list-style: none; }
        .sidebar-item { margin-bottom: 0.5rem; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px 1.5rem; color: white; text-decoration: none; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); border-left: 3px solid white; }
        .main-content { padding: 2rem; max-width: 1200px; }
        .page-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
        .back-btn { width: 40px; height: 40px; border-radius: 10px; background: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; color: #4a5568; transition: all 0.3s; }
        .back-btn:hover { background: #e2e8f0; transform: translateX(-3px); }
        .page-header h1 { font-size: 2rem; font-weight: 700; }
        .form-container { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
        .form-header { padding: 2rem; border-bottom: 1px solid #e2e8f0; }
        .form-header h2 { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem; }
        .form-header p { color: #718096; }
        .form-body { padding: 2rem; }
        .form-grid { display: grid; gap: 1.5rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-label { font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 8px; }
        .required { color: #e53e3e; }
        .form-control { padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; transition: all 0.3s; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        .form-control.is-invalid { border-color: #e53e3e; }
        textarea.form-control { resize: vertical; min-height: 120px; }
        textarea.form-control.large { min-height: 400px; }
        .error-text { color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem; }
        .form-help { color: #718096; font-size: 0.875rem; margin-top: 0.5rem; }
        .image-upload { border: 2px dashed #cbd5e0; border-radius: 10px; padding: 2rem; text-align: center; transition: all 0.3s; cursor: pointer; position: relative; }
        .image-upload:hover { border-color: #667eea; background: #f7fafc; }
        .image-upload input[type="file"] { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; top: 0; left: 0; }
        .upload-icon { font-size: 3rem; color: #cbd5e0; margin-bottom: 1rem; }
        .image-preview { margin-top: 1rem; }
        .image-preview img { max-width: 100%; max-height: 300px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .checkbox-wrapper { display: flex; align-items: center; gap: 12px; padding: 1rem; background: #f7fafc; border-radius: 10px; }
        .checkbox-wrapper input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
        .checkbox-wrapper label { cursor: pointer; font-weight: 500; user-select: none; }
        .form-actions { display: flex; gap: 1rem; padding: 2rem; background: #f7fafc; border-top: 1px solid #e2e8f0; }
        .btn { padding: 12px 24px; border-radius: 10px; border: none; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; font-size: 1rem; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); }
        .btn-secondary { background: #e2e8f0; color: #4a5568; }
        .btn-secondary:hover { background: #cbd5e0; }
        .btn-danger { background: #e53e3e; color: white; }
        .btn-danger:hover { background: #c53030; }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .alert { padding: 1rem 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 12px; }
        .alert-error { background: #fff5f5; color: #c53030; border-left: 4px solid #e53e3e; }
        .char-counter { text-align: right; font-size: 0.875rem; color: #718096; margin-top: 0.5rem; }
        .current-image { margin-bottom: 1rem; }
        .current-image img { max-width: 100%; max-height: 200px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .current-image p { margin-top: 0.5rem; color: #718096; font-size: 0.875rem; }
        @media (max-width: 968px) {
            .admin-layout { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .form-row { grid-template-columns: 1fr; }
            .main-content { padding: 1rem; }
            .form-body { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-newspaper"></i> PT KIM</h2>
                <p>Admin Panel</p>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('admin.articles.index') }}" class="sidebar-link active">
                        <i class="fas fa-file-alt"></i>
                        <span>Artikel</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('home') }}" class="sidebar-link">
                        <i class="fas fa-globe"></i>
                        <span>Lihat Website</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <a href="{{ route('admin.articles.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Edit Artikel</h1>
            </div>

            @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>Oops! Ada yang salah:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="form-header">
                        <h2>Edit Artikel: {{ $article->title }}</h2>
                        <p>Perbarui informasi artikel</p>
                    </div>

                    <div class="form-body">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="title" class="form-label">
                                    Judul Artikel <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $article->title) }}" required>
                                @error('title')<span class="error-text">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category" class="form-label">Kategori <span class="required">*</span></label>
                                    <select id="category" name="category" class="form-control @error('category') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $article->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<span class="error-text">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="author" class="form-label">Penulis</label>
                                    <input type="text" id="author" name="author" class="form-control" value="{{ old('author', $article->author) }}">
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="excerpt" class="form-label">Ringkasan <span class="required">*</span></label>
                                <textarea id="excerpt" name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" maxlength="500" required>{{ old('excerpt', $article->excerpt) }}</textarea>
                                @error('excerpt')<span class="error-text">{{ $message }}</span>@enderror>
                                <div class="char-counter"><span id="excerptCount">0</span> / 500 karakter</div>
                            </div>

                            <div class="form-group full-width">
                                <label for="content" class="form-label">Konten Artikel <span class="required">*</span></label>
                                <textarea id="content" name="content" class="form-control large @error('content') is-invalid @enderror" required>{{ old('content', $article->content) }}</textarea>
                                @error('content')<span class="error-text">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Gambar Artikel</label>
                                
                                @if($article->image)
                                <div class="current-image">
                                    <img src="{{ asset($article->image) }}" alt="Current Image">
                                    <p><i class="fas fa-info-circle"></i> Upload gambar baru untuk mengganti</p>
                                </div>
                                @endif

                                <div class="image-upload">
                                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <p style="color: #4a5568; font-weight: 500;">Klik atau seret gambar baru</p>
                                    <p style="color: #718096; font-size: 0.875rem; margin-top: 0.5rem;">Format: JPG, PNG, GIF (Maks 2MB)</p>
                                </div>
                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview">
                                </div>
                                @error('image')<span class="error-text">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group full-width">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                    <label for="is_published"><i class="fas fa-globe"></i> Publikasikan artikel</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Artikel
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="button" class="btn btn-danger" onclick="if(confirm('Yakin ingin menghapus artikel ini?')) document.getElementById('deleteForm').submit();" style="margin-left: auto;">
                            <i class="fas fa-trash"></i> Hapus Artikel
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="deleteForm" action="{{ route('admin.articles.destroy', $article) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </main>
    </div>

    <script>
        const excerptField = document.getElementById('excerpt');
        const excerptCount = document.getElementById('excerptCount');
        if (excerptField) {
            excerptField.addEventListener('input', function() {
                excerptCount.textContent = this.value.length;
                excerptCount.style.color = this.value.length > 450 ? '#e53e3e' : '#718096';
            });
            excerptCount.textContent = excerptField.value.length;
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        document.getElementById('articleForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });
    </script>
</body>
</html>