<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index() {
        $settings = [
            // OTP Controller
            'batas_percobaan_pin'       => Settings::get('batas_percobaan_pin', 3),
            'limit_reset_percobaan'     => Settings::get('limit_reset_percobaan', 5),
            'blokir_menggunakan_pin'    => Settings::get('blokir_menggunakan_pin', 5),

            'nama_klinik'               => Settings::get('nama_klinik', 'Medical Care'),
            'logo'                      => Settings::get('logo', '/images/medikit.jpg'),
            'otp_expire_sec'            => Settings::get('otp_expire_sec', 1),
            'batas_otp'                 => Settings::get('batas_otp', 3),

            // Pengaturan Fonnte ON/OFF
            'fonnte_active'             => Settings::get('fonnte_active', 1),
        ];
    
        return view('settings.index', compact('settings'));
    }
    public function update(Request $request) {
         // Validasi semua field sesuai kebutuhan
        $request->validate([
            // OTP Controller
            'batas_percobaan_pin'       => 'required|integer|min:1|max:10',
            'limit_reset_percobaan'     => 'required|integer|min:1|max:10',
            'blokir_menggunakan_pin'    => 'required|integer|min:1|max:10',

            'logo'                      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'batas_otp'                 => 'required|integer|min:1|max:10',
            'fonnte_active'             => 'required|integer|min:0|max:1',
            'otp_expire_sec'            => 'required|integer|min:1',
            'nama_klinik'               => 'required|string',
        ]);

        // 1. Proses upload logo jika ada
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/logo');
            $file->move($destinationPath, $filename);
            Settings::set('logo', 'images/logo/' . $filename);
        }

        // 2. Simpan semua pengaturan lain
        Settings::set('batas_percobaan_pin', $request->batas_percobaan_pin);
        Settings::set('limit_reset_percobaan', $request->limit_reset_percobaan);
        Settings::set('blokir_menggunakan_pin', $request->blokir_menggunakan_pin);
        Settings::set('nama_klinik', $request->nama_klinik);
        Settings::set('batas_otp', $request->batas_otp);
        Settings::set('otp_expire_sec', $request->otp_expire_sec);
        Settings::set('fonnte_active', $request->fonnte_active);

        // Cek status Fonnte untuk pesan
        $statusFonnte = $request->fonnte_active ? 'AKTIF' : 'NONAKTIF';


        // 3. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Semua pengaturan berhasil diperbarui.');
    }
}
