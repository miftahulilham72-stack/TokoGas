<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Username dan password khusus (hardcoded)
        $validUsername = 'admin';
        $validPassword = 'admin123'; // Ganti dengan password yang diinginkan

        // Cek username dan password
        if ($request->username === $validUsername && $request->password === $validPassword) {
            // Set session login
            Session::put('is_admin', true);
            Session::put('username', $request->username);
            
            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang, Admin!');
        }

        // Jika salah
        return redirect()->back()
            ->with('error', 'Username atau Password salah!')
            ->withInput();
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        Session::flush(); // Hapus semua session
        return redirect()->route('login')
            ->with('success', 'Anda telah logout.');
    }

    /**
     * Cek apakah user sudah login (middleware)
     */
    public static function isLoggedIn()
    {
        return Session::has('is_admin') && Session::get('is_admin') === true;
    }
}