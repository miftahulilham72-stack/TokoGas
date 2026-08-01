<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Toko Gas</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #f7971e, #ffd200);
        }
        
        .brand {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .brand .icon {
            display: inline-block;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f7971e, #ffd200);
            border-radius: 50%;
            line-height: 70px;
            font-size: 32px;
            color: #1a1a2e;
            margin-bottom: 15px;
        }
        
        .brand h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }
        
        .brand h1 span {
            color: #f7971e;
        }
        
        .brand p {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #f7971e;
            box-shadow: 0 0 0 4px rgba(247, 151, 30, 0.15);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
            font-size: 13px;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px 0 0 10px;
            color: #6c757d;
        }
        
        .form-control-with-icon {
            border-radius: 0 10px 10px 0;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #f7971e, #ffd200);
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            color: #1a1a2e;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(247, 151, 30, 0.3);
            color: #1a1a2e;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid #e9ecef;
        }
        
        .divider span {
            padding: 0 15px;
            color: #adb5bd;
            font-size: 13px;
            font-weight: 500;
        }
        
        .btn-google {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: #333;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .btn-google i {
            color: #ea4335;
            margin-right: 10px;
        }
        
        .form-check-input:checked {
            background-color: #f7971e;
            border-color: #f7971e;
        }
        
        .forgot-link {
            color: #f7971e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #6c757d;
            font-size: 13px;
        }
        
        .footer-text a {
            color: #f7971e;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer-text a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
            font-size: 14px;
        }
        
        .alert-danger {
            background: #fef3f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        
        .version {
            text-align: center;
            margin-top: 15px;
            color: #adb5bd;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Alert Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Brand -->
        <div class="brand">
            <div class="icon">
                <i class="fas fa-fire"></i>
            </div>
            <h1>Toko<span>Gas</span></h1>
            <p>Sistem Manajemen Toko Gas</p>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                       id="username" name="username" placeholder="Masukkan Username" 
                       value="{{ old('username') }}" required autofocus>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Masukkan Password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>
                <a href="#" class="forgot-link">Lupa kata sandi?</a>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> MASUK
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>atau</span>
        </div>

        <!-- Google Button -->
        <button class="btn-google" disabled>
            <i class="fab fa-google"></i> Masuk dengan Google
        </button>

        <!-- Footer -->
        <div class="footer-text">
            Belum memiliki akun?<br>
            <a href="#">Hubungi Admin</a>
        </div>

        <div class="version">
            <i class="fas fa-code"></i> Version 1.0
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>