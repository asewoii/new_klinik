<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\support\Facades\Validator;
use App\Models\Pasien;

class ScanQRController extends Controller
{
    public function process(Request $request)
    {
        // 1. Validasi request dari frontend
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data QR tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $qrData = $request->input('qr_data');

        // 2. Jika QR diawali "pasien:", artinya kita cari pasien
        if (str_starts_with($qrData, 'pasien:')) {
            $idPasien = str_replace('pasien:', '', $qrData);

            // 3. Cari data pasien pakai model Pasien
            $pasien = Pasien::with(['sesi', 'indikasi'])->where('Id_Pasien', $idPasien)->first();

            // 4. Jika ketemu, kirim datanya
            if ($pasien) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pasien ditemukan.',
                    'data' => $pasien
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan.'
                ]);
            }
        }

        // 5. Jika format QR tidak dikenali
        return response()->json([
            'success' => false,
            'message' => 'Format QR tidak dikenali.'
        ]);
    }
}
