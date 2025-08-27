<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Http;


class FormPasienController extends Controller {
    // ============= [ Menampilkan halaman/formulir registrasi pasien ]
    public function showRegistrationForm()
    {
        $notifError = "Gagal Memuat Halaman Daftar Pasien";
        try {
            return view('form.registpasien');
        } catch (\Exception $e) {
            Log::error('Error showing registration form: ' . $e->getMessage());
            return redirect()->back()->with('error', $notifError);
        }
    }

    public function sendFonnteImage($nomortarget, $imageUrl)
    {
        $fonnte = Settings::get('fonnte_api_key', 'kdDmvPWfkqAitgUQLTmT');

        $response = Http::withHeaders([
            'Authorization' => $fonnte,
        ])->asForm()->post('https://api.fonnte.com/send-image', [
            'target' => $nomortarget,
            'image' => $imageUrl,
            'delay' => 3,
            'schedule' => 0
        ]);

        if (!$response->successful()) {
            Log::error('Fonnte API gagal: ' . $response->body());
        } else {
            Log::info('Fonnte API sukses: ' . $response->body());
        }

    }

    public function sendFonnteMessage($nomortarget, $message) {
        $fonnte = Settings::get('fonnte_api_key', 'kdDmvPWfkqAitgUQLTmT');

        $response = Http::withHeaders([
            'Authorization' => $fonnte, // Aman dari hardcode
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomortarget,
            'message' => $message,
            'delay' => 3,
            'schedule' => 0
        ]);
    
        if (!$response->successful()) {
            Log::error('Fonnte API gagal: ' . $response->body());
        } else {
            Log::info('Fonnte API sukses: ' . $response->body());
        }
    }

    public function formstore(Request $request)
    { 
        $validatedData = $request->validate([
            'Nik' => 'required|string|size:16',
            'Nama_Pasien' => 'required|max:255',
            'Tanggal_Lahir' => 'required|date',
            'Jk' => 'required|in:L,P',
            'Alamat' => 'required|string|max:255',
            'No_Tlp' => 'required|regex:/^08[0-9]{8,13}$/',
            'Pin' => 'required|string|min:4|max:6',
        ]);

        $validatedData['Pin'] = bcrypt($validatedData['Pin']);
        $linklogin = "http://127.0.0.1:8000/login-pasien";
        $umur = Carbon::parse($request->Tanggal_Lahir)->age;

        $uuid = uniqid('PSN');
        $baseUrl="http://127.0.0.1:8000/user/datadiri/";
        $qr_url = $baseUrl.$uuid;

        // Nama file QR
        $qrFileName = 'qr_' . $uuid . '.svg';
        $qrFilePath = public_path('qr/' . $qrFileName);

        // Generate QR ke string lalu simpan ke file, aman tanpa imagick
        $qrImage = QrCode::format('svg')->size(200)->generate($qr_url);
        file_put_contents($qrFilePath, $qrImage);

        // Link gambar QR untuk dikirim ke WA
        $linkQrImage = asset('qr/' . $qrFileName);  

        Pasien::create([
            'Id_Pasien' =>$uuid,
            'Qr_Url' =>$qr_url,
            'Nik' => $request->Nik,
            'Nama_Pasien' => $request->Nama_Pasien,
            'Tanggal_Lahir' => $request->Tanggal_Lahir,
            'Umur' => $umur,
            'Jk' => $request->Jk,
            'Alamat' => $request->Alamat,
            'No_Tlp' => $request->No_Tlp,
            'Pin' => $validatedData['Pin'],
            'Tanggal_Registrasi' => now(),
            'Create_Date' => now(),
            'Last_Update' => now(),
            'Last_Update_By' => 'System',
        ]);

        // Format nomor WA
        $nomortarget = preg_replace('/^0/', '62', $request->No_Tlp);

        // Kirim gambar QR ke WA
        $this->sendFonnteImage($nomortarget, $linkQrImage);

        // Kirim pesan WhatsApp lewat Fonnte
        $message = "Halo *{$request->Nama_Pasien}*, 👋\n\n".
                "Terima kasih telah mendaftar di *Klinik Sehat Medika*.\n\n".
                "Data Diri Anda:\n".
                "• Nama: {$request->Nama_Pasien}\n".
                "• Nomor HP: {$request->No_Tlp}\n".
                "• Pin: {$request->Pin}\n".
                "• Tanggal Registrasi: {$request->Tanggal_Registrasi}\n\n".
                "Silakan login ke halaman berikut menggunakan *Nomor HP* dan *PIN* Anda:\n" .
                "{$linklogin}\n\n" .
                "Jika mengalami kendala, silakan hubungi kami di *08978842567*.\n\n" .
                "Salam sehat,\n" .
                "*Klinik Sehat Medika*";

        $this->sendFonnteMessage($nomortarget, $message);
 
        return redirect()->route('verify')->with('message', 'Berhasil mendaftar, silakan login!');
    }

    public function checkNoTlp(Request $request){
        try {
            $notlp = $request->input('no_tlp');

            if (!preg_match('/^08[0-9]{8,13}$/', $notlp)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Format nomor telepon tidak valid Gunakan 08 Untuk Awalan'
                ]);
            }

            $exists = Pasien::where('No_Tlp', $notlp)->exists();

            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'Nomor telepon sudah terdaftar' : 'Nomor telepon tersedia'
            ]);}
            catch (\Exception $e) {
            Log::error('Error checking No_Tlp: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Gagal mengecek nomor telepon'
            ], 500);
        }
    }

    public function checkNik(Request $request) {
        try {
            $nik = $request->input('nik');

            if (empty($nik) || strlen($nik) !== 16) {
                return response()->json([
                    'available' => false,
                    'message' => 'NIK harus terdiri dari 16 digit.'
                ]);
            }

            $exists = Pasien::where('Nik', $nik)->exists();

            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'NIK sudah terdaftar.' : 'NIK tersedia.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking NIK: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Terjadi kesalahan saat mengecek NIK.'
            ], 500);
        }
    }

}