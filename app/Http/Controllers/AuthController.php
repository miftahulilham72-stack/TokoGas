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
            'role' => 'required|in:admin,kasir,owner',
        ]);

        // Data user (hardcoded)
        $users = [
            'admin' => [
                'password' => 'admin123',
                'role' => 'admin',
                'name' => 'Administrator'
            ],
            'kasir' => [
                'password' => 'kasir123',
                'role' => 'kasir',
                'name' => 'Kasir'
            ],
            'owner' => [
                'password' => 'owner123',
                'role' => 'owner',
                'name' => 'Owner'
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

        // Cek role yang dipilih sesuai
        if ($users[$request->username]['role'] !== $request->role) {
            return redirect()->back()
                ->with('error', 'Role tidak sesuai dengan akun!')
                ->withInput();
        }

        // Set session
        Session::put('is_logged_in', true);
        Session::put('username', $request->username);
        Session::put('role', $request->role);
        Session::put('role_name', $users[$request->username]['name']);
        Session::put('user_data', $users[$request->username]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang, ' . $users[$request->username]['name'] . '!');
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

    /**
     * Check if user has specific role
     */
    public static function hasRole($role)
    {
        return Session::get('role') === $role;
    }
}