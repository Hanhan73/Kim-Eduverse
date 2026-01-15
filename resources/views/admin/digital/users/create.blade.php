@extends('layouts.admin-digital')

@section('page-title', 'Tambah User Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Form Tambah User</h2>
        <a href="{{ route('admin.digital.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Ada kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.digital.users.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap <span style="color: #f56565;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Email <span style="color: #f56565;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password <span style="color: #f56565;">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    <small style="color: #718096;">Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password <span style="color: #f56565;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Role <span style="color: #f56565;">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="">Pilih Role</option>
                        <option value="collaborator" {{ old('role') == 'collaborator' ? 'selected' : '' }}>
                            Collaborator (Creator Produk)
                        </option>
                        <option value="bendahara_digital" {{ old('role') == 'bendahara_digital' ? 'selected' : '' }}>
                            Bendahara Digital (Finance)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div
                style="background: #f7fafc; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4299e1;">
                <strong><i class="fas fa-info-circle"></i> Informasi Role:</strong>
                <ul style="margin: 10px 0 0 20px; color: #4a5568;">
                    <li><strong>Collaborator:</strong> Dapat membuat & mengelola produk digital, menerima revenue 70%
                    </li>
                    <li><strong>Bendahara Digital:</strong> Dapat approve/reject withdrawal dari collaborator</li>
                </ul>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="{{ route('admin.digital.users.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection