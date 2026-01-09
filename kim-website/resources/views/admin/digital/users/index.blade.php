@extends('layouts.admin-digital')

@section('page-title', 'User Management')

@section('content')
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

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <i class="fas fa-laptop-code"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['collaborators'] }}</div>
            <div class="stat-label">Collaborators</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['bendahara'] }}</div>
            <div class="stat-label">Bendahara Digital</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['active_users'] }}</div>
            <div class="stat-label">Active Users</div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h2>Daftar Users</h2>
        <a href="{{ route('admin.digital.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small style="color: #718096;">Joined: {{ $user->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'collaborator')
                            <span class="badge badge-info">Collaborator</span>
                            @else
                            <span class="badge badge-warning">Bendahara Digital</span>
                            @endif
                        </td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @if($user->is_active ?? true)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.digital.users.edit', $user) }}" class="btn btn-sm btn-info"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.digital.users.toggle', $user) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Toggle Status">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.digital.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus user ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px; color: #718096;">
            <i class="fas fa-users" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.3;"></i>
            <h3>Belum ada user</h3>
            <p>Mulai tambahkan user pertama</p>
            <a href="{{ route('admin.digital.users.create') }}" class="btn btn-primary" style="margin-top: 20px;">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
        @endif
    </div>
</div>
@endsection