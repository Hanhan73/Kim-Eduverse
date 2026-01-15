@extends('layouts.collaborator')
@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Tambah Seminar Baru</h1>
        <a href="{{ route('digital.collaborator.seminars.index') }}" class="btn-secondary"><i
                class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('digital.collaborator.seminars.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Judul Seminar *</label>
                    <input type="text" name="title" class="form-control" required placeholder="Judul seminar...">
                </div>
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="description" class="form-control" rows="5" required
                        placeholder="Deskripsi lengkap seminar..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Nama Instruktur *</label>
                        <input type="text" name="instructor_name" class="form-control" required
                            placeholder="Nama instruktur...">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp) *</label>
                        <input type="number" name="price" class="form-control" required min="0" placeholder="100000">
                    </div>
                </div>
                <div class="form-group">
                    <label>Bio Instruktur</label>
                    <textarea name="instructor_bio" class="form-control" rows="2"
                        placeholder="Bio singkat instruktur..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Durasi (menit) *</label>
                        <input type="number" name="duration_minutes" class="form-control" required min="1"
                            placeholder="60">
                    </div>
                    <div class="form-group">
                        <label>Upload Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Pre-Test *</label>
                        <select name="pre_test_id" class="form-control" required>
                            <option value="">Pilih Pre-Test</option>
                            @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Post-Test *</label>
                        <select name="post_test_id" class="form-control" required>
                            <option value="">Pilih Post-Test</option>
                            @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Link Materi PDF</label>
                    <input type="url" name="material_pdf_path" class="form-control"
                        placeholder="https://drive.google.com/...">
                </div>
                <div class="form-check" style="margin-bottom: 15px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label for="is_active">Aktif</label>
                </div>
                <div style="display: flex; gap: 15px;">
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan Seminar</button>
                    <a href="{{ route('digital.collaborator.seminars.index') }}" class="btn-secondary"><i
                            class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection