<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login - Sistem Manajemen Toko Gas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(247, 151, 30, 0.08), transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(247, 151, 30, 0.05), transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 420px;
            padding: 35px 30px 30px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f7971e, #ffd700, #f7971e);
            border-radius: 24px 24px 0 0;
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        
        .brand .icon {
            display: inline-block;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #f7971e, #ffd700);
            border-radius: 18px;
            line-height: 64px;
            font-size: 30px;
            color: #1a1a2e;
            margin-bottom: 14px;
            box-shadow: 0 8px 30px rgba(247, 151, 30, 0.3);
            transition: transform 0.3s;
        }
        .brand .icon:hover {
            transform: scale(1.05) rotate(-5deg);
        }
        
        .brand h1 {
            font-size: 26px;
            font-weight: 800;
            color: #1a1a2e;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .brand h1 span { color: #f7971e; }
        .brand p {
            color: #6c757d;
            font-size: 13px;
            margin-top: 2px;
        }
        
        .brand .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f7971e, #ffd700);
            color: #1a1a2e;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 14px;
            border-radius: 50px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        
        .form-control {
            border-radius: 12px;
            padding: 11px 16px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
            font-size: 14px;
            background: #f8f9fa;
        }
        .form-control:focus {
            border-color: #f7971e;
            box-shadow: 0 0 0 4px rgba(247, 151, 30, 0.12);
            background: white;
        }
        .form-control::placeholder {
            color: #adb5bd;
            font-size: 13px;
        }
        
        .input-group-custom {
            position: relative;
        }
        
        .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            z-index: 10;
            font-size: 16px;
        }
        
        .input-group-custom .form-control {
            padding-left: 44px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #f7971e, #ffd700);
            border: none;
            padding: 13px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            color: #1a1a2e;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(247, 151, 30, 0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 35px rgba(247, 151, 30, 0.4);
            color: #1a1a2e;
        }
        .btn-login:active { transform: translateY(0); }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 22px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid #e9ecef;
        }
        .divider span {
            padding: 0 15px;
            color: #adb5bd;
            font-size: 12px;
            font-weight: 500;
        }
        
        .btn-google {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 11px;
            font-weight: 600;
            color: #333;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            color: #333;
        }
        .btn-google i { color: #ea4335; font-size: 20px; }
        
        .form-check-input:checked {
            background-color: #f7971e;
            border-color: #f7971e;
        }
        
        .forgot-link {
            color: #f7971e;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .forgot-link:hover { 
            text-decoration: underline;
            color: #e07c0a;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 2px solid #e9ecef;
            color: #6c757d;
            font-size: 12px;
        }
        .footer-text a {
            color: #f7971e;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-text a:hover { text-decoration: underline; }
        
        .alert {
            border-radius: 12px;
            font-size: 13px;
            padding: 10px 16px;
            border: none;
        }
        .alert-danger {
            background: #fef3f2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        .alert-info {
            background: #eff6ff;
            color: #2563eb;
            border-left: 4px solid #2563eb;
        }
        
        .version {
            text-align: center;
            margin-top: 14px;
            color: #adb5bd;
            font-size: 11px;
        }
        
        .version i {
            color: #f7971e;
        }
        
        @media (max-width: 575.98px) {
            .login-container { padding: 24px 18px; border-radius: 18px; }
            .brand .icon { width: 50px; height: 50px; line-height: 50px; font-size: 24px; }
            .brand h1 { font-size: 22px; }
            .form-control { font-size: 13px; padding: 9px 14px; }
            .btn-login { font-size: 14px; padding: 11px; }
            .btn-google { font-size: 13px; padding: 9px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Alert Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Brand -->
        <div class="brand">
            <div class="icon"><i class="fas fa-fire"></i></div>
            <h1>Toko<span>Gas</span></h1>
            <p>Sistem Manajemen Toko Gas</p>
            <span class="admin-badge"><i class="fas fa-shield-alt me-1"></i> Administrator</span>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="fas fa-user me-1"></i> Username
                </label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" placeholder="Masukkan Username" 
                           value="{{ old('username') }}" required autofocus>
                </div>
                @error('username')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i> Password
                </label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Masukkan Password" required>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember" style="font-size: 13px;">
                        <i class="fas fa-check-circle me-1" style="color: #f7971e;"></i> Ingat saya
                    </label>
                </div>
                <a href="{{ route('lupa-password') }}" class="forgot-link">
                    <i class="fas fa-key me-1"></i> Lupa kata sandi?
                </a>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> MASUK
            </button>
        </form>

        <!-- Divider -->
        <div class="divider"><span>atau</span></div>

        <!-- Google Login -->
        <a href="{{ route('auth.google') }}" class="btn-google">
            <i class="fab fa-google"></i> Masuk dengan Google
        </a>

        <!-- Footer -->
        <div class="footer-text">
            Belum memiliki akun?<br>
            <a href="#"><i class="fas fa-headset me-1"></i> Hubungi Admin</a>
        </div>

        <div class="version">
            <i class="fas fa-code"></i> Version 1.0
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>