<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Jika pasien sudah login, arahkan langsung ke halaman datadiri
        //if (session()->has('pasien')) {
        //    return redirect()->route('user.datadiri', session('pasien')->Id_Pasien);
        //}

        // Jika tidak diberikan guard, pakai null (guard default: 'web')
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect berdasarkan role user
                return match ($user->role) {
                    'admin' => redirect('/admin'),
                    'dokter' => redirect('/dokter/'),
                    default => redirect('/'), // fallback jika role tidak dikenali
                };
            }
        }

        return $next($request); // user belum login → lanjut akses halaman login
    }
}
