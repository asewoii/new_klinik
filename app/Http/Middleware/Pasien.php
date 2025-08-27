<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\carbon;

class pasien
{
    public function handle(Request $request, Closure $next) {
        // Ambil data pasien dari session
        $pasien = Session::get('pasien');

        // Jika tidak ada data pasien di session → redirect ke halaman verifikasi
        if(!$pasien) {
            return redirect()->route('verify')->with('error', 'Sesi habis, silakan verifikasi ulang.');
        }

        // Cek waktu aktivitas terakhir
        $lastActivity = $pasien['last_activity'] ?? null;

         // Jika sudah lebih dari 3 menit tidak aktif → hapus session dan arahkan ke OTP
        if ($lastActivity && now()->diffInMinutes(Carbon::parse($lastActivity)) > 3) {
            Session::forget('pasien');
            return redirect()->route('verify_otp')->with('error', 'Sesi habis, silakan verifikasi ulang.');
        }

          // Perbarui waktu aktivitas terakhir ke sekarang
        $pasien['last_activity'] = now()->toDateTimeString();
        Session::put('pasien', $pasien);

        return $next($request);
    }
}
