<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\JadwalDokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Ruangan;
use App\Models\Sesi;
use App\Models\Settings;
use App\Exports\DokterExport;
use App\Exports\PemeriksaanExport;
use App\Exports\PasienExport;
use App\Exports\RuanganExport;
use App\Exports\KunjunganExport;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'kunjungan');
        $dokters = Dokter::all();
        $ruangans = Ruangan::all();
        $layanan = Layanan::all();
        $data = [];

        switch ($jenis) {
            case 'kunjungan':
                $data = $this->filterKunjunganQuery($request);
                break;

            case 'pemeriksaan':
                $data = $this->filterPemeriksaanQuery($request);
                break;

            case 'pasien':
                $data = $this->filterPasienQuery($request);
                break;

            case 'dokter':
                $data = $this->filterDokterQuery($request);
                break;

            case 'ruangan':
                $data = $this->filterRuanganQuery($request);
                break;
        }

        return view('laporan.index', compact('jenis', 'data', 'dokters', 'ruangans', 'layanan'));
    }

    private function filterKunjunganQuery(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Kunjungan::with('dokter');

        if ($request->filled('from')) {
            $query->whereDate('Tanggal_Registrasi', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('Tanggal_Registrasi', '<=', $request->to);
        }

        if ($request->filled('dokter')) {
            $query->where('Id_Dokter', $request->dokter);
        }

        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        if ($request->filled('ruangan')) {
            $query->where('Id_Ruangan', $request->ruangan);
        }

        if ($request->filled('layanan')) {
            $query->where('Id_Layanan', $request->layanan);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    private function filterPemeriksaanQuery(Request $request, $paginate = true)
    {
        $perPage = $request->get('per_page', 10);
        $query = Pemeriksaan::with('dokter', 'kunjungan');

        if ($request->filled('from')) {
            $query->whereDate('Tanggal_Pemeriksaan', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('Tanggal_Pemeriksaan', '<=', $request->to);
        }

        if ($request->filled('dokter')) {
            $query->where('Id_Dokter', $request->dokter);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Diagnosa', 'like', "%$search%")
                  ->orWhere('Tindakan', 'like', "%$search%")
                  ->orWhere('Catatan', 'like', "%$search%");
            });
        }

        return $paginate
            ? $query->paginate($perPage)->withQueryString()
            : $query->get();
    }

    private function filterPasienQuery(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('from')) {
            $query->whereDate('Tanggal_Registrasi', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('Tanggal_Registrasi', '<=', $request->to);
        }

        if ($request->filled('jk')) {
            $query->where('Jk', $request->jk);
        }

        if ($request->filled('umur_min')) {
            $query->where('Umur', '>=', $request->umur_min);
        }

        if ($request->filled('umur_max')) {
            $query->where('Umur', '<=', $request->umur_max);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('Nama_Pasien', 'like', '%' . $request->keyword . '%')
                  ->orWhere('Nik', 'like', '%' . $request->keyword . '%');
            });
        }

        return $query->orderBy('Tanggal_Registrasi', 'desc')->paginate(10)->withQueryString();
    }

    private function filterDokterQuery(Request $request, $paginate = true)
    {
    $perPage = $request->get('per_page', 10);
    $query = Dokter::query();
    $ruanganFilter = $request->input('ruangan'); // array atau null


    // Keyword filter (nama atau spesialis)
    if ($request->filled('keyword')) {
        $query->where(function ($q) use ($request) {
            $q->where('Nama_Dokter', 'like', '%' . $request->keyword . '%')
              ->orWhere('Spesialis', 'like', '%' . $request->keyword . '%');
        });
    }

    // Ambil semua data dulu
    $dokters = $query->get();

    // Filter tambahan di memory (karena field JSON tidak bisa di-query langsung)
    $sesiFilter = $request->input('sesi', []);
    $hariFilter = $request->input('hari', []);
    $ruanganFilter = $request->input('ruangan', []);

    // Konversi ke array jika perlu
    $sesiFilter = is_array($sesiFilter) ? $sesiFilter : [$sesiFilter];
    $hariFilter = is_array($hariFilter) ? $hariFilter : [$hariFilter];
    $hariFilter = array_map('ucfirst', $hariFilter);
    $ruanganFilter = is_array($ruanganFilter) ? $ruanganFilter : [$ruanganFilter];

    $filtered = $dokters->filter(function ($dokter) use ($sesiFilter, $hariFilter, $ruanganFilter) {
        if (!$dokter->Jadwal_Dokter) return false;

        $jadwal = json_decode($dokter->Jadwal_Dokter, true);
        if (!is_array($jadwal)) return false;

        foreach ($jadwal as $hari => $slots) {
            if (!empty($hariFilter) && !in_array($hari, $hariFilter)) continue;

            foreach ($slots as $slot) {
    $sesiInSlot = explode(',', $slot['sesi']);
    $matchSesi = empty($sesiFilter) || array_intersect($sesiFilter, $sesiInSlot);
    $matchRuangan = empty($ruanganFilter) || in_array(strtolower($slot['ruang']), array_map('strtolower', $ruanganFilter));

    if ($ruanganFilter && !in_array(strtolower($slot['ruang']), array_map('strtolower', $ruanganFilter))) {
        continue;
    }


    if ($matchSesi && $matchRuangan) {
        return true;
        }
    }

        }

        return false;
    });

    // Pagination manual karena kita pakai filter collection
    if (!$paginate) {
        return $filtered->values();
    }

    $page = $request->get('page', 1);
    $items = $filtered->forPage($page, $perPage);

    return new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $filtered->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    }

    private function filterRuanganQuery(Request $request, $paginate = true)
    {
        $perPage = $request->get('per_page', 10);
        $query = Ruangan::query();

        if ($request->filled('ruangan')) {
            $query->where('Id_Ruangan', $request->ruangan);
        }

        if ($request->filled('jenis_ruangan')) {
            $query->where('Jenis_Ruangan', $request->jenis_ruangan);
        }

        if ($request->filled('lantai')) {
            $query->where('Lantai', $request->lantai);
        }

        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        return $paginate
            ? $query->orderBy('Nama_Ruangan')->paginate($perPage)->withQueryString()
            : $query->orderBy('Nama_Ruangan')->get();
    }

    // ===================== EXPORT =====================

    public function exportPdf(Request $request)
    {
        $data = $this->filterKunjunganQuery($request);
        $pdf = Pdf::loadView('laporan.export_pdf_kunjungan', compact('data'));
        return $pdf->download('laporan_kunjungan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->filterKunjunganQuery($request);
        return Excel::download(new KunjunganExport($data), 'laporan_kunjungan.xlsx');
    }

    public function exportPdfPemeriksaan(Request $request)
    {
        $data = $this->filterPemeriksaanQuery($request, false);
        $pdf = Pdf::loadView('laporan.export_pdf_pemeriksaan', compact('data'));
        return $pdf->download('laporan_pemeriksaan.pdf');
    }

    public function exportExcelPemeriksaan(Request $request)
    {
        $data = $this->filterPemeriksaanQuery($request, false);
        return Excel::download(new PemeriksaanExport($data), 'laporan_pemeriksaan.xlsx');
    }

public function exportPasienPdf(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('from')) {
            $query->whereDate('Tanggal_Registrasi', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('Tanggal_Registrasi', '<=', $request->to);
        }

        if ($request->filled('jk')) {
            $query->where('Jk', $request->jk);
        }

        if ($request->filled('umur_min')) {
            $query->where('Umur', '>=', $request->umur_min);
        }

        if ($request->filled('umur_max')) {
            $query->where('Umur', '<=', $request->umur_max);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('Nama_Pasien', 'like', '%' . $request->keyword . '%')
                  ->orWhere('Nik', 'like', '%' . $request->keyword . '%');
            });
        }

        $pasien = $query->orderBy('Tanggal_Registrasi', 'desc')->get();
        $pdf = Pdf::loadView('laporan.export_pdf_pasien', compact('pasien'));
        return $pdf->download('laporan_pasien.pdf');
    }

    public function exportPasienExcel(Request $request)
    {
        return Excel::download(new PasienExport($request), 'laporan_pasien.xlsx');
    }

    public function exportDokterPdf(Request $request)
    {
        $dokters = $this->filterDokterQuery($request, false);
        $pdf = Pdf::loadView('laporan.export_pdf_dokter', compact('dokters'));
        return $pdf->download('laporan_dokter.pdf');
    }

    public function exportDokterExcel(Request $request)
    {
        return Excel::download(new DokterExport($request), 'laporan_dokter.xlsx');
    }

    public function exportRuanganPdf(Request $request)
    {
        $ruangans = $this->filterRuanganQuery($request, false);
        $pdf = Pdf::loadView('laporan.export_pdf_ruangan', compact('ruangans'));
        return $pdf->download('laporan_ruangan.pdf');
    }

    public function exportRuanganExcel(Request $request)
    {
        return Excel::download(new RuanganExport($request), 'laporan_ruangan.xlsx');
    }

    // Di app/Http/Controllers/LaporanController.php
    public function exportPdfRuangan(Request $request)
    {
        // Pastikan Anda mengambil data ruangan di sini
        $ruangans = Ruangan::all(); // Ambil semua data ruangan, atau sesuaikan dengan query Anda

        $pdf = Pdf::loadView('laporan.export_pdf_ruangan', ['ruangans' => $ruangans]); // Kirim variabel $ruangans
        return $pdf->download('laporan_ruangan.pdf');
    }
}
