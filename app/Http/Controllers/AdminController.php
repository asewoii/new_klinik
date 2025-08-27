<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\auth;



class AdminController extends Controller
{
    public function index()
    {
        // ===================== Inisialisasi Variabel =====================
        $today = now()->toDateString();
        $now = now()->locale('id');
        $perPage = 10;
        $currentTime = $now->format('H:i');
        $dokterList = Dokter::whereNotNull('jadwal_dokter')->get();

        //=====================PASIEN DI PANGGIL PER SESI======================
        $sesiWaktu = [
            'Pagi' => ['06:00', '11:59'],
            'Siang' => ['12:00', '14:59'],
            'Sore' => ['15:00', '18:00'],
            'Malam' => ['18:01', '22:00'],
        ];

        // ===================== Sesi Waktu =====================
        $sesiPagiStart = $sesiWaktu['Pagi'][0]; // '06:00'
        $sesiPagiEnd = $sesiWaktu['Pagi'][1];   // '11:59'
        $sesiSiangStart = $sesiWaktu['Siang'][0]; // '12:00'
        $sesiSiangEnd = $sesiWaktu['Siang'][1]; // '14:59'
        $sesiSoreStart = $sesiWaktu['Sore'][0]; // '15:00'
        $sesiSoreEnd = $sesiWaktu['Sore'][1]; // '18:00'
        $sesiMalamStart = $sesiWaktu['Malam'][0]; // '18:01'
        $sesiMalamEnd = $sesiWaktu['Malam'][1]; // '22:00'

        // ===================== Sesi Waktu dengan Carbon =====================
        $sesiWaktu = [
            'Pagi' => [Carbon::createFromTime(6, 0), Carbon::createFromTime(11, 59, 59)],
            'Siang' => [Carbon::createFromTime(12, 0), Carbon::createFromTime(15, 0)],
            'Sore' => [Carbon::createFromTime(15, 1), Carbon::createFromTime(18, 0)],
            'Malam' => [Carbon::createFromTime(18, 1), Carbon::createFromTime(22, 0)], // Tambahan malam
        ];

        // ===================== Sesi Saat Ini =====================
        $sesiSaatIni = match (true) {
            $now->between(Carbon::createFromTime(6), Carbon::createFromTime(11, 59, 59)) => 'Pagi',
            $now->between(Carbon::createFromTime(12), Carbon::createFromTime(14, 59, 59)) => 'Siang',
            $now->between(Carbon::createFromTime(15), Carbon::createFromTime(18)) => 'Sore',
            $now->between(Carbon::createFromTime(18, 1), Carbon::createFromTime(22)) => 'Malam',
            default => null
        };

        // ===================== Ambil Semua Kunjungan Hari Ini =====================
        $allKunjunganHariIni = Kunjungan::with(['dokter', 'pasien', 'layanan'])
            ->whereDate('Jadwal_Kedatangan', $today)
            ->get();

        // ===================== Pasien yang Boleh Ditampilkan =====================
        $pasienYangBolehDitampilkan = $allKunjunganHariIni
            ->filter(function ($kunjungan) use ($allKunjunganHariIni, $currentTime) {
                if ($kunjungan->Status !== 'menunggu') return false;
                $sesiSekarang = $this->getSesiDariJam($currentTime)[0] ?? null;
                $sesiKunjungan = $this->getSesiDariJam($kunjungan->Jadwal);

                if (!$sesiSekarang || !in_array($sesiSekarang, $sesiKunjungan)) {
                    return false;
                }

                $adaYangDiperiksa = $allKunjunganHariIni
                    ->where('Id_Dokter', $kunjungan->Id_Dokter)
                    ->where('Jadwal', $kunjungan->Jadwal)
                    ->where('Status', 'diperiksa')
                    ->isNotEmpty();
                return !$adaYangDiperiksa;
            })
            ->sortBy('Nomor_Urut')
            ->first();

        // ===================== Total Kunjungan Hari Ini =====================
        // Ambil semua kunjungan hari ini
        $kunjunganHariIniDetail = Kunjungan::with(['dokter', 'layanan', 'pasien', 'ruangan'])
            ->whereDate('Jadwal_Kedatangan', $today)
            ->orderBy('Nomor_Urut')
            ->paginate($perPage, ['*'], 'kunjungan_hari_ini');

        // ===================== Pasien Hari Ini =====================
        $pasienHariIni = Pasien::whereDate('Tanggal_Registrasi', $today)->get();

        // ===================== Layanan Hari Ini =====================
        $layananHariIni = DB::table('tr_kunjungan')
            ->join('ms_layanan', 'tr_kunjungan.Id_Layanan', '=', 'ms_layanan.Id_Layanan')
            ->whereDate('tr_kunjungan.Jadwal_Kedatangan', $today)
            ->select('ms_layanan.Nama_Layanan', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('ms_layanan.Nama_Layanan')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

// =========================================================================================== \\

        // ===================== Layanan 1 Bulan Terakhir =====================
        $grafikLayananBulan = DB::table('tr_kunjungan')
            ->join('ms_layanan', 'tr_kunjungan.Id_Layanan', '=', 'ms_layanan.Id_Layanan')
            ->whereBetween('tr_kunjungan.Jadwal_Kedatangan', [now()->subMonth()->toDateString(), $today])
            ->select('ms_layanan.Nama_Layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('ms_layanan.Nama_Layanan')
            ->pluck('total', 'ms_layanan.Nama_Layanan');

        // ===================== Detail Layanan Bulanan =====================
        $bulan = now()->format('Y-m'); // contoh '2025-08'
        $layananBulanDetail = DB::table('tr_kunjungan')
            ->join('ms_layanan', 'tr_kunjungan.Id_Layanan', '=', 'ms_layanan.Id_Layanan')
            ->whereMonth('tr_kunjungan.Jadwal_Kedatangan', now()->month)
            ->select('ms_layanan.Nama_Layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('ms_layanan.Nama_Layanan')
            ->get()
            ->mapWithKeys(function($item){
                return [$item->Nama_Layanan => ['Total' => $item->total]];
            });

        // ===================== Grafik Layanan Hari Ini =====================
        $grafikLayananHariIni = DB::table('tr_kunjungan')
            ->join('ms_layanan', 'tr_kunjungan.Id_Layanan', '=', 'ms_layanan.Id_Layanan')
            ->whereDate('tr_kunjungan.Jadwal_Kedatangan', $today)
            ->select('ms_layanan.Nama_Layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('ms_layanan.Nama_Layanan')
            ->pluck('total', 'ms_layanan.Nama_Layanan');

        // ===================== Detail Layanan Hari Ini =====================
        $layananHariDetail = DB::table('tr_kunjungan')
            ->join('ms_layanan', 'tr_kunjungan.Id_Layanan', '=', 'ms_layanan.Id_Layanan')
            ->whereDate('tr_kunjungan.Jadwal_Kedatangan', $today)
            ->select('ms_layanan.Nama_Layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('ms_layanan.Nama_Layanan')
            ->get()
            ->mapWithKeys(function($item){
                return [$item->Nama_Layanan => ['Total' => $item->total]];
            });

// =========================================================================================== \\



// =========================================================================================== \\

        // ===================== Statistik Jenis Kelamin =====================
        $genderStats = Kunjungan::whereDate('Jadwal_Kedatangan', $today)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select('ms_pasien.Jk', DB::raw('count(*) as jumlah'))
            ->groupBy('ms_pasien.Jk')
            ->pluck('jumlah', 'Jk');

        // Map jenis kelamin ke label yang lebih mudah dibaca
        $labelMap = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];

        // Buat data untuk grafik
        $grafikGender = [
            'labels' => $genderStats->keys()->map(fn($jk) => $labelMap[$jk] ?? $jk),
            'data' => $genderStats->values(),
        ];


        $grafikjeniskelaminhariini = Kunjungan::whereDate('Jadwal_Kedatangan', $today)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select(
                DB::raw("DATE(Jadwal_Kedatangan) as tanggal"),
                'ms_pasien.Jk as jenis_kelamin',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tanggal', 'jenis_kelamin')
            ->orderBy('tanggal')
            ->get();


// =========================================================================================== \\



// ======[CHART UMUR]===================================================================================== \\

        $umurStats = Kunjungan::whereDate('Jadwal_Kedatangan', $today)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select(DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 0 AND 5 THEN '0-5 tahun (Balita)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 6 AND 12 THEN '6-12 tahun (Anak-anak)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 13 AND 17 THEN '13-17 tahun (Remaja)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 18 AND 35 THEN '18-35 tahun (Dewasa Muda)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 36 AND 59 THEN '36-59 tahun (Dewasa)'
                    ELSE '60+ tahun (Lansia)'
                END AS Kelompok_Umur,
                COUNT(*) as jumlah
            "))
            ->groupBy('Kelompok_Umur')
            ->orderByRaw("MIN(TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()))")
            ->pluck('jumlah', 'Kelompok_Umur');

        // Buat data untuk grafik umur
        $grafikUmur = [
            'labels' => $umurStats->keys(),
            'data' => $umurStats->values(),
        ];





        // ===================== Statistik Umur Hari Ini =====================
        $grafikUmurHariIni = Kunjungan::whereDate('Jadwal_Kedatangan', $today)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select(DB::raw("
                DATE(Jadwal_Kedatangan) as tanggal,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 0 AND 5 THEN '0-5 tahun (Balita)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 6 AND 12 THEN '6-12 tahun (Anak-anak)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 13 AND 17 THEN '13-17 tahun (Remaja)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 18 AND 35 THEN '18-35 tahun (Dewasa Muda)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 36 AND 59 THEN '36-59 tahun (Dewasa)'
                    ELSE '60+ tahun (Lansia)'
                END AS Kelompok_Umur,
                COUNT(*) as jumlah
            "))
            ->groupBy('tanggal','Kelompok_Umur')
            ->orderByRaw("MIN(TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()))")
            ->orderBy('tanggal')
            ->get();

        // ===================== Statistik Umur Bulan Ini =====================
        $grafikUmurBulanIni = Kunjungan::whereMonth('Jadwal_Kedatangan', now()->month)
            ->whereYear('Jadwal_Kedatangan', now()->year)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select(DB::raw("
                DATE_FORMAT(Jadwal_Kedatangan, '%Y-%m') as bulan,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 0 AND 5 THEN '0-5 tahun (Balita)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 6 AND 12 THEN '6-12 tahun (Anak-anak)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 13 AND 17 THEN '13-17 tahun (Remaja)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 18 AND 35 THEN '18-35 tahun (Dewasa Muda)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 36 AND 59 THEN '36-59 tahun (Dewasa)'
                    ELSE '60+ tahun (Lansia)'
                END AS Kelompok_Umur,
                COUNT(*) as jumlah
            "))
            ->groupBy('bulan','Kelompok_Umur')
            ->orderByRaw("MIN(TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()))")
            ->orderBy('bulan')
            ->get();

        // ===================== Statistik Umur Tahun Ini =====================
        $grafikUmurTahunIni = Kunjungan::whereYear('Jadwal_Kedatangan', now()->year)
            ->join('ms_pasien', 'tr_kunjungan.Id_Pasien', '=', 'ms_pasien.Id_Pasien')
            ->select(DB::raw("
            YEAR(Jadwal_Kedatangan) as tahun,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 0 AND 5 THEN '0-5 tahun (Balita)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 6 AND 12 THEN '6-12 tahun (Anak-anak)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 13 AND 17 THEN '13-17 tahun (Remaja)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 18 AND 35 THEN '18-35 tahun (Dewasa Muda)'
                    WHEN TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()) BETWEEN 36 AND 59 THEN '36-59 tahun (Dewasa)'
                    ELSE '60+ tahun (Lansia)'
                END AS Kelompok_Umur,
                COUNT(*) as jumlah
            "))
            ->groupBy('tahun','Kelompok_Umur')
            ->orderByRaw("MIN(TIMESTAMPDIFF(YEAR, ms_pasien.Tanggal_Lahir, CURDATE()))")
            ->orderBy('tahun')
            ->get();


// =========================================================================================== \\



        // ===================== Jadwal Dokter Hari Ini =====================
        $hariIni = Str::ucfirst(now()->locale('id')->dayName);

        // Ambil semua jadwal dokter hari ini
        $kunjunganHariIni = DB::table('tr_kunjungan')
            ->select(
                'Id_Dokter',
                DB::raw("SUBSTRING_INDEX(Jadwal, ' ', 1) as jam_mulai"),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereDate('Jadwal_Kedatangan', Carbon::today())
            ->groupBy('Id_Dokter', DB::raw("SUBSTRING_INDEX(Jadwal, ' ', 1)"))
            ->get()
            ->keyBy(fn($item) => "{$item->Id_Dokter}|{$item->jam_mulai}");

        // Ambil semua jadwal dokter
        $jadwalDokterHariIni = [];

        // Loop melalui semua dokter yang memiliki jadwal
        foreach ($dokterList as $dokter) {
            $jadwalRaw = $dokter->Jadwal_Dokter;

            $cleaned = trim($jadwalRaw, "\"");
            $cleaned = stripslashes($cleaned);
            $decoded = json_decode($cleaned, true);

            if (!is_array($decoded) || !isset($decoded[$hariIni])) continue;

            foreach ($decoded[$hariIni] as $sesi) {
                $idDokter = $dokter->Id_Dokter;
                $jamMulai = isset($sesi['start']) ? Carbon::createFromFormat('H:i', $sesi['start'])->format('H:i') : '';
                $kuota = (int) ($sesi['kuota'] ?? 0);

                // Buat key
                $key = "{$idDokter}|{$jamMulai}";

                // Ambil jumlah terpakai dari data kunjungan hari ini
                $terpakai = $kunjunganHariIni[$key]->jumlah ?? 0;
                $sisa = max(0, $kuota - $terpakai);

                $jadwalDokterHariIni[] = [
                    'nama_dokter' => $dokter->Nama_Dokter,
                    'id_dokter'   => $idDokter,
                    'spesialis'   => $dokter->Spesialis,
                    'ruang'       => $sesi['ruang'] ?? '-',
                    'sesi'        => $sesi['sesi'] ?? '-',
                    'kuota'       => $kuota,
                    'terpakai'    => $terpakai,
                    'sisa'        => $sisa,
                    'jam_mulai'   => $jamMulai,
                    'jam_selesai' => $sesi['end'] ?? '',
                ];
            }
        }

        // ===================== Kunjungan Per Sesi =====================
        $kunjunganPerSesi = [
            'Pagi' => collect(),
            'Siang' => collect(),
            'Sore' => collect(),
            'Malam' => collect(),
        ];

        // Loop untuk mengelompokkan kunjungan berdasarkan sesi
        foreach ($allKunjunganHariIni as $kunjungan) {
            if (!$kunjungan->Jadwal || $kunjungan->Status !== 'menunggu') continue;

            // Ekstrak jam mulai & selesai
            [$jamMulai, $jamSelesai] = explode('-', $kunjungan->Jadwal . '-'); // trik aman supaya tetap dapet 2 elemen
            $jamMulai = trim($jamMulai);
            $jamSelesai = trim($jamSelesai);

            if (!$jamMulai || !$jamSelesai) continue;

            $start = Carbon::parse($kunjungan->Jadwal_Kedatangan . ' ' . $jamMulai);
            $end = Carbon::parse($kunjungan->Jadwal_Kedatangan . ' ' . $jamSelesai);

            // Cek overlap dengan masing-masing sesi
            $sesiPagiStart = Carbon::createFromTime(6, 0);
            $sesiPagiEnd = Carbon::createFromTime(11, 59, 59);

            $sesiSiangStart = Carbon::createFromTime(12, 0);
            $sesiSiangEnd = Carbon::createFromTime(14, 59);

            $sesiSoreStart = Carbon::createFromTime(15, 0);
            $sesiSoreEnd = Carbon::createFromTime(18, 0);

            $sesiMalamStart = Carbon::createFromTime(18, 1); // 18:01
            $sesiMalamEnd = Carbon::createFromTime(22, 0);

            // Push ke sesi yang overlap
            if ($start->lte($sesiPagiEnd) && $end->gte($sesiPagiStart)) {
                $kunjunganPerSesi['Pagi']->push($kunjungan);
            }

            if ($start->lte($sesiSiangEnd) && $end->gte($sesiSiangStart)) {
                $kunjunganPerSesi['Siang']->push($kunjungan);
            }

            if ($start->lte($sesiSoreEnd) && $end->gte($sesiSoreStart)) {
                $kunjunganPerSesi['Sore']->push($kunjungan);
            }

            if ($start->lte($sesiMalamEnd) && $end->gte($sesiMalamStart)) {
                $kunjunganPerSesi['Malam']->push($kunjungan);
            }
        }

        // ===================== Kunjungan Diperiksa & Belum Hadir Per Sesi =====================
        $kunjunganDiperiksaPerSesi = [
            'Pagi' => collect(),
            'Siang' => collect(),
            'Sore' => collect(),
            'Malam' => collect(),
        ];

        // ===================== Pasien Tidak Selesai Per Sesi =====================
        $pasienTidakSelesaiPerSesi = [
            'Pagi' => collect(),
            'Siang' => collect(),
            'Sore' => collect(),
            'Malam' => collect(),
        ];

        // Loop untuk mengelompokkan kunjungan berdasarkan sesi
        foreach ($allKunjunganHariIni as $kunjungan) {
            if (!$kunjungan->Jadwal) continue;

            [$jamMulai, $jamSelesai] = explode('-', $kunjungan->Jadwal . '-');
            $jamMulai = trim($jamMulai);
            $jamSelesai = trim($jamSelesai);

            if (!$jamMulai || !$jamSelesai) continue;

            $start = Carbon::parse($kunjungan->Jadwal_Kedatangan . ' ' . $jamMulai);
            $end = Carbon::parse($kunjungan->Jadwal_Kedatangan . ' ' . $jamSelesai);

            foreach ($sesiWaktu as $namaSesi => [$sesiStart, $sesiEnd]) {
                $isOverlap = $start->lte($sesiEnd) && $end->gte($sesiStart);

                if (!$isOverlap) continue;

                if ($kunjungan->Status === 'diperiksa') {
                    $kunjunganDiperiksaPerSesi[$namaSesi]->push($kunjungan);
                } elseif (trim(strtolower($kunjungan->Status)) === 'belum hadir') {
                    $pasienTidakSelesaiPerSesi[$namaSesi]->push($kunjungan);
                }
            }
        }

        // ===================== Pasien Selesai Per Sesi =====================
        $pasienSelesaiPerSesi = [
            'Pagi' => collect(),
            'Siang' => collect(),
            'Sore' => collect(),
            'Malam' => collect(), // Tambahkan sesi Malam
        ];

        // Loop untuk mengelompokkan pasien selesai berdasarkan sesi
        foreach ($allKunjunganHariIni as $kunjungan) {
            if (!$kunjungan->Jadwal || $kunjungan->Status !== 'Selesai') continue;

            $jamMulai = explode('-', $kunjungan->Jadwal)[0] ?? null;
            $jamMulai = trim($jamMulai);
            if (!$jamMulai) continue;

            $tanggalJam = Carbon::parse($kunjungan->Jadwal_Kedatangan . ' ' . $jamMulai);

            if ($tanggalJam->between(Carbon::createFromTime(6, 0), Carbon::createFromTime(11, 59, 59))) {
                $pasienSelesaiPerSesi['Pagi']->push($kunjungan);
            } elseif ($tanggalJam->between(Carbon::createFromTime(12, 0), Carbon::createFromTime(14, 59, 59))) {
                $pasienSelesaiPerSesi['Siang']->push($kunjungan);
            } elseif ($tanggalJam->between(Carbon::createFromTime(15, 0), Carbon::createFromTime(18, 0))) {
                $pasienSelesaiPerSesi['Sore']->push($kunjungan);
            } elseif ($tanggalJam->between(Carbon::createFromTime(18, 1), Carbon::createFromTime(22, 0))) {
                $pasienSelesaiPerSesi['Malam']->push($kunjungan);
            }
        }

        // ===================== Grafik Kunjungan dan Pasien 30 Hari Terakhir =====================
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Ambil jumlah kunjungan per hari
        $kunjunganPerHari = Kunjungan::select(
            DB::raw('DATE(Jadwal_Kedatangan) as tanggal'),
            DB::raw('COUNT(*) as total')
        )->whereBetween('Jadwal_Kedatangan', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->pluck('total', 'tanggal');

        // Ambil jumlah pasien berdasarkan TANGGAL REGISTRASI
        $pasienPerHari = Pasien::select(
            DB::raw('DATE(Tanggal_Registrasi) as tanggal'),
            DB::raw('COUNT(*) as total')
        )->whereBetween('Tanggal_Registrasi', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->pluck('total', 'tanggal');

        // Buat array tanggal dan data
        $tanggalLabels = [];
        $dataKunjungan = [];
        $dataPasien = [];

        // Loop untuk mengisi data dari startDate hingga endDate
        for ($i = 0; $i < 30; $i++) {
            $tanggal = $startDate->copy()->addDays($i)->toDateString();
            $tanggalLabels[] = Carbon::parse($tanggal)->format('d M');
            $dataKunjungan[] = $kunjunganPerHari[$tanggal] ?? 0;
            $dataPasien[] = $pasienPerHari[$tanggal] ?? 0;

        }


// ============================================================================== \\


        // ===================== Kategory Bulan =====================
        $bulanNama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $labelJenisKelamin = [
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
        ];



        // ===================== Render View =====================
        $layananHariLabels  = $grafikLayananHariIni->keys();
        $layananHariData    = $grafikLayananHariIni->values();
        $layananBulanLabels = $grafikLayananBulan->keys();
        $layananBulanData   = $grafikLayananBulan->values();

        $datagrafikumur = [
            'hari' => [
                'labels' => $grafikUmurHariIni->pluck('Kelompok_Umur')->unique()->values(), // tanggal unik
                'date' => $grafikUmurHariIni->pluck('tanggal')->unique()->values(), // tanggal unik
                'count' => $grafikUmurHariIni->pluck('jumlah')->values(),               // jumlah pasien
            ],
            'bulan' => [
                'labels' => $grafikUmurBulanIni->pluck('Kelompok_Umur')->unique()->values(), // tanggal unik
                'moon' => $grafikUmurBulanIni->pluck('bulan')
                        ->map(fn($m) => $bulanNama[$m] ?? $m)
                        ->unique()
                        ->values(),
                'count' => $grafikUmurBulanIni->pluck('jumlah')->values(),
            ],
            'tahun' => [
                'labels' => $grafikUmurTahunIni->pluck('Kelompok_Umur')->unique()->values(), // tanggal unik
                'year' => $grafikUmurTahunIni->pluck('tahun')->unique()->values(), // tanggal unik
                'count' => $grafikUmurTahunIni->pluck('jumlah')->values(),
            ],
        ];

        $datagrafikjeniskelamin = [
            'hari' => [
                'labels' => $grafikjeniskelaminhariini->pluck('jk')
                        ->map(fn($jk) => $labelJenisKelamin[$jk] ?? $jk)
                        ->unique()
                        ->values(),
                'date' => $grafikjeniskelaminhariini->pluck('tanggal')->unique()->values(),
                'count' => $grafikjeniskelaminhariini->pluck('jumlah')->values(),
            ]
        ];

        return view('admin', compact(
            'today',
            'now',
            'sesiSaatIni',
            'pasienYangBolehDitampilkan',
            'kunjunganHariIniDetail',
            'pasienHariIni',
            'layananHariIni',

            'layananHariLabels',
            'layananHariData',
            'layananHariDetail',
            'layananBulanLabels',
            'layananBulanData',
            'layananBulanDetail',
            'datagrafikumur',
            'datagrafikjeniskelamin',


            'grafikGender',
            'grafikUmur',
            'jadwalDokterHariIni',
            'kunjunganPerSesi',
            'kunjunganDiperiksaPerSesi',
            'pasienTidakSelesaiPerSesi',
            'pasienSelesaiPerSesi',
            'allKunjunganHariIni',
            'dokterList',
            'tanggalLabels',
            'dataKunjungan',
            'dataPasien',
        ));
    }

    //============ UPDATE STATUS PASIEN =====================
    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diperiksa,Belum Hadir,Selesai',
        ]);

        $kunjungan = Kunjungan::findOrFail($id);
        if ($request->status === 'diperiksa') {
            $sudahAda = Kunjungan::where('Id_Dokter', $kunjungan->Id_Dokter)
                ->where('Jadwal', $kunjungan->Jadwal)
                ->where('Status', 'diperiksa')
                ->where('Id_Kunjungan', '!=', $kunjungan->Id_Kunjungan)
                ->exists();

            if ($sudahAda) {
                return back()->with('error', 'Sudah ada pasien yang sedang diperiksa oleh dokter ini pada jadwal tersebut.');
            }
        }
        $kunjungan->Status = $request->status;
        $kunjungan->save();

        return back()->with('success', 'Status kunjungan berhasil diperbarui.');
    }
    //===================================================================
    private function getSesiDariJam($jam)
    {
        if (!$jam || !is_string($jam)) return [];

        try {
            if (str_contains($jam, '-')) {
                [$start, $end] = array_map('trim', explode('-', $jam));
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);

                $sesi = [];

                // Pagi: 06:00 - 11:59
                if ($startTime->lte(Carbon::createFromTime(11, 59)) && $endTime->gte(Carbon::createFromTime(6, 0))) {
                    $sesi[] = 'pagi';
                }

                // Siang: 12:00 - 14:59
                if ($startTime->lte(Carbon::createFromTime(14, 59)) && $endTime->gte(Carbon::createFromTime(12, 0))) {
                    $sesi[] = 'siang';
                }

                // Sore: 15:00 - 18:00
                if ($startTime->lte(Carbon::createFromTime(18, 0)) && $endTime->gte(Carbon::createFromTime(15, 0))) {
                    $sesi[] = 'sore';
                }

                // Malam: 18:01 - 22:00
                if ($startTime->lte(Carbon::createFromTime(22, 0)) && $endTime->gte(Carbon::createFromTime(18, 1))) {
                    $sesi[] = 'malam';
                }

                return $sesi;
            }

            // Jika hanya satu jam saja
            $time = Carbon::createFromFormat('H:i', $jam);

            if ($time->between(Carbon::createFromTime(6, 0), Carbon::createFromTime(11, 59))) return ['pagi'];
            if ($time->between(Carbon::createFromTime(12, 0), Carbon::createFromTime(14, 59))) return ['siang'];
            if ($time->between(Carbon::createFromTime(15, 0), Carbon::createFromTime(18, 0))) return ['sore'];
            if ($time->between(Carbon::createFromTime(18, 1), Carbon::createFromTime(22, 0))) return ['malam'];
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }
    public function tandaiBelumHadir($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $pasienId = $kunjungan->Id_Pasien;
        $tanggal = $kunjungan->Jadwal_Kedatangan;

        $sesiSekarang = $this->getSesiDariJam(now()->format('H:i'))[0] ?? null;

        if (!$sesiSekarang) {
            return redirect()->back()->with('warning', 'Sesi saat ini tidak dikenali.');
        }

        $kunjunganHariIni = Kunjungan::where('Id_Pasien', $pasienId)
            ->whereDate('Jadwal_Kedatangan', $tanggal)
            ->get();

        foreach ($kunjunganHariIni as $kunjungan) {
            $sesiKunjungan = $this->getSesiDariJam($kunjungan->Jadwal);

            if (in_array($sesiSekarang, $sesiKunjungan)) {
                $kunjungan->update(['Status' => 'Belum Hadir']);
            }
        }

        return redirect()->back()->with('success', 'Status pasien ditandai sebagai belum hadir.');
    }
    public function updateStatusMenungguOtomatis()
    {
        $now = now();
        $sesiSekarang = $this->getSesiDariJam($now->format('H:i'))[0] ?? null;

        if (!$sesiSekarang) {
            return redirect()->back()->with('warning', '⛔ Sesi saat ini tidak dikenali.');
        }

        $jumlah = 0;

        $kunjungans = Kunjungan::whereDate('Jadwal_Kedatangan', $now->toDateString())
            ->where('Status', 'Belum Hadir')
            ->get();

        foreach ($kunjungans as $kunjungan) {
            $sesiKunjungan = $this->getSesiDariJam($kunjungan->Jadwal);

            if (in_array($sesiSekarang, $sesiKunjungan)) {
                $kunjungan->update(['Status' => 'menunggu']);
                $jumlah++;
            }
        }

        return redirect()->back()->with('success', "✅ $jumlah kunjungan diubah ke status 'menunggu' (sesi: $sesiSekarang)");
    }
    // ============================================================
    public function updateStatusPasienBelumHadirMenjadiTidakSelesai()
    {
        $now = Carbon::now(); // Ambil waktu saat ini
        $hariIni = $now->toDateString(); // Format ke tanggal (contoh: 2025-07-23)

        // Ambil semua kunjungan yang statusnya "   Hadir" dan jadwal kedatangannya sebelum hari ini
        $kunjungans = Kunjungan::where('Status', 'Belum Hadir')
            ->whereDate('Jadwal_Kedatangan', '<', $hariIni)
            ->get();

        $count = 0; // Untuk menghitung berapa yang diupdate

        foreach ($kunjungans as $kunjungan) {
            // Ubah status menjadi "Tidak Selesai"
            $kunjungan->Status = 'Tidak Selesai';
            $kunjungan->save();
            $count++;
        }
    }
    //=============================================================//

    // ===================== Create Admin ===================== //
    public function createAdmin(Request $request)
    {
        // Proteksi: hanya user dengan role "admin" yang boleh akses
        if (auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            //'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,dokter',
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password, // otomatis terhash jika pakai mutator
            'role' => $request->role,
        ]);

        return back()->with('success', 'Admin baru berhasil dibuat.');
    }
}
