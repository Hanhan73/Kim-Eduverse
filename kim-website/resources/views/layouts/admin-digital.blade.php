<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - KIM Digital')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f7fafc;
            color: #2d3748;
        }

        .admin-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-navbar h1 {
            font-size: 1.3rem;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .admin-nav-links a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-nav-links a:hover {
            color: #667eea;
        }

        .logout-form {
            display: inline;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #e53e3e;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flash-message {
            max-width: 1400px;
            margin: 20px auto;
            padding: 15px 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .flash-success {
            background: #c6f6d5;
            color: #22543d;
            border: 2px solid #9ae6b4;
        }

        .flash-error {
            background: #fed7d7;
            color: #742a2a;
            border: 2px solid #fc8181;
        }

        .flash-info {
            background: #bee3f8;
            color: #2c5282;
            border: 2px solid #90cdf4;
        }

        @media (max-width: 768px) {
            .admin-nav-links {
                flex-direction: column;
                gap: 10px;
                align-items: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="admin-navbar">
        <h1>
            <i class="fas fa-shield-alt"></i>
            Admin Panel - KIM Digital
        </h1>
        <div class="admin-nav-links">
            <a href="{{ route('admin.digital.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('admin.digital.categories.index') }}">
                <i class="fas fa-folder"></i> Categories
            </a>
            <a href="{{ route('admin.digital.products.index') }}">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="{{ route('admin.digital.questionnaires.index') }}">
                <i class="fas fa-box"></i> Questionnaires
            </a>
            <a href="{{ route('admin.digital.orders.index') }}">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('digital.index') }}" target="_blank">
                <i class="fas fa-eye"></i> View Site
            </a>
            <form method="POST" action="{{ route('admin.digital.logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="flash-message flash-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="flash-message flash-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(session('info'))
    <div class="flash-message flash-info">
        <i class="fas fa-info-circle"></i>
        <span>{{ session('info') }}</span>
    </div>
    @endif

    <!-- Content -->
    @yield('content')
</body>
</html>
