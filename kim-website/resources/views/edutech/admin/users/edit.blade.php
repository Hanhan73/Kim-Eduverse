@extends('layouts.admin')

@section('title', 'Edit User')

@section('page-title', '✏️ Edit User')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h3>Edit User Information</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('edutech.admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Name <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    @error('name')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Email <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    @error('email')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Role <span style="color: var(--danger);">*</span>
                    </label>
                    <select name="role" required
                        style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="instructor" {{ $user->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Status
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 12px 0;">
                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}
                            style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: var(--dark); font-weight: 500;">Active</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; padding-top: 30px; border-top: 1px solid #e2e8f0; margin-top: 30px;">
                <button type="submit" style="background: var(--primary); color: white; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 1rem;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('edutech.admin.users.show', $user->id) }}" style="background: var(--gray); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection