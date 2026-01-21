{{-- File: resources/views/admin/digital/seminars/_form.blade.php --}}
<form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="seminarForm">
    @csrf
    @if($method !== 'POST')
    @method($method)
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        <!-- Main Content -->
        <div>
            <!-- Basic Info -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Informasi Dasar</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Judul Seminar <span style="color: red;">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $seminar->title ?? '') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Deskripsi <span style="color: red;">*</span></label>
                        <textarea name="description" rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            required>{{ old('description', $seminar->description ?? '') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Harga (Rp) <span style="color: red;">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $seminar->price ?? 0) }}" min="0" step="1000" required>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Durasi (menit) <span style="color: red;">*</span></label>
                            <input type="number" name="duration_minutes"
                                class="form-control @error('duration_minutes') is-invalid @enderror"
                                value="{{ old('duration_minutes', $seminar->duration_minutes ?? 60) }}" min="1"
                                required>
                            @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Thumbnail</label>
                        <input type="file" name="thumbnail"
                            class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*"
                            onchange="previewImage(this)">
                        @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if(isset($seminar) && $seminar->thumbnail)
                        <div style="margin-top: 10px;">
                            <img src="{{ Storage::url($seminar->thumbnail) }}" id="imagePreview"
                                style="max-width: 200px; border-radius: 8px;" alt="Current thumbnail">
                        </div>
                        @else
                        <img id="imagePreview"
                            style="max-width: 200px; border-radius: 8px; margin-top: 10px; display: none;">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Instructor Info -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-tie"></i> Instruktur / Collaborator</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Collaborator <span style="color: red;">*</span></label>
                        <select name="collaborator_id" id="collaborator_id"
                            class="form-control @error('collaborator_id') is-invalid @enderror" required
                            onchange="updateInstructorInfo(this)">
                            <option value="">-- Pilih Collaborator --</option>
                            @foreach($collaborators as $collab)
                            <option value="{{ $collab->id }}" data-name="{{ $collab->name }}"
                                data-bio="{{ $collab->bio ?? '' }}"
                                {{ old('collaborator_id', $seminar->collaborator_id ?? '') == $collab->id ? 'selected' : '' }}>
                                {{ $collab->name }} ({{ $collab->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('collaborator_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Collaborator akan menjadi instruktur seminar ini</small>
                    </div>

                    <div
                        style="background: #e0f2fe; border: 2px solid #0284c7; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <div style="display: flex; gap: 10px; align-items: start;">
                            <i class="fas fa-info-circle" style="color: #0284c7; margin-top: 3px;"></i>
                            <div style="color: #0c4a6e;">
                                <strong>Info:</strong> Data nama dan bio akan otomatis terisi dari collaborator yang
                                dipilih. Anda bisa mengubahnya jika perlu untuk seminar spesifik ini.
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Nama Instruktur
                            <span style="color: #64748b; font-weight: normal;">(Opsional - Override)</span>
                        </label>
                        <input type="text" name="instructor_name" id="instructor_name"
                            class="form-control @error('instructor_name') is-invalid @enderror"
                            value="{{ old('instructor_name', $seminar->instructor_name ?? '') }}"
                            placeholder="Kosongkan untuk gunakan nama collaborator">
                        @error('instructor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Kosongkan untuk menggunakan nama dari collaborator</small>
                    </div>

                    <div class="form-group">
                        <label>
                            Bio Instruktur
                            <span style="color: #64748b; font-weight: normal;">(Opsional - Override)</span>
                        </label>
                        <textarea name="instructor_bio" id="instructor_bio" rows="3"
                            class="form-control @error('instructor_bio') is-invalid @enderror"
                            placeholder="Kosongkan untuk gunakan bio collaborator">{{ old('instructor_bio', $seminar->instructor_bio ?? '') }}</textarea>
                        @error('instructor_bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Kosongkan untuk menggunakan bio dari collaborator</small>
                    </div>
                </div>
            </div>

            <!-- Material PDF -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-file-pdf"></i> Materi Seminar</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Link Google Drive Materi PDF</label>
                        <input type="url" name="material_pdf_path"
                            class="form-control @error('material_pdf_path') is-invalid @enderror"
                            value="{{ old('material_pdf_path', $seminar->material_pdf_path ?? '') }}"
                            placeholder="https://drive.google.com/file/d/...">
                        <small class="form-text">Pastikan file diset ke "Anyone with the link can view"</small>
                        @error('material_pdf_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Materi</label>
                        <textarea name="material_description" rows="3"
                            class="form-control @error('material_description') is-invalid @enderror">{{ old('material_description', $seminar->material_description ?? '') }}</textarea>
                        @error('material_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 📚 DAFTAR MATERI (untuk sertifikat) -->
            @if(isset($seminar))
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3><i class="fas fa-list-ol"></i> Daftar Materi (untuk Sertifikat)</h3>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openMaterialModal()">
                        <i class="fas fa-plus-circle"></i> Tambah Materi
                    </button>
                </div>
                <div class="card-body">
                    <div
                        style="background: #e0f2fe; border: 2px solid #0284c7; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                        <div style="display: flex; gap: 10px; align-items: start;">
                            <i class="fas fa-info-circle"
                                style="color: #0284c7; margin-top: 3px; font-size: 1.2rem;"></i>
                            <div style="color: #0c4a6e; line-height: 1.6;">
                                <strong>Tentang Daftar Materi:</strong>
                                <ul style="margin: 8px 0 0 20px;">
                                    <li>Materi ini akan tampil di <strong>halaman 2 sertifikat</strong></li>
                                    <li>Input nama materi dan Jam Pelajaran (JP)</li>
                                    <li>Total JP akan otomatis dihitung</li>
                                    <li>Drag & drop untuk ubah urutan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="materials-list" style="min-height: 100px;">
                        @forelse($seminar->materials ?? [] as $material)
                        <div class="material-item" data-id="{{ $material->id }}"
                            style="background: #f8f9fa; border: 2px solid #e2e8f0; border-radius: 10px; padding: 15px; margin-bottom: 12px; transition: all 0.3s ease;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                                    <i class="fas fa-grip-vertical drag-handle"
                                        style="color: #94a3b8; cursor: move; font-size: 1.2rem;"></i>

                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--dark); margin-bottom: 5px;">
                                            {{ $material->title }}
                                        </div>
                                        <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #64748b;">
                                            <span><i class="fas fa-clock"></i> {{ $material->jp }} JP</span>
                                            <span><i class="fas fa-sort-numeric-up"></i> Urutan:
                                                {{ $material->order }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-sm btn-warning"
                                        onclick="editMaterial({{ $material->id }}, '{{ addslashes($material->title) }}', {{ $material->jp }})">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="deleteMaterial({{ $material->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state" id="empty-materials"
                            style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                            <p style="font-size: 1.1rem; font-weight: 500;">Belum ada materi</p>
                            <p style="font-size: 0.9rem;">Klik "Tambah Materi" untuk mulai</p>
                        </div>
                        @endforelse
                    </div>

                    @if(($seminar->materials ?? collect())->count() > 0)
                    <div
                        style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; padding: 20px; margin-top: 20px; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">Total Jam Pelajaran</div>
                            <div style="font-size: 2rem; font-weight: 700;" id="total-jp">
                                {{ $seminar->materials->sum('jp') }} JP
                            </div>
                        </div>
                        <i class="fas fa-graduation-cap" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list-ol"></i> Daftar Materi (untuk Sertifikat)</h3>
                </div>
                <div class="card-body" style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 8px;">
                    <div style="display: flex; gap: 10px; align-items: center; color: #92400e;">
                        <i class="fas fa-info-circle" style="font-size: 1.3rem;"></i>
                        <div>
                            <strong>Info:</strong> Daftar materi dapat ditambahkan setelah seminar disimpan.
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tests -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-clipboard-check"></i> Quiz & Test</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Pre-Test <span style="color: red;">*</span></label>
                            <div style="display: flex; gap: 10px;">
                                <select name="pre_test_id" id="pre_test_id"
                                    class="form-control @error('pre_test_id') is-invalid @enderror" required
                                    onchange="updateQuizButton('pre', this)">
                                    <option value="">Pilih Pre-Test</option>
                                    @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}"
                                        {{ old('pre_test_id', $seminar->pre_test_id ?? '') == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" id="pre_test_button" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            @error('pre_test_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Post-Test <span style="color: red;">*</span></label>
                            <div style="display: flex; gap: 10px;">
                                <select name="post_test_id" id="post_test_id"
                                    class="form-control @error('post_test_id') is-invalid @enderror" required
                                    onchange="updateQuizButton('post', this)">
                                    <option value="">Pilih Post-Test</option>
                                    @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}"
                                        {{ old('post_test_id', $seminar->post_test_id ?? '') == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" id="post_test_button" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            @error('post_test_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div
                        style="background: #e0f2fe; border: 2px solid #0284c7; border-radius: 8px; padding: 15px; margin-top: 15px;">
                        <div style="display: flex; gap: 10px;">
                            <i class="fas fa-info-circle" style="color: #0284c7; margin-top: 3px;"></i>
                            <div>
                                <strong style="color: #0c4a6e;">Tentang Quiz:</strong>
                                <ul style="margin: 8px 0 0 20px; color: #0c4a6e; line-height: 1.6;">
                                    <li>Pre-test harus lulus sebelum bisa akses materi</li>
                                    <li>Post-test harus lulus untuk dapat sertifikat</li>
                                    <li>Pastikan quiz sudah memiliki soal yang cukup</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-cog"></i> Pengaturan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $seminar->is_active ?? true) ? 'checked' : '' }}>
                            <span>Aktif</span>
                        </label>
                        <small class="form-text">Seminar dapat dilihat dan dibeli customer</small>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $seminar->is_featured ?? false) ? 'checked' : '' }}>
                            <span><i class="fas fa-star" style="color: #fbbf24;"></i> Featured</span>
                        </label>
                        <small class="form-text">Tampilkan di halaman utama</small>
                    </div>

                    <div class="form-group">
                        <label>Urutan Tampilan</label>
                        <input type="number" name="order" class="form-control"
                            value="{{ old('order', $seminar->order ?? 0) }}" min="0">
                        <small class="form-text">Semakin kecil, semakin di atas</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-certificate"></i> Sertifikat</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Template Sertifikat</label>
                        <select name="certificate_template" class="form-control">
                            <option value="">Default Template</option>
                            <option value="modern"
                                {{ old('certificate_template', $seminar->certificate_template ?? '') == 'modern' ? 'selected' : '' }}>
                                Modern</option>
                            <option value="classic"
                                {{ old('certificate_template', $seminar->certificate_template ?? '') == 'classic' ? 'selected' : '' }}>
                                Classic</option>
                            <option value="elegant"
                                {{ old('certificate_template', $seminar->certificate_template ?? '') == 'elegant' ? 'selected' : '' }}>
                                Elegant</option>
                        </select>
                    </div>

                    <div
                        style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 8px; padding: 12px; font-size: 0.9rem;">
                        <i class="fas fa-info-circle" style="color: #92400e;"></i>
                        <span style="color: #92400e;">Sertifikat otomatis dibuat setelah peserta lulus post-test</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> {{ $submitText }}
                    </button>

                    @if(isset($seminar))
                    <a href="{{ route('admin.digital.seminars.show', $seminar) }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    @endif

                    <a href="{{ route('admin.digital.seminars.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Quiz Modal -->
<div id="quizModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="modalTitle">Buat Quiz Baru</h3>
            <button type="button" class="close" onclick="closeQuizModal()">&times;</button>
        </div>

        <form id="quizForm" action="{{ route('admin.digital.seminars.quizzes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="quiz_type" id="quizType">

            <div class="modal-body">
                <div class="form-group">
                    <label>Judul Quiz <span style="color: red;">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Durasi (menit) <span style="color: red;">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control" value="30" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Passing Score (%) <span style="color: red;">*</span></label>
                        <input type="number" name="passing_score" class="form-control" value="70" min="0" max="100"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Maksimal Percobaan <span style="color: red;">*</span></label>
                        <input type="number" name="max_attempts" class="form-control" value="3" min="1" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeQuizModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Quiz</button>
            </div>
        </form>
    </div>
</div>

<!-- Material Modal -->
@if(isset($seminar))
<div id="materialModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="materialModalTitle">Tambah Materi Baru</h3>
            <button type="button" class="close" onclick="closeMaterialModal()">&times;</button>
        </div>

        <form id="materialForm" onsubmit="saveMaterial(event)">
            <input type="hidden" id="material_id">

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Materi <span style="color: red;">*</span></label>
                    <input type="text" id="material_title" class="form-control" required
                        placeholder="Contoh: Pengenalan dan Konsep Dasar">
                    <small class="form-text">Nama materi yang akan tampil di sertifikat</small>
                </div>

                <div class="form-group">
                    <label>Jam Pelajaran (JP) <span style="color: red;">*</span></label>
                    <input type="number" id="material_jp" class="form-control" required min="1" max="100"
                        placeholder="Contoh: 2">
                    <small class="form-text">1 JP ≈ 45-60 menit</small>
                </div>

                <div
                    style="background: #e0f2fe; border-left: 4px solid #0284c7; padding: 12px; border-radius: 6px; margin-top: 15px;">
                    <small style="color: #0c4a6e;">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Pastikan total JP sesuai dengan durasi seminar
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeMaterialModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
}

.close:hover,
.close:focus {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--dark);
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

.btn-secondary {
    background: #e2e8f0;
    color: var(--dark);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.875rem;
}

.sortable-ghost {
    opacity: 0.4;
    background: #e0f2fe;
}

.material-item:hover {
    border-color: var(--primary) !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
let currentQuizType = '';

// Auto-fill instructor info when collaborator selected
function updateInstructorInfo(select) {
    const selected = select.options[select.selectedIndex];
    const name = selected.dataset.name || '';
    const bio = selected.dataset.bio || '';

    document.getElementById('instructor_name').placeholder = `Default: ${name}`;
    document.getElementById('instructor_bio').placeholder = `Default: ${bio}`;
}

// Quiz Modal Functions
function openQuizModal(type) {
    currentQuizType = type;
    document.getElementById('quizType').value = type;
    document.getElementById('modalTitle').textContent = type === 'pre' ? 'Buat Pre-Test Baru' : 'Buat Post-Test Baru';
    document.getElementById('quizModal').style.display = 'block';
}

function closeQuizModal() {
    document.getElementById('quizModal').style.display = 'none';
    document.getElementById('quizForm').reset();
}

function updateQuizButton(type, selectElement) {
    const buttonId = type + '_test_button';
    const button = document.getElementById(buttonId);
    const selectedValue = selectElement.value;

    if (selectedValue === "") {
        button.onclick = function() {
            openQuizModal(type);
        };
        button.innerHTML = '<i class="fas fa-plus"></i> Tambah';
        button.className = 'btn btn-sm btn-primary';
    } else {
        button.onclick = function() {
            window.location.href = `/admin/digital/quizzes/${selectedValue}/edit`;
        };
        button.innerHTML = '<i class="fas fa-edit"></i> Edit';
        button.className = 'btn btn-sm btn-info';
    }
}

document.getElementById('quizForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const selectId = currentQuizType === 'pre' ? 'pre_test_id' : 'post_test_id';
                const select = document.getElementById(selectId);

                const option = document.createElement('option');
                option.value = data.quiz.id;
                option.textContent = data.quiz.title + ' (0 soal)';
                option.selected = true;

                select.appendChild(option);
                select.dispatchEvent(new Event('change'));

                closeQuizModal();

                if (confirm('Quiz berhasil dibuat! Apakah Anda ingin menambahkan pertanyaan sekarang?')) {
                    window.location.href = `/admin/digital/quizzes/${data.quiz.id}/edit`;
                } else {
                    alert('Quiz berhasil dibuat! Anda dapat menambahkan pertanyaan nanti.');
                }
            } else {
                alert('Gagal membuat quiz: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat quiz');
        });
});

// Material Modal Functions
@if(isset($seminar))
// Initialize Sortable only if there are materials
document.addEventListener('DOMContentLoaded', function() {
    const materialsList = document.getElementById('materials-list');
    if (materialsList) {
        const hasItems = materialsList.querySelectorAll('.material-item').length > 0;
        if (hasItems) {
            new Sortable(materialsList, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    reorderMaterials();
                }
            });
        }
    }
});

function openMaterialModal() {
    console.log('Opening modal...');
    document.getElementById('material_id').value = '';
    document.getElementById('material_title').value = '';
    document.getElementById('material_jp').value = '';
    document.getElementById('materialModalTitle').textContent = 'Tambah Materi Baru';
    document.getElementById('materialModal').style.display = 'block';
    console.log('Modal display:', document.getElementById('materialModal').style.display);
}

function editMaterial(id, title, jp) {
    document.getElementById('material_id').value = id;
    document.getElementById('material_title').value = title;
    document.getElementById('material_jp').value = jp;
    document.getElementById('materialModalTitle').textContent = 'Edit Materi';
    document.getElementById('materialModal').style.display = 'block';
}

function closeMaterialModal() {
    document.getElementById('materialModal').style.display = 'none';
}

function saveMaterial(event) {
    event.preventDefault();

    const id = document.getElementById('material_id').value;
    const title = document.getElementById('material_title').value;
    const jp = document.getElementById('material_jp').value;
    const seminarId = {{ $seminar->id }};

    if (!title || !jp) {
        alert('Semua field harus diisi!');
        return;
    }

    const url = id ?
        `/admin/digital/seminars/${seminarId}/materials/${id}` :
        `/admin/digital/seminars/${seminarId}/materials`;

    const method = id ? 'PUT' : 'POST';

    fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title,
                jp
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menyimpan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan materi');
        });
}

function deleteMaterial(id) {
    if (!confirm('Yakin ingin menghapus materi ini?')) return;

const seminarId = {{ $seminar->id }};

    fetch(`/admin/digital/seminars/${seminarId}/materials/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus materi');
        });
}

function reorderMaterials() {
    const items = document.querySelectorAll('.material-item');
    const materials = Array.from(items).map((item, index) => ({
        id: item.dataset.id,
        order: index + 1
    }));

const seminarId = {{ $seminar->id }};

    fetch(`/admin/digital/seminars/${seminarId}/materials/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                materials
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Gagal mengubah urutan');
                location.reload();
            }
        });
}
@endif

// Close modal on outside click
window.onclick = function(event) {
    const quizModal = document.getElementById('quizModal');
    if (event.target === quizModal) {
        closeQuizModal();
    }

    @if(isset($seminar))
    const materialModal = document.getElementById('materialModal');
    if (event.target === materialModal) {
        closeMaterialModal();
    }
    @endif
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const preSelect = document.getElementById('pre_test_id');
    const postSelect = document.getElementById('post_test_id');
    if (preSelect) updateQuizButton('pre', preSelect);
    if (postSelect) updateQuizButton('post', postSelect);

    const collabSelect = document.getElementById('collaborator_id');
    if (collabSelect && collabSelect.value) {
        updateInstructorInfo(collabSelect);
    }
});
</script>