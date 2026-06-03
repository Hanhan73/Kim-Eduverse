@extends('layouts.admin-digital')
@section('title', isset($training) ? 'Edit Pelatihan' : 'Tambah Pelatihan')
@section('page-title', isset($training) ? 'Edit Pelatihan' : 'Tambah Pelatihan')

@section('content')
<div style="max-width:800px;">
    <form method="POST" action="{{ isset($training) ? route('admin.digital.trainings.update', $training) : route('admin.digital.trainings.store') }}">
        @csrf
        @if(isset($training)) @method('PUT') @endif

        @if($errors->any())
        <div class="alert alert-danger"><ul style="margin:0; padding-left:20px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        {{-- INFO PELATIHAN --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-info-circle"></i> Informasi Pelatihan</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Judul Pelatihan <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $training->title ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $training->description ?? '') }}</textarea>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Tanggal <span style="color:red">*</span></label>
                        <input type="date" name="training_date" class="form-control" value="{{ old('training_date', isset($training) ? $training->training_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tempat <span style="color:red">*</span></label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $training->location ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time', isset($training) ? substr($training->start_time,0,5) : '08:00') }}">
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time', isset($training) ? substr($training->end_time,0,5) : '16:00') }}">
                    </div>
                    <div class="form-group">
                        <label>Nama Narasumber</label>
                        <input type="text" name="trainer_name" class="form-control" value="{{ old('trainer_name', $training->trainer_name ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Penyelenggara</label>
                        <input type="text" name="organizer" class="form-control" value="{{ old('organizer', $training->organizer ?? '') }}">
                    </div>
                </div>

                {{-- TOTAL JP --}}
                <div class="form-group" style="margin-top:4px;">
                    <label>Total JP (Jam Pelajaran) <span style="color:red">*</span></label>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <input type="number" name="total_jp" class="form-control" style="max-width:140px;"
                            value="{{ old('total_jp', $training->total_jp ?? 0) }}" min="0" required>
                        <span style="color:#6b7280; font-size:0.875rem;">JP total keseluruhan pelatihan (akan tampil di sertifikat)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $training->is_active ?? true) ? 'checked' : '' }}>
                    <span>Pelatihan Aktif</span>
                </label>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-bottom:40px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isset($training) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.digital.trainings.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection