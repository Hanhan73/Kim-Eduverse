<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seminar->title }} - Seminar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --dark: #2d3748;
            --gray: #718096;
            --light: #f7fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: white;
        }

        .seminar-layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top Bar */
        .top-bar {
            background: #1e293b;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .seminar-info h2 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .seminar-info p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .progress-info {
            text-align: right;
        }

        .progress-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 5px;
        }

        .progress-bar-top {
            width: 150px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill-top {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #38a169);
            transition: width 0.3s ease;
        }

        .btn-back {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Content Area */
        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        /* PDF Viewer */
        .pdf-viewer {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            height: 80vh;
            margin-bottom: 20px;
        }

        .pdf-viewer iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Section Card */
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .section-card h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .section-card p {
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 20px;
        }

        /* Quiz Display */
        .quiz-display {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .quiz-header-display {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
        }

        .quiz-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .quiz-info {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quiz-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        .quiz-info-item i {
            color: var(--info);
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #38a169);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
        }

        /* Sidebar */
        .sidebar {
            width: 350px;
            background: #1e293b;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            overflow-y: auto;
        }

        .sidebar h3 {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .progress-item {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-item.completed {
            background: rgba(72, 187, 120, 0.2);
            border: 2px solid var(--success);
        }

        .progress-item.active {
            background: rgba(102, 126, 234, 0.2);
            border: 2px solid var(--primary);
        }

        .progress-item.locked {
            opacity: 0.5;
        }

        .progress-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .progress-item.completed .progress-icon {
            background: var(--success);
        }

        .progress-info-text {
            flex: 1;
        }

        .progress-info-text h4 {
            font-size: 0.95rem;
            margin-bottom: 3px;
        }

        .progress-info-text p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            border: 2px solid var(--success);
            color: var(--success);
        }

        .alert-warning {
            background: rgba(237, 137, 54, 0.1);
            border: 2px solid var(--warning);
            color: var(--warning);
        }

        .alert-info {
            background: rgba(66, 153, 225, 0.1);
            border: 2px solid var(--info);
            color: var(--info);
        }

        /* Quiz Taking */
        .quiz-taking {
            background: white;
            border-radius: 12px;
            padding: 30px;
            color: #333;
        }

        .question-navigator {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .nav-question {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-question.answered {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .nav-question.current {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .question-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e0e0e0;
        }

        .question-number {
            display: inline-block;
            background: var(--primary);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            font-weight: 700;
            margin-right: 15px;
        }

        .question-text {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .answer-option {
            display: block;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .answer-option:hover {
            border-color: var(--primary);
            background: #f0f4ff;
        }

        .answer-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .option-label {
            position: relative;
            z-index: 0;
            display: block;
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .answer-option input[type="radio"]:checked+.option-label {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .quiz-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        @media (max-width: 968px) {
            .seminar-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                max-height: 40vh;
            }
        }
    </style>
</head>

<body>
    <div class="seminar-layout">
        <div class="main-area">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="seminar-info">
                    <h2>{{ $seminar->title }}</h2>
                    <p>{{ $seminar->instructor_name }}</p>
                </div>
                <div style="display: flex; gap: 20px; align-items: center;">
                    <div class="progress-info">
                        <div class="progress-text">Progress: {{ $enrollment->progress_percentage }}%</div>
                        <div class="progress-bar-top">
                            <div class="progress-fill-top" style="width: {{ $enrollment->progress_percentage }}%"></div>
                        </div>
                    </div>
                    <a href="{{ route('digital.payment.success', $order->order_number) }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Content Display -->
            <div class="content-wrapper">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
                @endif

                @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                </div>
                @endif

                @if($currentView === 'pre_test')
                @if($ongoingAttempt)
                @include('digital.seminar.partials.quiz-taking', ['quiz' => $currentQuiz, 'attempt' => $ongoingAttempt,
                'quizType' => 'pre'])
                @else
                @include('digital.seminar.partials.quiz-preview', ['quiz' => $currentQuiz, 'quizType' => 'pre'])
                @endif

                @elseif($currentView === 'material')
                <div class="section-card">
                    <h2>Materi Seminar</h2>
                    <p>{{ $seminar->material_description }}</p>

                    @if($seminar->material_pdf_path)
                    @php
                    preg_match('/\/d\/(.*?)\//', $seminar->material_pdf_path, $matches);
                    $fileId = $matches[1] ?? null;
                    @endphp

                    @if($fileId)
                    <div class="pdf-viewer">
                        <iframe src="https://drive.google.com/file/d/{{ $fileId }}/preview"></iframe>
                    </div>
                    @endif
                    @endif

                    <div style="display: flex; gap: 15px;">
                        <button onclick="markAsViewed()" class="btn btn-success">
                            <i class="fas fa-check"></i> Tandai Sudah Dibaca
                        </button>
                        <a href="{{ route('digital.seminar.download.material', $order->order_number) }}"
                            class="btn btn-primary" target="_blank">
                            <i class="fas fa-download"></i> Download Materi
                        </a>
                    </div>
                </div>

                @elseif($currentView === 'post_test')
                @if($ongoingAttempt)
                @include('digital.seminar.partials.quiz-taking', ['quiz' => $currentQuiz, 'attempt' => $ongoingAttempt,
                'quizType' => 'post'])
                @else
                @include('digital.seminar.partials.quiz-preview', ['quiz' => $currentQuiz, 'quizType' => 'post'])
                @endif

                @elseif($currentView === 'completed')
                <div class="section-card" style="text-align: center; padding: 60px 30px;">
                    <i class="fas fa-trophy" style="font-size: 5rem; color: #ffd700; margin-bottom: 20px;"></i>
                    <h2>Selamat! Seminar Selesai</h2>
                    <p style="font-size: 1.1rem; margin-bottom: 30px;">
                        Anda telah menyelesaikan seminar ini dengan baik!
                    </p>

                    <div
                        style="background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                        <p style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.6); margin-bottom: 5px;">Nomor
                            Sertifikat</p>
                        <p style="font-size: 1.3rem; font-weight: 700;">{{ $enrollment->certificate_number }}</p>
                    </div>

                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="{{ route('digital.seminar.certificate.download', $enrollment->id) }}"
                            class="btn btn-success">
                            <i class="fas fa-download"></i> Download Sertifikat
                        </a>
                        <a href="{{ route('digital.seminar.download.material', $order->order_number) }}"
                            class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> Download Materi
                        </a>
                    </div>
                </div>

                @else
                <div class="section-card">
                    <h2>Selamat Datang!</h2>
                    <p>{{ $seminar->description }}</p>
                    <p style="margin-top: 20px;">Silakan selesaikan tahapan berikut untuk mendapatkan sertifikat:</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Progress -->
        <div class="sidebar">
            <h3>Progress Seminar</h3>

            <div
                class="progress-item {{ $enrollment->pre_test_passed ? 'completed' : ($currentView === 'pre_test' ? 'active' : '') }}">
                <div class="progress-icon">
                    @if($enrollment->pre_test_passed)
                    <i class="fas fa-check"></i>
                    @else
                    <i class="fas fa-clipboard-list"></i>
                    @endif
                </div>
                <div class="progress-info-text">
                    <h4>Pre-Test</h4>
                    <p>{{ $enrollment->pre_test_passed ? 'Selesai' : 'Belum selesai' }}</p>
                </div>
            </div>

            <div
                class="progress-item {{ $enrollment->material_viewed ? 'completed' : ($currentView === 'material' ? 'active' : 'locked') }}">
                <div class="progress-icon">
                    @if($enrollment->material_viewed)
                    <i class="fas fa-check"></i>
                    @elseif(!$enrollment->pre_test_passed)
                    <i class="fas fa-lock"></i>
                    @else
                    <i class="fas fa-file-pdf"></i>
                    @endif
                </div>
                <div class="progress-info-text">
                    <h4>Materi Seminar</h4>
                    <p>{{ $enrollment->material_viewed ? 'Sudah dibaca' : 'Belum dibaca' }}</p>
                </div>
            </div>

            <div
                class="progress-item {{ $enrollment->post_test_passed ? 'completed' : ($currentView === 'post_test' ? 'active' : 'locked') }}">
                <div class="progress-icon">
                    @if($enrollment->post_test_passed)
                    <i class="fas fa-check"></i>
                    @elseif(!$enrollment->canAccessPostTest())
                    <i class="fas fa-lock"></i>
                    @else
                    <i class="fas fa-clipboard-check"></i>
                    @endif
                </div>
                <div class="progress-info-text">
                    <h4>Post-Test</h4>
                    <p>{{ $enrollment->post_test_passed ? 'Selesai' : 'Belum selesai' }}</p>
                </div>
            </div>

            <div class="progress-item {{ $enrollment->is_completed ? 'completed' : 'locked' }}">
                <div class="progress-icon">
                    @if($enrollment->is_completed)
                    <i class="fas fa-check"></i>
                    @else
                    <i class="fas fa-certificate"></i>
                    @endif
                </div>
                <div class="progress-info-text">
                    <h4>Sertifikat</h4>
                    <p>{{ $enrollment->is_completed ? 'Sudah diterbitkan' : 'Belum diterbitkan' }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAsViewed() {
            fetch('{{ route("digital.seminar.material.viewed", $order->order_number) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>