@extends('layouts.admin-digital')

@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Form Edit User</h2>
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

        <form action="{{ route('admin.digital.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap <span style="color: #f56565;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Email <span style="color: #f56565;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Role <span style="color: #f56565;">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="collaborator" {{ old('role', $user->role) == 'collaborator' ? 'selected' : '' }}>
                            Collaborator (Creator Produk)
                        </option>
                        <option value="bendahara_digital"
                            {{ old('role', $user->role) == 'bendahara_digital' ? 'selected' : '' }}>
                            Bendahara Digital (Finance)
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <hr style="margin: 30px 0; border: none; border-top: 2px solid #e2e8f0;">

            <h3 style="margin-bottom: 20px; color: #2d3748;">Ubah Password (Opsional)</h3>
            <p style="color: #718096; margin-bottom: 20px;">Kosongkan jika tidak ingin mengubah password</p>

            <div class="form-row">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control">
                    <small style="color: #718096;">Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <hr style="margin: 30px 0; border: none; border-top: 2px solid #e2e8f0;">

            <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>Informasi Akun:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #4a5568;">
                    <li>Member since: {{ $user->created_at->format('d F Y') }}</li>
                    <li>Status:
                        @if($user->is_active ?? true)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                    </li>
                    <li>Email verified:
                        @if($user->email_verified_at)
                        <span class="badge badge-success">Yes</span>
                        @else
                        <span class="badge badge-warning">No</span>
                        @endif
                    </li>
                </ul>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <a href="{{ route('admin.digital.users.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection