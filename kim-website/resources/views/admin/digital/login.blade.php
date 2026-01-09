<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KIM Digital System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 450px;
        padding: 50px 40px;
    }

    .logo {
        text-align: center;
        margin-bottom: 30px;
    }

    .logo i {
        font-size: 3.5rem;
        color: #667eea;
        margin-bottom: 15px;
    }

    .logo h1 {
        font-size: 1.8rem;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .logo p {
        color: #718096;
        font-size: 0.95rem;
    }

    .roles-info {
        background: #f7fafc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 4px solid #667eea;
    }

    .roles-info p {
        color: #4a5568;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }

    .roles-info ul {
        margin: 10px 0 0 20px;
        color: #4a5568;
        font-size: 0.85rem;
    }

    .roles-info ul li {
        margin-bottom: 5px;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #48bb78;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #f56565;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-group input {
        width: 100%;
        padding: 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .remember-me {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .remember-me input {
        width: auto;
        margin-right: 8px;
    }

    .remember-me label {
        color: #4a5568;
        font-size: 0.9rem;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .back-home {
        text-align: center;
        margin-top: 25px;
    }

    .back-home a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .back-home a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
            <h1>KIM Digital System</h1>
            <p>Admin • Collaborator • Bendahara</p>
        </div>

        <div class="roles-info">
            <p><strong><i class="fas fa-info-circle"></i> Login untuk:</strong></p>
            <ul>
                <li><strong>Admin Digital</strong> - Kelola produk & pesanan</li>
                <li><strong>Collaborator</strong> - Creator produk digital</li>
                <li><strong>Bendahara Digital</strong> - Finance officer</li>
            </ul>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            @foreach($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.digital.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    value="{{ old('email', Cookie::get('digital_admin_email')) }}" required autofocus
                    placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="back-home">
            <a href="{{ route('digital.index') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </div>
</body>

</html>