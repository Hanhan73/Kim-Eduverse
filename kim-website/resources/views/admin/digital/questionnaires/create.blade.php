@extends('layouts.admin-digital')

@section('title', 'Tambah CEKMA - Admin Digital')
@section('page-title', 'Tambah CEKMA Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle"></i> Buat CEKMA Baru</h3>
        <a href="{{ route('admin.digital.questionnaires.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.digital.questionnaires.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="name">Nama CEKMA <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Indeks Burnout Akademik">
                        @error('name')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi <span style="color: var(--danger);">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="4" required placeholder="Jelaskan tujuan dan manfaat cekma ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="instructions">Petunjuk Pengisian</label>
                        <textarea name="instructions" id="instructions" class="form-control" rows="3" placeholder="Berikan petunjuk kepada responden tentang cara mengisi cekma...">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="type">Tipe CEKMA <span style="color: var(--danger);">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="">Pilih Tipe</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration_minutes">Durasi Pengisian (menit)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" value="{{ old('duration_minutes', 15) }}" min="1" placeholder="15">
                        @error('duration_minutes')
                            <small style="color: var(--danger);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="has_dimensions" id="has_dimensions" value="1" {{ old('has_dimensions') ? 'checked' : '' }}>
                            <label for="has_dimensions">Memiliki Dimensi Pengukuran</label>
                        </div>
                        <small style="color: var(--gray);">Centang jika cekma memiliki beberapa dimensi/aspek yang diukur terpisah</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active">Aktif</label>
                        </div>
                        <small style="color: var(--gray);">CEKMA aktif dapat diakses oleh pengguna</small>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div style="background: #ebf8ff; border: 1px solid #90cdf4; border-radius: 10px; padding: 20px; margin-top: 20px;">
                <h4 style="color: #2b6cb0; margin-bottom: 10px;">
                    <i class="fas fa-info-circle"></i> Langkah Selanjutnya
                </h4>
                <p style="color: #2c5282; margin: 0;">
                    Setelah membuat cekma, Anda akan diarahkan ke halaman detail untuk:
                </p>
                <ul style="color: #2c5282; margin: 10px 0 0 20px;">
                    <li>Menambahkan <strong>Dimensi</strong> (jika diperlukan)</li>
                    <li>Menambahkan <strong>Pertanyaan</strong> beserta opsi jawaban</li>
                    <li>Mengatur <strong>Interpretasi</strong> hasil skor</li>
                </ul>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan & Lanjutkan
                </button>
                <a href="{{ route('admin.digital.questionnaires.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
