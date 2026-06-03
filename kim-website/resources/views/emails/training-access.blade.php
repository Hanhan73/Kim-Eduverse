<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 20px; }
        .wrapper { max-width: 560px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 32px 28px; border-radius: 16px 16px 0 0; text-align: center; }
        .header h1 { margin: 0 0 6px; font-size: 1.4rem; }
        .header p { margin: 0; opacity: 0.85; font-size: 0.9rem; }
        .body { background: white; padding: 28px; border-radius: 0 0 16px 16px; }
        .info-box { background: #f8f9fa; border-radius: 10px; padding: 16px 20px; margin: 20px 0; }
        .info-row { display: flex; gap: 10px; padding: 6px 0; border-bottom: 1px solid #e5e7eb; font-size: 0.9rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; width: 120px; flex-shrink: 0; }
        .info-value { color: #1e293b; font-weight: 600; }
        .btn { display: block; background: linear-gradient(135deg, #667eea, #764ba2); color: #e5e7eb; text-align: center; padding: 14px 24px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1rem; margin: 24px 0;}
        .steps { counter-reset: step; }
        .step { display: flex; gap: 12px; margin-bottom: 12px; font-size: 0.875rem; color: #374151; }
        .step-num { width: 24px; height: 24px; background: #667eea; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; }
        .footer { text-align: center; font-size: 0.8rem; color: #9ca3af; padding: 20px 0 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>🎓 Undangan Pelatihan</h1>
        <p>PT KIM Eduverse</p>
    </div>
    <div class="body">
        <p>Assalamu'alaikum, <strong>{{ $participant->name }}</strong></p>
        <p style="color:#374151; font-size:0.9rem; line-height:1.6;">
            Anda terdaftar sebagai peserta pelatihan berikut. Gunakan link di bawah untuk mengakses halaman pelatihan Anda, mengerjakan pre/post test, absensi, dan mengumpulkan tugas.
        </p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Judul</span>
                <span class="info-value">{{ $training->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ $training->training_date->format('d F Y') }}</span>
            </div>
            @if($training->start_time)
            <div class="info-row">
                <span class="info-label">Waktu</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }} WIB</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Tempat</span>
                <span class="info-value">{{ $training->location }}</span>
            </div>
            @if($training->trainer_name)
            <div class="info-row">
                <span class="info-label">Narasumber</span>
                <span class="info-value">{{ $training->trainer_name }}</span>
            </div>
            @endif
            @if($participant->nip)
            <div class="info-row">
                <span class="info-label">NIP Anda</span>
                <span class="info-value">{{ $participant->nip }}</span>
            </div>
            @endif
        </div>

        <a href="{{ $accessUrl }}" class="btn" style="color: #f8f9fa;">
            Akses Halaman Pelatihan Saya
        </a>

        <p style="font-size:0.85rem; color:#6b7280; margin-bottom:16px;">Yang bisa Anda lakukan di halaman tersebut:</p>
        <div class="steps">
            <div class="step"><div class="step-num">1</div><div>Check-in kehadiran</div></div>
            @if($training->seminar)
            <div class="step"><div class="step-num">2</div><div>Mengerjakan pre-test</div></div>
            <div class="step"><div class="step-num">3</div><div>Mengakses materi pelatihan</div></div>
            @endif
            <div class="step"><div class="step-num">{{ $training->seminar ? '4' : '2' }}</div><div>Check-out kehadiran</div></div>
            @if($training->seminar)
            <div class="step"><div class="step-num">5</div><div>Mengerjakan post-test</div></div>
            @endif
            <div class="step"><div class="step-num">{{ $training->seminar ? '6' : '3' }}</div><div>Mengumpulkan tugas (link Google Drive)</div></div>
            <div class="step"><div class="step-num">{{ $training->seminar ? '7' : '4' }}</div><div>Download sertifikat</div></div>
        </div>

        <div style="background:#fef3c7; border-radius:8px; padding:12px 16px; margin-top:20px; font-size:0.8rem; color:#92400e;">
            <strong>⚠️ Penting:</strong> Simpan email ini dengan baik. Link di atas bersifat personal dan tidak perlu login.
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} PT KIM Eduverse. Email ini dikirim secara otomatis.</p>
    </div>
</div>
</body>
</html>