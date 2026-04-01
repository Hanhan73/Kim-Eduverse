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
                        <label>Tipe Seminar <span style="color: red;">*</span></label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe Seminar --</option>
                            <option value="pendidikan"
                                {{ old('type', $seminar->type ?? '') == 'pendidikan' ? 'selected' : '' }}>Pendidikan
                            </option>
                            <option value="manajemen"
                                {{ old('type', $seminar->type ?? '') == 'manajemen' ? 'selected' : '' }}>Manajemen
                            </option>
                            <option value="kearsipan"
                                {{ old('type', $seminar->type ?? '') == 'kearsipan' ? 'selected' : '' }}>Kearsipan
                            </option>
                        </select>
                        @error('type')
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
                            <img src="{{ asset('products/thumbnails/' . $seminar->thumbnail) }}" id="imagePreview"
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
                    </div>

                    <div class="form-group">
                        <label>Nama Instruktur</label>
                        <input type="text" name="instructor_name" id="instructor_name"
                            class="form-control @error('instructor_name') is-invalid @enderror"
                            value="{{ old('instructor_name', $seminar->instructor_name ?? '') }}"
                            placeholder="Kosongkan untuk gunakan nama collaborator">
                        @error('instructor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Bio Instruktur</label>
                        <textarea name="instructor_bio" id="instructor_bio" rows="3"
                            class="form-control @error('instructor_bio') is-invalid @enderror"
                            placeholder="Kosongkan untuk gunakan bio collaborator">{{ old('instructor_bio', $seminar->instructor_bio ?? '') }}</textarea>
                        @error('instructor_bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

            <!-- DAFTAR MATERI + TOTAL JP -->
            @if(isset($seminar))
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3><i class="fas fa-list-ol"></i> Daftar Materi & Total JP</h3>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openMaterialModal()">
                        <i class="fas fa-plus-circle"></i> Tambah Materi
                    </button>
                </div>
                <div class="card-body">
                    <!-- INPUT TOTAL JP DI ATAS -->
                    <div class="form-group"
                        style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                        <label
                            style="color: white; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-clock"></i> Total Jam Pelajaran (JP) Keseluruhan
                        </label>
                        <input type="number" name="total_jp" id="total_jp"
                            class="form-control @error('total_jp') is-invalid @enderror"
                            value="{{ old('total_jp', $seminar->total_jp ?? '') }}" min="1" max="100"
                            placeholder="Contoh: 6" style="font-size: 1.2rem; font-weight: 600; padding: 15px;">
                        @error('total_jp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div
                        style="background: #e0f2fe; border: 2px solid #0284c7; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                        <div style="display: flex; gap: 10px; align-items: start;">
                            <i class="fas fa-info-circle"
                                style="color: #0284c7; margin-top: 3px; font-size: 1.2rem;"></i>
                            <div style="color: #0c4a6e; line-height: 1.6;">
                                <strong>Cara Kerja:</strong>
                                <ul style="margin: 8px 0 0 20px;">
                                    <li>Input <strong>Total JP</strong> untuk keseluruhan materi di atas</li>
                                    <li>Daftar materi di bawah hanya untuk <strong>tampilan di sertifikat</strong></li>
                                    <li>Drag & drop untuk ubah urutan materi</li>
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
                                            {{ $loop->iteration }}. {{ $material->title }}
                                        </div>
                                        <div style="font-size: 0.85rem; color: #64748b;">
                                            <i class="fas fa-sort-numeric-up"></i> Urutan: {{ $material->order }}
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-sm btn-warning"
                                        onclick="editMaterial({{ $material->id }}, '{{ addslashes($material->title) }}')">
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
                        style="background: #f1f5f9; border-radius: 8px; padding: 15px; margin-top: 15px; text-align: center; color: #64748b;">
                        <i class="fas fa-list"></i> <strong>{{ ($seminar->materials ?? collect())->count() }}
                            Materi</strong> terdaftar
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list-ol"></i> Daftar Materi & Total JP</h3>
                </div>
                <div class="card-body">
                    <!-- TOTAL JP BISA DIISI SEKARANG -->
                    <div class="form-group"
                        style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; padding: 20px; margin-bottom: 15px;">
                        <label
                            style="color: white; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-clock"></i> Total Jam Pelajaran (JP) Keseluruhan
                        </label>
                        <input type="number" name="total_jp" id="total_jp"
                            class="form-control @error('total_jp') is-invalid @enderror" value="{{ old('total_jp') }}"
                            min="1" max="100" placeholder="Contoh: 6"
                            style="font-size: 1.2rem; font-weight: 600; padding: 15px;">
                        @error('total_jp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color: rgba(255,255,255,0.9); display: block; margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Input total JP di sini (1 JP ≈ 45-60 menit)
                        </small>
                    </div>

                    <div style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 8px; padding: 15px;">
                        <div style="display: flex; gap: 10px; align-items: center; color: #92400e;">
                            <i class="fas fa-info-circle" style="font-size: 1.3rem;"></i>
                            <div>
                                <strong>Info:</strong> Daftar materi dapat ditambahkan setelah seminar disimpan.
                            </div>
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
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_featured" value="1"
                                {{ old('is_featured', $seminar->is_featured ?? false) ? 'checked' : '' }}>
                            <span><i class="fas fa-star" style="color: #fbbf24;"></i> Featured</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Urutan Tampilan</label>
                        <input type="number" name="order" class="form-control"
                            value="{{ old('order', $seminar->order ?? 0) }}" min="0">
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

<!-- Material Modal (TANPA INPUT JP) -->

{{-- MODAL QUIZ BARU --}}
<div id="quizModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="quizModalTitle">Buat Quiz Baru</h3>
            <button type="button" class="close" onclick="closeQuizModal()">&times;</button>
        </div>

        <form id="quizForm" onsubmit="saveQuiz(event)">
            <input type="hidden" id="quiz_type"> {{-- pre atau post --}}

            <div class="modal-body">
                <div class="form-group">
                    <label>Judul Quiz <span style="color: red;">*</span></label>
                    <input type="text" id="quiz_title" class="form-control" required
                        placeholder="Contoh: Pre-Test Seminar Digital Marketing">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="quiz_description" class="form-control" rows="3"
                        placeholder="Deskripsi singkat tentang quiz..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Durasi (menit) <span style="color: red;">*</span></label>
                            <input type="number" id="quiz_duration" class="form-control" value="30" min="1" max="300"
                                required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nilai Lulus (%) <span style="color: red;">*</span></label>
                            <input type="number" id="quiz_passing_score" class="form-control" value="70" min="0"
                                max="100" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Max Percobaan</label>
                            <input type="number" id="quiz_max_attempts" class="form-control" value="3" min="1" max="10">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="quiz_is_active" checked value="1">
                        <label class="custom-control-label" for="quiz_is_active">
                            Quiz Aktif
                        </label>
                    </div>
                </div>

                <div style="background: #e0f2fe; border-left: 4px solid #0284c7; padding: 12px; border-radius: 6px;">
                    <small style="color: #0c4a6e;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Setelah quiz dibuat, Anda bisa menambahkan pertanyaan dengan klik tombol
                        "Edit"
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeQuizModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Quiz
                </button>
            </div>
        </form>
    </div>
</div>
{{-- MODAL MATERI --}}
@if(isset($seminar))
<div id="materialModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
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

                <div style="background: #e0f2fe; border-left: 4px solid #0284c7; padding: 12px; border-radius: 6px;">
                    <small style="color: #0c4a6e;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Total JP diatur di form utama, bukan per materi
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

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
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

.custom-control-input:checked~.custom-control-label::before {
    background-color: #4e73df;
    border-color: #4e73df;
}

.modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Alert notification styles */
.alert {
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
@if(isset($seminar))
document.addEventListener('DOMContentLoaded', function() {
    const materialsList = document.getElementById('materials-list');
    if (materialsList && materialsList.querySelectorAll('.material-item').length > 0) {
        new Sortable(materialsList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                reorderMaterials();
            }
        });
    }
});

function openMaterialModal() {
    document.getElementById('material_id').value = '';
    document.getElementById('material_title').value = '';
    document.getElementById('materialModalTitle').textContent = 'Tambah Materi Baru';
    document.getElementById('materialModal').style.display = 'block';
}

function editMaterial(id, title) {
    document.getElementById('material_id').value = id;
    document.getElementById('material_title').value = title;
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
    const seminarId = {{$seminar -> id}};
}


if (!title) {
    alert('Nama materi harus diisi!');
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
            title
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

    const seminarId = {{$seminar -> id}};


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
        });
}

function reorderMaterials() {
    const items = document.querySelectorAll('.material-item');
    const materials = Array.from(items).map((item, index) => ({
        id: item.dataset.id,
        order: index + 1
    }));
    const seminarId = {{$seminar -> id}};

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

function updateInstructorInfo(select) {
    const selected = select.options[select.selectedIndex];
    const name = selected.dataset.name || '';
    const bio = selected.dataset.bio || '';
    document.getElementById('instructor_name').placeholder = `Default: ${name}`;
    document.getElementById('instructor_bio').placeholder = `Default: ${bio}`;
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

// ============================================
// QUIZ MODAL FUNCTIONS
// ============================================

function openQuizModal(type) {
    document.getElementById('quiz_type').value = type;
    document.getElementById('quiz_title').value = '';
    document.getElementById('quiz_description').value = '';
    document.getElementById('quiz_duration').value = '30';
    document.getElementById('quiz_passing_score').value = '70';
    document.getElementById('quiz_max_attempts').value = '3';
    document.getElementById('quiz_is_active').checked = true;

    const typeText = type === 'pre' ? 'Pre-Test' : 'Post-Test';
    document.getElementById('quizModalTitle').textContent = `Buat ${typeText} Baru`;

    // Auto-fill title
    const seminarTitle = document.querySelector('input[name="title"]').value;
    if (seminarTitle) {
        document.getElementById('quiz_title').value = `${typeText} - ${seminarTitle}`;
    }

    document.getElementById('quizModal').style.display = 'block';
}

function closeQuizModal() {
    document.getElementById('quizModal').style.display = 'none';
}

function saveQuiz(event) {
    event.preventDefault();

    const quizData = {
        title: document.getElementById('quiz_title').value,
        description: document.getElementById('quiz_description').value,
        duration_minutes: document.getElementById('quiz_duration').value,
        passing_score: document.getElementById('quiz_passing_score').value,
        max_attempts: document.getElementById('quiz_max_attempts').value,
        is_active: document.getElementById('quiz_is_active').checked ? 1 : 0,
    };

    // Tampilkan loading
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    submitBtn.disabled = true;

    fetch('/admin/digital/quizzes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(quizData)
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.message || 'Server error');
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // Tambahkan quiz baru ke dropdown
                const quizType = document.getElementById('quiz_type').value;
                const selectId = quizType === 'pre' ? 'pre_test_id' : 'post_test_id';
                const select = document.getElementById(selectId);

                const option = new Option(data.quiz.title, data.quiz.id, true, true);
                select.add(option);

                // Update button
                updateQuizButton(quizType, select);

                // Close modal
                closeQuizModal();

                // Show success message
                showNotification('success',
                    'Quiz berhasil dibuat! Klik tombol "Edit" untuk menambahkan pertanyaan.');
            } else {
                showNotification('danger', data.message || 'Gagal menyimpan quiz');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('danger', 'Terjadi kesalahan saat menyimpan quiz: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
}

// SATU-SATUNYA FUNCTION updateQuizButton (YANG BENAR)
function updateQuizButton(type, select) {
    const button = document.getElementById(`${type}_test_button`);
    const selectedValue = select.value;

    if (selectedValue) {
        // Jika ada quiz dipilih, ubah tombol jadi "Edit"
        button.innerHTML = '<i class="fas fa-edit"></i> Edit';
        button.classList.remove('btn-primary');
        button.classList.add('btn-warning');
        button.onclick = function() {
            window.open(`/admin/digital/quizzes/${selectedValue}/edit`, '_blank');
        };
    } else {
        // Jika belum ada yang dipilih, tombol jadi "Tambah" dengan modal
        button.innerHTML = '<i class="fas fa-plus"></i> Tambah';
        button.classList.remove('btn-warning');
        button.classList.add('btn-primary');
        button.onclick = function() {
            openQuizModal(type);
        };
    }
}

// Helper function untuk notification
function showNotification(type, message) {
    // Buat elemen notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto dismiss setelah 5 detik
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Initialize buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    const preTestSelect = document.getElementById('pre_test_id');
    const postTestSelect = document.getElementById('post_test_id');

    if (preTestSelect) {
        updateQuizButton('pre', preTestSelect);
    }

    if (postTestSelect) {
        updateQuizButton('post', postTestSelect);
    }
});


function showNotification(type, message) {
    // Buat elemen notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto dismiss setelah 5 detik
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>