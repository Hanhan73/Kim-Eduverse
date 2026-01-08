@extends('layouts.instructor')

@section('title', 'Bank Accounts')

@section('page-title', 'Bank Accounts')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Bank Accounts</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
            <i class="fas fa-plus me-2"></i>Tambah Rekening
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-university me-2"></i>Rekening Bank Saya</h5>
    </div>
    <div class="card-body">
        @if(($bankAccounts ?? collect())->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Nama Pemilik</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bankAccounts ?? [] as $bank)
                    <tr>
                        <td>
                            <strong>{{ $bank->bank_name }}</strong>
                        </td>
                        <td><code class="fs-6">{{ $bank->account_number }}</code></td>
                        <td>{{ $bank->account_holder_name }}</td>
                        <td>
                            @if($bank->is_primary)
                            <span class="badge bg-success">
                                <i class="fas fa-star me-1"></i>Primary
                            </span>
                            @endif
                            @if($bank->is_verified)
                            <span class="badge bg-info">
                                <i class="fas fa-check-circle me-1"></i>Verified
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning"
                                onclick="editBank({{ $bank->id }}, '{{ $bank->bank_name }}', '{{ $bank->account_number }}', '{{ $bank->account_holder_name }}', {{ $bank->is_primary ? 'true' : 'false' }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('instructor.bank-accounts.delete', $bank) }}"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus rekening ini?')"
                                    {{ $bank->is_primary ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-university fa-4x text-muted mb-4"></i>
            <h5 class="text-muted">Belum ada rekening bank</h5>
            <p class="text-muted mb-4">Tambahkan rekening bank untuk melakukan penarikan dana</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
                <i class="fas fa-plus me-2"></i>Tambah Rekening Sekarang
            </button>
        </div>
        @endif
    </div>
</div>

@if(($bankAccounts ?? collect())->count() > 0)
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Info:</strong> Rekening dengan status "Primary" akan digunakan sebagai default untuk penarikan dana.
    Anda hanya dapat menghapus rekening yang bukan primary.
</div>
@endif

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Rekening Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('instructor.bank-accounts.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control" placeholder="contoh: BCA, Mandiri, BNI"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control" placeholder="contoh: 1234567890"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder_name" class="form-control"
                            placeholder="Sesuai buku rekening" required>
                        <small class="text-muted">Harus sesuai dengan nama di buku rekening</small>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1"
                            {{ ($bankAccounts ?? collect())->count() == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_primary">
                            Jadikan rekening utama (primary)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bank Modal -->
<div class="modal fade" id="editBankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Rekening Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editBankForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" id="edit_bank_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" id="edit_account_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder_name" id="edit_account_holder_name" class="form-control"
                            required>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_primary" id="edit_is_primary"
                            value="1">
                        <label class="form-check-label" for="edit_is_primary">
                            Jadikan rekening utama (primary)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editBank(id, bankName, accountNumber, accountHolderName, isPrimary) {
    document.getElementById('editBankForm').action = `/instructor/bank-accounts/${id}`;
    document.getElementById('edit_bank_name').value = bankName;
    document.getElementById('edit_account_number').value = accountNumber;
    document.getElementById('edit_account_holder_name').value = accountHolderName;
    document.getElementById('edit_is_primary').checked = isPrimary;
    new bootstrap.Modal(document.getElementById('editBankModal')).show();
}
</script>
@endpush