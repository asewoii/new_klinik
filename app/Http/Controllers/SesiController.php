<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Sesi;
use App\Models\Kunjungan;
use App\Models\JadwalDokter;
use App\Models\Dokter;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SesiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil Nama Hari Ini
        $hariIni = now()->locale('id')->translatedFormat('l');

        // Ambil Semua Data Sesi
        $sesiList = Sesi::orderByDesc('Create_Date')->get();

        // Loop Tiap Sesi | Cari Dokter yang Aktif di Hari Ini dan Punya Sesi Tersebut
        foreach ($sesiList as $sesi) {
            $namaSesi = $sesi->Nama_Sesi;

            $dokterAktifHariIni = Dokter::all()->filter(function ($dokter) use ($hariIni, $namaSesi) {
                $jadwal = $dokter->Jadwal_Dokter;
                if (!is_array($jadwal) || !isset($jadwal[$hariIni])) return false;

                foreach ($jadwal[$hariIni] as $j) {
                    $sesiDokter = explode(',', $j['sesi'] ?? '');
                    if (in_array($namaSesi, array_map('trim', $sesiDokter))) {
                        return true;
                    }
                }
                return false;
            });

            $sesi->dokterList = $dokterAktifHariIni;

            // Kunjungan hari ini oleh dokter yang sesuai sesi
            $sesi->kunjungan = Kunjungan::whereDate('Tanggal_Registrasi', now())
                ->whereIn('Id_Dokter', $dokterAktifHariIni->pluck('Id_Dokter'))
                ->with('dokter')
                ->get()
                ->filter(function ($kunjungan) use ($hariIni, $namaSesi) {
                    $dokter = $kunjungan->dokter;
                    $jadwal = $dokter->Jadwal_Dokter ?? [];

                    if (!isset($jadwal[$hariIni])) return false;

                    foreach ($jadwal[$hariIni] as $j) {
                        $sesiDokter = explode(',', $j['sesi'] ?? '');
                        if (in_array($namaSesi, array_map('trim', $sesiDokter))) {
                            return true;
                        }
                    }
                    return false;
                });
        }

        return view('sesi.index', compact('sesiList'));
    }


    public function create()
    {
        return view('sesi.create');
    }

    // Menyimpan data sesi baru ke database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nama_Sesi' => 'required|string|max:255',
            'Mulai_Sesi' => 'required|date_format:H:i',
            'Selesai_Sesi' => 'required|date_format:H:i|after:Mulai_Sesi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek nama sesi sudah ada
        if (Sesi::where('Nama_Sesi', $request->Nama_Sesi)->exists()) {
            return back()->withErrors(['Nama_Sesi' => 'Nama sesi sudah digunakan.'])->withInput();
        }

        // Cek apakah jam sesi bentrok
        $jamBentrok = Sesi::where(function ($query) use ($request) {
            $query->whereBetween('Mulai_Sesi', [$request->Mulai_Sesi, $request->Selesai_Sesi])
                ->orWhereBetween('Selesai_Sesi', [$request->Mulai_Sesi, $request->Selesai_Sesi])
                ->orWhere(function ($q) use ($request) {
                    $q->where('Mulai_Sesi', '<=', $request->Mulai_Sesi)
                    ->where('Selesai_Sesi', '>=', $request->Selesai_Sesi);
                });
        })->exists();

        if ($jamBentrok) {
            return back()->withErrors(['Mulai_Sesi' => 'Jam sesi bertabrakan dengan sesi lain.'])->withInput();
        }

        // Generate UUID
        $uuid = Uuid::uuid4();
 
        Sesi::create([
            'Id_Sesi' => $uuid,
            'Nama_Sesi' => $request->Nama_Sesi,
            'Mulai_Sesi' => $request->Mulai_Sesi,
            'Selesai_Sesi' => $request->Selesai_Sesi,
            'Status' => 'Aktif',
            'Create_Date' => now(),
            'Create_By' => Auth::user()->username,
            'Last_Update' => now(),
            'Last_Update_By' => Auth::user()->username,
        ]);


        return redirect()->route('sesi.index')->with('success', 'Data sesi berhasil ditambahkan.');
    }
 
    // Menampilkan form edit sesi
    public function edit($id)
    {
        $sesi = Sesi::findOrFail($id);
        return view('sesi.edit', compact('sesi'));
    }
 
    // Update data sesi
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Sesi' => 'required|string|max:255',
            'Mulai_Sesi' => ['required', 'date_format:H:i'],
            'Selesai_Sesi' => ['required', 'date_format:H:i', 'after:Mulai_Sesi'],
        ]);

        $nama = strtolower(trim($request->Nama_Sesi));
        $mulai = $request->Mulai_Sesi;
        $selesai = $request->Selesai_Sesi;

        // Cek bentrok (kecuali diri sendiri)
        $bentrok = Sesi::where(function ($q) use ($nama, $mulai, $selesai) {
            $q->whereRaw('LOWER(Nama_Sesi) = ?', [$nama])
            ->orWhere(function ($q2) use ($mulai, $selesai) {
                $q2->where('Mulai_Sesi', '<', $selesai)
                    ->where('Selesai_Sesi', '>', $mulai);
            });
        })->where('Id_Sesi', '!=', $id)->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors(['Nama_Sesi' => 'Nama sesi atau jam bentrok dengan sesi lain.'])->withInput();
        }

        $sesi = Sesi::findOrFail($id);

        $sesi->update([
            'Nama_Sesi' => $request->Nama_Sesi,
            'Mulai_Sesi' => Carbon::parse($request->Mulai_Sesi),
            'Selesai_Sesi' => Carbon::parse($request->Selesai_Sesi),
            'Last_Update' => now(),
            'Last_Update_By' => auth::user()->username ?? 'admin',
        ]);

        return redirect()->route('sesi.index')->with('success', 'Data sesi berhasil diperbarui.');
    }


    // Menghapus data sesi
    public function destroy($id)
    {
        $sesi = Sesi::findOrFail($id);
        $sesi->delete();

        return redirect()->route('sesi.index')->with('success', 'Data sesi berhasil dihapus.');
    }

    public function storeMultiple(Request $request)
    {
        $count = count($request->Nama_Sesi);

        for ($i = 0; $i < $count; $i++) {
            $mulai = $request->Mulai_Sesi[$i];
            $selesai = $request->Selesai_Sesi[$i];

            if (!$mulai || !$selesai) continue;

            Sesi::create([
                'Id_Sesi' => Uuid::uuid4(),
                'Nama_Sesi' => $request->Nama_Sesi[$i],
                'Mulai_Sesi' => Carbon::parse($mulai),
                'Selesai_Sesi' => Carbon::parse($selesai),
                'Status' => 'Aktif',
                'Create_Date' => now(),
                'Create_By' => 'admin',
                'Last_Update' => now(),
                'Last_Update_By' => 'admin',
            ]);
        }

        return redirect()->route('sesi.index')->with('success', 'Semua sesi berhasil disimpan.');
    }

    // Menghapus data berdasarkan Select
    public function SelectDelete(Request $request) {
        $ids = $request->input('selected_sesi');

        if(!$ids || !is_array($ids)) {
            return redirect()->route('sesi.index')->with('error', 'Tidak ada data yang di pilih!');
        }

        Sesi::whereIn('Id_Sesi', $ids)->delete();

        return redirect()->route('sesi.index')->with('success', 'Data berhasil di hapus');
    }


    // --- CHECK VALIDASI STORE MODAL SESI --- \\
    public function checkStore(Request $request)
    {
        $exists = Sesi::where('Nama_Sesi', $request->Nama_Sesi)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkStoreJamBentrok(Request $request)
    {
        $mulai = $request->Mulai_Sesi;
        $selesai = $request->Selesai_Sesi;

        if (!$mulai || !$selesai) {
            return response()->json(['bentrok' => false]);
        }

        $jamBentrok = Sesi::where(function ($query) use ($mulai, $selesai) {
            $query->whereBetween('Mulai_Sesi', [$mulai, $selesai])
                ->orWhereBetween('Selesai_Sesi', [$mulai, $selesai])
                ->orWhere(function ($q) use ($mulai, $selesai) {
                    $q->where('Mulai_Sesi', '<=', $mulai)
                    ->where('Selesai_Sesi', '>=', $selesai);
                });
        })->first();

        return response()->json([
            'bentrok' => !is_null($jamBentrok),
            'bentrok_with' => $jamBentrok ? $jamBentrok->Nama_Sesi : null
        ]);
    }




    // --- CHECK VALIDASI EDIT MODAL SESI --- \\
    public function checkSesi(Request $request)
    {
        $nama = strtolower(trim($request->Nama_Sesi));
        $jamMulai = $request->Mulai_Sesi;
        $jamSelesai = $request->Selesai_Sesi;
        $idSesi = $request->Id_Sesi;

        $queryNama = DB::table('ms_sesi')
            ->whereRaw('LOWER(Nama_Sesi) = ?', [$nama]);

        $queryJam = DB::table('ms_sesi')
            ->where('Mulai_Sesi', '<', $jamSelesai)
            ->where('Selesai_Sesi', '>', $jamMulai);

        if ($idSesi) {
            $queryNama->where('Id_Sesi', '!=', $idSesi);
            $queryJam->where('Id_Sesi', '!=', $idSesi);
        }

        $existsNama = $queryNama->exists();
        $sesiBentrok = $queryJam->first();
        $existsJam = !is_null($sesiBentrok);

        return response()->json([
            'exists_nama' => $existsNama,
            'exists_jam' => $existsJam,
            'bentrok_with' => $existsJam ? $sesiBentrok->Nama_Sesi : null
        ]);
    }
}
