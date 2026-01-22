@extends('layouts.instructor')

@section('title', 'Create New Course - KIM EDUVERSE')

<style>
    /* --- Base Variables & Reset --- */
    :root {
        --primary: #667eea;
        --primary-dark: #5a67d8;
        --secondary: #764ba2;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --dark: #2d3748;
        --gray: #718096;
        --light: #f7fafc;
        --border: #e2e8f0;
        --bg: #f8f9fa;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: var(--bg);
        color: var(--dark);
    }

    /* --- Layout --- */
    .create-wrapper {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    /* --- Cards --- */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }

    .card-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .card-header p {
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* --- Forms --- */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .form-group label span.req {
        color: var(--danger);
    }

    .form-group .form-hint {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* --- Toggle Switch Custom Style --- */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e0;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: var(--primary);
    }

    input:focus+.slider {
        box-shadow: 0 0 1px var(--primary);
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }

    /* --- Buttons --- */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 10px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--dark);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--light);
    }

    /* --- Image Upload --- */
    .image-upload-area {
        border: 2px dashed var(--border);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--light);
        position: relative;
        overflow: hidden;
    }

    .image-upload-area:hover {
        border-color: var(--primary);
        background: #ebf4ff;
    }

    .image-preview {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
        z-index: 5;
    }

    .upload-placeholder i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .upload-placeholder p {
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* --- Alert Box --- */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .alert-danger {
        background: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    .alert ul {
        margin-top: 0.5rem;
        margin-left: 1.2rem;
    }
</style>

@section('content')
<div class="create-wrapper">

    <!-- Header Section -->
    <div class="card">
        <div class="card-header">
            <h1>Create New Course</h1>
            <p>Mulai perjalanan instruksimu dengan membuat materi berkualitas tinggi.</p>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    <strong><i class="fas fa-exclamation-circle"></i> Terjadi kesalahan:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('edutech.instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <!-- Title -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Course Title <span class="req">*</span></label>
                        <input type="text" name="title" class="form-control"
                            placeholder="Contoh: Mastering Laravel 10 for Beginners" value="{{ old('title') }}"
                            required>
                        <div class="form-hint">Gunakan judul yang jelas dan menarik minat.</div>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label>Category <span class="req">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Level -->
                    <div class="form-group">
                        <label>Level <span class="req">*</span></label>
                        <select name="level" class="form-control" required>
                            <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner
                                (Pemula)</option>
                            <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                Intermediate (Menengah)</option>
                            <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced
                                (Lanjutan)</option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label>Price (IDR) <span class="req">*</span></label>
                        <input type="number" name="price" class="form-control" placeholder="0"
                            value="{{ old('price', 0) }}" min="0" required>
                        <div class="form-hint">Set 0 jika course ini gratis.</div>
                    </div>

                    <!-- Duration -->
                    <div class="form-group">
                        <label>Estimasi Durasi (Jam) <span class="req">*</span></label>
                        <input type="number" name="duration_hours" class="form-control" placeholder="10"
                            value="{{ old('duration_hours') }}" min="1" required>
                    </div>

                    <!-- FITUR BARU: DEGREE / GELAR -->
                    <div class="form-group"
                        style="grid-column: span 2; background: #fff; padding: 1rem; border: 1px solid var(--border); border-radius: 8px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="margin: 0; font-size: 1rem;">Apakah Course ini memberikan Gelar / Sertifikat
                                Resmi?</label>
                            <label class="switch">
                                <input type="checkbox" name="has_degree" id="hasDegreeToggle">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div id="degreeTitleGroup" style="display: none; transition: all 0.3s; margin-top: 10px;">
                            <label>Judul Gelar / Sertifikat <span class="req">*</span></label>
                            <input type="text" name="degree_title" class="form-control"
                                value="{{ old('degree_title') }}"
                                placeholder="Contoh: Sertifikat Kompetensi Fullstack Developer">
                            <div class="form-hint">Judul ini akan dicetak pada sertifikat kelulusan siswa.</div>
                        </div>
                    </div>
                    <!-- END FITUR BARU -->

                </div>

                <!-- Description -->
                <div class="form-group" style="margin-top: 1rem;">
                    <label>Course Description <span class="req">*</span></label>
                    <textarea name="description" class="form-control" rows="4"
                        placeholder="Jelaskan apa yang akan dipelajari siswa..."
                        required>{{ old('description') }}</textarea>
                </div>

                <!-- Thumbnail Upload -->
                <div class="form-group">
                    <label>Course Thumbnail</label>
                    <div class="image-upload-area" onclick="document.getElementById('thumbnailInput').click()">
                        <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" style="display: none;"
                            onchange="previewImage(event)">

                        <img id="imgPreview" class="image-preview">

                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Klik untuk upload gambar</strong></p>
                            <p style="font-size: 0.8rem;">Rekomendasi: 1280x720px (JPG/PNG)</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('edutech.instructor.courses') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Create Course
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    // Image Preview Logic
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imgPreview');
        const placeholder = document.querySelector('.upload-placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.opacity = '0';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            placeholder.style.opacity = '1';
        }
    }

    // Degree Toggle Logic
    const degreeToggle = document.getElementById('hasDegreeToggle');
    const degreeTitleGroup = document.getElementById('degreeTitleGroup');
    const degreeInput = document.querySelector('input[name="degree_title"]');

    if (degreeToggle) {
        degreeToggle.addEventListener('change', function() {
            if (this.checked) {
                degreeTitleGroup.style.display = 'block';
                degreeInput.setAttribute('required', 'required');
            } else {
                degreeTitleGroup.style.display = 'none';
                degreeInput.removeAttribute('required');
                degreeInput.value = ''; // Reset value if unchecked
            }
        });
    }
</script>
@endsection