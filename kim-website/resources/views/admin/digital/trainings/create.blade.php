@extends('layouts.admin-digital')
@section('title', isset($training) ? 'Edit Pelatihan' : 'Tambah Pelatihan')
@section('page-title', isset($training) ? 'Edit Pelatihan' : 'Tambah Pelatihan')

@section('content')
<div style="max-width:800px;">
    <form method="POST" action="{{ isset($training) ? route('admin.digital.trainings.update', $training) : route('admin.digital.trainings.store') }}">
        @csrf
        @if(isset($training)) @method('PUT') @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- INFO PELATIHAN --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-info-circle"></i> Informasi Pelatihan</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Judul Pelatihan <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $training->title ?? '') }}"
                        placeholder="Optimalisasi Penggunaan AI untuk Guru PAUD..." required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Deskripsi singkat pelatihan...">{{ old('description', $training->description ?? '') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Tanggal Pelatihan <span style="color:red">*</span></label>
                        <input type="date" name="training_date" class="form-control"
                            value="{{ old('training_date', isset($training) ? $training->training_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tempat <span style="color:red">*</span></label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $training->location ?? '') }}"
                            placeholder="Gedung Dinas Pendidikan Jakarta..." required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control"
                            value="{{ old('start_time', isset($training) ? substr($training->start_time, 0, 5) : '08:00') }}">
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control"
                            value="{{ old('end_time', isset($training) ? substr($training->end_time, 0, 5) : '16:00') }}">
                    </div>
                    <div class="form-group">
                        <label>Nama Pemateri/Fasilitator</label>
                        <input type="text" name="trainer_name" class="form-control"
                            value="{{ old('trainer_name', $training->trainer_name ?? '') }}"
                            placeholder="Nama narasumber...">
                    </div>
                    <div class="form-group">
                        <label>Penyelenggara</label>
                        <input type="text" name="organizer" class="form-control"
                            value="{{ old('organizer', $training->organizer ?? '') }}"
                            placeholder="PT KIM Eduverse / Dinas Pendidikan...">
                    </div>
                </div>
            </div>
        </div>

        {{-- LINK SEMINAR --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-link"></i> Integrasi Seminar On Demand</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Pilih Seminar (opsional)</label>
                    <select name="seminar_id" class="form-control">
                        <option value="">-- Tanpa Seminar (Tidak ada pre/post test) --</option>
                        @foreach($seminars as $seminar)
                        <option value="{{ $seminar->id }}"
                            {{ old('seminar_id', $training->seminar_id ?? '') == $seminar->id ? 'selected' : '' }}>
                            {{ $seminar->title }}
                        </option>
                        @endforeach
                    </select>
                    <small class="form-text">Jika dipilih, peserta akan mengerjakan pre-test, melihat materi, dan post-test dari seminar ini. Sertifikat juga menggunakan template seminar.</small>
                </div>

                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:12px; font-size:0.875rem; color:#1e40af;">
                    <i class="fas fa-info-circle"></i>
                    Alur peserta: <strong>Check-in → Pre-test → Materi → Check-out → Post-test → Upload Tugas → Sertifikat</strong>
                </div>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="card">
            <div class="card-body">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $training->is_active ?? true) ? 'checked' : '' }}>
                    <span>Pelatihan Aktif</span>
                </label>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-bottom:40px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($training) ? 'Update Pelatihan' : 'Simpan Pelatihan' }}
            </button>
            <a href="{{ route('admin.digital.trainings.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection