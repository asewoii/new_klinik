<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\Ruangan;
use App\Models\Pemeriksaan;
use App\Models\Dokter;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KunjunganExport;
use Maatwebsite\Excel\Facades\Excel;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $kunjungan = Kunjungan::with(['pasien', 'layanan', 'dokter',])
            ->orderBy('Jadwal_Kedatangan', 'asc')
            ->paginate($limit)
            ->withQueryString();

        if ($request->filled('search')) {
            $search = $request->search;
            $kunjungan->where('Nama_Pasien', 'like', "%$search%")
                      ->orWhere('Nik', 'like', "%$search%");
        }

        $sesiOrder = ['Pagi', 'Siang', 'Sore', 'Lainnya'];

        return view('kunjungan.index', compact('kunjungan', 'sesiOrder'));
    }

    public function create($id)
    {
        $pasien = Pasien::findOrFail($id);
        $dokterList = Dokter::select('Id_Dokter', 'Nama_Dokter', 'Spesialis', 'Jadwal_Dokter')->get();

        foreach ($dokterList as $dokter) {
            $dokter->Jadwal_Dokter = json_decode($dokter->Jadwal_Dokter, true);
        }

        return view('kunjungan.create', compact('dokterList', 'pasien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required',
            'jadwal_kedatangan' => 'required|date_format:Y-m-d H:i',
            'nama_pasien' => 'required',
            'nik' => 'required',
            'id_pasien' => 'required',
        ]);

        Kunjungan::create([
            'Id_Kunjungan' => (string) Str::uuid(),
            'Id_Pasien' => $request->id_pasien,
            'Id_Dokter' => $request->id_dokter,
            'Jadwal_Kedatangan' => $request->jadwal_kedatangan,
            'Nama_Pasien' => $request->nama_pasien,
            'Nik' => $request->nik,
            'Status' => 'Antri',
            'Create_Date' => now(),
            'Create_By' => 'system',
        ]);

        return redirect()->route('kunjungan.index')->with('success', 'Data kunjungan berhasil disimpan');
    }

    public function edit($id)
    {
        $data = Kunjungan::with(['pasien'])->findOrFail($id);
        $pasienList = Pasien::all();

        return view('kunjungan.edit', compact('data', 'pasienList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_Pasien' => 'required|integer',
            'Id_Layanan' => 'required|string',
            'Nik' => 'required|string|max:20',
            'Nama_Pasien' => 'required|string|max:100',
            'Tanggal_Registrasi' => 'required|date',
            'Jadwal_Kedatangan' => 'required|date',
            'Nomor_Urut' => 'required|integer',
        ]);

        $data = Kunjungan::findOrFail($id);
        $data->update([
            'Id_Pasien' => $request->Id_Pasien,
            'Id_Layanan' => $request->Id_Layanan,
            'Nik' => $request->Nik,
            'Nama_Pasien' => $request->Nama_Pasien,
            'Tanggal_Registrasi' => $request->Tanggal_Registrasi,
            'Jadwal_Kedatangan' => $request->Jadwal_Kedatangan,
            'Nomor_Urut' => $request->Nomor_Urut,
        ]);

        return redirect()->route('kunjungan.index')->with('success', 'Kunjungan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $data = Kunjungan::findOrFail($id);
        $data->delete();

        return redirect()->route('kunjungan.index')->with('success', 'Kunjungan berhasil dihapus.');
    }

    public function laporan(Request $request)
    {
        $dokters = Dokter::all();

        $kunjungan = Kunjungan::with(['layanan', 'dokter'])
            ->when($request->start_date && $request->end_date, fn($q) =>
                $q->whereBetween('Jadwal_Kedatangan', [$request->start_date, $request->end_date])
            )
            ->when($request->start_date && !$request->end_date, fn($q) =>
                $q->whereDate('Jadwal_Kedatangan', '>=', $request->start_date)
            )
            ->when(!$request->start_date && $request->end_date, fn($q) =>
                $q->whereDate('Jadwal_Kedatangan', '<=', $request->end_date)
            )
            ->when($request->dokter_id, fn($q) =>
                $q->where('Id_Dokter', $request->dokter_id)
            )
            ->when($request->sesi_id, function ($q, $sesi) {
                $jam = $this->getJamSesi($sesi);
                $q->whereTime('Jadwal_Kedatangan', '>=', $jam['start'])
                  ->whereTime('Jadwal_Kedatangan', '<=', $jam['end']);
            })
            ->when($request->status, fn($q, $status) =>
                $q->where('Status', $status)
            )
            ->orderBy('Jadwal_Kedatangan', 'desc')
            ->get();

        return view('kunjungan.laporan', compact('kunjungan', 'dokters'));
    }

    public static function getFilteredKunjungan($request)
    {
        return Kunjungan::with(['layanan', 'dokter'])
            ->when($request->start_date && $request->end_date, fn($q) =>
                $q->whereBetween('Jadwal_Kedatangan', [$request->start_date, $request->end_date])
            )
            ->when($request->start_date && !$request->end_date, fn($q) =>
                $q->whereDate('Jadwal_Kedatangan', '>=', $request->start_date)
            )
            ->when(!$request->start_date && $request->end_date, fn($q) =>
                $q->whereDate('Jadwal_Kedatangan', '<=', $request->end_date)
            )
            ->when($request->dokter_id, fn($q) =>
                $q->where('Id_Dokter', $request->dokter_id)
            )
            ->when($request->sesi_id, function ($q, $sesi) {
                $jam = (new static)->getJamSesi($sesi);
                $q->whereTime('Jadwal_Kedatangan', '>=', $jam['start'])
                  ->whereTime('Jadwal_Kedatangan', '<=', $jam['end']);
            })
            ->when($request->status, fn($q) =>
                $q->where('Status', $request->status)
            )
            ->orderBy('Jadwal_Kedatangan', 'desc')
            ->get();
    }

    public function exportPdf(Request $request)
    {
        $kunjungan = $this->getFilteredKunjungan($request);
        $pdf = Pdf::loadView('kunjungan.export_pdf', compact('kunjungan'));
        return $pdf->download('laporan_kunjungan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new KunjunganExport($request), 'laporan_kunjungan.xlsx');
    }

    public function history($id)
    {
        $riwayat = Kunjungan::with(['dokter', 'layanan',])
            ->where('Id_Pasien', $id)
            ->orderBy('Jadwal_Kedatangan', 'desc')
            ->get();

        return view('kunjungan.history_partial', compact('riwayat'));
    }

    public function show($id)
    {
        $data = Kunjungan::with(['pasien', 'dokter', 'layanan'])->findOrFail($id);
        return view('kunjungan.show', compact('data'));
    }

    public function hariIni()
    {
        $today = now()->toDateString();
        $listDokter = Dokter::select('Nama_Dokter')->get();

        $data = Kunjungan::with(['dokter', 'layanan'])
            ->whereDate('Jadwal_Kedatangan', $today)
            ->orderBy('Nomor_Urut')
            ->paginate(15);

        return view('kunjungan.hari_ini', compact('listDokter', 'data'));
    }

    public function updateStatus(Request $request, $id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['diperiksa', 'tidak hadir'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $kunjungan->Status = $status;
        $kunjungan->save();

        return redirect()->back()->with('success', 'Status berhasil diubah.');
    }

    // Mapping jam berdasarkan sesi
    private function getJamSesi($sesi)
    {
        $map = [
            'pagi' => ['start' => '06:00:00', 'end' => '11:59:59'],
            'siang' => ['start' => '12:00:00', 'end' => '15:59:59'],
            'sore' => ['start' => '16:00:00', 'end' => '18:59:59'],
        ];

        return $map[strtolower($sesi)] ?? ['start' => '00:00:00', 'end' => '23:59:59'];
    }
}
