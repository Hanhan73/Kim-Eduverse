<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KIM EduTech - Platform Pembelajaran Online</title>
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
            --danger: #f56565;
            --warning: #ed8936;
            --dark: #2d3748;
            --light: #f7fafc;
            --gray: #718096;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
        }

        /* NAVBAR */
        .navbar-edutech {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-edutech .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .navbar-edutech .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar-edutech .logo i {
            font-size: 2rem;
        }

        .navbar-edutech .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .navbar-edutech .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .navbar-edutech .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

.btn-login {
    background: linear-gradient(135deg, #ffffff33, #ffffff22);
    color: #fff;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-login:hover {
    background: #fff;
    color: #4b2bbf;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
}

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 20px;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
        }

        /* RECOMMENDATION SECTION */
        .recommendation-section {
            background: white;
            padding: 80px 20px;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .recommendation-section .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 50px;
        }

        .recommendation-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .recommendation-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
        }

        /* Quiz Form - FIXED: Show one question at a time */
        .quiz-form {
            display: none;
        }

        .quiz-form.active {
            display: block;
        }

        .quiz-step {
            display: none;
        }

        .quiz-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .quiz-question label {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
        }

        .quiz-options {
            display: grid;
            gap: 15px;
        }

        .quiz-option {
            position: relative;
        }

        .quiz-option input[type="radio"],
        .quiz-option input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        .quiz-option label {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            background: var(--light);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .quiz-option input:checked + label {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-color: var(--primary);
            color: var(--primary);
        }

        .quiz-option label:hover {
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .quiz-option label i {
            margin-right: 15px;
            font-size: 1.5rem;
        }

        /* Start Screen */
        .start-screen {
            text-align: center;
        }

        .start-screen i {
            font-size: 5rem;
            color: var(--primary);
            margin-bottom: 30px;
        }

        /* Results Screen */
        .results-screen {
            display: none;
        }

        .results-screen.active {
            display: block;
        }

        .recommended-course {
            display: flex;
            gap: 20px;
            padding: 25px;
            background: var(--light);
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .recommended-course:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .recommended-course-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }

        .match-badge {
            display: inline-block;
            padding: 5px 12px;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            padding: 15px 35px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .quiz-navigation {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        /* Progress Bar */
        .quiz-progress {
            margin-bottom: 30px;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        .progress-text {
            text-align: center;
            margin-top: 10px;
            color: var(--gray);
            font-weight: 600;
        }

        /* Stats */
        .stats {
            background: white;
            padding: 60px 20px;
        }

        .stats .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
        }

        /* Featured Courses */
        .featured-courses {
            padding: 80px 20px;
            background: var(--light);
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
        }

        .course-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .recommendation-section .container { padding: 30px 20px; }
            .navbar-edutech .nav-links { display: none; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar-edutech">
        <div class="container">
            <a href="{{ route('edutech.landing') }}" class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>KIM EduTech</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('edutech.landing') }}">Beranda</a></li>
                <li><a href="{{ route('edutech.courses.index') }}">Kursus</a></li>
                @if(session()->has('edutech_user_id'))
                    @if(session('edutech_user_role') === 'admin')
                        <li><a href="{{ route('edutech.admin.dashboard') }}">Dashboard</a></li>
                    @elseif(session('edutech_user_role') === 'instructor')
                        <li><a href="{{ route('edutech.instructor.dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('edutech.student.dashboard') }}">Dashboard</a></li>
                    @endif
                @else
                    <li><a href="{{ route('edutech.login') }}" class="btn-login">Masuk</a></li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <h1>🎓 Belajar Tanpa Batas</h1>
        <p>Temukan course yang sempurna untuk Anda dengan rekomendasi personal kami</p>
    </section>

    <!-- RECOMMENDATION SYSTEM -->
    <section class="recommendation-section">
        <div class="container">
            <div class="recommendation-header">
                <h2>🎯 Temukan Course yang Tepat</h2>
                <p>Jawab beberapa pertanyaan untuk mendapatkan rekomendasi course yang sesuai</p>
            </div>

            <!-- Start Screen -->
            <div id="startScreen" class="start-screen">
                <i class="fas fa-compass"></i>
                <h3>Siap Memulai Perjalanan Belajar?</h3>
                <p>
                    Kami akan membantu Anda menemukan course yang paling cocok berdasarkan:<br>
                </p>
                <button onclick="startQuiz()" class="btn btn-primary">
                    <i class="fas fa-play"></i> Mulai Rekomendasi
                </button>
            </div>

            <!-- Quiz Form -->
            <div id="quizForm" class="quiz-form">
                <div class="quiz-progress">
                    <div class="progress-bar">
                        <div id="progressFill" class="progress-fill" style="width: 0%"></div>
                    </div>
                    <div class="progress-text">
                        Pertanyaan <span id="currentQuestion">1</span> dari 5
                    </div>
                </div>

                <!-- Question 1 -->
                <div class="quiz-step active" data-step="1">
                    <div class="quiz-question">
                        <label>1. Apa tujuan utama Anda dalam belajar?</label>
                        <div class="quiz-options">
                            <div class="quiz-option">
                                <input type="radio" name="goal" id="goal1" value="career">
                                <label for="goal1">
                                    <i class="fas fa-briefcase"></i>
                                    Mengembangkan karir profesional
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="goal" id="goal2" value="skill">
                                <label for="goal2">
                                    <i class="fas fa-tools"></i>
                                    Menguasai skill baru
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="goal" id="goal3" value="hobby">
                                <label for="goal3">
                                    <i class="fas fa-heart"></i>
                                    Hobi dan pengembangan diri
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="quiz-step" data-step="2">
                    <div class="quiz-question">
                        <label>2. Bidang apa yang paling menarik bagi Anda? (Pilih semua yang sesuai)</label>
                        <div class="quiz-options">
                            <div class="quiz-option">
                                <input type="checkbox" name="interest" id="int1" value="Education">
                                <label for="int1">
                                    <i class="fas fa-book"></i>
                                    Pendidikan & Pengajaran
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="checkbox" name="interest" id="int2" value="Language">
                                <label for="int2">
                                    <i class="fas fa-language"></i>
                                    Bahasa Asing
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="checkbox" name="interest" id="int3" value="Teknologi Informasi">
                                <label for="int3">
                                    <i class="fas fa-laptop-code"></i>
                                    Teknologi & Programming
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="checkbox" name="interest" id="int4" value="Desain">
                                <label for="int4">
                                    <i class="fas fa-palette"></i>
                                    Desain & Kreativitas
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="checkbox" name="interest" id="int5" value="Manajemen dan Teknik Industri">
                                <label for="int5">
                                    <i class="fas fa-chart-line"></i>
                                    Manajemen & Bisnis
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="quiz-step" data-step="3">
                    <div class="quiz-question">
                        <label>3. Bagaimana level pengalaman Anda?</label>
                        <div class="quiz-options">
                            <div class="quiz-option">
                                <input type="radio" name="experience" id="exp1" value="beginner">
                                <label for="exp1">
                                    <i class="fas fa-seedling"></i>
                                    Pemula - Baru mulai belajar
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="experience" id="exp2" value="intermediate">
                                <label for="exp2">
                                    <i class="fas fa-layer-group"></i>
                                    Menengah - Sudah punya dasar
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="experience" id="exp3" value="advanced">
                                <label for="exp3">
                                    <i class="fas fa-trophy"></i>
                                    Mahir - Ingin level lanjutan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="quiz-step" data-step="4">
                    <div class="quiz-question">
                        <label>4. Berapa waktu yang bisa Anda dedikasikan per minggu?</label>
                        <div class="quiz-options">
                            <div class="quiz-option">
                                <input type="radio" name="time" id="time1" value="minimal">
                                <label for="time1">
                                    <i class="fas fa-clock"></i>
                                    1-3 jam/minggu (Santai)
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="time" id="time2" value="moderate">
                                <label for="time2">
                                    <i class="fas fa-business-time"></i>
                                    4-7 jam/minggu (Moderat)
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="time" id="time3" value="intensive">
                                <label for="time3">
                                    <i class="fas fa-fire"></i>
                                    8+ jam/minggu (Intensif)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 5 -->
                <div class="quiz-step" data-step="5">
                    <div class="quiz-question">
                        <label>5. Gaya belajar yang Anda sukai?</label>
                        <div class="quiz-options">
                            <div class="quiz-option">
                                <input type="radio" name="learning_style" id="style1" value="visual">
                                <label for="style1">
                                    <i class="fas fa-eye"></i>
                                    Visual - Video & infografis
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="learning_style" id="style2" value="practical">
                                <label for="style2">
                                    <i class="fas fa-hands"></i>
                                    Praktis - Langsung praktek
                                </label>
                            </div>
                            <div class="quiz-option">
                                <input type="radio" name="learning_style" id="style3" value="interactive">
                                <label for="style3">
                                    <i class="fas fa-comments"></i>
                                    Interaktif - Diskusi & kolaborasi
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="quiz-navigation">
                    <button id="prevBtn" onclick="previousStep()" class="btn btn-outline" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Sebelumnya
                    </button>
                    <button id="nextBtn" onclick="nextStep()" class="btn btn-primary">
                        Selanjutnya <i class="fas fa-arrow-right"></i>
                    </button>
                    <button id="submitBtn" onclick="getRecommendations()" class="btn btn-primary" style="display: none;">
                        <i class="fas fa-magic"></i> Lihat Rekomendasi
                    </button>
                </div>
            </div>

            <!-- Results Screen -->
            <div id="resultsScreen" class="results-screen">
                <div style="text-align: center; margin-bottom: 40px;">
                    <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--success);"></i>
                    <h3 style="font-size: 2rem; font-weight: 700; margin: 20px 0 15px;">
                        Kami Menemukan Kursus yang Cocok!
                    </h3>
                    <p style="font-size: 1.1rem; color: var(--gray);">
                        Klik pada Kursus untuk melihat detail dan mulai belajar
                    </p>
                </div>

                <div id="recommendedCourses" class="recommended-courses"></div>

                <div style="text-align: center; margin-top: 30px;">
                    <button onclick="resetQuiz()" class="btn btn-outline">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                    <a href="{{ route('edutech.courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-th"></i> Lihat Semua Kursus
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <i class="fas fa-book"></i>
                <h3>{{ $stats['total_courses'] ?? 0 }}+</h3>
                <p>Kursus Tersedia</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <h3>{{ $stats['total_students'] ?? 0 }}+</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>{{ $stats['total_instructors'] ?? 0 }}+</h3>
                <p>Instruktur Profesional</p>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="featured-courses">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.5rem; font-weight: 800;">✨ Kursus Unggulan</h2>
                <p style="font-size: 1.2rem; color: var(--gray);">Pilihan terbaik untuk memulai perjalanan belajar</p>
            </div>

            <div class="courses-grid">
                @forelse($featuredCourses ?? [] as $course)
                <a href="{{ route('edutech.courses.detail', $course->slug) }}" class="course-card">
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumbnail">
                    @else
                    <div class="course-thumbnail"></div>
                    @endif
                    <div style="padding: 25px;">
                        <span style="display: inline-block; padding: 6px 14px; background: #e6f2ff; color: #4299e1; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 12px;">{{ $course->category }}</span>
                        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 12px;">{{ $course->title }}</h3>
                        <div style="display: flex; align-items: center; gap: 10px; color: var(--gray);">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ $course->instructor->name }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-book" style="font-size: 4rem; color: #cbd5e0;"></i>
                    <p style="color: #718096; font-size: 1.1rem;">Belum ada course tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <script>
        let currentStep = 0;
        const totalSteps = 5;

        function startQuiz() {
            document.getElementById('startScreen').style.display = 'none';
            document.getElementById('quizForm').classList.add('active');
            updateProgress();
        }

        function nextStep() {
            const currentQuiz = document.querySelector('.quiz-step.active');
            const inputs = currentQuiz.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            const isAnswered = Array.from(inputs).some(input => input.checked);

            if (!isAnswered) {
                alert('Silakan pilih minimal satu jawaban!');
                return;
            }

            if (currentStep < totalSteps - 1) {
                currentQuiz.classList.remove('active');
                currentStep++;
                document.querySelectorAll('.quiz-step')[currentStep].classList.add('active');
                updateProgress();
                updateButtons();
            }
        }

        function previousStep() {
            if (currentStep > 0) {
                document.querySelector('.quiz-step.active').classList.remove('active');
                currentStep--;
                document.querySelectorAll('.quiz-step')[currentStep].classList.add('active');
                updateProgress();
                updateButtons();
            }
        }

        function updateProgress() {
            const progress = ((currentStep + 1) / totalSteps) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
            document.getElementById('currentQuestion').textContent = currentStep + 1;
        }

        function updateButtons() {
            document.getElementById('prevBtn').style.display = currentStep > 0 ? 'inline-block' : 'none';
            document.getElementById('nextBtn').style.display = currentStep < totalSteps - 1 ? 'inline-block' : 'none';
            document.getElementById('submitBtn').style.display = currentStep === totalSteps - 1 ? 'inline-block' : 'none';
        }

        function getRecommendations() {
            const interests = Array.from(document.querySelectorAll('input[name="interest"]:checked')).map(i => i.value);
            
            if (interests.length === 0) {
                alert('Silakan pilih minimal satu bidang minat!');
                return;
            }

            displayRecommendations(interests);
            document.getElementById('quizForm').classList.remove('active');
            document.getElementById('resultsScreen').classList.add('active');
        }

        function displayRecommendations(categories) {
            const container = document.getElementById('recommendedCourses');
            const icons = {
                'Education': 'fas fa-book',
                'Language': 'fas fa-language',
                'Teknologi Informasi': 'fas fa-laptop-code',
                'Desain': 'fas fa-palette',
                'Manajemen dan Teknik Industri': 'fas fa-chart-line'
            };

            container.innerHTML = '';
            categories.forEach((category, index) => {
                const match = 95 - (index * 5);
                container.innerHTML += `
                    <a href="{{ route('edutech.courses.index') }}?category=${encodeURIComponent(category)}" class="recommended-course">
                        <div class="recommended-course-icon">
                            <i class="${icons[category] || 'fas fa-star'}"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">${category}</h4>
                            <p style="color: var(--gray); margin-bottom: 10px;">Jelajahi course terbaik di bidang ${category}</p>
                            <span class="match-badge">
                                <i class="fas fa-star"></i> ${match}% Cocok
                            </span>
                        </div>
                    </a>
                `;
            });
        }

        function resetQuiz() {
            currentStep = 0;
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => input.checked = false);
            document.querySelector('.quiz-step.active')?.classList.remove('active');
            document.querySelectorAll('.quiz-step')[0].classList.add('active');
            document.getElementById('resultsScreen').classList.remove('active');
            document.getElementById('startScreen').style.display = 'block';
            document.getElementById('quizForm').classList.remove('active');
            updateProgress();
            updateButtons();
        }
    </script>
</body>

</html>