<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $training->title }} - KIM Eduverse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fb; min-height: 100vh; }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px 24px;
        }
        .header h1 { font-size: 1.1rem; margin-bottom: 4px; }
        .header p { font-size: 0.85rem; opacity: 0.85; }

        .container { max-width: 720px; margin: 0 auto; padding: 24px 16px; }

        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            margin-bottom: 20px;
        }

        .step-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
        .step-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            font-size: 0.9rem;
        }
        .step-item.done { border-color: #10b981; background: #f0fdf4; }
        .step-item.active { border-color: #667eea; background: #eff6ff; }
        .step-item.locked { opacity: 0.5; }
        .step-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .step-icon.done { background: #10b981; color: white; }
        .step-icon.active { background: #667eea; color: white; }
        .step-icon.locked { background: #e5e7eb; color: #9ca3af; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px; border-radius: 10px; font-size: 0.95rem;
            font-weight: 600; border: none; cursor: pointer; text-decoration: none;
            transition: opacity .2s;
        }
        .btn:hover { opacity: 0.9; }
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

        .info-row { display: flex; gap: 8px; margin-bottom: 8px; font-size: 0.9rem; color: #4b5563; }
        .info-row i { color: #667eea; width: 18px; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
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

    {{-- Alert --}}
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
            <div style="width:52px; height:52px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-size:1.3rem; flex-shrink:0;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <div style="font-size:0.85rem; color:#6b7280;">Selamat datang,</div>
                <div style="font-size:1.1rem; font-weight:700; color:#1e293b;">{{ $participant->name }}</div>
                @if($participant->nip)<div style="font-size:0.8rem; color:#6b7280;">NIP: {{ $participant->nip }}</div>@endif
            </div>
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="card">
        <h3 style="margin-bottom:16px; color:#1e293b;">Tahapan Pelatihan</h3>
        <div class="step-list">
            @php
            $seminar = $training->seminar;
            $enrollment = $participant->enrollment;
            $submission = $participant->submission;
            @endphp

            {{-- CHECK-IN --}}
            <div class="step-item {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_in_at ? 'done' : ($currentView === 'checkin' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_in_at ? 'check' : 'sign-in-alt' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Check-in Kehadiran</div>
                    @if($participant->checked_in_at)
                    <div style="font-size:0.8rem; color:#10b981;">{{ $participant->checked_in_at->format('H:i') }} WIB</div>
                    @endif
                </div>
            </div>

            {{-- PRE-TEST --}}
            @if($seminar)
            <div class="step-item {{ $enrollment && $enrollment->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->pre_test_passed ? 'done' : ($currentView === 'pre_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->pre_test_passed ? 'check' : 'clipboard-list' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Pre-Test</div>
                    @if($enrollment && $enrollment->pre_test_passed)
                    <div style="font-size:0.8rem; color:#10b981;">Lulus — {{ $enrollment->pre_test_score }}%</div>
                    @endif
                </div>
            </div>

            {{-- MATERI --}}
            <div class="step-item {{ $enrollment && $enrollment->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->material_viewed ? 'done' : ($currentView === 'material' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->material_viewed ? 'check' : 'book-open' }}"></i>
                </div>
                <div><div style="font-weight:600;">Materi Pelatihan</div></div>
            </div>
            @endif

            {{-- CHECK-OUT --}}
            <div class="step-item {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $participant->checked_out_at ? 'done' : ($currentView === 'checkout' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $participant->checked_out_at ? 'check' : 'sign-out-alt' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Check-out</div>
                    @if($participant->checked_out_at)
                    <div style="font-size:0.8rem; color:#3b82f6;">{{ $participant->checked_out_at->format('H:i') }} WIB</div>
                    @endif
                </div>
            </div>

            {{-- POST-TEST --}}
            @if($seminar)
            <div class="step-item {{ $enrollment && $enrollment->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                <div class="step-icon {{ $enrollment && $enrollment->post_test_passed ? 'done' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                    <i class="fas fa-{{ $enrollment && $enrollment->post_test_passed ? 'check' : 'clipboard-check' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Post-Test</div>
                    @if($enrollment && $enrollment->post_test_passed)
                    <div style="font-size:0.8rem; color:#10b981;">Lulus — {{ $enrollment->post_test_score }}%</div>
                    @endif
                </div>
            </div>
            @endif

            {{-- TUGAS --}}
            <div class="step-item {{ $submission && $submission->status === 'approved' ? 'done' : ($currentView === 'task' ? 'active' : ($submission ? 'done' : 'locked')) }}">
                <div class="step-icon {{ $submission ? 'done' : ($currentView === 'task' ? 'active' : 'locked') }}" style="{{ $submission && $submission->status !== 'approved' ? 'background:#f59e0b;' : '' }}">
                    <i class="fas fa-{{ $submission ? 'check' : 'file-upload' }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;">Kumpul Tugas</div>
                    @if($submission)
                    <div style="font-size:0.8rem; color:#f59e0b;">{{ ucfirst($submission->status) }}</div>
                    @endif
                </div>
            </div>

            {{-- SERTIFIKAT --}}
            <div class="step-item {{ $participant->certificate_path ? 'done' : 'locked' }}">
                <div class="step-icon {{ $participant->certificate_path ? 'done' : 'locked' }}">
                    <i class="fas fa-{{ $participant->certificate_path ? 'check' : 'certificate' }}"></i>
                </div>
                <div><div style="font-weight:600;">Sertifikat</div></div>
            </div>
        </div>
    </div>

    {{-- KONTEN AKTIF --}}

    {{-- CHECK-IN --}}
    @if($currentView === 'checkin')
    <div class="card">
        <h3 style="margin-bottom:8px;">Check-in Kehadiran</h3>
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
    <div class="card">
        <h3 style="margin-bottom:8px;">Pre-Test</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Kerjakan pre-test sebelum mengakses materi pelatihan.</p>
        <a href="{{ route('digital.seminar.learn', $enrollment->order->order_number) }}" class="btn btn-primary btn-block">
            <i class="fas fa-play"></i> Mulai Pre-Test
        </a>
    </div>
    @endif

    {{-- MATERI --}}
    @if($currentView === 'material')
    <div class="card">
        <h3 style="margin-bottom:8px;">Materi Pelatihan</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Pelajari materi berikut selama sesi pelatihan berlangsung.</p>
        <a href="{{ route('digital.seminar.learn', $enrollment->order->order_number) }}" class="btn btn-primary btn-block">
            <i class="fas fa-book-open"></i> Buka Materi
        </a>
    </div>
    @endif

    {{-- CHECK-OUT --}}
    @if($currentView === 'checkout')
    <div class="card">
        <h3 style="margin-bottom:8px;">Check-out</h3>
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
    <div class="card">
        <h3 style="margin-bottom:8px;">Post-Test</h3>
        <p style="color:#6b7280; margin-bottom:20px; font-size:0.9rem;">Kerjakan post-test untuk mengukur pemahaman Anda setelah pelatihan.</p>
        <a href="{{ route('digital.seminar.learn', $enrollment->order->order_number) }}" class="btn btn-primary btn-block">
            <i class="fas fa-clipboard-check"></i> Mulai Post-Test
        </a>
    </div>
    @endif

    {{-- TUGAS --}}
    @if($currentView === 'task')
    <div class="card">
        <h3 style="margin-bottom:8px;">Kumpulkan Tugas</h3>
        <p style="color:#6b7280; margin-bottom:4px; font-size:0.9rem;">Upload tugas Anda ke Google Drive, lalu tempelkan linknya di sini.</p>
        <p style="font-size:0.8rem; color:#9ca3af; margin-bottom:20px;">Pastikan link Google Drive sudah diset ke "Anyone with the link can view".</p>

        @if($submission && $submission->status === 'revision')
        <div class="alert alert-warning" style="margin-bottom:16px;">
            <i class="fas fa-exclamation-triangle"></i> <strong>Perlu Revisi:</strong> {{ $submission->feedback }}
        </div>
        @endif

        <form method="POST" action="{{ route('training.participant.task.submit', $participant->access_token) }}">
            @csrf
            <div class="form-group">
                <label>Link Google Drive Tugas *</label>
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
        <p style="color:#6b7280; margin-top:8px;">Semua tahapan sudah selesai! Sertifikat Anda sedang diproses oleh admin.</p>
    </div>
    @endif

    {{-- SELESAI + DOWNLOAD SERTIFIKAT --}}
    @if($currentView === 'completed')
    <div class="card" style="text-align:center; background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:40px;">
        <i class="fas fa-certificate" style="font-size:3rem; margin-bottom:16px;"></i>
        <h2>Selamat!</h2>
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