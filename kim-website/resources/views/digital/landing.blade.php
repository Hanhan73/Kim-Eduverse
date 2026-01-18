<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - KIM Digital</title>
    <meta name="description" content="{{ $product->short_description ?? $product->description }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* Reset untuk navbar */
    * {
        box-sizing: border-box;
    }

    /* Sticky Navbar */
    .lp-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        padding: 12px 0;
        transition: all 0.3s ease;
    }

    .lp-navbar.scrolled {
        padding: 8px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
    }

    .lp-navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .lp-navbar-logo {
        font-size: 20px;
        font-weight: 700;
        color: #667eea;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lp-navbar-logo:hover {
        color: #5a67d8;
    }

    .lp-navbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .lp-navbar-price {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-right: 8px;
    }

    .lp-navbar-price small {
        font-size: 12px;
        color: #a0aec0;
        font-weight: 400;
        display: block;
    }

    /* Tombol Detail - outline style */
    .lp-btn-detail {
        background: white;
        color: #667eea;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 2px solid #667eea;
    }

    .lp-btn-detail:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    /* Tombol Beli - primary style */
    .lp-btn-buy {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .lp-btn-buy:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Content wrapper untuk push content di bawah navbar */
    .lp-content-wrapper {
        padding-top: 70px;
        /* Height of navbar */
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .lp-navbar-container {
            padding: 0 16px;
        }

        .lp-navbar-price {
            font-size: 13px;
        }

        .lp-btn-detail,
        .lp-btn-buy {
            padding: 10px 16px;
            font-size: 13px;
        }

        .lp-btn-detail span,
        .lp-btn-buy span {
            display: none;
        }

        .lp-navbar-logo {
            font-size: 18px;
        }

        .lp-navbar-right {
            gap: 8px;
        }
    }
    </style>
</head>

<body>
    <!-- Sticky Navbar -->
    <nav class="lp-navbar" id="lpNavbar">
        <div class="lp-navbar-container">
            <a href="{{ route('digital.index') }}" class="lp-navbar-logo">
                <i class="fas fa-graduation-cap"></i>
                {{ $product->landingPage->navbar_logo_text ?? 'KIM Digital' }}
            </a>

            <div class="lp-navbar-right">
                <div class="lp-navbar-price">
                    {{ $product->formatted_price }}
                    <small>Sekali bayar</small>
                </div>

                <!-- Tombol Beli Sekarang - langsung ke cart -->
                <form action="{{ route('digital.cart.add', $product->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="lp-btn-buy">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ $product->landingPage->navbar_button_text ?? 'Beli Sekarang' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Landing Page Content -->
    <div class="lp-content-wrapper">
        {!! $product->landingPage->html_content !!}
    </div>

    <!-- Navbar scroll effect -->
    <script>
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('lpNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll untuk anchor links di dalam landing page
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navbarHeight = document.getElementById('lpNavbar').offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset -
                    navbarHeight - 20;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    </script>
</body>

</html>