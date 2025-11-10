@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: 5px;">
            <i class="fas fa-users" style="color: var(--primary);"></i> Users Management
        </h1>
        <p style="color: var(--gray);">Manage all users, roles, and permissions</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Total Users</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['students'] ?? 0 }}</h3>
                <p>Students</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['instructors'] ?? 0 }}</h3>
                <p>Instructors</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $stats['active_users'] ?? 0 }}</h3>
                <p>Active Users</p>
            </div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h3>All Users</h3>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.users') }}">
            <div style="padding: 20px 30px; background: #f7fafc; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Search</label>
                    <input type="text" name="search" class="filter-input" placeholder="Name or email..." value="{{ request('search') }}"
                        style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Role</label>
                    <select name="role" class="filter-select" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--dark); font-weight: 500; font-size: 0.9rem;">Status</label>
                    <select name="status" class="filter-select" style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.users') }}" class="btn" style="background: var(--gray); color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 style="color: var(--dark); font-weight: 600; margin-bottom: 3px;">{{ $user->name }}</h4>
                                    <p style="font-size: 0.85rem; color: var(--gray);">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('edutech.admin.users.show', $user->id) }}" 
                                    style="background: #bee3f8; color: #2c5282; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('edutech.admin.users.edit', $user->id) }}" 
                                    style="background: #c6f6d5; color: #22543d; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($user->id != auth()->id())
                                <form action="{{ route('edutech.admin.users.toggle', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('{{ $user->is_active ? 'Deactivate' : 'Activate' }} this user?')"
                                        style="background: {{ $user->is_active ? '#fed7d7' : '#c6f6d5' }}; color: {{ $user->is_active ? '#742a2a' : '#22543d' }}; padding: 6px 12px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 500; cursor: pointer;">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i> 
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            <i class="fas fa-users" style="font-size: 3rem; color: var(--gray); opacity: 0.3; margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray);">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0;">
            <div style="color: var(--gray);">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection