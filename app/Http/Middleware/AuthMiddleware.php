<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!AuthController::isLoggedIn()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }

        return $next($request);
    }
}