@extends('layouts.admin-digital')
@section('title', $training->title)
@section('page-title', 'Detail Pelatihan')

@section('content')

@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

{{-- HEADER --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <h2 style="margin:0 0 6px; color:#1e293b;">{{ $training->title }}</h2>
                <div style="display:flex; gap:20px; flex-wrap:wrap; color:#6b7280; font-size:0.875rem;">
                    <span><i class="fas fa-calendar"></i> {{ $training->training_date->format('d M Y') }}</span>
                    <span><i class="fas fa-map-marker-alt"></i> {{ $training->location }}</span>
                    @if($training->start_time)<span><i class="fas fa-clock"></i> {{ substr($training->start_time,0,5) }} - {{ substr($training->end_time,0,5) }}</span>@endif
                    @if($training->trainer_name)<span><i class="fas fa-user-tie"></i> {{ $training->trainer_name }}</span>@endif
                    @if($training->total_jp)<span><i class="fas fa-book"></i> {{ $training->total_jp }} JP</span>@endif
                </div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('admin.digital.trainings.edit', $training) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                <form method="POST" action="{{ route('admin.digital.trainings.send-emails', $training) }}">@csrf
                    <button class="btn btn-info btn-sm" onclick="return confirm('Kirim email ke semua peserta yang belum menerima?')"><i class="fas fa-paper-plane"></i> Kirim Email</button>
                </form>
                <form method="POST" action="{{ route('admin.digital.trainings.generate-certificates', $training) }}">@csrf
                    <button class="btn btn-success btn-sm" onclick="return confirm('Generate sertifikat peserta yang memenuhi syarat?')"><i class="fas fa-certificate"></i> Generate Sertifikat</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- STATS --}}
<div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:20px;">
@foreach([
    ['Total Peserta',$stats['total'],'users','#667eea','#ede9fe'],
    ['Check-in',$stats['checked_in'],'sign-in-alt','#10b981','#d1fae5'],
    ['Check-out',$stats['checked_out'],'sign-out-alt','#3b82f6','#dbeafe'],
    ['Tugas Masuk',$stats['submitted'],'file-alt','#f59e0b','#fef3c7'],
    ['Sertifikat',$stats['certified'],'certificate','#8b5cf6','#ede9fe'],
] as $s)
<div style="background:white; border-radius:12px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,.06); text-align:center;">
    <div style="width:38px; height:38px; background:{{ $s[4] }}; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 8px;">
        <i class="fas fa-{{ $s[2] }}" style="color:{{ $s[3] }};"></i>
    </div>
    <div style="font-size:1.4rem; font-weight:700; color:#1e293b;">{{ $s[1] }}</div>
    <div style="font-size:0.72rem; color:#6b7280;">{{ $s[0] }}</div>
</div>
@endforeach
</div>

{{-- TABS --}}
<div style="display:flex; gap:0; border-bottom:2px solid #e5e7eb; margin-bottom:20px;">
    @foreach([['peserta','Peserta','users'],['materi','Materi','book-open'],['soal','Bank Soal','clipboard-list']] as $tab)
    <button onclick="showTab('{{ $tab[0] }}')" id="tab-btn-{{ $tab[0] }}"
        style="padding:10px 20px; border:none; background:none; font-size:0.9rem; font-weight:600; cursor:pointer; border-bottom:3px solid transparent; margin-bottom:-2px; color:#6b7280; transition:all .2s;"
        class="tab-btn">
        <i class="fas fa-{{ $tab[2] }}"></i> {{ $tab[1] }}
        @if($tab[0]==='peserta')<span style="background:#667eea; color:white; border-radius:10px; padding:1px 8px; font-size:0.75rem; margin-left:4px;">{{ $stats['total'] }}</span>@endif
        @if($tab[0]==='materi')<span style="background:#10b981; color:white; border-radius:10px; padding:1px 8px; font-size:0.75rem; margin-left:4px;">{{ $training->materials->count() }}</span>@endif
        @if($tab[0]==='soal')<span style="background:#f59e0b; color:white; border-radius:10px; padding:1px 8px; font-size:0.75rem; margin-left:4px;">{{ $training->questions->count() }}</span>@endif
    </button>
    @endforeach
</div>

{{-- ================================================================ --}}
{{-- TAB: PESERTA --}}
{{-- ================================================================ --}}
<div id="tab-peserta" class="tab-content">
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Daftar Peserta</h3>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('admin.digital.trainings.template') }}" class="btn btn-sm btn-success"><i class="fas fa-download"></i> Template Excel</a>
                <button onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-sm btn-secondary"><i class="fas fa-file-excel"></i> Import</button>
                <button onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            @if($training->participants->count() > 0)
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                    <thead><tr style="background:#f8f9fa;">
                        <th style="padding:12px 14px;">NO</th>
                        <th style="padding:12px 14px;">NAMA</th>
                        <th style="padding:12px 14px; text-align:center;">CHECK-IN</th>
                        <th style="padding:12px 14px; text-align:center;">CHECK-OUT</th>
                        <th style="padding:12px 14px; text-align:center;">PRE-TEST</th>
                        <th style="padding:12px 14px; text-align:center;">POST-TEST</th>
                        <th style="padding:12px 14px; text-align:center;">TUGAS</th>
                        <th style="padding:12px 14px; text-align:center;">SERTIFIKAT</th>
                        <th style="padding:12px 14px; text-align:center;">AKSI</th>
                    </tr></thead>
                    <tbody>
                    @foreach($training->participants as $i => $p)
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:12px 14px; color:#9ca3af;">{{ $i+1 }}</td>
                        <td style="padding:12px 14px;">
                            <div style="font-weight:600;">{{ $p->name }}</div>
                            <small style="color:#6b7280;">{{ $p->nip ?? $p->email }}</small>
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->checked_in_at)
                                <span style="color:#10b981; font-size:0.8rem;"><i class="fas fa-check-circle"></i> {{ $p->checked_in_at->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                            @else
                                <form method="POST" action="{{ route('admin.digital.trainings.participants.checkin', [$training, $p]) }}">@csrf
                                    <button style="background:#d1fae5; color:#065f46; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:0.75rem;"><i class="fas fa-sign-in-alt"></i> In</button>
                                </form>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->checked_out_at)
                                <span style="color:#3b82f6; font-size:0.8rem;"><i class="fas fa-check-circle"></i> {{ $p->checked_out_at->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                            @elseif($p->checked_in_at)
                                <form method="POST" action="{{ route('admin.digital.trainings.participants.checkout', [$training, $p]) }}">@csrf
                                    <button style="background:#dbeafe; color:#1e40af; border:none; padding:4px 8px; border-radius:6px; cursor:pointer; font-size:0.75rem;"><i class="fas fa-sign-out-alt"></i> Out</button>
                                </form>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->pre_test_passed)
                                <span style="color:#10b981; font-size:0.8rem;"><i class="fas fa-check"></i> {{ $p->pre_test_score }}%</span>
                            @else
                                <span style="color:#d1d5db; font-size:0.75rem;">Belum</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->post_test_passed)
                                <span style="color:#10b981; font-size:0.8rem;"><i class="fas fa-check"></i> {{ $p->post_test_score }}%</span>
                            @else
                                <span style="color:#d1d5db; font-size:0.75rem;">Belum</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->submission)
                                <a href="{{ $p->submission->drive_link }}" target="_blank" style="font-size:0.8rem; color:#667eea;"><i class="fas fa-external-link-alt"></i> {{ ucfirst($p->submission->status) }}</a>
                            @else
                                <span style="color:#d1d5db; font-size:0.75rem;">Belum</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            @if($p->certificate_path)
                                <a href="{{ route('admin.digital.trainings.participants.certificate', $p) }}" style="color:#8b5cf6; font-size:0.8rem;"><i class="fas fa-download"></i></a>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px; text-align:center;">
                            <div style="display:flex; gap:4px; justify-content:center;">
                                <form method="POST" action="{{ route('admin.digital.trainings.participants.send-email', [$training, $p]) }}">@csrf
                                    <button title="Kirim Email" style="background:#eff6ff; color:#2563eb; border:none; padding:4px 7px; border-radius:6px; cursor:pointer;"><i class="fas fa-envelope"></i></button>
                                </form>
                                @if($p->submission)
                                <button onclick="openReview({{ $p->submission->id }}, '{{ $p->submission->status }}', '{{ addslashes($p->submission->feedback ?? '') }}')"
                                    title="Review Tugas" style="background:#fef3c7; color:#92400e; border:none; padding:4px 7px; border-radius:6px; cursor:pointer;"><i class="fas fa-clipboard-check"></i></button>
                                @endif
                                <form method="POST" action="{{ route('admin.digital.trainings.participants.remove', [$training, $p]) }}" onsubmit="return confirm('Hapus peserta ini?')">@csrf @method('DELETE')
                                    <button style="background:#fee2e2; color:#dc2626; border:none; padding:4px 7px; border-radius:6px; cursor:pointer;"><i class="fas fa-trash"></i></button>
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
                <i class="fas fa-users" style="font-size:2rem; color:#d1d5db; margin-bottom:12px; display:block;"></i>
                Belum ada peserta.
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- TAB: MATERI --}}
{{-- ================================================================ --}}
<div id="tab-materi" class="tab-content" style="display:none;">
 
    {{-- SUB-SECTION 1: Materi Akses Peserta --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0;">Materi untuk Peserta</h3>
                <small style="color:#6b7280; font-size:0.8rem;">Link yang dibuka peserta saat pelatihan (PDF, PPT, YouTube, GDrive)</small>
            </div>
            <button onclick="document.getElementById('addMateriModal').style.display='flex'" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($training->materials as $m)
            @php
                $icon  = match($m->type) { 'youtube'=>'fab fa-youtube', 'ppt'=>'fas fa-file-powerpoint', 'pdf'=>'fas fa-file-pdf', 'gdrive'=>'fab fa-google-drive', default=>'fas fa-link' };
                $color = match($m->type) { 'youtube'=>'#ef4444', 'ppt'=>'#f59e0b', 'pdf'=>'#3b82f6', 'gdrive'=>'#10b981', default=>'#667eea' };
            @endphp
            <div style="display:flex; align-items:center; gap:14px; padding:12px 20px; border-bottom:1px solid #f0f0f0;">
                <div style="width:38px; height:38px; border-radius:9px; background:{{ $color }}20; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="{{ $icon }}" style="color:{{ $color }};"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; color:#1e293b; font-size:0.9rem;">{{ $m->title }}</div>
                    <div style="font-size:0.78rem; color:#6b7280; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                        <span style="background:{{ $color }}15; color:{{ $color }}; padding:2px 8px; border-radius:10px; font-weight:600; font-size:0.72rem;">{{ strtoupper($m->type) }}</span>
                        <a href="{{ $m->url }}" target="_blank" style="color:#667eea; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:300px; display:inline-block;">{{ Str::limit($m->url, 60) }}</a>
                    </div>
                </div>
                <div style="display:flex; gap:6px; flex-shrink:0;">
                    <button onclick="openEditMateri({{ $m->id }}, '{{ addslashes($m->title) }}', '{{ $m->type }}', '{{ addslashes($m->url) }}')"
                        style="background:#fef3c7; color:#92400e; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-edit"></i></button>
                    <form method="POST" action="{{ route('admin.digital.trainings.materials.destroy', [$training, $m]) }}" onsubmit="return confirm('Hapus materi ini?')">
                        @csrf @method('DELETE')
                        <button style="background:#fee2e2; color:#dc2626; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:30px; text-align:center; color:#9ca3af; font-size:0.875rem;">
                <i class="fas fa-link" style="font-size:1.5rem; margin-bottom:8px; display:block;"></i>
                Belum ada materi akses.
            </div>
            @endforelse
        </div>
    </div>
 
    {{-- SUB-SECTION 2: Materi Sertifikat --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0;">Materi untuk Sertifikat</h3>
                <small style="color:#6b7280; font-size:0.8rem;">Nama materi yang tercantum di halaman 2 sertifikat &nbsp;·&nbsp; Total JP: <strong>{{ $training->total_jp }} JP</strong></small>
            </div>
            <button onclick="document.getElementById('addCertMateriModal').style.display='flex'" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($training->certificateMaterials as $i => $cm)
            <div style="display:flex; align-items:center; gap:14px; padding:12px 20px; border-bottom:1px solid #f0f0f0;">
                <div style="width:28px; height:28px; border-radius:50%; background:#ede9fe; color:#7c3aed; display:flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700; flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                <div style="flex:1; font-size:0.9rem; color:#1e293b; font-weight:500;">{{ $cm->title }}</div>
                <div style="display:flex; gap:6px; flex-shrink:0;">
                    <button onclick="openEditCertMateri({{ $cm->id }}, '{{ addslashes($cm->title) }}')"
                        style="background:#fef3c7; color:#92400e; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-edit"></i></button>
                    <form method="POST" action="{{ route('admin.digital.trainings.certificate-materials.destroy', [$training, $cm]) }}" onsubmit="return confirm('Hapus materi sertifikat ini?')">
                        @csrf @method('DELETE')
                        <button style="background:#fee2e2; color:#dc2626; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:30px; text-align:center; color:#9ca3af; font-size:0.875rem;">
                <i class="fas fa-certificate" style="font-size:1.5rem; margin-bottom:8px; display:block;"></i>
                Belum ada materi sertifikat.
            </div>
            @endforelse
        </div>
    </div>
</div>
{{-- ================================================================ --}}
{{-- TAB: SOAL --}}
{{-- ================================================================ --}}
<div id="tab-soal" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3>
                Bank Soal
                <small style="color:#6b7280; font-weight:400; font-size:0.82rem;">
                    Pre-test: 5 soal random &nbsp;·&nbsp; Post-test: semua {{ $training->questions->count() }} soal
                </small>
            </h3>
            <button onclick="document.getElementById('addSoalModal').style.display='flex'" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Soal
            </button>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($training->questions as $i => $q)
            <div style="padding:16px 20px; border-bottom:1px solid #f0f0f0;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                    <div style="flex:1;">
                        <div style="display:flex; gap:10px; margin-bottom:10px; align-items:flex-start;">
                            <span style="background:#667eea; color:white; border-radius:50%; min-width:24px; height:24px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0; margin-top:2px;">{{ $i+1 }}</span>
                            <div style="font-size:0.9rem; font-weight:600; color:#1e293b; line-height:1.5;">{{ $q->question }}</div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; padding-left:34px;">
                            @foreach(['A'=>$q->option_a,'B'=>$q->option_b,'C'=>$q->option_c,'D'=>$q->option_d,'E'=>$q->option_e] as $lbl=>$txt)
                            @if($txt)
                            <div style="display:flex; gap:6px; align-items:center; font-size:0.82rem; padding:6px 8px; border-radius:6px; background:{{ $q->correct_answer === $lbl ? '#d1fae5' : '#f8f9fa' }}; border:1px solid {{ $q->correct_answer === $lbl ? '#10b981' : '#e5e7eb' }};">
                                <span style="font-weight:700; color:{{ $q->correct_answer === $lbl ? '#065f46' : '#6b7280' }}; flex-shrink:0;">{{ $lbl }}.</span>
                                <span style="color:{{ $q->correct_answer === $lbl ? '#065f46' : '#374151' }};">{{ $txt }}</span>
                                @if($q->correct_answer === $lbl)<i class="fas fa-check" style="color:#10b981; margin-left:auto; flex-shrink:0;"></i>@endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div style="display:flex; gap:6px; flex-shrink:0;">
                        <button onclick="openEditSoal({{ $q->id }}, {{ json_encode($q->question) }}, {{ json_encode($q->option_a) }}, {{ json_encode($q->option_b) }}, {{ json_encode($q->option_c) }}, {{ json_encode($q->option_d) }}, {{ json_encode($q->option_e ?? '') }}, '{{ $q->correct_answer }}')"
                            style="background:#fef3c7; color:#92400e; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admin.digital.trainings.questions.destroy', [$training, $q]) }}" onsubmit="return confirm('Hapus soal ini?')">@csrf @method('DELETE')
                            <button style="background:#fee2e2; color:#dc2626; border:none; padding:6px 10px; border-radius:8px; cursor:pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:40px; text-align:center; color:#6b7280;">Belum ada soal.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: Tambah Peserta --}}
{{-- ================================================================ --}}
<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:500px; margin:20px; max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Tambah Peserta</h3>
            <button onclick="document.getElementById('addModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.digital.trainings.participants.add', $training) }}">
            @csrf
            <div class="form-group"><label>Nama *</label><input type="text" name="name" class="form-control" required></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group"><label>NIP/NIKKI</label><input type="text" name="nip" class="form-control"></div>
                <div class="form-group"><label>No. HP</label><input type="text" name="phone" class="form-control"></div>
            </div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label>Instansi</label><input type="text" name="institution" class="form-control"></div>
            <label style="display:flex; align-items:center; gap:8px; margin-bottom:16px; cursor:pointer;">
                <input type="checkbox" name="send_email" value="1" checked> Langsung kirim email akses
            </label>
            <button type="submit" class="btn btn-primary btn-block">Tambah Peserta</button>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: Import Excel --}}
{{-- ================================================================ --}}
<div id="importModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:460px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Import via Excel</h3>
            <button onclick="document.getElementById('importModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.digital.trainings.participants.import', $training) }}" enctype="multipart/form-data">
            @csrf
            <div style="border:2px dashed #d1d5db; border-radius:10px; padding:24px; text-align:center; margin-bottom:16px;">
                <i class="fas fa-file-excel" style="font-size:2.5rem; color:#10b981; margin-bottom:8px; display:block;"></i>
                <p style="color:#6b7280; font-size:0.85rem; margin-bottom:12px;">Format: Nama, NIP, Email, No HP, Instansi</p>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-upload"></i> Upload & Import</button>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: Tambah / Edit Materi --}}
{{-- ================================================================ --}}
<div id="addMateriModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:500px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;" id="materiModalTitle">Tambah Materi Akses</h3>
            <button onclick="closeMateriModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" id="materiForm" action="{{ route('admin.digital.trainings.materials.store', $training) }}">
            @csrf
            <div id="materiMethod"></div>
            <div class="form-group">
                <label>Judul Materi *</label>
                <input type="text" name="title" id="materiTitle" class="form-control" required placeholder="Pengantar Kecerdasan Buatan...">
            </div>
            <div class="form-group">
                <label>Tipe *</label>
                <select name="type" id="materiType" class="form-control" required>
                    <option value="pdf">PDF</option>
                    <option value="ppt">PowerPoint</option>
                    <option value="youtube">YouTube</option>
                    <option value="gdrive">Google Drive</option>
                </select>
            </div>
            <div class="form-group">
                <label>URL / Link *</label>
                <input type="url" name="url" id="materiUrl" class="form-control" required placeholder="https://...">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Materi</button>
        </form>
    </div>
</div>
 
{{-- ================================================================ --}}
{{-- MODAL: Tambah/Edit Materi Sertifikat --}}
{{-- ================================================================ --}}
<div id="addCertMateriModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:460px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;" id="certMateriModalTitle">Tambah Materi Sertifikat</h3>
            <button onclick="closeCertMateriModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" id="certMateriForm" action="{{ route('admin.digital.trainings.certificate-materials.store', $training) }}">
            @csrf
            <div id="certMateriMethod"></div>
            <div class="form-group">
                <label>Nama Materi *</label>
                <input type="text" name="title" id="certMateriTitle" class="form-control" required
                    placeholder="Pengenalan AI dalam Pendidikan...">
                <small style="color:#6b7280;">Nama ini yang akan tercantum di sertifikat peserta.</small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: Tambah / Edit Soal --}}
{{-- ================================================================ --}}
<div id="addSoalModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:620px; margin:20px; max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;" id="soalModalTitle">Tambah Soal</h3>
            <button onclick="closeSoalModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" id="soalForm" action="{{ route('admin.digital.trainings.questions.store', $training) }}">
            @csrf
            <div id="soalMethod"></div>
            <div class="form-group">
                <label>Pertanyaan *</label>
                <textarea name="question" id="soalQuestion" class="form-control" rows="3" required></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div class="form-group"><label>Opsi A *</label><input type="text" name="option_a" id="soalA" class="form-control" required></div>
                <div class="form-group"><label>Opsi B *</label><input type="text" name="option_b" id="soalB" class="form-control" required></div>
                <div class="form-group"><label>Opsi C *</label><input type="text" name="option_c" id="soalC" class="form-control" required></div>
                <div class="form-group"><label>Opsi D *</label><input type="text" name="option_d" id="soalD" class="form-control" required></div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label>Opsi E <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                    <input type="text" name="option_e" id="soalE" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Jawaban Benar *</label>
                <select name="correct_answer" id="soalCorrect" class="form-control" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Soal</button>
        </form>
    </div>
</div>

{{-- ================================================================ --}}
{{-- MODAL: Review Tugas --}}
{{-- ================================================================ --}}
<div id="reviewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; width:100%; max-width:460px; margin:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">Review Tugas</h3>
            <button onclick="document.getElementById('reviewModal').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" id="reviewForm">
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
// ─── Tabs ─────────────────────────────────────────────────────────────────────
function showTab(name) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.style.color = '#6b7280';
        btn.style.borderBottomColor = 'transparent';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    const btn = document.getElementById('tab-btn-' + name);
    btn.style.color = '#667eea';
    btn.style.borderBottomColor = '#667eea';
}
showTab('peserta');

// ─── Materi Modal ─────────────────────────────────────────────────────────────
function openEditMateri(id, title, type, url) {
    document.getElementById('materiModalTitle').textContent = 'Edit Materi';
    document.getElementById('materiTitle').value = title;
    document.getElementById('materiType').value  = type;
    document.getElementById('materiUrl').value   = url;
    document.getElementById('materiMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('materiForm').action = '/admin/digital/trainings/{{ $training->id }}/materials/' + id;
    document.getElementById('addMateriModal').style.display = 'flex';
}

function closeMateriModal() {
    document.getElementById('materiModalTitle').textContent = 'Tambah Materi';
    document.getElementById('materiMethod').innerHTML = '';
    document.getElementById('materiForm').action = '{{ route("admin.digital.trainings.materials.store", $training) }}';
    document.getElementById('materiForm').reset();
    document.getElementById('addMateriModal').style.display = 'none';
}

// ─── Soal Modal ───────────────────────────────────────────────────────────────
function openEditSoal(id, q, a, b, c, d, e, correct) {
    document.getElementById('soalModalTitle').textContent = 'Edit Soal';
    document.getElementById('soalQuestion').value = q;
    document.getElementById('soalA').value = a;
    document.getElementById('soalB').value = b;
    document.getElementById('soalC').value = c;
    document.getElementById('soalD').value = d;
    document.getElementById('soalE').value = e || '';
    document.getElementById('soalCorrect').value = correct;
    document.getElementById('soalMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('soalForm').action = '/admin/digital/trainings/{{ $training->id }}/questions/' + id;
    document.getElementById('addSoalModal').style.display = 'flex';
}

function closeSoalModal() {
    document.getElementById('soalModalTitle').textContent = 'Tambah Soal';
    document.getElementById('soalMethod').innerHTML = '';
    document.getElementById('soalForm').action = '{{ route("admin.digital.trainings.questions.store", $training) }}';
    document.getElementById('soalForm').reset();
    document.getElementById('addSoalModal').style.display = 'none';
}

// ─── Review Modal ─────────────────────────────────────────────────────────────
function openReview(id, status, feedback) {
    document.getElementById('reviewForm').action = '/admin/digital/training-submissions/' + id + '/review';
    document.getElementById('reviewStatus').value   = status;
    document.getElementById('reviewFeedback').value = feedback;
    document.getElementById('reviewModal').style.display = 'flex';
}

// Materi akses
function openEditMateri(id, title, type, url) {
    document.getElementById('materiModalTitle').textContent = 'Edit Materi Akses';
    document.getElementById('materiTitle').value = title;
    document.getElementById('materiType').value  = type;
    document.getElementById('materiUrl').value   = url;
    document.getElementById('materiMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('materiForm').action = '/admin/digital/trainings/{{ $training->id }}/materials/' + id;
    document.getElementById('addMateriModal').style.display = 'flex';
}
function closeMateriModal() {
    document.getElementById('materiModalTitle').textContent = 'Tambah Materi Akses';
    document.getElementById('materiMethod').innerHTML = '';
    document.getElementById('materiForm').action = '{{ route("admin.digital.trainings.materials.store", $training) }}';
    document.getElementById('materiForm').reset();
    document.getElementById('addMateriModal').style.display = 'none';
}
 
// Materi sertifikat
function openEditCertMateri(id, title) {
    document.getElementById('certMateriModalTitle').textContent = 'Edit Materi Sertifikat';
    document.getElementById('certMateriTitle').value = title;
    document.getElementById('certMateriMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('certMateriForm').action = '/admin/digital/trainings/{{ $training->id }}/certificate-materials/' + id;
    document.getElementById('addCertMateriModal').style.display = 'flex';
}
function closeCertMateriModal() {
    document.getElementById('certMateriModalTitle').textContent = 'Tambah Materi Sertifikat';
    document.getElementById('certMateriMethod').innerHTML = '';
    document.getElementById('certMateriForm').action = '{{ route("admin.digital.trainings.certificate-materials.store", $training) }}';
    document.getElementById('certMateriForm').reset();
    document.getElementById('addCertMateriModal').style.display = 'none';
}
</script>
@endsection