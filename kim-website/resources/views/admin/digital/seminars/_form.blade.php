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

            <!-- Instructor Info - UPDATED -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-tie"></i> Instruktur / Collaborator</h3>
                </div>
                <div class="card-body">
                    <!-- PILIH COLLABORATOR -->
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

                    <!-- OVERRIDE INSTRUCTOR NAME (OPTIONAL) -->
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

                    <!-- OVERRIDE INSTRUCTOR BIO (OPTIONAL) -->
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

            <!-- Material -->
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

            <!-- Tests -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-clipboard-check"></i> Quiz & Test</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <!-- Pre-Test -->
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

                        <!-- Post-Test -->
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
            <!-- Publish Settings -->
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

            <!-- Template Certificate -->
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

            <!-- Action Buttons -->
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

<!-- Quiz Creation Modal (sama seperti sebelumnya) -->
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

<style>
/* Styles tetap sama */
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
</style>

<script>
let currentQuizType = '';

// Auto-fill instructor info when collaborator selected
function updateInstructorInfo(select) {
    const selected = select.options[select.selectedIndex];
    const name = selected.dataset.name || '';
    const bio = selected.dataset.bio || '';

    // Fill the override fields dengan data collaborator sebagai placeholder
    document.getElementById('instructor_name').placeholder = `Default: ${name}`;
    document.getElementById('instructor_bio').placeholder = `Default: ${bio}`;
}

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

window.onclick = function(event) {
    const modal = document.getElementById('quizModal');
    if (event.target === modal) {
        closeQuizModal();
    }
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

    // Trigger initial instructor info update if collaborator already selected
    const collabSelect = document.getElementById('collaborator_id');
    if (collabSelect && collabSelect.value) {
        updateInstructorInfo(collabSelect);
    }
});
</script>