<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user login dan memiliki salah satu role yang diizinkan
        if(Auth::check() && in_array(Auth::user()->role, $roles)) {
            return $next($request); // lanjut jika role cocok
        }

        // Kalau tidak cocok, tolak akses (403 Forbidden)
        abort(403, 'Unauthorized');
        // return redirect('/login')->with('error', 'Akses ditolak.');
    }
}
