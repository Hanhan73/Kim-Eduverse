@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Ajukan Penarikan Dana</h1>
        <div class="user-info">
            <a href="{{ route('digital.collaborator.revenue.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="content-section">
        <div class="section-header">
            <h2>Form Penarikan Dana</h2>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Informasi Penting:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>Minimum penarikan: Rp 50.000</li>
                <li>Penarikan akan diproses oleh Bendahara Digital</li>
                <li>Dana akan ditransfer ke rekening yang Anda daftarkan</li>
                <li>Proses approval 1-3 hari kerja</li>
            </ul>
        </div>

        <div style="background: #f7fafc; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="margin: 0 0 10px 0; color: var(--dark);">Saldo Tersedia</h3>
            <p style="font-size: 2rem; font-weight: 800; color: var(--success); margin: 0;">
                Rp {{ number_format($availableRevenue, 0, ',', '.') }}
            </p>
        </div>

        <form action="{{ route('digital.collaborator.revenue.withdrawal.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Jumlah Penarikan <span style="color: var(--danger);">*</span>
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--gray); font-weight: 600;">Rp</span>
                    <input type="number" 
                           name="amount" 
                           class="form-input"
                           style="width: 100%; padding: 12px 12px 12px 45px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                           placeholder="50000"
                           min="50000"
                           max="{{ $availableRevenue }}"
                           value="{{ old('amount') }}"
                           required>
                </div>
                @error('amount')
                <small style="color: var(--danger); display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Minimum: Rp 50.000 | Maksimum: Rp {{ number_format($availableRevenue, 0, ',', '.') }}
                </small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nama Bank <span style="color: var(--danger);">*</span>
                </label>
                <select name="bank_name" 
                        style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                        required>
                    <option value="">Pilih Bank</option>
                    <option value="BCA" {{ old('bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                    <option value="Mandiri" {{ old('bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    <option value="BNI" {{ old('bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                    <option value="BRI" {{ old('bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                    <option value="CIMB Niaga" {{ old('bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                    <option value="Permata" {{ old('bank_name') == 'Permata' ? 'selected' : '' }}>Permata</option>
                    <option value="Danamon" {{ old('bank_name') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                    <option value="BTN" {{ old('bank_name') == 'BTN' ? 'selected' : '' }}>BTN</option>
                    <option value="BCA Syariah" {{ old('bank_name') == 'BCA Syariah' ? 'selected' : '' }}>BCA Syariah</option>
                    <option value="BSI" {{ old('bank_name') == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)</option>
                </select>
                @error('bank_name')
                <small style="color: var(--danger); display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nomor Rekening <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" 
                       name="account_number" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                       placeholder="1234567890"
                       value="{{ old('account_number') }}"
                       required>
                @error('account_number')
                <small style="color: var(--danger); display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Nama Pemilik Rekening <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" 
                       name="account_name" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                       placeholder="Nama sesuai rekening"
                       value="{{ old('account_name') }}"
                       required>
                @error('account_name')
                <small style="color: var(--danger); display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
                <small style="color: var(--gray); display: block; margin-top: 5px;">
                    Pastikan nama sesuai dengan nama di rekening bank
                </small>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--dark);">
                    Catatan (Opsional)
                </label>
                <textarea name="notes" 
                          rows="4"
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                          placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                @error('notes')
                <small style="color: var(--danger); display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn-primary" style="flex: 1;">
                    <i class="fas fa-paper-plane"></i> Ajukan Penarikan
                </button>
                <a href="{{ route('digital.collaborator.revenue.index') }}" 
                   class="btn-secondary" 
                   style="flex: 0.3; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection