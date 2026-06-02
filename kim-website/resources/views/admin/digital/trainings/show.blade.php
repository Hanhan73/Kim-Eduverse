@extends('layouts.admin-digital')
@section('title', $training->title)
@section('page-title', 'Detail Pelatihan')

@section('content')

@if(session('success'))
<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif

{{-- HEADER INFO --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <h2 style="margin:0 0 6px; color:#1e293b;">{{ $training->title }}</h2>
                <div style="display:flex; gap:20px; flex-wrap:wrap; color:#6b7280; font-size:0.9rem;">
                    <span><i class="fas fa-calendar"></i> {{ $training->training_date->format('d F Y') }}</span>
                    <span><i class="fas fa-map-marker-alt"></i> {{ $training->location }}</span>
                    @if($training->start_time)
                    <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</span>
                    @endif
                    @if($training->trainer_name)
                    <span><i class="fas fa-user-tie"></i> {{ $training->trainer_name }}</span>
                    @endif
                </div>
                @if($training->seminar)
                <div style="margin-top:8px;">
                    <span style="background:#ede9fe; color:#7c3aed; padding:4px 12px; border-radius:20px; font-size:0.8rem;">
                        <i class="fas fa-link"></i> Seminar: {{ $training->seminar->title }}
                    </span>
                </div>
                @endif
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('admin.digital.trainings.edit', $training) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.digital.trainings.send-emails', $training) }}">
                    @csrf
                    <button class="btn btn-info btn-sm" onclick="return confirm('Kirim email akses ke semua peserta yang belum menerima?')">
                        <i class="fas fa-paper-plane"></i> Kirim Email
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.digital.trainings.generate-certificates', $training) }}">
                    @csrf
                    <button class="btn btn-success btn-sm" onclick="return confirm('Generate sertifikat untuk peserta yang sudah memenuhi syarat?')">
                        <i class="fas fa-certificate"></i> Generate Sertifikat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- STATS --}}
<div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:20px;">
    @php
    $statItems = [
        ['label'=>'Total Peserta','value'=>$stats['total'],'color'=>'#667eea','bg'=>'#ede9fe','icon'=>'users'],
        ['label'=>'Check-in','value'=>$stats['checked_in'],'color'=>'#10b981','bg'=>'#d1fae5','icon'=>'sign-in-alt'],
        ['label'=>'Check-out','value'=>$stats['checked_out'],'color'=>'#3b82f6','bg'=>'#dbeafe','icon'=>'sign-out-alt'],
        ['label'=>'Tugas Masuk','value'=>$stats['submitted'],'color'=>'#f59e0b','bg'=>'#fef3c7','icon'=>'file-alt'],
        ['label'=>'Sertifikat','value'=>$stats['certified'],'color'=>'#8b5cf6','bg'=>'#ede9fe','icon'=>'certificate'],
    ];
    @endphp
    @foreach($statItems as $s)
    <div style="background:white; border-radius:12px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,.06); text-align:center;">
        <div style="width:40px; height:40px; background:{{ $s['bg'] }}; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 8px;">
            <i class="fas fa-{{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
        </div>
        <div style="font-size:1.5rem; font-weight:700; color:#1e293b;">{{ $s['value'] }}</div>
        <div style="font-size:0.75rem; color:#6b7280;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px;">

    {{-- TABEL PESERTA --}}
    <div>
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h3><i class="fas fa-users"></i> Daftar Peserta</h3>
                <div style="display:flex; gap:8px;">
                    {{-- Import Excel --}}
                    <button onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-sm btn-secondary">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>
                    <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Manual
                    </button>
                </div>
            </div>
            <div class="card-body" style="padding:0;">
                @if($training->participants->count() > 0)
                <div class="table-responsive">
                    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="padding:12px 14px;">NO</th>
                                <th style="padding:12px 14px;">NAMA / NIP</th>
                                <th style="padding:12px 14px; text-align:center;">CHECK-IN</th>
                                <th style="padding:12px 14px; text-align:center;">CHECK-OUT</th>
                                <th style="padding:12px 14px; text-align:center;">PRE-TEST</th>
                                <th style="padding:12px 14px; text-align:center;">POST-TEST</th>
                                <th style="padding:12px 14px; text-align:center;">TUGAS</th>
                                <th style="padding:12px 14px; text-align:center;">SERTIFIKAT</th>
                                <th style="padding:12px 14px; text-align:center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($training->participants as $i => $participant)
                            <tr style="border-bottom:1px solid #f0f0f0;">
                                <td style="padding:12px 14px; color:#6b7280;">{{ $i+1 }}</td>
                                <td style="padding:12px 14px;">
                                    <div style="font-weight:600;">{{ $participant->name }}</div>
                                    <small style="color:#6b7280;">{{ $participant->nip ?? $participant->email }}</small>
                                </td>

                                {{-- CHECK-IN --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->checked_in_at)
                                        <span style="color:#10b981; font-size:0.8rem;">
                                            <i class="fas fa-check-circle"></i><br>{{ $participant->checked_in_at->format('H:i') }}
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.digital.trainings.participants.checkin', [$training, $participant]) }}">
                                            @csrf
                                            <button class="btn btn-xs" style="background:#d1fae5; color:#065f46; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:0.75rem;">
                                                <i class="fas fa-sign-in-alt"></i> In
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                {{-- CHECK-OUT --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->checked_out_at)
                                        <span style="color:#3b82f6; font-size:0.8rem;">
                                            <i class="fas fa-check-circle"></i><br>{{ $participant->checked_out_at->format('H:i') }}
                                        </span>
                                    @elseif($participant->checked_in_at)
                                        <form method="POST" action="{{ route('admin.digital.trainings.participants.checkout', [$training, $participant]) }}">
                                            @csrf
                                            <button class="btn btn-xs" style="background:#dbeafe; color:#1e40af; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:0.75rem;">
                                                <i class="fas fa-sign-out-alt"></i> Out
                                            </button>
                                        </form>
                                    @else
                                        <span style="color:#d1d5db;">—</span>
                                    @endif
                                </td>

                                {{-- PRE-TEST --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->enrollment)
                                        @if($participant->enrollment->pre_test_passed)
                                            <span style="color:#10b981; font-size:0.8rem;"><i class="fas fa-check"></i> {{ $participant->enrollment->pre_test_score }}%</span>
                                        @else
                                            <span style="color:#ef4444; font-size:0.75rem;">Belum</span>
                                        @endif
                                    @elseif($training->seminar)
                                        <span style="color:#9ca3af; font-size:0.75rem;">—</span>
                                    @else
                                        <span style="color:#9ca3af;">N/A</span>
                                    @endif
                                </td>

                                {{-- POST-TEST --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->enrollment)
                                        @if($participant->enrollment->post_test_passed)
                                            <span style="color:#10b981; font-size:0.8rem;"><i class="fas fa-check"></i> {{ $participant->enrollment->post_test_score }}%</span>
                                        @else
                                            <span style="color:#ef4444; font-size:0.75rem;">Belum</span>
                                        @endif
                                    @else
                                        <span style="color:#9ca3af;">N/A</span>
                                    @endif
                                </td>

                                {{-- TUGAS --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->submission)
                                        @php
                                        $statusColor = match($participant->submission->status) {
                                            'approved' => '#10b981',
                                            'reviewed' => '#3b82f6',
                                            'revision' => '#f59e0b',
                                            default => '#6b7280',
                                        };
                                        $statusLabel = match($participant->submission->status) {
                                            'approved' => 'Disetujui',
                                            'reviewed' => 'Direview',
                                            'revision' => 'Revisi',
                                            default => 'Masuk',
                                        };
                                        @endphp
                                        <a href="{{ $participant->submission->drive_link }}" target="_blank"
                                            style="color:{{ $statusColor }}; font-size:0.8rem; text-decoration:none;">
                                            <i class="fas fa-external-link-alt"></i> {{ $statusLabel }}
                                        </a>
                                    @else
                                        <span style="color:#d1d5db; font-size:0.8rem;">Belum</span>
                                    @endif
                                </td>

                                {{-- SERTIFIKAT --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    @if($participant->certificate_path)
                                        <a href="{{ route('admin.digital.trainings.participants.certificate', $participant) }}" 
                                            style="color:#8b5cf6; font-size:0.8rem;">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @else
                                        <span style="color:#d1d5db; font-size:0.75rem;">—</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td style="padding:12px 14px; text-align:center;">
                                    <div style="display:flex; gap:4px; justify-content:center; flex-wrap:wrap;">
                                        {{-- Kirim ulang email --}}
                                        <form method="POST" action="{{ route('admin.digital.trainings.participants.send-email', [$training, $participant]) }}">
                                            @csrf
                                            <button class="btn btn-xs" style="background:#eff6ff; color:#2563eb; border:none; padding:4px 6px; border-radius:6px; cursor:pointer;" title="Kirim Email Akses">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </form>
                                        {{-- Review tugas --}}
                                        @if($participant->submission)
                                        <button onclick="openReviewModal({{ $participant->submission->id }}, '{{ $participant->submission->status }}', '{{ addslashes($participant->submission->feedback ?? '') }}')"
                                            class="btn btn-xs" style="background:#fef3c7; color:#92400e; border:none; padding:4px 6px; border-radius:6px; cursor:pointer;" title="Review Tugas">
                                            <i class="fas fa-clipboard-check"></i>
                                        </button>
                                        @endif
                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('admin.digital.trainings.participants.remove', [$training, $participant]) }}" onsubmit="return confirm('Hapus peserta ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs" style="background:#fee2e2; color:#dc2626; border:none; padding:4px 6px; border-radius:6px; cursor:pointer;" title="Hapus">
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
                @else
                <div style="padding:40px; text-align:center; color:#6b7280;">
                    <i class="fas fa-users" style="font-size:2rem; margin-bottom:12px; color:#d1d5db;"></i>
                    <p>Belum ada peserta. Tambah manual atau import Excel.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SIDEBAR: Info + Template Excel --}}
    <div>
        {{-- Template Download --}}
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-file-excel"></i> Template Import</h3></div>
            <div class="card-body">
                <p style="font-size:0.875rem; color:#6b7280; margin-bottom:12px;">
                    Format kolom Excel: <strong>Nama, NIP, Email, No HP, Instansi</strong>
                </p>
                <a href="{{ route('admin.digital.trainings.template') }}" class="btn btn-success btn-block">
                    <i class="fas fa-download"></i> Download Template Excel
                </a>
            </div>
        </div>

        {{-- Info Seminar --}}
        @if($training->seminar)
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-chalkboard-teacher"></i> Seminar Terhubung</h3></div>
            <div class="card-body">
                <p style="font-weight:600; font-size:0.9rem;">{{ $training->seminar->title }}</p>
                <div style="font-size:0.8rem; color:#6b7280;">
                    <div>Pre-test: {{ $training->seminar->preTest ? $training->seminar->preTest->questions->count() . ' soal' : 'Tidak ada' }}</div>
                    <div>Post-test: {{ $training->seminar->postTest ? $training->seminar->postTest->questions->count() . ' soal' : 'Tidak ada' }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL: Tambah Peserta Manual --}}
<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:500px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Tambah Peserta</h3>
            <button onclick="document.getElementById('addModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.digital.trainings.participants.add', $training) }}">
            @csrf
            <div class="form-group">
                <label>Nama <span style="color:red">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Nama lengkap peserta">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label>NIP/NIKKI</label>
                    <input type="text" name="nip" class="form-control" placeholder="NIP peserta">
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="08xx...">
                </div>
            </div>
            <div class="form-group">
                <label>Email <span style="color:red">*</span></label>
                <input type="email" name="email" class="form-control" required placeholder="email@sekolah.sch.id">
            </div>
            <div class="form-group">
                <label>Instansi</label>
                <input type="text" name="institution" class="form-control" placeholder="TK Meruya Selatan...">
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="send_email" value="1" checked>
                    <span>Langsung kirim email akses</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Tambah Peserta</button>
        </form>
    </div>
</div>

{{-- MODAL: Import Excel --}}
<div id="importModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:480px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Import Peserta via Excel</h3>
            <button onclick="document.getElementById('importModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.digital.trainings.participants.import', $training) }}" enctype="multipart/form-data">
            @csrf
            <div style="background:#f8f9fa; border:2px dashed #d1d5db; border-radius:8px; padding:20px; text-align:center; margin-bottom:16px;">
                <i class="fas fa-file-excel" style="font-size:2rem; color:#10b981; margin-bottom:8px;"></i>
                <p style="color:#6b7280; font-size:0.875rem; margin:0 0 12px;">Format: Nama, NIP, Email, No HP, Instansi</p>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required style="display:block; margin:0 auto;">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-upload"></i> Upload & Import
            </button>
        </form>
    </div>
</div>

{{-- MODAL: Review Tugas --}}
<div id="reviewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:460px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Review Tugas</h3>
            <button onclick="document.getElementById('reviewModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" id="reviewForm" action="">
            @csrf
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="reviewStatus" class="form-control">
                    <option value="reviewed">Direview</option>
                    <option value="approved">Disetujui ✅</option>
                    <option value="revision">Perlu Revisi ⚠️</option>
                </select>
            </div>
            <div class="form-group">
                <label>Feedback / Catatan</label>
                <textarea name="feedback" id="reviewFeedback" class="form-control" rows="3" placeholder="Catatan untuk peserta..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Review</button>
        </form>
    </div>
</div>

<script>
function openReviewModal(submissionId, status, feedback) {
    document.getElementById('reviewForm').action = '/admin/digital/training-submissions/' + submissionId + '/review';
    document.getElementById('reviewStatus').value = status;
    document.getElementById('reviewFeedback').value = feedback;
    document.getElementById('reviewModal').style.display = 'flex';
}
</script>
@endsection