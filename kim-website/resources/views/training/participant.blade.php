<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $training->title }} - KIM Eduverse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fb; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px 24px; }
        .header h1 { font-size: 1.1rem; margin-bottom: 4px; }
        .header p { font-size: 0.85rem; opacity: 0.85; }
        .container { max-width: 760px; margin: 0 auto; padding: 24px 16px 60px; }
        .card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 20px; }
        .step-list { display: flex; flex-direction: column; gap: 10px; }
        .step-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; border: 2px solid #e5e7eb; font-size: 0.9rem; transition: transform .15s, box-shadow .15s; }
        .step-item.done { border-color: #10b981; background: #f0fdf4; }
        .step-item.active { border-color: #667eea; background: #eff6ff; }
        .step-item.locked { opacity: 0.45; }
        .step-item.clickable { cursor: pointer; }
        .step-item.clickable:hover { transform: scale(1.01); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .step-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
        .step-icon.done { background: #10b981; color: white; }
        .step-icon.active { background: #667eea; color: white; }
        .step-icon.locked { background: #e5e7eb; color: #9ca3af; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: opacity .2s; }
        .btn:hover { opacity: 0.88; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-block { width: 100%; justify-content: center; }
        .alert { padding: 14px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; color: #374151; }
        .form-control { width: 100%; padding: 10px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; }
        .form-control:focus { outline: none; border-color: #667eea; }
        .quiz-info-bar { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; padding: 14px 16px; background: #f8f9fa; border-radius: 10px; font-size: 0.875rem; color: #374151; }
        .quiz-info-bar span { display: flex; align-items: center; gap: 6px; }
        .quiz-info-bar i { color: #667eea; }
        .score-card { text-align: center; padding: 30px; }
        .score-circle { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; margin: 0 auto 16px; }
        .score-pass { background: #d1fae5; color: #065f46; border: 4px solid #10b981; }
        .score-fail { background: #fee2e2; color: #991b1b; border: 4px solid #ef4444; }
        .step-nav-hint { font-size: 0.72rem; color: #10b981; display: flex; align-items: center; gap: 4px; margin-left: auto; flex-shrink: 0; }
    </style>
</head>
<body>

<div class="header">
    <h1>{{ $training->title }}</h1>
    <p>
        <i class="fas fa-calendar"></i> {{ $training->training_date->format('d F Y') }}
        &nbsp;·&nbsp;
        <i class="fas fa-map-marker-alt"></i> {{ $training->location }}
    </p>
</div>

<div class="container">

    @if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
    @endif
    @if(session('info'))
    <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
    @endif

    {{-- Sambutan --}}
    <div class="card">
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:48px; height:48px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:1.2rem; flex-shrink:0;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div style="font-size:0.82rem; color:#6b7280;">Selamat datang,</div>
                <div style="font-size:1.05rem; font-weight:700; color:#1e293b;">{{ $participant->name }}</div>
                @if($participant->nip)<div style="font-size:0.78rem; color:#9ca3af;">NIP: {{ $participant->nip }}</div>@endif
            </div>
        </div>
    </div>

    {{-- Progress Steps --}}
    @php
        $hasQuestions = $training->questions->count() > 0;
        $hasMaterials = $training->materials->count() > 0;
        $submission   = $participant->submission;
        $token        = $participant->access_token;

        // Helper: apakah step bisa diklik (sudah done DAN bukan step aktif sekarang)
        $canGoCheckin   = false; // checkin tidak bisa diulang
        $canGoPreTest   = $hasQuestions && $participant->pre_test_passed && $currentView !== 'pre_test';
        $canGoMaterial  = $hasMaterials && $participant->material_viewed && $currentView !== 'material';
        $canGoCheckout  = false; // checkout tidak bisa diulang
        $canGoPostTest  = $hasQuestions && $participant->post_test_passed && $currentView !== 'post_test';
        $canGoTask      = $submission && $currentView !== 'task';
    @endphp

    <div class="card">
        <h3 style="margin-bottom:14px; color:#1e293b; font-size:1rem;">Tahapan Pelatihan</h3>
        <div class="step-list">

            {{-- CHECK-IN --}}
            <div class="step-item {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_in_at ? 'check' : 'sign-in-alt' }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;">Check-in Kehadiran</div>
                    @if($participant->checked_in_at)<small style="color:#10b981;">{{ $participant->checked_in_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</small>@endif
                </div>
            </div>

            {{-- PRE-TEST --}}
            @if($hasQuestions)
            @if($canGoPreTest)
            <a href="{{ route('training.participant.goto', [$token, 'pre_test']) }}" style="text-decoration:none;">
                <div class="step-item done clickable">
                    <div class="step-icon done"><i class="fas fa-check"></i></div>
                    <div style="flex:1;">
                        <div style="font-weight:600;">Pre-Test</div>
                        <small style="color:#10b981;">Selesai — {{ $participant->pre_test_score }}%</small>
                    </div>
                    <span class="step-nav-hint"><i class="fas fa-eye"></i> Lihat</span>
                </div>
            </a>
            @else
            <div class="step-item {{ $participant->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->pre_test_passed ? 'check' : 'clipboard-list' }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;">Pre-Test</div>
                    @if($participant->pre_test_passed)<small style="color:#10b981;">Selesai — {{ $participant->pre_test_score }}%</small>@endif
                </div>
            </div>
            @endif
            @endif

            {{-- MATERI --}}
            @if($hasMaterials)
            @if($canGoMaterial)
            <a href="{{ route('training.participant.goto', [$token, 'material']) }}" style="text-decoration:none;">
                <div class="step-item done clickable">
                    <div class="step-icon done"><i class="fas fa-check"></i></div>
                    <div style="flex:1;"><div style="font-weight:600;">Materi Pelatihan</div></div>
                    <span class="step-nav-hint"><i class="fas fa-book-open"></i> Buka Lagi</span>
                </div>
            </a>
            @else
            <div class="step-item {{ $participant->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->material_viewed ? 'check' : 'book-open' }}"></i>
                </div>
                <div style="flex:1;"><div style="font-weight:600;">Materi Pelatihan</div></div>
            </div>
            @endif
            @endif

            {{-- CHECK-OUT --}}
            <div class="step-item {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_out_at ? 'check' : 'sign-out-alt' }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;">Check-out</div>
                    @if($participant->checked_out_at)<small style="color:#3b82f6;">{{ $participant->checked_out_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</small>@endif
                </div>
            </div>

            {{-- POST-TEST --}}
            @if($hasQuestions)
            @if($canGoPostTest)
            <a href="{{ route('training.participant.goto', [$token, 'post_test']) }}" style="text-decoration:none;">
                <div class="step-item done clickable">
                    <div class="step-icon done"><i class="fas fa-check"></i></div>
                    <div style="flex:1;">
                        <div style="font-weight:600;">Post-Test</div>
                        <small style="color:#10b981;">Lulus — {{ $participant->post_test_score }}%</small>
                    </div>
                    <span class="step-nav-hint"><i class="fas fa-eye"></i> Lihat</span>
                </div>
            </a>
            @else
            <div class="step-item {{ $participant->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->post_test_passed ? 'check' : 'clipboard-check' }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;">Post-Test</div>
                    @if($participant->post_test_passed)<small style="color:#10b981;">Lulus — {{ $participant->post_test_score }}%</small>@endif
                </div>
            </div>
            @endif
            @endif

            {{-- TUGAS --}}
            @if($canGoTask)
            <a href="{{ route('training.participant.goto', [$token, 'task']) }}" style="text-decoration:none;">
                <div class="step-item {{ $submission->status === 'approved' ? 'done' : 'active' }} clickable">
                    <div class="step-icon {{ $submission->status === 'approved' ? 'done' : 'active' }}">
                        <i class="fas fa-{{ $submission->status === 'approved' ? 'check' : 'file-upload' }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;">Kumpul Tugas</div>
                        <small style="color:#f59e0b;">{{ ucfirst($submission->status) }}</small>
                    </div>
                    <span class="step-nav-hint" style="color:#667eea;"><i class="fas fa-edit"></i> Update</span>
                </div>
            </a>
            @else
            <div class="step-item {{ $submission ? ($submission->status === 'approved' ? 'done' : 'active') : ($currentView === 'task' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $submission ? 'done' : ($currentView === 'task' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $submission ? 'check' : 'file-upload' }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-weight:600;">Kumpul Tugas</div>
                    @if($submission)<small style="color:#f59e0b;">{{ ucfirst($submission->status) }}</small>@endif
                </div>
            </div>
            @endif

            {{-- SERTIFIKAT --}}
            <div class="step-item {{ $participant->certificate_path ? 'done' : 'locked' }}">
                <div class="step-icon {{ $participant->certificate_path ? 'done' : 'locked' }}">
                    <i class="fas fa-{{ $participant->certificate_path ? 'check' : 'certificate' }}"></i>
                </div>
                <div style="flex:1;"><div style="font-weight:600;">Sertifikat</div></div>
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- KONTEN AKTIF --}}
    {{-- ============================================================ --}}

    {{-- CHECK-IN --}}
    @if($currentView === 'checkin')
    <div class="card">
        <h3 style="margin-bottom:8px;"><i class="fas fa-sign-in-alt" style="color:#10b981;"></i> Check-in Kehadiran</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Konfirmasi kehadiran Anda di pelatihan ini.</p>
        <form method="POST" action="{{ route('training.participant.checkin', $token) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-sign-in-alt"></i> Saya Sudah Hadir — Check-in Sekarang
            </button>
        </form>
    </div>
    @endif

    {{-- PRE-TEST --}}
    @if($currentView === 'pre_test')
    @php $preTestCount = min(5, $training->questions->count()); @endphp
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
            <h3><i class="fas fa-clipboard-list" style="color:#667eea;"></i> Pre-Test</h3>
            @if($participant->pre_test_passed)
            <a href="{{ route('training.participant.access', $token) }}" class="btn btn-secondary" style="padding:6px 14px; font-size:0.82rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @endif
        </div>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:16px;">Kerjakan sebelum mengakses materi.</p>

        @if($participant->pre_test_passed && !isset($ongoingAttempt))
        <div class="score-card">
            <div class="score-circle score-pass">{{ round($participant->pre_test_score) }}%</div>
            <h3 style="margin-bottom:8px;">✅ Pre-Test Selesai</h3>
            <p style="color:#6b7280; font-size:0.9rem; margin-bottom:16px;">Nilai Anda: {{ round($participant->pre_test_score) }}%</p>
            <a href="{{ route('training.participant.access', $token) }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </a>
        </div>
        @elseif(isset($ongoingAttempt) && $ongoingAttempt)
            @include('training.partials.quiz-form', [
                'attempt'  => $ongoingAttempt,
                'quizType' => 'pre',
                'token'    => $token,
                'training' => $training,
            ])
        @else
        <div class="quiz-info-bar">
            <span><i class="fas fa-question-circle"></i> {{ $preTestCount }} soal (random)</span>
            <span><i class="fas fa-clock"></i> 30 menit</span>
            <span><i class="fas fa-info-circle"></i> Semua peserta lanjut setelah pre-test</span>
        </div>
        <form method="POST" action="{{ route('training.participant.quiz.start', [$token, 'pre']) }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-play"></i> Mulai Pre-Test
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- MATERI --}}
    @if($currentView === 'material')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; flex-wrap:wrap; gap:10px;">
            <h3><i class="fas fa-book-open" style="color:#667eea;"></i> Materi Pelatihan</h3>
            @if($participant->material_viewed)
            <a href="{{ route('training.participant.access', $token) }}" class="btn btn-secondary" style="padding:6px 14px; font-size:0.82rem;">
                <i class="fas fa-arrow-left"></i> Kembali ke Timeline
            </a>
            @endif
        </div>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:20px;">Pelajari semua materi berikut selama sesi pelatihan berlangsung.</p>

        <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:24px;">
            @forelse($training->materials as $idx => $m)
            @php
                $icon  = match($m->type) { 'youtube'=>'fab fa-youtube', 'ppt'=>'fas fa-file-powerpoint', 'pdf'=>'fas fa-file-pdf', 'gdrive'=>'fab fa-google-drive', default=>'fas fa-link' };
                $color = match($m->type) { 'youtube'=>'#ef4444', 'ppt'=>'#f59e0b', 'pdf'=>'#3b82f6', 'gdrive'=>'#10b981', default=>'#667eea' };
                $youtubeId = null;
                if ($m->type === 'youtube') { preg_match('/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]{11})/', $m->url, $yt); $youtubeId = $yt[1] ?? null; }
                $driveId = null;
                if (in_array($m->type, ['pdf','gdrive','ppt'])) { preg_match('/\/d\/(.*?)\//', $m->url, $dr); $driveId = $dr[1] ?? null; }
            @endphp
            <div style="border:2px solid #e5e7eb; border-radius:14px; overflow:hidden;">
                <div style="display:flex; align-items:center; gap:12px; padding:14px 16px; background:#f8f9fa;">
                    <div style="width:36px; height:36px; border-radius:8px; background:{{ $color }}20; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="{{ $icon }}" style="color:{{ $color }};"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600; font-size:0.9rem; color:#1e293b;">{{ $m->title }}</div>
                        <div style="font-size:0.75rem; color:#9ca3af;">{{ strtoupper($m->type) }}</div>
                    </div>
                    @if($m->type === 'youtube' && $youtubeId || in_array($m->type, ['pdf','gdrive','ppt']) && $driveId)
                    <button type="button" onclick="toggleEmbed('embed-{{ $idx }}', this)"
                        data-color="{{ $color }}"
                        style="display:inline-flex; align-items:center; gap:6px; background:{{ $color }}; color:white; padding:6px 14px; border-radius:8px; font-size:0.8rem; font-weight:600; border:none; cursor:pointer;">
                        <i class="fas fa-{{ $m->type === 'youtube' ? 'play' : 'eye' }}"></i>
                        {{ $m->type === 'youtube' ? 'Putar' : 'Lihat' }}
                    </button>
                    @endif
                    <a href="{{ $m->url }}" target="_blank"
                        style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; background:white; color:#374151; border-radius:8px; border:2px solid #e5e7eb; text-decoration:none; flex-shrink:0; margin-left:4px;">
                        <i class="fas fa-external-link-alt" style="font-size:0.8rem;"></i>
                    </a>
                </div>
                <div id="embed-{{ $idx }}" style="display:none;">
                    @if($m->type === 'youtube' && $youtubeId)
                    <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; background:#000;">
                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
                            allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    </div>
                    @elseif($driveId)
                    <iframe src="https://drive.google.com/file/d/{{ $driveId }}/preview"
                        style="width:100%; height:65vh; border:none; display:block;" allowfullscreen>
                    </iframe>
                    @endif
                </div>
            </div>
            @empty
            <p style="color:#9ca3af; font-size:0.875rem; text-align:center; padding:20px;">Materi belum tersedia.</p>
            @endforelse
        </div>

        @if(!$participant->material_viewed)
        <form method="POST" action="{{ route('training.participant.material.viewed', $token) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-check"></i> Tandai Semua Sudah Dipelajari
            </button>
        </form>
        @else
        <div style="text-align:center; color:#10b981; font-size:0.875rem; padding:8px;">
            <i class="fas fa-check-circle"></i> Materi sudah ditandai selesai
        </div>
        @endif
    </div>
    @endif

    {{-- CHECK-OUT --}}
    @if($currentView === 'checkout')
    <div class="card">
        <h3 style="margin-bottom:8px;"><i class="fas fa-sign-out-alt" style="color:#f59e0b;"></i> Check-out</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Konfirmasi bahwa Anda telah mengikuti pelatihan hingga selesai.</p>
        <form method="POST" action="{{ route('training.participant.checkout', $token) }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-block">
                <i class="fas fa-sign-out-alt"></i> Check-out — Selesai Mengikuti Pelatihan
            </button>
        </form>
    </div>
    @endif

    {{-- POST-TEST --}}
    @if($currentView === 'post_test')
    @php $postTestCount = $training->questions->count(); @endphp
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
            <h3><i class="fas fa-clipboard-check" style="color:#667eea;"></i> Post-Test</h3>
            @if($participant->post_test_passed)
            <a href="{{ route('training.participant.access', $token) }}" class="btn btn-secondary" style="padding:6px 14px; font-size:0.82rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @endif
        </div>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:16px;">Kerjakan setelah mengikuti pelatihan.</p>

        @if($participant->post_test_passed && !isset($ongoingAttempt))
        <div class="score-card">
            <div class="score-circle score-pass">{{ round($participant->post_test_score) }}%</div>
            <h3 style="margin-bottom:8px;">🎉 Post-Test Lulus!</h3>
            <p style="color:#6b7280; font-size:0.9rem; margin-bottom:16px;">Nilai Anda: {{ round($participant->post_test_score) }}%</p>
            <a href="{{ route('training.participant.access', $token) }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </a>
        </div>
        @elseif(isset($ongoingAttempt) && $ongoingAttempt)
            @include('training.partials.quiz-form', [
                'attempt'  => $ongoingAttempt,
                'quizType' => 'post',
                'token'    => $token,
                'training' => $training,
            ])
        @else
        <div class="quiz-info-bar">
            <span><i class="fas fa-question-circle"></i> {{ $postTestCount }} soal</span>
            <span><i class="fas fa-clock"></i> 60 menit</span>
            <span><i class="fas fa-check-double"></i> Min. lulus: 60%</span>
        </div>
        <form method="POST" action="{{ route('training.participant.quiz.start', [$token, 'post']) }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-play"></i> Mulai Post-Test
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- TUGAS --}}
    @if($currentView === 'task')
    <div class="card">
        <h3 style="margin-bottom:4px;"><i class="fas fa-file-upload" style="color:#667eea;"></i> Kumpulkan Tugas</h3>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:4px;">Upload tugas ke Google Drive, lalu tempelkan linknya di sini.</p>
        <p style="font-size:0.78rem; color:#9ca3af; margin-bottom:16px;">Pastikan link sudah diset ke "Anyone with the link can view".</p>

        @if($submission && $submission->status === 'revision')
        <div class="alert alert-warning" style="margin-bottom:16px;">
            <i class="fas fa-exclamation-triangle"></i> <strong>Perlu Revisi:</strong> {{ $submission->feedback }}
        </div>
        @endif

        @if($submission && $submission->status === 'approved')
        <div class="alert alert-success" style="margin-bottom:16px;">
            <i class="fas fa-check-circle"></i> Tugas Anda sudah disetujui!
        </div>
        @endif

        <form method="POST" action="{{ route('training.participant.task.submit', $token) }}">
            @csrf
            <div class="form-group">
                <label>Link Google Drive *</label>
                <input type="url" name="drive_link" class="form-control" required
                    value="{{ $submission->drive_link ?? '' }}"
                    placeholder="https://drive.google.com/file/d/...">
            </div>
            <div class="form-group">
                <label>Catatan (opsional)</label>
                <textarea name="notes" class="form-control" rows="3"
                    placeholder="Catatan untuk pengajar...">{{ $submission->notes ?? '' }}</textarea>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> {{ $submission ? 'Update Tugas' : 'Kumpulkan Tugas' }}
                </button>
                @if($submission)
                <a href="{{ route('training.participant.access', $token) }}" class="btn btn-secondary" style="flex-shrink:0;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @endif
            </div>
        </form>
    </div>
    @endif

    {{-- WAITING --}}
    @if($currentView === 'waiting')
    <div class="card" style="text-align:center; padding:40px;">
        <i class="fas fa-hourglass-half" style="font-size:3rem; color:#f59e0b; margin-bottom:16px;"></i>
        <h3>Menunggu Sertifikat</h3>
        <p style="color:#6b7280; margin-top:8px;">Semua tahapan sudah selesai! Sertifikat sedang diproses oleh admin.</p>
    </div>
    @endif

    {{-- COMPLETED --}}
    @if($currentView === 'completed')
    <div class="card" style="text-align:center; background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:40px;">
        <i class="fas fa-certificate" style="font-size:3rem; margin-bottom:16px;"></i>
        <h2>Selamat! 🎉</h2>
        <p style="opacity:0.9; margin:12px 0 24px;">Anda telah menyelesaikan pelatihan ini.</p>
        <div style="background:rgba(255,255,255,.15); border-radius:10px; padding:12px; margin-bottom:24px; font-size:0.85rem;">
            No. Sertifikat: <strong>{{ $participant->certificate_number }}</strong>
        </div>
        <a href="{{ route('training.participant.certificate', $token) }}" class="btn btn-success">
            <i class="fas fa-download"></i> Download Sertifikat PDF
        </a>
    </div>
    @endif

</div>

<script>
function toggleEmbed(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el.style.display === 'none') {
        el.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-chevron-up"></i> Tutup';
        btn.style.background = '#6b7280';
    } else {
        el.style.display = 'none';
        const color = btn.dataset.color || '#667eea';
        btn.style.background = color;
        const isYoutube = btn.dataset.color === '#ef4444';
        btn.innerHTML = isYoutube
            ? '<i class="fas fa-play"></i> Putar'
            : '<i class="fas fa-eye"></i> Lihat';
    }
}
</script>
</body>
</html>