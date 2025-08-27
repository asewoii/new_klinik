<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\Ruangan;
use App\Models\Layanan;
use App\Molels\Kunjungan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function index(Request $request) {
        $namaRuangan = $request->input('ruangan');
        $searchs = $request->filled('search');
        $datepicker = $request->filled('date_range');
        $limit = $request->input('limit', 10);
        $totals = Dokter::count();
        $dokter = Dokter::query();
        $search = $request->input('search');

        if ($datepicker) {
            [$start, $end] = explode(' - ', $request->date_range);
            $dokter->whereBetween('Create_Date', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ]);
        }

        if ($searchs) {
            $dokter->where(function($q) use ($search) {
                $q->where('Nama_Dokter', 'like', "%$search%")
                  ->orWhere('Spesialis', 'like', "%$search%")
                  ->orWhere('No_Telp', 'like', "%$search%")
                  ->orWhere('Email', 'like', "%$search%");
            });
        }

        $jumlah_notifikasi = Dokter::whereDate('Create_Date', Carbon::today())
            ->orderBy('Create_Date', 'desc')
            ->take(5)
            ->get();

        $sudah_dibaca = Cache::get('dokter.dibaca', []);
        $dokter_belum_dibaca = $jumlah_notifikasi->filter(fn($item) => !in_array($item->Id_Dokter, $sudah_dibaca));

        $jumlah_notifikasi = $dokter_belum_dibaca->count();
        $dokter->orderBy('Create_Date', 'desc');
        $data = $dokter->paginate($limit)->withQueryString();

        return view('dokter.index', compact(
            'data', 'limit', 'jumlah_notifikasi', 'dokter_belum_dibaca', 'totals'
        ))->with([
            'namaRuangan' => $namaRuangan
        ]);
    }

    public function SelectDelete(Request $request)
    {
        $ids = $request->input('selected_dokter');

        if (!$ids || !is_array($ids)) {
            return redirect()->route('dokter.index')->with('error', 'Tidak ada data yang dipilih!');
        }

        Dokter::whereIn('Id_Dokter', $ids)->delete();

        return redirect()->route('dokter.index')->with('success', 'Data berhasil dihapus');
    }
    
    public function create()
    {
        $ruangans = Ruangan::all();
        $layanan = Layanan::orderBy('Nama_Layanan')->get();
        return view('dokter.create',compact('ruangans', 'layanan'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Dokter' => 'required|string|min:5|max:255',
            'Spesialis'   => 'required|string|min:3|max:255',
            'No_Telp'     => 'nullable|string|min:10|max:15',
            'Email'       => 'nullable|email|max:255',
            'Alamat'      => 'nullable|string|max:255',
        ]);

        $spesialis = $request->Spesialis;

        // Auto-create spesialis baru jika belum ada ( OPSIONAL )
        $existing = Layanan::where('Nama_Layanan', $spesialis)->first();
        if (!$existing && $spesialis) {
            Layanan::create([
                'Id_Layanan'    => 'IND-' . strtoupper(Str::random(6)),
                'Nama_Layanan'  => $spesialis,
                'Create_Date'   => now(),
                'Create_By'     => 'admin',
            ]);
        }

        // Format jadwal dokter
        $rawJadwal = $request->input('jadwal', []);
        $formattedJadwal = [];

        foreach ($rawJadwal as $hari => $jam) {
            $starts       = $jam['start'] ?? [];
            $ends         = $jam['end'] ?? [];
            $kuotas       = $jam['kuota'] ?? [];
            $ruangs       = $jam['ruang'] ?? [];

            $count = min(count($starts), count($ends), count($kuotas), count($ruangs));

            for ($i = 0; $i < $count; $i++) {
                $start      = $starts[$i];
                $end        = $ends[$i];
                $kuota      = (int) $kuotas[$i];
                $ruang      = $ruangs[$i];

                if (!empty($start) && !empty($end) && $kuota > 0 && !empty($ruang)) {
                    // Validasi format waktu
                    try {
                        $startTime = Carbon::createFromFormat('H:i', $start);
                        $endTime   = Carbon::createFromFormat('H:i', $end);
                    } catch (\Exception $e) {
                        return back()->withInput()->with('error', "Format jam tidak valid di hari $hari (baris ke-" . ($i + 1) . ")");
                    }

                    // Validasi jam mulai sebelum selesai
                    if ($startTime->gte($endTime)) {
                        return back()->withInput()->with('error', "Jam mulai harus sebelum jam selesai di hari $hari (baris ke-" . ($i + 1) . ")");
                    }

                    // Validasi bentrok jadwal (opsional, jika diaktifkan)
                    if ($this->cekBentrokJadwal($hari, $start, $end, $ruang)) {
                        return back()->withInput()->with('error', "Jadwal bentrok di hari $hari jam $start - $end di ruangan $ruang.");
                    }

                    $sesi = $this->tentukanSesi($start, $end);

                    $formattedJadwal[$hari][] = [
                        'start'       => $start,
                        'end'         => $end,
                        'kuota'       => $kuota,
                        'ruang'       => $ruang,
                        'sesi'        => implode(',', $sesi),
                    ];
                }
            }
        }

        Dokter::create([
            'Id_Dokter'      => Str::uuid(),
            'Nama_Dokter'    => $request->Nama_Dokter,
            'Spesialis'      => $request->Spesialis,
            'No_Telp'        => $request->No_Telp,
            'Email'          => $request->Email,
            'Alamat'         => $request->Alamat,
            'Jadwal_Dokter'  => json_encode($formattedJadwal),
            'Create_Date'    => now(),
            'Create_By'      => 'admin',
            'Last_Update_By' => 'admin',
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dokter = Dokter::findOrFail($id);
        $ruangans = Ruangan::all();

        // Ambil semua jadwal dokter lain (untuk validasi bentrok jika diperlukan)
        $jadwalBentrok = [];

        $doktersLain = Dokter::where('Id_Dokter', '!=', $id)->get();

        foreach ($doktersLain as $d) {
            $jadwal = json_decode($d->Jadwal_Dokter, true) ?? [];
            foreach ($jadwal as $hari => $items) {
                foreach ($items as $item) {
                    if (!empty($item['start']) && !empty($item['end']) && !empty($item['ruang'])) {
                        $jadwalBentrok[] = [
                            'hari'  => $hari,
                            'start' => $item['start'],
                            'end'   => $item['end'],
                            'ruang' => $item['ruang'],
                            'dokter' => $d->Nama_Dokter,
                        ];
                    }
                }
            }
        }

        return view('dokter.edit', compact('dokter', 'ruangans', 'jadwalBentrok'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Dokter' => 'required|string|min:5|max:255',
            'Spesialis'   => 'required|string',
            'No_Telp'     => 'nullable|string|min:10|max:15',
            'Email'       => 'nullable|email|max:255',
            'Alamat'      => 'nullable|string|max:255',
        ]);

        $rawJadwal = $request->input('jadwal');
        $formattedJadwal = [];

        foreach ($rawJadwal as $hari => $jam) {
            $starts       = $jam['start'] ?? [];
            $ends         = $jam['end'] ?? [];
            $kuotas       = $jam['kuota'] ?? [];
            $ruangs       = $jam['ruang'] ?? [];
            //$id_ruangans  = $jam['id_ruangan'] ?? [];

            $count = min(count($starts), count($ends), count($kuotas), count($ruangs)); //count($id_ruangans)

            for ($i = 0; $i < $count; $i++) {
                $start      = $starts[$i];
                $end        = $ends[$i];
                $kuota      = (int) $kuotas[$i];
                $ruang      = $ruangs[$i];
                //$id_ruangan = $id_ruangans[$i] ?? null;

                if (!empty($start) && !empty($end) && $kuota > 0 && !empty($ruang)) { // && !empty($id_ruangan)
                    // Validasi: format waktu
                    try {
                        $startTime = Carbon::createFromFormat('H:i', $start);
                        $endTime   = Carbon::createFromFormat('H:i', $end);
                    } catch (\Exception $e) {
                        return back()->withInput()->with('error', "Format jam tidak valid di hari $hari (baris ke-" . ($i + 1) . ")");
                    }

                    // Validasi: jam mulai sebelum jam selesai
                    if ($startTime->gte($endTime)) {
                        return back()->withInput()->with('error', "Jam mulai harus sebelum jam selesai di hari $hari (baris ke-" . ($i + 1) . ")");
                    }

                    // Validasi bentrok jadwal (aktifkan jika perlu)
                    if ($this->cekBentrokJadwal($hari, $start, $end, $ruang, $id)) {
                        return back()->withInput()->with('error', "Jadwal bentrok di hari $hari jam $start - $end di ruangan $ruang.");
                    }

                    // Hitung sesi
                    $sesi = $this->tentukanSesi($start, $end);

                    $formattedJadwal[$hari][] = [
                        'start'       => $start,
                        'end'         => $end,
                        'kuota'       => $kuota,
                        'ruang'       => $ruang,
                        //'id_ruangan'  => $id_ruangan,
                        'sesi'        => implode(',', $sesi),
                    ];
                }
            }
        }

        // Simpan ke database
        $dokter = Dokter::findOrFail($id);
        $dokter->update([
            'Nama_Dokter'    => $request->Nama_Dokter,
            'Spesialis'      => $request->Spesialis,
            'No_Telp'        => $request->No_Telp,
            'Email'          => $request->Email,
            'Alamat'         => $request->Alamat,
            'Jadwal_Dokter'  => json_encode($formattedJadwal),
            'Last_Update_By' => 'admin',
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();
        return redirect()->route('dokter.index')->with('success', 'Data terapis berhasil dihapus.');
    }

    private function tentukanSesi($start, $end)
{
    $pagi  = ['start' => '06:00', 'end' => '11:59'];
    $siang = ['start' => '12:00', 'end' => '14:59'];
    $sore  = ['start' => '15:00', 'end' => '17:59'];
    $malam = ['start' => '18:00', 'end' => '22:00'];

    $hasil = [];

    if ($start <= $pagi['end'] && $end >= $pagi['start']) {
        $hasil[] = 'Pagi';
    }

    if ($start <= $siang['end'] && $end >= $siang['start']) {
        $hasil[] = 'Siang';
    }

    if ($start <= $sore['end'] && $end >= $sore['start']) {
        $hasil[] = 'Sore';
    }

    return $hasil;
}
public function getAvailableRuangan(Request $request)
{
    $hari = $request->input('hari');
    $start = $request->input('start');
    $end = $request->input('end');

    // Validasi input
    if (!$hari || !$start || !$end) {
        return response()->json([
            'tersedia' => [],
            'bentrok' => [],
        ]);
    }

    // Format waktu menggunakan Carbon
    try {
        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);
    } catch (\Exception $e) {
        return response()->json([
            'tersedia' => [],
            'bentrok' => [],
            'error' => 'Format jam tidak valid',
        ]);
    }

    // Ambil semua ruangan
    $allRooms = DB::table('ms_ruangan')->pluck('Nama_Ruangan');
    $ruanganBentrok = collect();
    $bentrokInfo = [];

    // Ambil semua dokter dengan jadwal
    $dokters = Dokter::select('Nama_Dokter', 'Jadwal_Dokter')->whereNotNull('Jadwal_Dokter')->get();

    foreach ($dokters as $dokter) {
        $jadwal = json_decode($dokter->Jadwal_Dokter, true);
        if (!isset($jadwal[$hari])) continue;

        foreach ($jadwal[$hari] as $entry) {
            if (!isset($entry['ruang'], $entry['start'], $entry['end'])) continue;

            try {
                $startExisting = Carbon::createFromFormat('H:i', $entry['start']);
                $endExisting = Carbon::createFromFormat('H:i', $entry['end']);
            } catch (\Exception $e) {
                continue; // skip jadwal yang format jam-nya tidak valid
            }

            // Cek apakah waktu bentrok
            $isOverlap = $startTime->lt($endExisting) && $endTime->gt($startExisting);

            if ($isOverlap) {
                $ruanganBentrok->push($entry['ruang']);
                $bentrokInfo[] = [
                    'ruang'  => $entry['ruang'],
                    'start'  => $entry['start'],
                    'end'    => $entry['end'],
                    'dokter' => $dokter->Nama_Dokter,
                ];
            }
        }
    }

    // Filter ruangan yang tersedia
    $ruanganTersedia = $allRooms->diff($ruanganBentrok->unique())->values();

    return response()->json([
        'tersedia' => $ruanganTersedia,
        'bentrok' => $bentrokInfo,
    ]);
}

public function jadwalHarian()
{
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

    return view('dokter.jadwal_harian', compact('jadwal', 'ruangans', 'kunjungan'));
}

public function getDokterByPoliHari(Request $request)
{
    $poli = $request->query('poli');
    $hari = $request->query('hari');

    $dokters = Dokter::where('Spesialis', $poli)->get()->filter(function ($dokter) use ($hari) {
        $jadwal = json_decode($dokter->Jadwal_Dokter, true);
        return isset($jadwal[$hari]) && is_array($jadwal[$hari]) && count($jadwal[$hari]) > 0;
    });

    $data = $dokters->map(function ($d) {
        return [
            'id_dokter' => $d->Id_Dokter,
            'nama_dokter' => $d->Nama_Dokter,
            'ruangan' => optional($d->ruangan)->Nama_Ruangan
        ];
    });

    return response()->json($data->values());
}

public function getJadwalByDokterHari(Request $request)
{
    $dokterId = $request->query('dokter_id');
    $hari = $request->query('hari');

    $dokter = Dokter::findOrFail($dokterId);
    $jadwal = json_decode($dokter->Jadwal_Dokter, true);

    if (!isset($jadwal[$hari])) {
        return response()->json([]);
    }

    $jamList = collect($jadwal[$hari])->map(function ($item) {
        return "{$item['start']} - {$item['end']} | Ruangan: {$item['ruang']} | Kuota: {$item['kuota']} | Sesi: {$item['sesi']}";
    });

    return response()->json($jamList->values());
}

    // agar tidak bentrok
    private function cekBentrokJadwal($hari, $start, $end, $ruang, $dokterId = null)
    {
        try {
            $startTime = Carbon::createFromFormat('H:i', $start);
            $endTime = Carbon::createFromFormat('H:i', $end);
        } catch (\Exception $e) {
            return false; // jika jam salah, anggap tidak bentrok
        }

        $query = Dokter::query();
        if ($dokterId) {
            $query->where('Id_Dokter', '!=', $dokterId);
        }

        $dokters = $query->get();

        foreach ($dokters as $dokter) {
            $jadwal = json_decode($dokter->Jadwal_Dokter, true);

            // 🟡 1. Hanya cek hari yang sama
            if (!isset($jadwal[$hari])) continue;

            foreach ($jadwal[$hari] as $item) {
                // 🟡 2. Hanya cek ruangan yang sama
                if (!isset($item['ruang'], $item['start'], $item['end'])) continue;
                if ($item['ruang'] !== $ruang) continue;

                try {
                    $itemStart = Carbon::createFromFormat('H:i', $item['start']);
                    $itemEnd = Carbon::createFromFormat('H:i', $item['end']);
                } catch (\Exception $e) {
                    continue;
                }

                // 🟡 3. Cek apakah jam overlap
                $isOverlap = $startTime->lt($itemEnd) && $endTime->gt($itemStart);
                if ($isOverlap) {
                    return true; // bentrok
                }
            }
        }

        return false; // tidak bentrok
    }

}