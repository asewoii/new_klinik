<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ruangan;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Kunjungan;
use Illuminate\Support\Carbon;

class RuanganController extends Controller
{
    public function index()
{
    $ruangan = Ruangan::with(['kunjungans.pasien', 'kunjungans.dokter', 'kunjungans.ruangan'])->get();
    $today = strtolower(Carbon::now()->locale('id')->translatedFormat('l'));

    $semuaDokter = Dokter::all()->filter(function ($dokter) use ($today) {
        $jadwal = is_string($dokter->Jadwal_Dokter) ? json_decode($dokter->Jadwal_Dokter, true) : $dokter->Jadwal_Dokter;
        $jadwal = array_change_key_case($jadwal, CASE_LOWER);
        return isset($jadwal[$today]) && count($jadwal[$today]) > 0;
    });

    foreach ($ruangan as $r) {
        $kunjunganHariIni = $r->kunjungans->filter(fn($k) => Carbon::parse($k->Tanggal_Registrasi)->isToday());

        // Filter dokter yang punya jadwal dengan id_ruangan yang sesuai
        $dokterList = $semuaDokter->filter(function ($dokter) use ($r, $today) {
            $jadwal = is_string($dokter->Jadwal_Dokter) ? json_decode($dokter->Jadwal_Dokter, true) : $dokter->Jadwal_Dokter;
            $jadwal = array_change_key_case($jadwal, CASE_LOWER);
            return collect($jadwal[$today] ?? [])->contains(function ($jam) use ($r) {
                return strtolower((string) ($jam['id_ruangan'] ?? '')) === strtolower((string) $r->Id_Ruangan);
            });
        });

        $r->dokterList = $dokterList;

        // Main Modal Detail
        foreach ($r->dokterList as $dokter) {
            $nomor = preg_replace('/[^0-9]/', '', $dokter->No_Telp ?? '');
            if (str_starts_with($nomor, '08')) {
                $nomor = '62' . substr($nomor, 1);
            }
            $dokter->wa_link = $nomor ? "https://wa.me/{$nomor}" : null;

            $jadwal = is_string($dokter->Jadwal_Dokter) ? json_decode($dokter->Jadwal_Dokter, true) : $dokter->Jadwal_Dokter;
            $jadwal = array_change_key_case($jadwal, CASE_LOWER);

            $kategori = [];

            $jadwalHariIni = collect($jadwal[$today] ?? [])
                ->filter(function ($jam) use ($r) {
                    logger("Checking jadwal: jam_id = " . $jam['id_ruangan'] . ", ruangan_id = " . $r->Id_Ruangan);
                    return strtolower((string) ($jam['id_ruangan'] ?? '')) === strtolower((string) $r->Id_Ruangan);
                })->map(function ($jam) use ($dokter, $r, $kunjunganHariIni, &$kategori) {
                    $start = Carbon::createFromFormat('H:i', $jam['start']);
                    $end = Carbon::createFromFormat('H:i', $jam['end']);

                    $label = match (true) {
                        $start->lt(Carbon::createFromTime(12, 0)) => 'Pagi',
                        $start->lt(Carbon::createFromTime(16, 0)) => 'Siang',
                        $start->lt(Carbon::createFromTime(18, 0)) => 'Sore',
                        default => 'Malam',
                    };
                    $kategori[] = $label;

                    $kuota = (int) ($jam['kuota'] ?? 10);
                    $terpakai = $kunjunganHariIni->filter(function ($k) use ($dokter, $r) {
                        return $k->Id_Dokter === $dokter->Id_Dokter && $k->Id_Ruangan === $r->Id_Ruangan;
                    })->count();

                    $sisa = max(0, $kuota - $terpakai);
                    $now = Carbon::now();

                    if ($kuota > 0 && $sisa == 0 && $now->between($start, $end)) {
                        $status = 'Full';
                    } elseif ($kuota > 0 && $now->gt($end)) {
                        $status = 'Close';
                    } elseif ($kuota > 0 && $now->between($start, $end)) {
                        $status = 'Tersedia';
                    } else {
                        $status = 'Tutup';
                    }

                    return [
                        'start' => $jam['start'],
                        'end' => $jam['end'],
                        'kuota' => $kuota,
                        'terpakai' => $terpakai,
                        'sisa' => $sisa,
                        'status' => $status,
                        'ruang' => $jam['ruang'] ?? '-',
                        'id_ruangan' => $jam['id_ruangan'] ?? null,
                        'sesi' => $label,
                    ];
                })->values()->toArray();


            $dokter->jadwalHariIni = $jadwalHariIni;
            $dokter->jamKategori = implode(', ', array_unique($kategori));
        }

        $r->total_dokter = $dokterList->count();
        $r->total_jadwal_dokter = $dokterList->sum(function ($dokter) use ($r, $today) {
            $jadwal = is_string($dokter->Jadwal_Dokter) ? json_decode($dokter->Jadwal_Dokter, true) : $dokter->Jadwal_Dokter;
            $jadwal = array_change_key_case($jadwal, CASE_LOWER);
            return collect($jadwal[$today] ?? [])->filter(
                fn($jam) => ($jam['id_ruangan'] ?? null) === $r->Id_Ruangan
            )->count();
        });

        $r->total_pasien_hari_ini = $kunjunganHariIni->count();
        $r->pasienList = $kunjunganHariIni->map(function ($k) {
            return (object)[
                'Nama_Pasien' => $k->pasien->Nama_Pasien ?? '-',
                'Nama_Ruangan' => $k->ruangan->Nama_Ruangan ?? '-',
                'Nama_Dokter' => $k->dokter->Nama_Dokter ?? '-',
                'Nomor_Urut' => $k->Nomor_Urut,
                'No_Tlp' => $k->pasien->No_Tlp ?? '-',
                'Waktu_Daftar' => Carbon::parse($k->Tanggal_Registrasi)->format('H:i'),
            ];
        });
    }

    return view('ruangan.index', compact('ruangan', 'today'));
}






    public function create()
    {
        return view('ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Ruangan' => 'required',
            'Jenis_Ruangan' => 'required',
            'Lantai' => 'required|integer',
            'Status' => 'required|in:aktif,nonaktif,dalam perbaikan',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil disimpan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Ruangan' => 'required',
            'Jenis_Ruangan' => 'required',
            'Lantai' => 'required|integer',
            'Status' => 'required|in:aktif,nonaktif,dalam perbaikan',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($request->all());

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil dihapus.');
    }


    // -------- SELECT HAPUS DATA -------- \\
    public function SelectDelete(Request $request) {
        $delete = $request->input('selected_ruangan');

        if(!$delete || !is_array($delete)) {
            return redirect()->route('ruangan.index')->with('error', 'Tidak ada data yang di pilih!');
        }

        Ruangan::whereIn('Id_Ruangan', $delete)->delete();

        return redirect()->route('ruangan.index')->with('success', 'Data berhasil di hapus');
    }

    // --- VALIDASI MODAL STORE --- \\
    public function checkStore(Request $request)
    {
        $exists = Ruangan::whereRaw('LOWER(Nama_Ruangan) = ?', [strtolower($request->Nama_Ruangan)])->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }



    // --- MODAL EDIT RUANGAN --- \\
    public function checkEdit(Request $request)
    {
        $nama = strtolower(trim($request->Nama_Ruangan));
        $id = $request->Id_Ruangan;

        $query = DB::table('ms_ruangan')
            ->whereRaw('LOWER(Nama_Ruangan) = ?', [$nama]);

        if ($id) {
            $query->where('Id_Ruangan', '!=', $id);
        }

        $existsNama = $query->exists();

        return response()->json([
            'exists_nama' => $existsNama,
        ]);
    }


}
