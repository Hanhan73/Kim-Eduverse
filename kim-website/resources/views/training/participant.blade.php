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
        .step-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; border: 2px solid #e5e7eb; font-size: 0.9rem; }
        .step-item.done { border-color: #10b981; background: #f0fdf4; }
        .step-item.active { border-color: #667eea; background: #eff6ff; }
        .step-item.locked { opacity: 0.45; }
        .step-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
        .step-icon.done { background: #10b981; color: white; }
        .step-icon.active { background: #667eea; color: white; }
        .step-icon.locked { background: #e5e7eb; color: #9ca3af; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: opacity .2s; }
        .btn:hover { opacity: 0.88; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
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
        .question-nav { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
        .q-btn { width: 36px; height: 36px; border-radius: 50%; border: 2px solid #e5e7eb; background: white; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
        .q-btn.answered { background: #10b981; border-color: #10b981; color: white; }
        .q-btn.current { background: #667eea; border-color: #667eea; color: white; }
        .question-card { background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .question-text { font-size: 1rem; color: #1e293b; margin-bottom: 16px; line-height: 1.6; font-weight: 500; }
        .answer-option { display: block; width: 100%; padding: 12px 16px; margin-bottom: 10px; border: 2px solid #e5e7eb; border-radius: 10px; background: white; cursor: pointer; text-align: left; font-size: 0.9rem; transition: all .2s; }
        .answer-option:hover { border-color: #667eea; background: #eff6ff; }
        .answer-option.selected { border-color: #667eea; background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .quiz-nav-btns { display: flex; justify-content: space-between; margin-top: 20px; }
        .score-card { text-align: center; padding: 30px; }
        .score-circle { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; margin: 0 auto 16px; }
        .score-pass { background: #d1fae5; color: #065f46; border: 4px solid #10b981; }
        .score-fail { background: #fee2e2; color: #991b1b; border: 4px solid #ef4444; }
        .pdf-frame { width: 100%; height: 65vh; border: none; border-radius: 10px; }
        #quiz-timer { font-size: 1.1rem; font-weight: 700; color: #667eea; background: #eff6ff; padding: 6px 14px; border-radius: 8px; display: inline-block; margin-bottom: 12px; }
        #quiz-timer.danger { color: #dc2626; background: #fee2e2; }
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
    <div class="card">
        <h3 style="margin-bottom:14px; color:#1e293b; font-size:1rem;">Tahapan Pelatihan</h3>
        @php
            $seminar = $training->seminar;
            $enrollment = $participant->enrollment;
            $submission = $participant->submission;
        @endphp
        <div class="step-list">
            <div class="step-item {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_in_at ? 'check' : 'sign-in-alt' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Check-in Kehadiran</div>
                    @if($participant->checked_in_at)<small style="color:#10b981;">{{ $participant->checked_in_at->format('H:i') }} WIB</small>@endif
                </div>
            </div>

            @if($seminar)
            <div class="step-item {{ $enrollment && $enrollment->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->pre_test_passed ? 'check' : 'clipboard-list' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Pre-Test</div>
                    @if($enrollment && $enrollment->pre_test_passed)<small style="color:#10b981;">Lulus — {{ $enrollment->pre_test_score }}%</small>@endif
                </div>
            </div>

            <div class="step-item {{ $enrollment && $enrollment->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->material_viewed ? 'check' : 'book-open' }}"></i>
                </div>
                <div><div style="font-weight:600;">Materi Pelatihan</div></div>
            </div>
            @endif

            <div class="step-item {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_out_at ? 'check' : 'sign-out-alt' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Check-out</div>
                    @if($participant->checked_out_at)<small style="color:#3b82f6;">{{ $participant->checked_out_at->format('H:i') }} WIB</small>@endif
                </div>
            </div>

            @if($seminar)
            <div class="step-item {{ $enrollment && $enrollment->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->post_test_passed ? 'check' : 'clipboard-check' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Post-Test</div>
                    @if($enrollment && $enrollment->post_test_passed)<small style="color:#10b981;">Lulus — {{ $enrollment->post_test_score }}%</small>@endif
                </div>
            </div>
            @endif

            <div class="step-item {{ $submission ? ($submission->status === 'approved' ? 'done' : 'active') : ($currentView === 'task' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $submission ? 'done' : ($currentView === 'task' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $submission ? 'check' : 'file-upload' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Kumpul Tugas</div>
                    @if($submission)<small style="color:#f59e0b;">{{ ucfirst($submission->status) }}</small>@endif
                </div>
            </div>

            <div class="step-item {{ $participant->certificate_path ? 'done' : 'locked' }}">
                <div class="step-icon {{ $participant->certificate_path ? 'done' : 'locked' }}">
                    <i class="fas fa-{{ $participant->certificate_path ? 'check' : 'certificate' }}"></i>
                </div>
                <div><div style="font-weight:600;">Sertifikat</div></div>
            </div>
        </div>
    </div>

    {{-- CHECK-IN --}}
    @if($currentView === 'checkin')
    <div class="card">
        <h3 style="margin-bottom:8px;"><i class="fas fa-sign-in-alt" style="color:#10b981;"></i> Check-in Kehadiran</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Konfirmasi kehadiran Anda di pelatihan ini.</p>
        <form method="POST" action="{{ route('training.participant.checkin', $participant->access_token) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-sign-in-alt"></i> Saya Sudah Hadir — Check-in Sekarang
            </button>
        </form>
    </div>
    @endif

    {{-- PRE-TEST --}}
    @if($currentView === 'pre_test')
    @php $quiz = $seminar->preTest; @endphp
    <div class="card">
        <h3 style="margin-bottom:4px;"><i class="fas fa-clipboard-list" style="color:#667eea;"></i> Pre-Test</h3>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:16px;">Kerjakan sebelum mengakses materi.</p>

        @if(isset($quizResult) && $quizResult && $quizResult['type'] === 'pre')
        <div class="score-card">
            <div class="score-circle {{ $quizResult['passed'] ? 'score-pass' : 'score-fail' }}">
                {{ round($quizResult['score']) }}%
            </div>
            <h3 style="margin-bottom:8px;">{{ $quizResult['passed'] ? '🎉 Lulus!' : '😔 Belum Lulus' }}</h3>
            <p style="color:#6b7280; font-size:0.9rem; margin-bottom:16px;">
                {{ $quizResult['passed'] ? 'Silakan lanjut ke materi.' : 'Nilai minimum ' . $quiz->passing_score . '%. Silakan coba lagi.' }}
            </p>
            <a href="{{ route('training.participant.access', $participant->access_token) }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </a>
        </div>
        @elseif(isset($ongoingAttempt) && $ongoingAttempt)
        @include('training.partials.quiz-form', ['quiz' => $quiz, 'attempt' => $ongoingAttempt, 'quizType' => 'pre', 'token' => $participant->access_token])
        @else
        <div class="quiz-info-bar">
            <span><i class="fas fa-question-circle"></i> {{ $quiz->questions->count() }} soal</span>
            <span><i class="fas fa-clock"></i> {{ $quiz->duration_minutes }} menit</span>
            <span><i class="fas fa-check-double"></i> Min. lulus: {{ $quiz->passing_score }}%</span>
        </div>
        @if($quiz->description)<p style="color:#374151; font-size:0.9rem; margin-bottom:20px;">{{ $quiz->description }}</p>@endif
        <form method="POST" action="{{ route('training.participant.quiz.start', [$participant->access_token, 'pre']) }}">
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
        <h3 style="margin-bottom:4px;"><i class="fas fa-book-open" style="color:#667eea;"></i> Materi Pelatihan</h3>
        @if($seminar->material_description)
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:16px;">{{ $seminar->material_description }}</p>
        @endif

        @php
            preg_match('/\/d\/(.*?)\//', $seminar->material_pdf_path ?? '', $matches);
            $fileId = $matches[1] ?? null;
        @endphp

        @if($fileId)
        <iframe src="https://drive.google.com/file/d/{{ $fileId }}/preview" class="pdf-frame" style="margin-bottom:16px;"></iframe>
        @endif

        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <form method="POST" action="{{ route('training.participant.material.viewed', $participant->access_token) }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Tandai Sudah Dibaca
                </button>
            </form>
            @if($seminar->material_pdf_path)
            <a href="{{ $seminar->material_pdf_path }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-external-link-alt"></i> Buka di Google Drive
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- CHECK-OUT --}}
    @if($currentView === 'checkout')
    <div class="card">
        <h3 style="margin-bottom:8px;"><i class="fas fa-sign-out-alt" style="color:#f59e0b;"></i> Check-out</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Konfirmasi bahwa Anda telah mengikuti pelatihan hingga selesai.</p>
        <form method="POST" action="{{ route('training.participant.checkout', $participant->access_token) }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-block">
                <i class="fas fa-sign-out-alt"></i> Check-out — Selesai Mengikuti Pelatihan
            </button>
        </form>
    </div>
    @endif

    {{-- POST-TEST --}}
    @if($currentView === 'post_test')
    @php $quiz = $seminar->postTest; @endphp
    <div class="card">
        <h3 style="margin-bottom:4px;"><i class="fas fa-clipboard-check" style="color:#667eea;"></i> Post-Test</h3>
        <p style="color:#6b7280; font-size:0.85rem; margin-bottom:16px;">Kerjakan setelah mengikuti pelatihan.</p>

        @if(isset($quizResult) && $quizResult && $quizResult['type'] === 'post')
        <div class="score-card">
            <div class="score-circle {{ $quizResult['passed'] ? 'score-pass' : 'score-fail' }}">
                {{ round($quizResult['score']) }}%
            </div>
            <h3 style="margin-bottom:8px;">{{ $quizResult['passed'] ? '🎉 Lulus!' : '😔 Belum Lulus' }}</h3>
            <p style="color:#6b7280; font-size:0.9rem; margin-bottom:16px;">
                {{ $quizResult['passed'] ? 'Silakan kumpulkan tugas.' : 'Nilai minimum ' . $quiz->passing_score . '%. Silakan coba lagi.' }}
            </p>
            <a href="{{ route('training.participant.access', $participant->access_token) }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </a>
        </div>
        @elseif(isset($ongoingAttempt) && $ongoingAttempt)
        @include('training.partials.quiz-form', ['quiz' => $quiz, 'attempt' => $ongoingAttempt, 'quizType' => 'post', 'token' => $participant->access_token])
        @else
        <div class="quiz-info-bar">
            <span><i class="fas fa-question-circle"></i> {{ $quiz->questions->count() }} soal</span>
            <span><i class="fas fa-clock"></i> {{ $quiz->duration_minutes }} menit</span>
            <span><i class="fas fa-check-double"></i> Min. lulus: {{ $quiz->passing_score }}%</span>
        </div>
        <form method="POST" action="{{ route('training.participant.quiz.start', [$participant->access_token, 'post']) }}">
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

        <form method="POST" action="{{ route('training.participant.task.submit', $participant->access_token) }}">
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
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-paper-plane"></i> {{ $submission ? 'Update Tugas' : 'Kumpulkan Tugas' }}
            </button>
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
        <a href="{{ route('training.participant.certificate', $participant->access_token) }}" class="btn btn-success">
            <i class="fas fa-download"></i> Download Sertifikat PDF
        </a>
    </div>
    @endif

</div>
</body>
</html>