@extends('layouts.admin')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.super-admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('admin.super-admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah User Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Users</h5>
        <span class="badge bg-primary">{{ $users->total() }} Total Users</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2"
                                    style="width: 35px; height: 35px; font-size: 14px; background: #6F42C1;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role)
                            <span
                                class="badge bg-{{ $user->role->name === 'super_admin' ? 'danger' : ($user->role->name === 'bendahara' ? 'warning' : ($user->role->name === 'instructor' ? 'info' : 'secondary')) }}">
                                {{ $user->role->display_name }}
                            </span>
                            @else
                            <span class="badge bg-secondary">No Role</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.super-admin.users.edit', $user->id) }}" class="btn btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-danger" onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus user ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteUser(userId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/super-admin/users/${userId}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush