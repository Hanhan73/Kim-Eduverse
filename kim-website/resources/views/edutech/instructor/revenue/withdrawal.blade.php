@extends('layouts.instructor')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Tarik Dana</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('edutech_user_name'), 0, 1) }}</div>
            <span>{{ session('edutech_user_name') }}</span>
        </div>
    </div>

    <div class="content-section" style="max-width: 800px; margin: 0 auto;">
        <div class="alert alert-info" style="background: #bee3f8; color: #2c5282; border-left: 4px solid #4299e1;">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Saldo tersedia untuk ditarik:</strong><br>
                <span style="font-size: 1.3rem; font-weight: 700;">Rp
                    {{ number_format($availableRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('edutech.instructor.revenue.withdrawal.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">
                    Jumlah Penarikan <span style="color: #f56565;">*</span>
                </label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input type="number" name="amount" class="form-input @error('amount') error @enderror"
                        value="{{ old('amount') }}" min="50000" max="{{ $availableRevenue }}"
                        placeholder="Masukkan jumlah penarikan" required>
                </div>
                @error('amount')
                <small class="error-text">{{ $message }}</small>
                @enderror
                <small class="hint-text">Minimal penarikan Rp 50.000</small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Nama Bank <span style="color: #f56565;">*</span>
                </label>
                <select name="bank_name" class="form-input @error('bank_name') error @enderror" required>
                    <option value="">Pilih Bank</option>
                    <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                    <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                    <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                    <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga
                    </option>
                    <option value="Permata" {{ old('bank_name') == 'Permata' ? 'selected' : '' }}>Permata</option>
                    <option value="BSI" {{ old('bank_name') == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)
                    </option>
                    <option value="Danamon" {{ old('bank_name') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                    <option value="BTN" {{ old('bank_name') == 'BTN' ? 'selected' : '' }}>BTN</option>
                    <option value="OCBC NISP" {{ old('bank_name') == 'OCBC NISP' ? 'selected' : '' }}>OCBC NISP</option>
                </select>
                @error('bank_name')
                <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    Nomor Rekening <span style="color: #f56565;">*</span>
                </label>
                <input type="text" name="account_number" class="form-input @error('account_number') error @enderror"
                    value="{{ old('account_number') }}" placeholder="Masukkan nomor rekening" required>
                @error('account_number')
                <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    Nama Pemilik Rekening <span style="color: #f56565;">*</span>
                </label>
                <input type="text" name="account_name" class="form-input @error('account_name') error @enderror"
                    value="{{ old('account_name') }}" placeholder="Nama sesuai rekening bank" required>
                @error('account_name')
                <small class="error-text">{{ $message }}</small>
                @enderror
                <small class="hint-text">Nama harus sesuai dengan rekening bank</small>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="notes" class="form-input @error('notes') error @enderror" rows="3"
                    placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                @error('notes')
                <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="alert alert-warning"
                style="background: #feebc8; color: #7c2d12; border-left: 4px solid #ed8936;">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Perhatian:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        <li>Pastikan data rekening yang Anda masukkan sudah benar</li>
                        <li>Dana akan ditransfer setelah disetujui oleh Bendahara</li>
                        <li>Proses persetujuan biasanya memakan waktu 1-3 hari kerja</li>
                    </ul>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('edutech.instructor.revenue.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Ajukan Penarikan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input.error {
    border-color: #f56565;
}

.input-group {
    display: flex;
    align-items: center;
    gap: 0;
}

.input-prefix {
    padding: 12px 15px;
    background: var(--light);
    border: 2px solid #e2e8f0;
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-weight: 600;
    color: var(--gray);
}

.input-group .form-input {
    border-radius: 0 8px 8px 0;
}

.hint-text {
    display: block;
    margin-top: 5px;
    color: var(--gray);
    font-size: 0.85rem;
}

.error-text {
    display: block;
    margin-top: 5px;
    color: #f56565;
    font-size: 0.85rem;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    gap: 15px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert-info {
    background: #bee3f8;
    color: #2c5282;
    border-left: 4px solid #4299e1;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border-left: 4px solid #f56565;
}

.alert-warning {
    background: #feebc8;
    color: #7c2d12;
    border-left: 4px solid #ed8936;
}

.alert ul {
    margin: 10px 0 0 0;
    padding-left: 20px;
}

.alert li {
    margin: 5px 0;
}

select.form-input {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23718096' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
}

textarea.form-input {
    resize: vertical;
    min-height: 80px;
}
</style>
@endsection