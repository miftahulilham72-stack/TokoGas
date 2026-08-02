@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="brand">
    <div class="icon"><i class="fas fa-key"></i></div>
    <h1>Lupa <span>Password</span></h1>
    <p>Masukkan email untuk reset password</p>
</div>

<form action="{{ route('lupa-password.proses') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-1"></i> Email
        </label>
        <div class="input-group-custom">
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" placeholder="Masukkan Email" 
                   value="{{ old('email') }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn-login">
        <i class="fas fa-paper-plane me-2"></i> KIRIM LINK RESET
    </button>
</form>

<div class="footer-text">
    <a href="{{ route('login') }}">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
    </a>
</div>
@endsection