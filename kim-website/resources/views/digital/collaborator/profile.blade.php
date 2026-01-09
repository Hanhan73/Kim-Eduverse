@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Profile Settings</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
            <span>{{ $user->name }}</span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
        <!-- Profile Info -->
        <div class="content-section">
            <div class="section-header">
                <h2>Informasi Profile</h2>
            </div>

            @if($errors->updateProfile->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                @foreach($errors->updateProfile->all() as $error)
                {{ $error }}<br>
                @endforeach
            </div>
            @endif

            <form action="{{ route('digital.collaborator.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Alamat</label>
                    <textarea name="address" rows="3"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; resize: vertical;">{{ old('address', $user->address) }}</textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="content-section">
            <div class="section-header">
                <h2>Ubah Password</h2>
            </div>

            @if($errors->updatePassword->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                @foreach($errors->updatePassword->all() as $error)
                {{ $error }}<br>
                @endforeach
            </div>
            @endif

            <form action="{{ route('digital.collaborator.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Password Saat Ini</label>
                    <input type="password" name="current_password"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Password Baru</label>
                    <input type="password" name="password"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                    <small style="color: var(--gray); display: block; margin-top: 5px;">
                        Minimal 6 karakter
                    </small>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Konfirmasi Password
                        Baru</label>
                    <input type="password" name="password_confirmation"
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%;">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
            </form>

            <div
                style="background: #f7fafc; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid var(--info);">
                <i class="fas fa-info-circle"></i>
                <strong>Tips Keamanan:</strong>
                <ul style="margin: 10px 0 0 20px; color: var(--gray); font-size: 0.9rem;">
                    <li>Gunakan kombinasi huruf, angka, dan simbol</li>
                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                    <li>Ubah password secara berkala</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Account Info -->
    <div class="content-section" style="margin-top: 25px;">
        <div class="section-header">
            <h2>Informasi Akun</h2>
        </div>

        <table style="width: 100%;">
            <tr>
                <td style="padding: 12px 0; width: 30%; color: var(--dark); font-weight: 600;">Role</td>
                <td style="padding: 12px 0;">
                    <span class="badge purple">Collaborator</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Status</td>
                <td style="padding: 12px 0;">
                    @if($user->is_active ?? true)
                    <span class="badge success">Active</span>
                    @else
                    <span class="badge danger">Inactive</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Email Verified</td>
                <td style="padding: 12px 0;">
                    @if($user->email_verified_at)
                    <span class="badge success">Verified</span>
                    @else
                    <span class="badge warning">Not Verified</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; color: var(--dark); font-weight: 600;">Member Since</td>
                <td style="padding: 12px 0;">{{ $user->created_at->format('d F Y') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection