@extends('layouts.admin-digital')

@section('title', 'Edit User')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>Edit User</h1>
            <p>Update informasi user: {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.digital.users.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.digital.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-card">
                <h3>Informasi User</h3>

                <div class="form-group">
                    <label for="name">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="phone">No. Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="+62">
                    @error('phone')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-sidebar">
                <div class="form-card">
                    <h3>Role & Password</h3>

                    <div class="form-group">
                        <label for="role">Role <span class="required">*</span></label>
                        <select id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="instructor" {{ old('role', $user->role) == 'instructor' ? 'selected' : '' }}>
                                Instructor</option>
                            <option value="collaborator"
                                {{ old('role', $user->role) == 'collaborator' ? 'selected' : '' }}>Collaborator</option>
                            <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>
                                Student</option>
                        </select>
                        @error('role')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password">
                        @error('password')<span class="error">{{ $message }}</span>@enderror
                        <small>Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="form-card">
                    <h3>Status & Info</h3>

                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Akun Aktif</label>
                    </div>

                    <div class="info-box">
                        <div class="info-item">
                            <span class="label">Bergabung:</span>
                            <span class="value">{{ $user->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email Verified:</span>
                            <span class="value">
                                @if($user->email_verified_at)
                                <i class="fas fa-check-circle text-success"></i> Ya
                                @else
                                <i class="fas fa-times-circle text-danger"></i> Belum
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Last Update:</span>
                            <span class="value">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 2rem;
    color: #2d3748;
    margin-bottom: 5px;
}

.btn-secondary {
    background: white;
    color: #667eea;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    border: 2px solid #667eea;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.form-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.form-card h3 {
    font-size: 1.2rem;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.required {
    color: #e53e3e;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
}

.form-group small {
    display: block;
    color: #a0aec0;
    margin-top: 5px;
    font-size: 0.85rem;
}

.error {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.form-check input[type="checkbox"] {
    width: 20px;
    height: 20px;
}

.info-box {
    background: #f7fafc;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
}

.info-item .value {
    color: #718096;
    font-size: 0.9rem;
}

.text-success {
    color: #48bb78;
}

.text-danger {
    color: #f56565;
}

.btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.form-sidebar {
    position: sticky;
    top: 20px;
    height: fit-content;
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-sidebar {
        position: static;
    }
}
</style>
@endsection