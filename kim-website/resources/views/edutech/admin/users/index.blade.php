@extends('layouts.admin')

@section('title', 'Users Management - Admin Panel')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --primary: #667eea;
        --secondary: #764ba2;
        --success: #48bb78;
        --danger: #e53e3e;
        --warning: #ed8936;
        --info: #4299e1;
        --dark: #2d3748;
        --gray: #718096;
        --light: #f7fafc;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .admin-container {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
        padding: 0;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 30px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
        color: white;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .menu-item.active {
        background: rgba(255, 255, 255, 0.1);
        border-left: 4px solid white;
        color: white;
    }

    .menu-item i {
        width: 25px;
        margin-right: 12px;
    }

    .menu-divider {
        margin: 20px 25px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Main Content */
    .main-content {
        margin-left: 260px;
        flex: 1;
        padding: 30px;
        width: calc(100% - 260px);
    }

    .top-bar {
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-bar h1 {
        font-size: 1.8rem;
        color: var(--dark);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e53e3e, #c53030);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .btn-logout {
        background: var(--danger);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-logout:hover {
        background: #c53030;
        transform: translateY(-2px);
    }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        padding: 25px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 1.5rem;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Filter Section */
    .filter-section {
        padding: 20px 30px;
        background: #f7fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-input, .filter-select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .btn-reset {
        background: var(--gray);
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #4a5568;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f7fafc;
    }

    th {
        text-align: left;
        padding: 15px 20px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        color: var(--gray);
    }

    tbody tr {
        transition: background 0.3s ease;
    }

    tbody tr:hover {
        background: #f7fafc;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-small {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .user-info-cell h4 {
        color: var(--dark);
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-info-cell p {
        font-size: 0.85rem;
        color: var(--gray);
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.admin {
        background: #fed7d7;
        color: #742a2a;
    }

    .badge.instructor {
        background: #feebc8;
        color: #7c2d12;
    }

    .badge.student {
        background: #e6f2ff;
        color: #2c5282;
    }

    .badge.active {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge.inactive {
        background: #fed7d7;
        color: #742a2a;
    }

    /* Action Buttons */
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        margin-right: 5px;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-view:hover {
        background: #90cdf4;
    }

    .btn-edit {
        background: #c6f6d5;
        color: #22543d;
    }

    .btn-edit:hover {
        background: #9ae6b4;
    }

    .btn-delete {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-delete:hover {
        background: #fc8181;
    }

    .btn-promote {
        background: #feebc8;
        color: #7c2d12;
    }

    .btn-promote:hover {
        background: #fbd38d;
    }

    /* Pagination */
    .pagination {
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        color: var(--gray);
    }

    .pagination-links {
        display: flex;
        gap: 8px;
    }

    .pagination-links a,
    .pagination-links span {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: var(--gray);
        transition: all 0.3s ease;
    }

    .pagination-links a:hover {
        background: #e2e8f0;
    }

    .pagination-links .active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    /* Alert */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .filter-section {
            flex-direction: column;
        }

        .table-container {
            overflow-x: auto;
        }
    }
</style>


<!-- Main Content -->
<main class="main-content">

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

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h2>
                <i class="fas fa-users"></i>
                All Users
            </h2>
        </div>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('edutech.admin.users') }}">
            <div class="filter-section">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" class="filter-input" placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                
                <div class="filter-group">
                    <label>Role</label>
                    <select name="role" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="filter-group" style="display: flex; gap: 10px; align-items: flex-end;">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('edutech.admin.users') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-container">
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
                            <div class="user-cell">
                                <div class="user-avatar-small">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="user-info-cell">
                                    <h4>{{ $user->name }}</h4>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('edutech.admin.users.show', $user->id) }}" class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('edutech.admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if($user->role != 'admin')
                            <form action="{{ route('edutech.admin.users.promote', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-promote" onclick="return confirm('Promote this user to {{ $user->role == 'student' ? 'Instructor' : 'Admin' }}?')">
                                    <i class="fas fa-arrow-up"></i> Promote
                                </button>
                            </form>
                            @endif
                            @if($user->id != auth()->id())
                            <form action="{{ route('edutech.admin.users.toggle', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action {{ $user->is_active ? 'btn-delete' : 'btn-edit' }}" onclick="return confirm('{{ $user->is_active ? 'Deactivate' : 'Activate' }} this user?')">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i> 
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            <i class="fas fa-users" style="font-size: 3rem; color: var(--gray); margin-bottom: 15px; display: block;"></i>
                            <p style="color: var(--gray);">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div class="pagination-links">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</main>
@endsection