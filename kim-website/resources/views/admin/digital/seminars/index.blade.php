@extends('layouts.admin-digital')

@section('title', 'Seminar On Demand')
@section('page-title', 'Seminar On Demand')

@section('content')
<div class="page-header">
    <div>
        <h1>Seminar On Demand</h1>
        <p>Kelola seminar online dengan pre-test, materi, dan post-test</p>
    </div>
    <a href="{{ route('admin.digital.seminars.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Seminar
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

<!-- Stats Cards -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $seminars->total() }}</h4>
            <p>Total Seminar</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $seminars->where('is_active', true)->count() }}</h4>
            <p>Aktif</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $seminars->sum('enrollments_count') }}</h4>
            <p>Total Peserta</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $seminars->where('is_featured', true)->count() }}</h4>
            <p>Featured</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 15px; align-items: center;">
            <div style="position: relative; flex: 1; max-width: 400px;">
                <i class="fas fa-search"
                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <input type="text" id="searchInput" placeholder="Cari seminar..."
                    style="width: 100%; padding: 10px 15px 10px 45px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
            <select id="filterStatus" style="padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
                <option value="featured">Featured</option>
            </select>
        </div>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($seminars->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Seminar</th>
                        <th>Instruktur</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seminars as $seminar)
                    <tr>
                        <td>
                            <input type="checkbox" class="row-checkbox" value="{{ $seminar->id }}">
                        </td>
                        <td>
                            <div style="display: flex; gap: 15px; align-items: center;">
                                @if($seminar->thumbnail)
                                <img src="{{ Storage::url($seminar->thumbnail) }}" alt="{{ $seminar->title }}"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div
                                    style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chalkboard-teacher" style="color: white; font-size: 1.5rem;"></i>
                                </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.digital.seminars.show', $seminar) }}"
                                        style="font-weight: 600; color: var(--dark); text-decoration: none;">
                                        {{ $seminar->title }}
                                    </a>
                                    <div style="display: flex; gap: 8px; margin-top: 5px;">
                                        @if($seminar->is_featured)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                        @endif
                                        <span class="badge badge-info">{{ $seminar->sold_count }} terjual</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $seminar->instructor_name }}</div>
                            <small style="color: var(--gray);">{{ Str::limit($seminar->instructor_bio, 40) }}</small>
                        </td>
                        <td>
                            <strong style="color: var(--primary);">{{ $seminar->formatted_price }}</strong>
                        </td>
                        <td>
                            <i class="fas fa-clock" style="color: var(--info);"></i>
                            {{ $seminar->duration_minutes }} menit
                        </td>
                        <td>
                            <a href="{{ route('admin.digital.seminars.enrollments', $seminar) }}"
                                style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                <i class="fas fa-users"></i> {{ $seminar->enrollments_count }}
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('admin.digital.seminars.toggle-active', $seminar) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="badge {{ $seminar->is_active ? 'badge-success' : 'badge-danger' }}"
                                    style="border: none; cursor: pointer;">
                                    {{ $seminar->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.seminars.show', $seminar) }}"
                                    class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.digital.seminars.edit', $seminar) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.digital.seminars.destroy', $seminar) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus seminar ini?')">
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

        <div style="padding: 20px;">
            {{ $seminars->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Belum Ada Seminar</h3>
            <p>Mulai tambahkan seminar on demand pertama Anda</p>
            <a href="{{ route('admin.digital.seminars.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Seminar
            </a>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const tableRows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusFilter = filterStatus.value;

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(searchTerm);

                let matchesStatus = true;
                if (statusFilter === 'active') {
                    matchesStatus = row.querySelector('.badge-success') !== null;
                } else if (statusFilter === 'inactive') {
                    matchesStatus = row.querySelector('.badge-danger') !== null;
                } else if (statusFilter === 'featured') {
                    matchesStatus = text.includes('featured');
                }

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterStatus.addEventListener('change', filterTable);

        // Select all checkbox
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        selectAll?.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });
</script>
@endsection