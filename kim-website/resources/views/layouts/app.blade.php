<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'PT KIM - Konsultan, Developer & Edutech')</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home-styles.css') }}">


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #2d3748;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 800;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .logo-text {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: #f7fafc;
            color: #667eea;
        }

        .nav-link.active {
            color: #667eea;
            background: #eef2ff;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 12px;
            padding: 8px;
            min-width: 220px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 10px 15px;
            color: #4a5568;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: #f7fafc;
            color: #667eea;
            transform: translateX(5px);
        }

        /* Contact Button */
        .btn-contact {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2d3748;
            cursor: pointer;
            padding: 8px;
        }

        /* Footer */
        .footer {
            background: #1a202c;
            color: #cbd5e0;
            padding: 60px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand {
            max-width: 300px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .footer-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .footer-description {
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateY(-3px);
        }

        .footer-section h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
        }

        .footer-link {
            display: block;
            color: #cbd5e0;
            text-decoration: none;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-link:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Utility Classes */
        .gradient-text {
            background: linear-gradient(100deg, rgb(235, 167, 19), rgb(255, 255, 255));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: #2d3748;
        }

        /* Mobile Responsive */
        @media (max-width: 968px) {
            .menu-toggle {
                display: block;
            }

            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background: white;
                flex-direction: column;
                padding: 20px;
                transition: left 0.3s ease;
                align-items: flex-start;
                gap: 0;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                width: 100%;
                padding: 15px;
            }

            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                padding-left: 20px;
                margin-top: 0;
                display: none;
            }

            .dropdown.active .dropdown-menu {
                display: block;
            }

            .btn-contact {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .animate-fade-in-delay-2 {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-container">
                <a href="{{ route('home') }}" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <span class="logo-text">KIM Eduverse</span>
                </a>

                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-menu" id="navMenu">
                    <!-- Produk Dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">
                            Produk <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('consultant.index') }}" class="dropdown-item">
                                <i class="fas fa-handshake"></i> KIM Consultant
                            </a>
                            <a href="{{ route('developer.index') }}" class="dropdown-item">
                                <i class="fas fa-code"></i> KIM Developer
                            </a>
                            <a href="{{ route('edutech.index') }}" class="dropdown-item">
                                <i class="fas fa-graduation-cap"></i> KIM Edutech
                            </a>
                        </div>
                    </li>

                    <!-- Tentang Kami Dropdown -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">
                            Tentang Kami <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('about.profile') }}" class="dropdown-item">
                                <i class="fas fa-building"></i> Profil Perusahaan 
                            </a>
                            <a href="{{ route('about.organization') }}" class="dropdown-item">
                                <i class="fas fa-sitemap"></i> Tim Kami
                            </a>
                        </div>
                    </li>

                    <!-- Blog -->
                    <li class="nav-item">
                        <a href="{{ route('blog.index') }}" class="nav-link">
                            Blog
                        </a>
                    </li>

                    <!-- Mitra -->
                    <li class="nav-item">
                        <a href="{{ route('partner.index') }}" class="nav-link">
                            Mitra
                        </a>
                    </li>

                    <!-- Contact Button -->
                    <li class="nav-item">
                        <a href="{{ route('contact.index') }}" class="btn-contact">
                            <i class="fas fa-phone-alt"></i> Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Produk</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('consultant.index') }}" class="footer-link">KIM Consultant</a></li>
                        <li><a href="{{ route('developer.index') }}" class="footer-link">KIM Developer</a></li>
                        <li><a href="{{ route('edutech.index') }}" class="footer-link">KIM Edutech</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Perusahaan</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('about.profile') }}" class="footer-link">Tentang Kami</a></li>
                        <li><a href="{{ route('blog.index') }}" class="footer-link">Blog</a></li>
                        <li><a href="{{ route('partner.index') }}" class="footer-link">Mitra</a></li>
                        <li><a href="{{ route('contact.index') }}" class="footer-link">Kontak</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Kontak</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><i class="fas fa-envelope"></i> info@kim.co.id</li>
                        <li class="footer-link"><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                        <li class="footer-link"><i class="fas fa-map-marker-alt"></i> Bandung, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} PT KIM. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Mobile Dropdown
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                if (window.innerWidth <= 968) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Active link highlighting
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>