<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Sesi;
use App\Models\Settings;
use App\Models\Layanan;
use App\Models\Ruangan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\JadwalDokter;

class FormKunjunganController extends Controller
{
    public function create(Request $request)
    {
        $idPasien = $request->query('id');
        $pasien = Pasien::where('Id_Pasien', $idPasien)->firstOrFail();
        $layananList = Layanan::all();

         // Ambil jadwal dokter
        $dokters = Dokter::all();
        $ruangans = Ruangan::pluck('Lantai', 'Nama_Ruangan');

        $kunjungan = DB::table('tr_kunjungan')
            ->select('Id_Dokter', 'Id_Ruangan', DB::raw("TIME_FORMAT(Jadwal, '%H:%i') as jam"), DB::raw('COUNT(*) as jumlah'))
            ->whereDate('Tanggal_Registrasi', Carbon::today())
            ->whereNotNull('Jadwal')
            ->groupBy('Id_Dokter', 'Id_Ruangan', DB::raw("TIME_FORMAT(Jadwal, '%H:%i')"))
            ->get()
            ->groupBy(function ($item) {
                return "{$item->Id_Dokter}|{$item->Id_Ruangan}|{$item->jam}";
            });

        $jadwal = $dokters->map(function ($dokter) {
            return [
                'Id_Dokter' => $dokter->Id_Dokter,
                'nama' => $dokter->Nama_Dokter,
                'spesialis' => $dokter->Spesialis,
                'jadwal' => json_decode($dokter->Jadwal_Dokter, true) ?? [],
            ];
        });

            return view('form.formkunjungan', compact('pasien', 'layananList','jadwal', 'ruangans', 'kunjungan'));
        }

    public function getNamaLayanan(Request $request)
    {
        $id_layanan = $request->id_layanan;
        $layanan = Layanan::where('Id_Layanan', $id_layanan)->first();

        if (!$layanan) {
            return response()->json(['error' => 'Layanan tidak ditemukan.'], 404);
        }

        return response()->json([
            'spesialis' => $layanan->Nama_Layanan 
        ]);
    }

    public function getJamByLayanan(Request $request)
    {
        $tanggal = $request->tanggal;
        $id_layanan = $request->id_layanan;
        $hari = ucfirst(Carbon::parse($tanggal)->locale('id')->dayName);

        $dokters = Dokter::where('Spesialis', $id_layanan)->get();
        $jamList = [];

        foreach ($dokters as $dokter) {
            $jadwal = json_decode($dokter->Jadwal_Dokter, true);
            if (!isset($jadwal[$hari])) continue;
    
            foreach ($jadwal[$hari] as $slot) {
                if (isset($slot['start'], $slot['end'])) {
                    $jamLabel = $slot['start'] . ' - ' . $slot['end'];
                    if (!in_array($jamLabel, $jamList)) {
                        $jamList[] = $jamLabel;
                    }
                }
            }
        }

        sort($jamList);
        return response()->json($jamList);
    }

    public function getDokterByLayanan(Request $request)
    {
        $tanggal = $request->tanggal;
        $jam = $request->jam;
        $id_layanan = $request->id_layanan;
        $hari = ucfirst(Carbon::parse($tanggal)->locale('id')->dayName);

        if (!$tanggal || !$jam || !$id_layanan) {
            return response()->json(['error' => 'Data tidak lengkap'], 400);
        }

        list($start, $end) = explode(' - ', $jam);

        // Ambil dokter berdasarkan spesialis (nama layanan)
        $dokters = Dokter::where('Spesialis', $id_layanan)->get();
        $hasil = [];


        // ✅ Log di sini
        Log::info('Pilih Dokter', [
            'layanan' => $id_layanan,
            'tanggal' => $tanggal,
            'jam' => $jam,
            'hari' => $hari,
            'jumlah_dokter' => $dokters->count()
        ]);

        foreach ($dokters as $dokter) {
            $decoded = json_decode($dokter->Jadwal_Dokter, true);
            Log::info('Jadwal Dokter', [
                'dokter' => $dokter->Nama_Dokter,
                'jadwal' => $decoded
            ]);
    
            if (!is_array($decoded)) continue;
    
            $jadwalsHari = $decoded[$hari] ?? [];
    
            foreach ($jadwalsHari as $jadwal) {
                if (
                    isset($jadwal['start'], $jadwal['end']) &&
                    $jadwal['start'] === $start &&
                    $jadwal['end'] === $end
                ) {
                    $kuota = $jadwal['kuota'] ?? 0;
                    $terpakai = Kunjungan::where('Id_Dokter', $dokter->Id_Dokter)
                        ->whereDate('Jadwal_Kedatangan', $tanggal)
                        ->count();
                    $sisa = $kuota - $terpakai;
    
                    if ($sisa > 0) {
                        $hasil[] = [
                            'id_dokter' => $dokter->Id_Dokter,
                            'nama' => $dokter->Nama_Dokter,
                            'spesialis' => $dokter->Spesialis,
                            'ruangan' => $jadwal['ruang'] ?? '-',
                            'sisa_kuota' => $sisa
                        ];
                    }
                }
            }
        }

        return response()->json($hasil);
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

    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:ms_pasien,Id_Pasien',
            'id_dokter' => 'required|exists:ms_dokter,Id_Dokter',
            'id_layanan' => 'required|exists:ms_layanan,Id_Layanan',
            'pilih_tanggal' => 'required|date',
            'keluhan' => 'required|string|max:255',
            'jam' => 'required|string', // pastikan jam dikirim
        ]);

        $idPasien = $request->id_pasien;
        $idDokter = $request->id_dokter;
        $idLayanan = $request->id_layanan;
        $jadwalKedatangan = Carbon::parse($request->pilih_tanggal)->toDateString();
        $keluhan = $request->keluhan;
        $dokter = \App\Models\Dokter::findOrFail($idDokter);

        // Ambil jadwal dokter berdasarkan hari
        $hari = ucfirst(Carbon::parse($jadwalKedatangan)->locale('id')->dayName);
        $decodedJadwal = json_decode($dokter->Jadwal_Dokter, true);
        $jadwalHariIni = collect($decodedJadwal[$hari] ?? []);

        if ($jadwalHariIni->isEmpty()) {
            return back()->with('error', 'Terapis tidak memiliki jadwal pada hari tersebut.');
        }

        // Ambil jam dari form
        $jamDipilih = $request->jam;
        [$jamStart, $jamEnd] = explode(' - ', $jamDipilih);

        // Cek apakah sudah daftar di jam yang sama
        $sudahDaftarJamYangSama = \App\Models\Kunjungan::where('Id_Pasien', $idPasien)
            ->where('Id_Dokter', $idDokter)
            ->whereDate('Jadwal_Kedatangan', $jadwalKedatangan)
            ->where('Jadwal', $jamStart . ' - ' . $jamEnd)
            ->exists();

        if ($sudahDaftarJamYangSama) {
            return back()->with('error', 'Anda sudah terdaftar pada jam tersebut.');
        }

        // Hitung total kuota & kuota terpakai untuk hari itu
        $totalKuota = $jadwalHariIni->sum('kuota');
        $kuotaTerpakai = \App\Models\Kunjungan::where('Id_Dokter', $idDokter)
            ->whereDate('Jadwal_Kedatangan', $jadwalKedatangan)
            ->count();

        if ($kuotaTerpakai >= $totalKuota) {
            return back()->with('error', 'Kuota terapis untuk hari tersebut sudah penuh.');
        }

        // Cek indikasi lainnya
        $keluhanLain = null;
        if ($idLayanan === 'lainnya') {
            $Nama_Layanan = trim($request->layanan_lain);
            if (!$Nama_Layanan) {
                return back()->with('error', 'Nama_Layanan keluhan harus diisi.');
            }

            $keluhanLain = $Nama_Layanan;
            do {
                $idLayanan = 'INDL' . mt_rand(100000, 999999);
            } while (Layanan::where('Id_Layanan', $idLayanan)->exists());
        }

        // Nomor urut otomatis
        $nomorUrut = \App\Models\Kunjungan::where('Id_Dokter', $idDokter)
            ->whereDate('Jadwal_Kedatangan', $jadwalKedatangan)
            ->where('Jadwal', $jamStart . ' - ' . $jamEnd)
            ->max('Nomor_Urut');
        $nomorUrut = $nomorUrut ? $nomorUrut + 1 : 1;

        $pasien = \App\Models\Pasien::findOrFail($idPasien);
        $idKunjungan = 'KUNJ' . now()->format('YmdHis') . rand(100, 999);
        $qrk = route('pasien.form.nourut', ['id' => $idPasien]);

        // Cari ruangan dari jadwal yang dipilih
        $idRuangan = null;
        foreach ($jadwalHariIni as $slot) {
            if (
                isset($slot['start'], $slot['end'], $slot['ruang']) &&
                $slot['start'] === $jamStart &&
                $slot['end'] === $jamEnd
            ) {
                $idRuangan = \App\Models\Ruangan::where('Nama_Ruangan', $slot['ruang'])->value('Id_Ruangan');
                break;
            }
        }
        try {
            DB::beginTransaction();

            \App\Models\Kunjungan::create([
                'Id_Kunjungan' => $idKunjungan,
                'Id_Pasien' => $idPasien,
                'Id_Dokter' => $idDokter,
                'Id_Ruangan' => $idRuangan,
                'Id_Layanan' => $idLayanan,
                'Keluhan' => $keluhan,
                'Nik' => $pasien->Nik,
                'Nama_Pasien' => $pasien->Nama_Pasien,
                'Tanggal_Registrasi' => now()->toDateString(),
                'Jadwal_Kedatangan' => $jadwalKedatangan,
                'Nomor_Urut' => $nomorUrut,
                'QRK' => $qrk,
                'Status' => 'terdaftar',
                'Jadwal'=> $jamStart . ' - ' . $jamEnd, 
                'Create_Date' => now(),
                'Create_By' => 'Form',
            ]);

            // Kurangi kuota pada slot yang sesuai
            foreach ($decodedJadwal[$hari] as $index => $slot) {
                if (
                    isset($slot['start'], $slot['end'], $slot['kuota']) &&
                    $slot['start'] === $jamStart &&
                    $slot['end'] === $jamEnd &&
                    $slot['kuota'] > 0
                ) {
                    $decodedJadwal[$hari][$index]['kuota'] = $slot['kuota'] - 1;
                    break;
                }
            }

            // Simpan kembali ke DB
            $dokter->Jadwal_Dokter = json_encode($decodedJadwal);
            $dokter->save();

            DB::commit();

            $route = Auth::user()?->role === 'admin' ? 'admin.form.nourut' : 'pasien.form.nourut';
            return redirect()->route($route, ['id' => $idPasien])
                ->with('success', 'Pendaftaran berhasil. Nomor urut: ' . $nomorUrut);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

//NO URUT
    public function nourut($id)
{
    // Ubah status 'terdaftar' jadi 'menunggu' untuk hari ini
    Kunjungan::where('Id_Pasien', $id)
        ->where('Status', 'terdaftar')
        ->whereDate('Jadwal_Kedatangan', Carbon::today('Asia/Jakarta'))
        ->update(['Status' => 'menunggu']);

    // Ambil data kunjungan pasien hari ini
    $kunjunganData = DB::table('tr_kunjungan as tk')
        ->join('ms_dokter as md', 'tk.Id_Dokter', '=', 'md.Id_Dokter')
        ->join('ms_pasien as mp', 'tk.Id_Pasien', '=', 'mp.Id_Pasien')
        ->join('ms_layanan as mi', 'tk.Id_Layanan', '=', 'mi.Id_Layanan')
        ->leftJoin('ms_ruangan as mr', 'tk.Id_Ruangan', '=', 'mr.Id_Ruangan')  
        ->select(
            'tk.*', 
            'md.Nama_Dokter', 
            'mp.Nama_Pasien', 
            'mp.No_Tlp',
            'mi.Nama_Layanan as Nama_Layanan',
            'mr.Nama_Ruangan as Nama_Ruangan'
        )
        ->where('tk.Id_Pasien', $id)
        ->whereDate('tk.Jadwal_Kedatangan', today())
        ->orderByDesc('tk.Create_Date')
        ->get();

    // Tambahkan Total_Antrian untuk setiap data kunjungan
    $kunjunganData->transform(function ($item) {
        $total = DB::table('tr_kunjungan')
            ->where('Id_Dokter', $item->Id_Dokter)
            ->whereDate('Jadwal_Kedatangan', $item->Jadwal_Kedatangan)
            ->count();

        $item->Total_Antrian = $total;
        return $item;
    });

    $qr = optional($kunjunganData->first())->QRK;
    $dataPasien = $kunjunganData->first();

    $liveAntrian = DB::table('tr_kunjungan')
    ->where('Id_Dokter', $dataPasien->Id_Dokter ?? null)
    ->whereDate('Jadwal_Kedatangan', today())
    ->where('Status', 'menunggu') 
    ->min('Nomor_Urut');

    return view('form.form.nourut', compact('kunjunganData', 'dataPasien', 'qr','liveAntrian'));
}

public function getLiveAntrian(Request $request)
{
    $dokterId = $request->query('dokter');
    $tanggal = $request->query('tanggal');

    if (!$dokterId || !$tanggal) {
        return response()->json(['nomor' => null], 400);
    }

    // Ambil nomor urut tertinggi dari pasien yang berstatus 'menunggu'
    $nomor = DB::table('tr_kunjungan')
        ->where('Id_Dokter', $dokterId)
        ->whereDate('Jadwal_Kedatangan', $tanggal)
        ->where('Status', 'menunggu')
        ->min('Nomor_Urut');

    // Total pasien hari ini untuk dokter tsb (semua status)
    $total = DB::table('tr_kunjungan')
        ->where('Id_Dokter', $dokterId)
        ->whereDate('Jadwal_Kedatangan', $tanggal)
        ->count();

    return response()->json([
        'nomor' => $nomor ?? 0, // fallback ke 0 kalau belum ada
        'total' => $total
    ]);
}

 

    //LOGIKA FORM 
    public function getByTanggal(Request $request)
    {
        
        $tanggal = $request->tanggal;
        $id_layanan = $request->Id_Layanan;

        $layanan = Layanan::find($id_layanan);
        if (!$layanan) return response()->json([]);

        $spesialis = $layanan->Nama_Layanan;
        $hari = ucfirst(Carbon::parse($tanggal)->locale('id')->isoFormat('dddd'));

        $dokterList = [];

        foreach (Dokter::where('Spesialis', $spesialis)->get() as $dokter) {
            $jadwal = json_decode($dokter->Jadwal_Dokter, true)[$hari] ?? [];

            foreach ($jadwal as $slot) {
                $kuota = $slot['kuota'] ?? 0;
                $terpakai = Kunjungan::where('Id_Dokter', $dokter->Id_Dokter)
                    ->whereDate('Jadwal_Kedatangan', $tanggal)
                    ->where('Jadwal', $slot['start'] . ' - ' . $slot['end'])
                    ->count();

                $sisa = $kuota - $terpakai;
                if ($sisa > 0) {
                    $dokterList[] = [
                        'id_dokter' => $dokter->Id_Dokter,
                        'nama' => $dokter->Nama_Dokter,
                        'spesialis' => $dokter->Spesialis,
                        'ruangan' => $slot['ruang'] ?? '-',
                        'sisa_kuota' => $sisa
                    ];
                    break; // hanya ambil 1 slot per dokter
                }
            }
        }

        

        return response()->json($dokterList);
    }

    public function getDokterByTanggal(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_layanan' => 'required|string'
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $hari = ucfirst($tanggal->locale('id')->dayName); // Misal: Senin, Selasa
        $spesialis = Layanan::where('Id_Layanan', $request->id_layanan)->value('Nama_Layanan');

        if (!$spesialis) {
            return response()->json([]);
        }

        $dokterList = Dokter::where('Spesialis', $spesialis)->get();
        $result = [];

        foreach ($dokterList as $dokter) {
            $jadwal = json_decode($dokter->Jadwal_Dokter, true);

            if (!isset($jadwal[$hari])) continue;

            $jamList = $jadwal[$hari];
            $kuotaTotal = collect($jamList)->sum('kuota');

            // Hitung kuota terpakai untuk tanggal itu
            $kuotaTerpakai = Kunjungan::where('Id_Dokter', $dokter->Id_Dokter)
                ->whereDate('Jadwal_Kedatangan', $tanggal->toDateString())
                ->count();

            if ($kuotaTerpakai >= $kuotaTotal) continue; // skip kalau penuh

            $result[] = [
                'id_dokter' => $dokter->Id_Dokter,
                'nama' => $dokter->Nama_Dokter,
                'spesialis' => $dokter->Spesialis,
                'sisa_kuota' => $kuotaTotal - $kuotaTerpakai,
                'ruangan' => optional(collect($jamList)->first())['ruang'] ?? 'Tidak diketahui',
            ];
        }

        return response()->json($result);
    } 

    public function getJamByDokter(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_dokter' => 'required|exists:ms_dokter,Id_Dokter',
            'id_layanan' => 'required|string',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();
        $hari = ucfirst(Carbon::parse($tanggal)->locale('id')->dayName);

        $dokter = Dokter::findOrFail($request->id_dokter);
        $jadwal = json_decode($dokter->Jadwal_Dokter, true);

        if (!isset($jadwal[$hari])) {
            return response()->json([]); // Tidak ada jadwal pada hari itu
        }

        $jadwalHari = $jadwal[$hari];
        $response = [];

        foreach ($jadwalHari as $slot) {
            $jamLabel = $slot['start'] . ' - ' . $slot['end'];
            $kuotaSlot = $slot['kuota'] ?? 0;
            $ruanganNama = $slot['ruang'] ?? null;
            $ruanganId = Ruangan::where('Nama_Ruangan', $ruanganNama)->value('Id_Ruangan');

            $sesi = $slot['sesi']?? '-';

            // Hitung kuota terpakai pada jam ini
            $jumlahTerpakai = Kunjungan::where('Id_Dokter', $dokter->Id_Dokter)
                ->whereDate('Jadwal_Kedatangan', $tanggal)
                ->where('Jadwal', $jamLabel)
                ->count();

            $sisaKuota = max(0, $kuotaSlot - $jumlahTerpakai);

            $response[] = [
                'jam' => $jamLabel,
                'ruangan_nama' => $ruanganNama,
                'ruangan_id' => $ruanganId,
                'kuota' => $kuotaSlot,
                'terpakai' => $jumlahTerpakai,
                'sisa_kuota' => $sisaKuota,
                'sesi'=>$sesi,
            ];
        }

        return response()->json($response);
    }

    public function getTanggalByLayanan(Request $request)
    {
        $id_layanan = $request->query('id_layanan');
        $hariIni = Carbon::today();
        $tanggalTersedia = [];

        $spesialis = Layanan::where('Id_Layanan', $id_layanan)->value('Nama_Layanan');

        if (!$spesialis) {
            return response()->json([]);
        }

        $dokterList = Dokter::where('Spesialis', $spesialis)
            ->whereNotNull('Jadwal_Dokter')
            ->get();

        foreach (range(0, 30) as $i) {
            $tanggal = $hariIni->copy()->addDays($i);
            $hari = ucfirst($tanggal->locale('id')->dayName); // Senin, Selasa, dst

            foreach ($dokterList as $dokter) {
                $jadwal = json_decode($dokter->Jadwal_Dokter, true);
                if (isset($jadwal[$hari])) {
                    $tanggalTersedia[] = $tanggal->toDateString();
                    break;
                }
            }
        }

        return response()->json(array_values(array_unique($tanggalTersedia)));
    }

    public function updateStatusMenunggu()
    {
        $today = Carbon::today();

        $updated = Kunjungan::where('Status', 'terdaftar')
            ->whereDate('Jadwal_Kedatangan', $today)
            ->update(['Status' => 'menunggu']);

        return response()->json([
            'message' => 'Status kunjungan diperbarui ke "menunggu".',
            'jumlah_diperbarui' => $updated
        ]);
    }

    public function downloadQRkunjungan($id)
    {
        $kunjungan = Kunjungan::where('Id_Kunjungan', $id)->firstOrFail();
        $qr = QrCode::format('svg')->size(300)->generate($kunjungan->QRK);
        $filename = 'qr_' . $kunjungan->Id_Kunjungan . '.svg';

        return Response::make($qr, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }



}
