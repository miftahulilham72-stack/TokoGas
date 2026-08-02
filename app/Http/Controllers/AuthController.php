<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login manual
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Data user (hanya admin)
        $users = [
            'admin' => [
                'password' => 'admin123',
                'role' => 'admin',
                'name' => 'Administrator',
                'email' => 'admin@tokogas.com'
            ],
        ];

        // Cek apakah username ada
        if (!isset($users[$request->username])) {
            return redirect()->back()
                ->with('error', 'Username tidak ditemukan!')
                ->withInput();
        }

        // Cek password
        if ($users[$request->username]['password'] !== $request->password) {
            return redirect()->back()
                ->with('error', 'Password salah!')
                ->withInput();
        }

        // Set session
        Session::put('is_logged_in', true);
        Session::put('username', $request->username);
        Session::put('role', 'admin');
        Session::put('role_name', 'Administrator');
        Session::put('user_data', $users[$request->username]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang, Administrator!');
    }

    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek email (hardcoded)
        $adminEmail = 'admin@tokogas.com';
        
        if ($request->email !== $adminEmail) {
            return redirect()->back()
                ->with('error', 'Email tidak terdaftar!');
        }

        // Generate reset token (simulasi)
        $token = Str::random(60);
        
        // Simpan token di session (simulasi)
        Session::put('reset_token', $token);
        Session::put('reset_email', $request->email);

        return redirect()->route('login')
            ->with('success', 'Link reset password telah dikirim ke email Anda!');
    }

    /**
     * Tampilkan halaman reset password
     */
    public function showResetPasswordForm($token)
    {
        // Cek token di session
        if (Session::get('reset_token') !== $token) {
            return redirect()->route('login')
                ->with('error', 'Token tidak valid!');
        }

        return view('auth.reset-password', compact('token'));
    }

    /**
     * Proses update password baru
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        // Cek token di session
        if (Session::get('reset_token') !== $request->token) {
            return redirect()->route('login')
                ->with('error', 'Token tidak valid!');
        }

        // Hapus token
        Session::forget('reset_token');
        Session::forget('reset_email');

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login.');
    }

    /**
     * Redirect ke Google untuk login
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }

        // Cari user berdasarkan email
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            // Buat user baru jika belum terdaftar
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(Str::random(32)), // Password random
                'role' => 'admin', // Default role
            ]);

            // Set session
            Session::put('is_logged_in', true);
            Session::put('username', 'admin');
            Session::put('role', 'admin');
            Session::put('role_name', 'Administrator');
            Session::put('user_data', [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin'
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '! Akun berhasil dibuat.');
        }

        // Update google_id jika kosong
        if (empty($user->google_id)) {
            $user->update(['google_id' => $googleUser->id]);
        }

        // Set session
        Session::put('is_logged_in', true);
        Session::put('username', $user->name);
        Session::put('role', $user->role ?? 'admin');
        Session::put('role_name', 'Administrator');
        Session::put('user_data', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'admin'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('login')
            ->with('success', 'Anda telah logout.');
    }

    /**
     * Cek apakah user sudah login (middleware)
     */
    public static function isLoggedIn()
    {
        return Session::has('is_logged_in') && Session::get('is_logged_in') === true;
    }

    /**
     * Get current user role
     */
    public static function getRole()
    {
        return Session::get('role', 'guest');
    }
}