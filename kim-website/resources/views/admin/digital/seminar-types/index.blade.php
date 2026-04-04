{{-- resources/views/admin/digital/seminar-types/index.blade.php --}}

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Kelola Tipe Seminar</h3>
    </div>
    <div class="card-body">

        {{-- Form Tambah --}}
        <form action="{{ route('admin.digital.seminar-types.store') }}" method="POST" class="d-flex gap-2 mb-4">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="Nama tipe baru..." required>
            <input type="number" name="order" class="form-control" placeholder="Urutan" style="width:100px" min="0">
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>

        {{-- Daftar Tipe --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Seminar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                <tr>
                    <td>
                        <form action="{{ route('admin.digital.seminar-types.update', $type) }}" method="POST"
                            class="d-flex gap-2 align-items-center">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $type->name }}"
                                class="form-control form-control-sm" style="width:150px">
                            <input type="number" name="order" value="{{ $type->order }}"
                                class="form-control form-control-sm" style="width:70px">
                            <button type="submit" class="btn btn-sm btn-warning">Simpan</button>
                        </form>
                    </td>
                    <td><code>{{ $type->slug }}</code></td>
                    <td>{{ $type->order }}</td>
                    <td>
                        <form action="{{ route('admin.digital.seminar-types.toggle', $type) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm {{ $type->is_active ? 'btn-success' : 'btn-secondary' }}">
                                {{ $type->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>{{ $type->seminars()->count() }}</td>
                    <td>
                        <form action="{{ route('admin.digital.seminar-types.destroy', $type) }}" method="POST"
                            onsubmit="return confirm('Hapus tipe ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>