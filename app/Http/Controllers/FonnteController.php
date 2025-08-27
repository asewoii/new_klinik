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

class FonnteController extends Controller
{
    function kirimPesan($nomor_telephone, $pesan)
    {
        $fonnte = "kdDmvPWfkqAitgUQLTmT";
        $response = Http::withHeaders([
            'Authorization' => $fonnte,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor_telephone,
            'message' => $pesan,
        ]);
    
        return $response->json();
    }

    // Fungsi simulasi auto respon dari webhook Fonnte
    public function webhook(Request $request)
    {
        // Ambil data dari Fonnte
        $nomor_telephone = $request->input('number');    // Nomor pengirim
        $pesan = $request->input('message');   // Isi pesan

        // Cek logika AI sederhana
        if (str_contains(strtolower($pesan), 'halo')) {
            $this->kirimPesan($nomor_telephone, 'Halo juga! Ada yang bisa kami bantu?');
        } elseif (str_contains(strtolower($pesan), 'harga')) {
            $this->kirimPesan($nomor_telephone, 'Harga layanan kami mulai dari Rp100.000.');
        } elseif (str_contains(strtolower($pesan), 'terima kasih')) {
            $this->kirimPesan($nomor_telephone, 'Sama-sama! Semoga harimu menyenangkan.');
        }

        // Respon ke Fonnte biar gak error
        return response()->json(['status' => 'success']);
    }
    
}