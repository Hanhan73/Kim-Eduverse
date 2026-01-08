@extends('layouts.admin')

@section('title', 'Edit User')



@section('page-title', 'Edit User')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.users') }}">Users</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.super-admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles ?? [] as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Current role:
                            <strong>{{ $user->role->display_name ?? 'No Role' }}</strong></small>
                    </div>

                    <hr>

                    <h6 class="mb-3">Change Password (Optional)</h6>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">User Info</label>
                        <div class="alert alert-secondary">
                            <small>
                                <strong>User ID:</strong> {{ $user->id }}<br>
                                <strong>Created:</strong> {{ $user->created_at->format('d M Y H:i') }}<br>
                                <strong>Last Update:</strong> {{ $user->updated_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                        <a href="{{ route('admin.super-admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Delete User
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('admin.super-admin.users.destroy', $user) }}"
                    style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Ya, Hapus User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection