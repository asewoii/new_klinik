<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\Pasien;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Settings;

class OtpController extends Controller
{
    public function index() {
        return view('verify_otp');
    }

    // Cek apakah nomor telepon terdaftar
    public function cekNoTlp(Request $request)
    {
        $request->validate(['no_tlp' => 'required|regex:/^08[0-9]{8,13}$/']);
        $pasien = Pasien::where('No_Tlp', $request->no_tlp)->first();
        return response()->json(['status' => $pasien ? 'found' : 'not_found']);
    }

    // Login PIN dengan Hash Check
    public function loginPin(Request $request) {
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15|regex:/^08[0-9]{8,13}$/',
            'pin' => 'required|string|min:4|max:6'
        ]);

        $nomor_telephone = $request->no_tlp;
        $inputPin  = $request->pin;
        $pasien = Pasien::where('No_Tlp', $nomor_telephone)->first();

        if (!$pasien) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Nomor ini belum terdaftar di sistem kami']);
        }

        // Cek apakah PIN masih kosong
        if (empty($pasien->Pin)) {
            return response()->json([
                'status' => 'no_pin',
                'message' => 'Silakan lakukan reset PIN.'
            ]);
        }

        if (Hash::check($inputPin, $pasien->Pin)) {
            Cache::forget("pin_fail_$nomor_telephone");

            session([
                'pasien' => [
                    'Id_Pasien' => $pasien->Id_Pasien,
                    'Nama_Pasien' => $pasien->Nama_Pasien,
                    'No_Tlp' => $pasien->No_Tlp,
                    'umur' => Carbon::parse($pasien->Tanggal_Lahir)->age,
                    'login_time' => now()->timestamp,
                    'last_activity' => now()
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'redirect_url' => route('pasien.datadiri', ['id' => $pasien->Id_Pasien])
            ]);
        }

        // Hitung percobaan PIN dengan expire
        $failpinKey  = "pin_fail_$nomor_telephone";
        $batasPin = (int) Settings::get('batas_percobaan_pin', 3);
        $Pencegahan_Brute_Force_Sementara = (int) Settings::get('limit_reset_percobaan', 5);
        $Pemblokiran_Akses_Login_Menggunakan_PIN = (int) Settings::get('blokir_menggunakan_pin', 5);


        if (!Cache::has($failpinKey )) {
            Cache::put($failpinKey , 1, now()->addMinutes($Pencegahan_Brute_Force_Sementara));
            $failpin = 1;
        } else {
            $failpin = Cache::increment($failpinKey);
        }

        // bagian blokir PIN
        if ($failpin >= $batasPin) {
            $expireAt = now()->addMinutes((int) $Pemblokiran_Akses_Login_Menggunakan_PIN);
            Cache::put("pin_fail_expire_$nomor_telephone", $expireAt, $expireAt); // Simpan expire
            Cache::put("pin_fail_$nomor_telephone", $failpin, $expireAt); // Perbarui hitungan sampai expire juga

            return response()->json([
                'status' => 'to_otp',
                'message' => "Percobaan PIN salah sebanyak $failpin/$batasPin. ",
                'redirect_url' => route('verify'),
                'expire_at' => $expireAt->format('H:i:s')
            ]);
        }

        return response()->json([
            'status' => 'wrong_pin',
            'message' => "Gagal percobaan PIN ke-$failpin dari $batasPin"
        ]);
    }

    // Kirim OTP ke nomor pasien
    public function sendOtp(Request $request) {
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15',
        ]);

        $nomor_telephone = $request->no_tlp;
        $sessionPasien  = session('pasien');
        $pasien = Pasien::where('No_Tlp', $nomor_telephone)->first();

        // === [ 1. Jika ada session lama tapi nomor berbeda, hapus session ]
        if ($sessionPasien && $sessionPasien['No_Tlp'] !== $nomor_telephone) {
            Session::forget('pasien');
            $sessionPasien = null;
        }

        // === [ 2. Cek apakah sudah verifikasi sebelumnya dan masih dalam durasi aktif ]
        if ($sessionPasien) {
            $lastActivity = $sessionPasien['last_activity'] ?? null;

            // === [ 2.1 Cek Session ]
            if($lastActivity  && now()->diffInMinutes(Carbon::parse($lastActivity)) <= 3) {
                return response()->json([
                    'status' => 'Sudah_Verifikasi',
                    'message' => 'Status: Terverifikasi. Halaman sedang dialihkan...',
                    'redirect_url' => route('pasien.datadiri', ['id' => $sessionPasien['Id_Pasien']])
                ]);
            } else {
                // Sesi tidak valid lagi
                Session::forget('pasien');
            }
        }

        // === [ 3. Cek apakah sistem pengirim OTP aktif ]
        if (!Settings::get('fonnte_active', 1)) {
            return response()->json([
                'status' => 'fonnte_disabled',
                'message' => 'Sistem pengirim OTP (Fonnte) sedang off.'
            ]);
        }

        // === [ 4. Cek data pasien ]
        if (!$pasien) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Nomor telepon tidak dikenali. Cek kembali atau daftar baru'
            ]);
        }

        // === [ 5. Batas pengiriman OTP per jam ]
        $batas_otp = (int) Settings::get('batas_otp', 5);
        $otp_countkey = "otp_count_$nomor_telephone";
        $otp_count = Cache::get($otp_countkey, 0);

        if ($otp_count >= $batas_otp) {
            $start = Cache::get("otp_limit_start_$nomor_telephone");
            $diffInSeconds = now()->diffInSeconds(Carbon::parse($start));

            $remainingSeconds = max(0, 3600 - $diffInSeconds);
            $remainingMinutes = floor($remainingSeconds / 60);
            $remainingHours   = floor($remainingMinutes / 60);
            $remainingMinutes = $remainingMinutes % 60;

            // Buat teks yang lebih manusiawi
            if ($remainingHours > 0) {
                $sisaWaktu = "$remainingHours jam $remainingMinutes menit";
            } else {
                $sisaWaktu = "$remainingMinutes menit";
            }

            return response()->json([
                'status'  => 'limit_reached',
                'message' => "OTP melebihi batas $batas_otp kali per jam. Coba lagi setelah $sisaWaktu."
            ]);
        }

        // Hitung Pengiriman
        if ($otp_count == 0) {
            Cache::put($otp_countkey, 1, now()->addHour());
        } else {
            Cache::increment($otp_countkey);
        }

        // === [ 6. Cek OTP yang masih tersimpan ]
        if (Cache::has("otp_$nomor_telephone")) {
            // Cek apakah OTP sebelumnya *sudah* berhasil dikirim?
            if (Cache::get("otp_sent_success_$nomor_telephone")) {
                return response()->json([
                    'status' => 'waiting',
                    'message' => 'OTP dikirim via WhatsApp. Harap cek pesan masuk Anda.'
                ]);
            } else {
                Cache::forget("otp_$nomor_telephone"); // pastikan OTP gagal tidak tersimpan
            }
        }

        // === [ 7. Generate & simpan OTP ]
        $otp = random_int(100000, 999999); // cryptographically secure
        $otp_expire_min = (int) Settings::get('otp_expire_sec', 1);
        Cache::put("otp_$nomor_telephone", $otp, now()->addMinutes($otp_expire_min));

        // === [ 8. Kirim OTP ke Fonnte (retry 3x) ]
        $fonnte = Settings::get('fonnte_api_key', 'sfmrAXGeMpyt8GyiQN5K');
        $maxRetry = 3;
        $response = null;

        for ($i = 0; $i < $maxRetry; $i++) {
            $response = Http::withHeaders([
                'Authorization' => 'sfmrAXGeMpyt8GyiQN5K',
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $nomor_telephone,
                'message' => "PERINGATAN!\nKode OTP $otp bersifat rahasia. Jangan bagikan kepada siapa pun, demi keamanan akun Anda",
            ]);

            if ($response->successful()) {
                Cache::put("otp_sent_success_$nomor_telephone", true, now()->addMinutes($otp_expire_min));
                return response()->json(['status' => 'otp_sent']);
            }

            sleep(1); // jeda sebelum retry
        }

        // === [ 9. Jika gagal total, hapus OTP agar bisa request ulang ]
        Cache::forget("otp_$nomor_telephone");

        return response()->json([
        'status' => 'error',
        'message' => 'Gagal mengirim kode verifikasi setelah beberapa percobaan. Silakan coba lagi.',
        'debug' => config('app.debug') && $response ? $response->body() : null,
        'http_code' => $response ? $response->status() : 500
    ], 500);
    }

    // Verifikasi OTP yang dimasukkan user
    public function verifyOtp(Request $request) {
        session()->forget('id_pasien');

        $request->validate([
            'no_tlp' => 'required|string',
            'kode_otp' => 'required|string'
        ]);

        $nomor_telephone = $request->no_tlp;
        $inputOtp = $request->kode_otp;
        $cachedOtp = Cache::get("otp_$nomor_telephone");

        if ($cachedOtp && $cachedOtp == $inputOtp) {
            $pasien = Pasien::where('No_Tlp', $nomor_telephone)->first();

            if (!$pasien) {
                return response()->json(['status' => 'not_found', 'message' => 'Pasien tidak terdaftar di database']);
            }

            // Simpan data pasien dan waktu verifikasi di session
            session([
                'pasien' => [
                    'Id_Pasien' => $pasien->Id_Pasien,
                    'Nama_Pasien' => $pasien->Nama_Pasien,
                    'No_Tlp' => $pasien->No_Tlp,
                    'Alamat' => $pasien->Alamat,
                    'umur' => Carbon::parse($pasien->Tanggal_Lahir)->age,
                    'login_time' => now()->timestamp, // catat waktu verifikasi sebagai timestamp
                    'last_activity' => now()
                ]
            ]);

            Cache::forget("otp_$nomor_telephone"); // hapus OTP setelah sukses

            return response()->json([
                'status' => 'verified',
                'data' => session('pasien'),  // kirim langsung array pasien, bukan array di dalam array
                'redirect_url' => route('pasien.datadiri', $pasien->Id_Pasien)

            ]);
        }

        // Jika OTP salah, simpan waktu terakhir gagal dan batasi percobaan dalam 10 detik
        Cache::put("last_failed_$nomor_telephone", now(), now()->addSeconds(10));

        return response()->json(['status' => 'invalid', 'message' => 'Kode OTP tidak valid']);
    }

    public function reset_pin(Request $request) {
        return view('pasien.resetPin');
    }

    // Request OTP untuk Reset PIN
    public function requestOtpResetPin(Request $request) {
        $request->validate([
            'no_tlp' => 'required|string|min:10|max:15',
        ]);

        $nomor_telephone = $request->no_tlp;
        $pasien = Pasien::where('No_Tlp', $nomor_telephone)->first();

        if (!$pasien) {
            return response()->json(['status' => 'not_found', 'message' => 'Nomor tidak terdaftar']);
        }

        // Batas OTP sama seperti fungsi sebelumnya
        $batas_otp = Settings::get('batas_otp', 5);
        $otp_countkey = "otp_reset_count_$nomor_telephone";
        $otp_count = Cache::get($otp_countkey, 0);

        if ($otp_count >= $batas_otp) {
            return response()->json([
                'status' => 'limit_reached',
                'message' => "Batas pengiriman OTP per jam ($batas_otp) sudah tercapai."
            ]);
        }

        // Cek apakah OTP sebelumnya masih berlaku
        $otpData = Cache::get("otp_reset_data_$nomor_telephone");
        if ($otpData) {
            $expireTime = Carbon::parse($otpData['expire_at']);
            $remaining = $expireTime->diffInSeconds(now(), false);

            if ($remaining > 0) {
                return response()->json([
                    'status' => 'waiting',
                    'message' => "Kode OTP sudah dikirim. Silakan cek WhatsApp Anda. Bisa kirim ulang dalam $remaining detik."
                ]);
            }
        }

        $otp = rand(100000, 999999);
        $otp_expire_sec = Settings::get('otp_expire_sec', 1) * 60;
        $expireAt = now()->addSeconds($otp_expire_sec);
        Cache::put("otp_reset_$nomor_telephone", $otp, now()->addSeconds($otp_expire_sec));

        // Simpan ke cache
        Cache::put("otp_reset_data_$nomor_telephone", [
            'otp' => $otp,
            'expire_at' => $expireAt->toDateTimeString()
        ], $expireAt);

        // Hitung Pengiriman
        if ($otp_count == 0) {
            Cache::put($otp_countkey, 1, now()->addHour());
        } else {
            Cache::increment($otp_countkey);
        }

        $fonnte = "kdDmvPWfkqAitgUQLTmT";
        $response = Http::withHeaders([
            'Authorization' => $fonnte,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor_telephone,
            'message' => "Kode OTP Reset PIN Anda adalah: $otp\nJangan berikan kode ini ke siapa pun.",
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'otp_sent', 'expire_at' => $expireAt->format('H:i:s')]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim OTP',
                'debug' => config('app.debug') ? $response->body() : null,
                'http_code' => $response->status()
            ], 500);
        }
    }

    // Verifikasi OTP untuk Reset PIN
    public function verifyOtpResetPin(Request $request) {
        $request->validate([
            'no_tlp' => 'required|string',
            'kode_otp' => 'required|string'
        ]);

        $nomor_telephone = $request->no_tlp;
        $inputOtp = $request->kode_otp;
        $otpData = Cache::get("otp_reset_data_$nomor_telephone");

        // Cek apakah OTP sudah kadaluarsa (tidak ada lagi di cache)
        if (!$otpData) {
            return response()->json([
                'status' => 'expired',
                'message' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.'
            ]);
        }

        if ($otpData && $otpData['otp'] == $inputOtp) {
            Cache::forget("otp_reset_data_$nomor_telephone");
            Session::put("reset_pin_verified_$nomor_telephone", true);

            return response()->json(['status' => 'verified']);
        }

        return response()->json(['status' => 'invalid', 'message' => 'Kode OTP tidak valid']);
    }

    // Simpan PIN Baru
    public function simpanResetPin(Request $request) {
        $request->validate([
            'no_tlp' => 'required|string',
            'pin' => 'required|string|min:4|max:6',
        ]);

        $nomor_telephone = $request->no_tlp;

        if (!Session::get("reset_pin_verified_$nomor_telephone")) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Verifikasi OTP belum dilakukan'
            ]);
        }

        $pasien = Pasien::where('No_Tlp', $nomor_telephone)->first();

        if (!$pasien) {
            return response()->json(['status' => 'not_found', 'message' => 'Maaf, kami tidak menemukan data pasien tersebut']);
        }

        $pasien->Pin = Hash::make($request->pin);
        $pasien->save();

        Session::forget("reset_pin_verified_$nomor_telephone");

        return response()->json([
            'status' => 'success',
            'message' => 'PIN-nya sudah direset! Sekarang kamu bisa login lagi 😊'
        ]);
    }

    // Cek cooldown untuk pembatasan percobaan OTP
    public function check_cd(Request $request)
    {
        $nomor_telephone = $request->no_tlp;
        $lastFail = Cache::get("last_failed_$nomor_telephone");
        $cooldown_percobaan_otp = Settings::get('otp_cooldown_sec', 10);

        if ($lastFail && now()->diffInSeconds($lastFail) < $cooldown_percobaan_otp) {
            return response::json(['cooldown' => true]);
        }

        return response::json(['cooldown' => false]);
    }


    public function cekStatusPin(Request $request)
    {
        $no_tlp = $request->no_tlp;
        $expire = Cache::get("pin_fail_expire_$no_tlp");

        if ($expire && now()->lt(Carbon::parse($expire))) {
            return response()->json([
                'status' => 'blocked',
                'expire_at' => Carbon::parse($expire)->format('H:i:s'),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }



}
