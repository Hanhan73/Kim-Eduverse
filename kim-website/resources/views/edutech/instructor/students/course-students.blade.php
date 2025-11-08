@extends('layouts.instructor')

@section('title', 'Kelola Siswa - ' . $course->title)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 40px;
        border-radius: 20px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .assign-section {
        background: white;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .assign-controls {
        display: flex;
        gap: 15px;
        align-items: end;
    }

    .btn-assign {
        padding: 12px 24px;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-assign:disabled {
        background: #cbd5e0;
        cursor: not-allowed;
    }

    .student-item {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        position: relative;
    }

    .student-item.selected {
        border-color: #667eea;
        background: #f7fafc;
    }

    .student-checkbox {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 24px;
        height: 24px;
    }

    .batch-badge {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 15px;
        font-size: 0.8rem;
        margin-top: 10px;
    }

    .no-batch-badge {
        background: #fed7d7;
        color: #742a2a;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid py-4">
        <div class="page-header">
            <div>
                <h2><i class="fas fa-users-cog"></i> Kelola Siswa</h2>
                <p style="margin: 0; opacity: 0.9;">{{ $course->title }}</p>
            </div>
            <a href="{{ route('edutech.instructor.batches.index', $course->id) }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('edutech.instructor.students.assign-batch', $course->id) }}" id="assignForm">
            @csrf
            <div class="assign-section">
                <h4 style="margin-bottom: 20px;">
                    <i class="fas fa-clipboard-check"></i> Assign Siswa ke Batch
                </h4>
                
                <div class="assign-controls">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Filter:</label>
                        <select class="form-select" onchange="window.location.href='?batch='+this.value">
                            <option value="">Semua Siswa ({{ $enrollments->count() }})</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->batch_name }} ({{ $batch->enrollments_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Batch Tujuan:</label>
                        <select name="batch_id" class="form-select" required>
                            <option value="">-- Pilih Batch --</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">
                                    {{ $batch->batch_name }} ({{ $batch->getAvailableSeats() }} kursi)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 5px; color: transparent;">Action</label>
                        <button type="submit" class="btn-assign" id="assignBtn" disabled>
                            <i class="fas fa-arrow-right"></i> Assign (<span id="count">0</span>)
                        </button>
                    </div>
                </div>
            </div>

            @foreach($enrollments as $enrollment)
            <div class="student-item">
                <input type="checkbox" class="student-checkbox" name="enrollment_ids[]" value="{{ $enrollment->id }}">
                
                <div style="padding-right: 40px;">
                    <strong style="font-size: 1.1rem;">{{ $enrollment->student->name }}</strong>
                    <div style="color: #718096;">{{ $enrollment->student->email }}</div>
                    
                    @if($enrollment->batch_id)
                        @php
                            $currentBatch = \App\Models\CourseBatch::find($enrollment->batch_id);
                        @endphp
                        <span class="batch-badge">
                            <i class="fas fa-layer-group"></i> {{ $currentBatch->batch_name ?? 'Batch' }}
                        </span>
                    @else
                        <span class="batch-badge no-batch-badge">
                            <i class="fas fa-exclamation-triangle"></i> Belum Ada Batch
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.student-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            this.closest('.student-item').classList.toggle('selected', this.checked);
            const count = document.querySelectorAll('.student-checkbox:checked').length;
            document.getElementById('count').textContent = count;
            document.getElementById('assignBtn').disabled = count === 0;
        });
    });

    document.querySelectorAll('.student-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const cb = this.querySelector('.student-checkbox');
                cb.checked = !cb.checked;
                cb.dispatchEvent(new Event('change'));
            }
        });
    });
</script>
@endpush
@endsection