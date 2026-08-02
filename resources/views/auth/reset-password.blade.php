@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="brand">
    <div class="icon"><i class="fas fa-lock-open"></i></div>
    <h1>Reset <span>Password</span></h1>
    <p>Buat password baru Anda</p>
</div>

<form action="{{ route('reset-password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="fas fa-lock me-1"></i> Password Baru
        </label>
        <div class="input-group-custom">
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" placeholder="Masukkan Password Baru" required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">
            <i class="fas fa-check-circle me-1"></i> Konfirmasi Password
        </label>
        <div class="input-group-custom">
            <span class="input-icon"><i class="fas fa-check-circle"></i></span>
            <input type="password" class="form-control" 
                   id="password_confirmation" name="password_confirmation" 
                   placeholder="Konfirmasi Password" required>
        </div>
    </div>
    
    <button type="submit" class="btn-login">
        <i class="fas fa-save me-2"></i> SIMPAN PASSWORD
    </button>
</form>

<div class="footer-text">
    <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
    </a>
</div>
@endsection