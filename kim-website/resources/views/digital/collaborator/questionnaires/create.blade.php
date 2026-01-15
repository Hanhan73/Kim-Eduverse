@extends('layouts.collaborator')
@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Tambah CEKMA Baru</h1>
        <a href="{{ route('digital.collaborator.questionnaires.index') }}" class="btn-secondary"><i
                class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('digital.collaborator.questionnaires.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama CEKMA *</label>
                    <input type="text" name="name" class="form-control" required
                        placeholder="Contoh: Indeks Burnout Akademik">
                </div>
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="description" class="form-control" rows="4" required
                        placeholder="Jelaskan tujuan cekma..."></textarea>
                </div>
                <div class="form-group">
                    <label>Petunjuk Pengisian</label>
                    <textarea name="instructions" class="form-control" rows="3"
                        placeholder="Petunjuk untuk responden..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Tipe CEKMA *</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Tipe</option>
                            @foreach($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Durasi (menit)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="15" min="1">
                    </div>
                </div>
                <div class="form-check" style="margin-bottom: 15px;">
                    <input type="checkbox" name="has_dimensions" id="has_dimensions" value="1">
                    <label for="has_dimensions">Memiliki Dimensi Pengukuran</label>
                </div>
                <div
                    style="background: #ebf8ff; border: 1px solid #90cdf4; border-radius: 10px; padding: 20px; margin: 20px 0;">
                    <h4 style="color: #2b6cb0; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Langkah
                        Selanjutnya</h4>
                    <p style="color: #2c5282; margin: 0;">Setelah membuat cekma, Anda akan masuk ke Builder untuk
                        menambahkan dimensi dan pertanyaan.</p>
                </div>
                <div style="display: flex; gap: 15px;">
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan & Lanjutkan</button>
                    <a href="{{ route('digital.collaborator.questionnaires.index') }}" class="btn-secondary"><i
                            class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection