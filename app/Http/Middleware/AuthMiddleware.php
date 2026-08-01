<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_admin') || Session::get('is_admin') !== true) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        return $next($request);
    }
}