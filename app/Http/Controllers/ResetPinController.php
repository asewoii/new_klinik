<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\Pasien;
use App\Models\Settings;
use Carbon\Carbon;

class ResetPinController extends Controller
{
    // Halaman Reset PIN
    public function index()
    {
        return view('pasien.resetPin');
    }

    // Kirim OTP untuk Reset PIN
    public function kirimOtp(Request $request)
    {
        // Check validate
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15',
        ]);

        // Check validate
        $nomor = $request->no_tlp;
        $pasien = Pasien::where('No_Tlp', $nomor)->first();

        if (!$pasien) {
            return response()->json(['status' => 'not_found', 'message' => 'Nomor tidak terdaftar']);
        }

        // Batas Pengiriman OTP
        $batas_otp = max(1, Settings::get('batas_otp', 5));
        $otp_key_count = "otp_reset_count_$nomor";
        $otp_count = Cache::get($otp_key_count, 0);

        if ($otp_count >= $batas_otp) {
            return response()->json([
                'status' => 'limit_reached',
                'message' => "Batas pengiriman OTP ($batas_otp kali per jam) sudah tercapai."
            ]);
        }

        // Cek jika masih ada OTP aktif
        if (Cache::has("otp_reset_$nomor")) {
            return response()->json([
                'status' => 'waiting',
                'message' => 'Kode OTP sudah dikirim. Silakan cek WhatsApp Anda.'
            ]);
        }

        // Generate & Kirim OTP
        $otp = random_int(100000, 999999);
        $hash = Hash::make($otp);
        $linkReset = url("/reset-pin?no=$nomor&otp=$otp");
        $otp_expire_min = max(1, Settings::get('otp_expire_sec', 1));
        Cache::put("otp_reset_$nomor", $otp, now()->addMinutes($otp_expire_min));

        // Hitung Pengiriman
        if ($otp_count == 0) {
            Cache::put($otp_key_count, 1, now()->addHour());
        } else {
            Cache::increment($otp_key_count);
        }

        $message = "Kode OTP Reset PIN Anda adalah: $otp\n".
           "Klik untuk reset: $linkReset\n".
           "Kode berlaku selama $otp_expire_min menit.";

        // Kirim via Fonnte
        $token = "kdDmvPWfkqAitgUQLTmT";
        $res = Http::withHeaders(['Authorization' => $token])
            ->asForm()
            ->post('https://api.fonnte.com/send', [
                'target' => $nomor,
                'message' => $message,
            ]);

        if ($res->successful()) {
            return response()->json(['status' => 'otp_sent']);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim OTP.',
                'debug' => config('app.debug') ? $res->body() : null
            ], 500);
        }
    }

    // Verifikasi OTP Reset PIN
    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15',
            'kode_otp' => 'required|string|size:6'
        ]);

        $nomor = $request->no_tlp;
        $inputOtp = $request->kode_otp;
        $cacheOtp = Cache::get("otp_reset_$nomor");

        if ($cacheOtp && $cacheOtp == $inputOtp) {
            Cache::forget("otp_reset_$nomor");
            Session::put("reset_pin_verified_$nomor", true);

            return response()->json(['status' => 'verified']);
        }

        return response()->json(['status' => 'invalid', 'message' => 'Kode OTP salah atau kadaluarsa.']);
    }

    // Simpan PIN Baru
    public function simpanPin(Request $request)
    {
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15',
            'pin' => 'required|string|min:4|max:6'
        ]);

        $nomor = $request->no_tlp;

        if (!Session::get("reset_pin_verified_$nomor")) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Anda belum melakukan verifikasi OTP.'
            ]);
        }

        $pasien = Pasien::where('No_Tlp', $nomor)->first();

        if (!$pasien) {
            return response()->json(['status' => 'not_found', 'message' => 'Data pasien tidak ditemukan']);
        }

        $pasien->Pin = Hash::make($request->pin);
        $pasien->save();

        Session::forget("reset_pin_verified_$nomor");

        return response()->json(['status' => 'success', 'message' => 'PIN berhasil direset. Silakan login kembali.']);
    }

    public function halamanResetPin(Request $request)
    {
        $nomor = $request->query('no');
        $otp = $request->query('otp');

        if (!$nomor || !$otp) {
            return redirect('/')->with('error', 'Link tidak valid.');
        }

        $cacheOtp = Cache::get("otp_reset_$nomor");

        if ($cacheOtp && $cacheOtp == $otp) {
            // Berhasil verifikasi
            Cache::forget("otp_reset_$nomor");
            Session::put("reset_pin_verified_$nomor", true);

            return view('pasien.resetPinForm', ['nomor' => $nomor]); // Form input PIN baru
        }

        return redirect('/')->with('error', 'Link kadaluarsa atau OTP tidak valid.');
    }
}
