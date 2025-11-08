@extends('layouts.instructor')

@section('title', 'Buat Batch Baru')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background: #f7fafc;
        border-radius: 8px;
    }

    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 25px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <div class="page-header">
            <h2><i class="fas fa-plus-circle"></i> Buat Batch Baru</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">{{ $course->title }}</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">
                    <form action="{{ route('edutech.instructor.batches.store', $course->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-tag"></i> Nama Batch *</label>
                            <input type="text" name="batch_name" class="form-control" value="{{ old('batch_name') }}" 
                                   placeholder="Contoh: Batch 1 - Januari 2025" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-calendar"></i> Tanggal Mulai *</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-calendar-check"></i> Tanggal Selesai *</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-clock"></i> Tipe Jadwal *</label>
                            <select name="schedule_type" class="form-select" id="scheduleType" required>
                                <option value="weekday">Weekday (Senin-Jumat)</option>
                                <option value="weekend">Weekend (Sabtu-Minggu)</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div class="mb-4" id="customDays" style="display: none;">
                            <label class="form-label"><i class="fas fa-calendar-day"></i> Pilih Hari</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="monday" id="monday">
                                    <label for="monday">Senin</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="tuesday" id="tuesday">
                                    <label for="tuesday">Selasa</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="wednesday" id="wednesday">
                                    <label for="wednesday">Rabu</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="thursday" id="thursday">
                                    <label for="thursday">Kamis</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="friday" id="friday">
                                    <label for="friday">Jumat</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="saturday" id="saturday">
                                    <label for="saturday">Sabtu</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="schedule_days[]" value="sunday" id="sunday">
                                    <label for="sunday">Minggu</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-clock"></i> Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-clock"></i> Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-users"></i> Maksimal Siswa *</label>
                            <input type="number" name="max_students" class="form-control" value="{{ old('max_students', 30) }}" min="1" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-sticky-note"></i> Catatan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Buat Batch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('scheduleType').addEventListener('change', function() {
        const customDays = document.getElementById('customDays');
        if (this.value === 'custom') {
            customDays.style.display = 'block';
        } else {
            customDays.style.display = 'none';
        }
    });
</script>
@endpush
@endsection