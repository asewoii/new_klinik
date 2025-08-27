<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Sesi;
use App\Models\Layanan;
use App\Models\Kunjungan;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PasienController extends Controller
{
    public function index(request $request){
        $limit = $request->input('limit'); // jika null, akan tampil semua
        $pasien = Pasien::query()->orderBy('Create_Date', 'desc'); // === Query awal
        $totals = Pasien::count(); // === Total data (tanpa filter)
        

        // ============= [1. Notifikasi Hari Ini ]
        $jumlah_notifikasi = Layanan::whereDate('Create_Date', Carbon::today())
            ->orderBy('Create_Date', 'desc')
            ->take(5)
            ->get();

        // ============= [1.2 Ambil Cache "sudah_dibaca" ]
        $sudah_dibaca = Cache::get('pasien.dibaca', []);

        // ============= [1.3 Filter Belom Dibaca ]
        $pasien_belum_dibaca = $jumlah_notifikasi->filter(function($item) use ($sudah_dibaca) {
            return !in_array($item->Id_Pasien, $sudah_dibaca);
        });
        $jumlah_notifikasi = $pasien_belum_dibaca->count();


        // ============= [ Data To View ]
        if ($limit && $limit !== 'all') {
            $data = $pasien->paginate($limit)->withQueryString();
        } else {
            $data = $pasien->get();
        }
        return view('pasien.index', compact('data', 'limit', 'pasien', 'jumlah_notifikasi', 'pasien_belum_dibaca', 'totals'));
    }

    

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    { 
        $request->validate([
            'Nik' => 'required|digits:16',
            'Nama_Pasien' => 'required',
            'Tanggal_Lahir' => 'required|date',
            'Jk' => 'required|in:L,P',
            'Alamat' => 'required',
            'No_Tlp' => 'required|regex:/^08[0-9]{8,12}$/',
        ]);
        $umur = Carbon::parse($request->Tanggal_Lahir)->age;
        
        $uuid = uniqid('PSN');
        $qr_url = "http://127.0.0.1:8000/view/datadiri/" . $uuid;

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
            'Tanggal_Registrasi' => now(),
            'Create_Date' => now(),
            'Last_Update' => now(),
            'Last_Update_By' => 'System',
        ]);

        


        return redirect()->route('pasien.index')->with('Pasien berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $pasien = Pasien::where('Id_Pasien', $id)->firstOrFail();
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::where('Id_Pasien', $id)->firstOrFail();

        $request->validate([
            'Nik' => 'required',
            'Nama_Pasien' => 'required',
            'Tanggal_Lahir' => 'required|date',
            'Jk' => 'required|in:L,P',
            'Alamat' => 'required',
            'No_Tlp' => 'required|regex:/^08[0-9]{8,12}$/',
        ]);

        $umur = Carbon::parse($request->Tanggal_Lahir)->age;

        $pasien->update([
            'Nik' => $request->Nik,
            'Nama_Pasien' => $request->Nama_Pasien,
            'Tanggal_Lahir' => $request->Tanggal_Lahir,
            'Umur' => $umur,
            'Jk' => $request->Jk,
            'Alamat' => $request->Alamat,
            'No_Tlp' => $request->No_Tlp,
            'Tanggal_Registrasi' => $request->Tanggal_Registrasi,
            'Last_Update' => now(),
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pasien = Pasien::where('Id_Pasien', $id)->firstOrFail();
        $pasien->delete();
        return redirect()->route('pasien.index')->with('Data pasien berhasil dihapus.');
    }

    public function show($id)
    { 
        // Ambil data pasien
        $pasien = Pasien::where('Id_Pasien', $id)->firstOrFail();

        $riwayatKunjungan = Kunjungan::with([
            'dokter:Id_Dokter,Nama_Dokter', 
            'layanan:Id_Layanan,Nama_Layanan',
            ])

            ->where('Id_Pasien', $id)
            ->orderByDesc('Jadwal_Kedatangan')
            ->get();
    
        return view('pasien.datadiri', compact('pasien','riwayatKunjungan'));
    }

    public function downloadQR($id) {
        $pasien = Pasien::where('Id_Pasien', $id)->firstOrFail();
        $qr = QrCode::format('svg')->size(300)->generate($pasien->Qr_Url);
        $filename = 'qr_' . $pasien->Id_Pasien . '.svg';
    
        return Response::make($qr, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }

    public function logout()
    {
        Session::forget('pasien');
        return redirect()->route('verify')->with('message', 'Anda berhasil logout');
    }

   public function getDetailPemeriksaan($id)
{
    $periksa = Pemeriksaan::with(['dokter', 'kunjungan.layanan'])->where('Id_Kunjungan', $id)->first();

    if (!$periksa || !$periksa->kunjungan) {
        return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }

    $kunjungan = $periksa->kunjungan;

    return response()->json([
        'status' => 'success',
        'data' => [
            'tanggal_pemeriksaan' => \Carbon\Carbon::parse($kunjungan->Tanggal_Registrasi)->format('d M Y'),
            'status'         => $kunjungan->Status ?? '-',
            'dokter'         => $periksa->dokter->Nama_Dokter ?? '-',
            'layanan'       => $kunjungan->layanan->Nama_Layanan ?? '-', // karena di kunjungan
            'diagnosa'       => $periksa->Diagnosa ?? '-',
            'resep'          => $periksa->Resep ?? '-',
            'jam_diperiksa'  => $periksa->Jam_Pemeriksaan ?? '-',
            'catatan'     => $periksa->Catatan ?? '-',
            'ruangan'       => $kunjungan->Id_Ruangan?? '-',
            'tindakan'       => $periksa->Tindakan?? '-',
            'keluhan'       => $kunjungan->Keluhan?? '-',
            'tanggal'        => \Carbon\Carbon::parse($periksa->Tanggal_Pemeriksaan)->format('d M Y'),
        ]
    ]);
}



    // --- Bulk Delete -- \\
    public function SelectDelete(Request $request) {
        $ids = $request->input('selected_pasien');

        if(!$ids || !is_array($ids)) {
            return redirect()->route('pasien.index')->with('error', 'Tidak ada data yang di pilih!');
        }

        Pasien::whereIn('Id_Pasien', $ids)->delete();

        return redirect()->route('pasien.index')->with('success', 'Data berhasil di hapus');
    }
}



