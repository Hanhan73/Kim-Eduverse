@extends('layouts.collaborator')
@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Edit Seminar</h1>
        <a href="{{ route('digital.collaborator.seminars.index') }}" class="btn-secondary"><i
                class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('digital.collaborator.seminars.update', $seminar) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Judul Seminar *</label>
                    <input type="text" name="title" class="form-control" required value="{{ $seminar->title }}">
                </div>
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="description" class="form-control" rows="5"
                        required>{{ $seminar->description }}</textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Nama Instruktur *</label>
                        <input type="text" name="instructor_name" class="form-control" required
                            value="{{ $seminar->instructor_name }}">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp) *</label>
                        <input type="number" name="price" class="form-control" required min="0"
                            value="{{ $seminar->price }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Bio Instruktur</label>
                    <textarea name="instructor_bio" class="form-control"
                        rows="2">{{ $seminar->instructor_bio }}</textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Durasi (menit) *</label>
                        <input type="number" name="duration_minutes" class="form-control" required min="1"
                            value="{{ $seminar->duration_minutes }}">
                    </div>
                    <div class="form-group">
                        <label>Upload Thumbnail Baru</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        @if($seminar->thumbnail)
                        <small style="color: var(--gray);">Current: <img src="{{ Storage::url($seminar->thumbnail) }}"
                                style="height: 30px; margin-top: 5px;"></small>
                        @endif
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Pre-Test *</label>
                        <select name="pre_test_id" class="form-control" required>
                            @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}" {{ $seminar->pre_test_id == $quiz->id ? 'selected' : '' }}>
                                {{ $quiz->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Post-Test *</label>
                        <select name="post_test_id" class="form-control" required>
                            @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}" {{ $seminar->post_test_id == $quiz->id ? 'selected' : '' }}>
                                {{ $quiz->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Link Materi PDF</label>
                    <input type="url" name="material_pdf_path" class="form-control"
                        value="{{ $seminar->material_pdf_path }}">
                </div>
                <div class="form-check" style="margin-bottom: 15px;">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ $seminar->is_active ? 'checked' : '' }}>
                    <label for="is_active">Aktif</label>
                </div>
                <div style="display: flex; gap: 15px;">
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Seminar</button>
                    <a href="{{ route('digital.collaborator.seminars.index') }}" class="btn-secondary"><i
                            class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection