@extends('layouts.admin-digital')

@section('title', 'User Management - Admin')

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p>Kelola semua pengguna sistem</p>
        </div>
        <a href="{{ route('admin.digital.users.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.digital.users.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select name="role">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructor
                    </option>
                    <option value="collaborator" {{ request('role') == 'collaborator' ? 'selected' : '' }}>Collaborator
                    </option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div class="filter-group">
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.digital.users.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Users Table -->
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="user-info">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                            <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
                            @endif
                            <div>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                <span class="badge badge-primary">You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                        <span class="badge badge-danger">Admin</span>
                        @elseif($user->role === 'instructor')
                        <span class="badge badge-info">Instructor</span>
                        @elseif($user->role === 'collaborator')
                        <span class="badge badge-warning">Collaborator</span>
                        @else
                        <span class="badge badge-secondary">Student</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-dark">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.digital.users.edit', $user->id) }}" class="btn-icon btn-edit"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.digital.users.toggle-status', $user->id) }}"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-icon btn-toggle" title="Toggle Status">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.digital.users.destroy', $user->id) }}"
                                style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
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

.page-header p {
    color: #718096;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.filters-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filters-form {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
}

.btn-filter {
    background: #667eea;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-reset {
    background: #f7fafc;
    color: #4a5568;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border: 1px solid #9ae6b4;
}

.alert-error {
    background: #fed7d7;
    color: #742a2a;
    border: 1px solid #fc8181;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    background: #f7fafc;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-info img,
.avatar-placeholder {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.badge-primary {
    background: #bee3f8;
    color: #2c5282;
}

.badge-success {
    background: #c6f6d5;
    color: #22543d;
}

.badge-danger {
    background: #fed7d7;
    color: #742a2a;
}

.badge-info {
    background: #bee3f8;
    color: #2c5282;
}

.badge-warning {
    background: #feebc8;
    color: #7c2d12;
}

.badge-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.badge-dark {
    background: #4a5568;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-edit {
    background: #bee3f8;
    color: #2c5282;
}

.btn-toggle {
    background: #feebc8;
    color: #7c2d12;
}

.btn-delete {
    background: #fed7d7;
    color: #742a2a;
}

.text-center {
    text-align: center;
    padding: 40px !important;
    color: #a0aec0;
}

.pagination-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
@endsection