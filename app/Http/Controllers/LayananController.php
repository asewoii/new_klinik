<?php

namespace App\Http\Controllers;

// Import model dan library yang dibutuhkan
use App\Models\Layanan;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class LayananController extends Controller
{
    // Menampilkan daftar data indikasi dengan fitur pencarian
    public function index(Request $request)
    {
        $indikasi = Layanan::query();
        $totals = Layanan::count();
        $now = Carbon::now();
        $hariIni = strtolower($now->locale('id')->translatedFormat('l'));

        // ------- [1] Notifikasi ------- \\
        $jumlah_notifikasi = Layanan::whereDate('Create_Date', Carbon::today())
            ->orderBy('Create_Date', 'desc')
            ->take(5)
            ->get();

        $sudah_dibaca = Cache::get('indikasi.dibaca', []);
        $indikasi_belum_dibaca = $jumlah_notifikasi->filter(function ($item) use ($sudah_dibaca) {
            return !in_array($item->Id_Layanan, $sudah_dibaca);
        });
        $jumlah_notifikasi = $indikasi_belum_dibaca->count();

        // ------- [2] Ambil data indikasi ------- \\
        $indikasi = Layanan::with(['dokters', 'kunjungans.ruangan'])->orderBy('Create_Date', 'desc');
        $data = $indikasi->get();

        // ------- [3] Ambil data kunjungan hari ini per dokter-ruang-jam ------- \\
        $kunjungan = DB::table('tr_kunjungan')
            ->join('ms_ruangan', 'tr_kunjungan.Id_Ruangan', '=', 'ms_ruangan.Id_Ruangan')
            ->select(
                'tr_kunjungan.Id_Dokter',
                'ms_ruangan.Nama_Ruangan',
                DB::raw("TIME_FORMAT(tr_kunjungan.Jadwal, '%H:%i') as jam"),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereDate('Tanggal_Registrasi', Carbon::today())
            ->whereNotNull('Jadwal')
            ->groupBy('tr_kunjungan.Id_Dokter', 'ms_ruangan.Nama_Ruangan', DB::raw("TIME_FORMAT(tr_kunjungan.Jadwal, '%H:%i')"))
            ->get()
            ->groupBy(function ($item) {
                return "{$item->Id_Dokter}|" . strtolower(trim($item->Nama_Ruangan)) . "|{$item->jam}";
            });

        // ------- [4] Isi Modal Indikasi ------- \\
        foreach ($data as $item) {
            $totalJadwal = 0;
            $groupedRuangan = [];

            // ============ Jadwal Dokter Hari Ini ============
            foreach ($item->dokters as $dokter) {
                $jadwals = is_string($dokter->Jadwal_Dokter)
                    ? json_decode($dokter->Jadwal_Dokter, true)
                    : ($dokter->Jadwal_Dokter ?? []);

                $jadwalHariIni = [];
                $totalJadwal = 0;

                foreach ($jadwals as $hari => $items) {
                    if (strtolower($hari) !== $hariIni) continue;

                    foreach ($items as $j) {
                        $start = $j['start'] ?? null;
                        $end   = $j['end'] ?? null;
                        $ruang = $j['ruang'] ?? '-';
                        $kuota = $j['kuota'] ?? 0;

                        $startTime = $start ? Carbon::parse($start) : null;
                        $endTime   = $end ? Carbon::parse($end) : null;

                        // Cari kuota terpakai
                        $key = "{$dokter->Id_Dokter}|" . strtolower(trim($ruang)) . "|{$start}";
                        $terpakai = $kunjungan[$key][0]->jumlah ?? 0;
                        $sisa = max(0, $kuota - $terpakai);

                        $status = '-';
                        if ($startTime && $endTime) {
                            if ($kuota == 0 && $now->between($startTime, $endTime)) {
                                $status = 'Full';
                            } elseif ($kuota > 0 && $now->gt($endTime)) {
                                $status = 'Close';
                            } elseif ($kuota > 0 && $now->between($startTime, $endTime)) {
                                $status = 'Tersedia';
                            } else {
                                $status = 'Tutup';
                            }
                        }

                        $jadwalHariIni[] = [
                            'hari' => ucfirst($hari),
                            'start' => $start,
                            'end' => $end,
                            'ruang' => $ruang,
                            'kuota max' => $kuota,
                            'kuota terpakai' => $terpakai,
                            'kuota sisa' => $sisa,
                            'status' => $status,
                        ];
                    }
                }

                $dokter->setAttribute('jadwal_hari_ini', $jadwalHariIni);
                $totalJadwal += count($jadwalHariIni);
            }

            // ============ Pasien Hari Ini (via kunjungans) ============ \\
            $pasienHariIni = collect();
            foreach ($item->kunjungans as $kunjungan) {
                if (
                    $kunjungan->Tanggal_Registrasi &&
                    Carbon::parse($kunjungan->Tanggal_Registrasi)->isToday()
                ) {
                    $pasienHariIni->push($kunjungan);
                }
            }

            // --- Simpan Atribut Tambahan ---
            $item->setAttribute('pasien_hari_ini', $pasienHariIni->unique('Id_Pasien'));
            $item->setAttribute('total_dokter', $item->dokters->count());
            $item->setAttribute('total_jadwal_dokter', $totalJadwal);
            $item->setAttribute('total_pasien_hari_ini', $pasienHariIni->count());
        }


        return view('indikasi.index', compact(
            'data', 'jumlah_notifikasi', 'indikasi_belum_dibaca', 'totals'
        ));
    }

    // ============= [ Notifikasi ]
    public function clearNotification() {
        try { 
            $Id_Layanan = Layanan::whereDate('Create_Date', Carbon::today())
                ->orderBy('Create_Date', 'desc')
                ->pluck('Id_Layanan')
                ->toArray();

            Cache::put('indikasi_dibaca', $Id_Layanan, now()->addHours(1));

            return response()->json(['status' => 'cleared']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Menampilkan form untuk membuat data baru
    public function create()
    {
        return view('indikasi.create'); // Tampilkan form create
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        // Validasi array dan setiap elemen
        $request->validate([
            'Nama_Layanan' => 'required|array|min:1',
            'Nama_Layanan.*' => 'required|string|max:255',
        ]);

        // Daftar keyword dan prefix kode
        $prefixes = [
            // Poli Umum & Spesialis
            'poli umum'         => 'POLI',
            'general clinic'    => 'POLI',
            
            'poli gigi'         => 'GIGI',
            'dental clinic'     => 'GIGI',
        
            'poli anak'         => 'ANAK',
            'pediatric clinic'  => 'ANAK',
            'child clinic'      => 'ANAK',
        
            'poli kandungan'    => 'OBGYN',
            'obgyn'             => 'OBGYN',
            'gynecology'        => 'OBGYN',
            'gynecologist'      => 'OBGYN',
            'maternity'         => 'OBGYN',
        
            'poli mata'         => 'MATA',
            'eye clinic'        => 'MATA',
            'ophthalmology'     => 'MATA',
        
            'poli tht'          => 'THT',
            'ent'               => 'THT',
            'ear nose throat'   => 'THT',
        
            'poli saraf'        => 'SARAF',
            'neurology'         => 'SARAF',
            'nerves'            => 'SARAF',
        
            'poli penyakit dalam' => 'IPD',
            'internal medicine'   => 'IPD',
        
            'poli jantung'      => 'JANT',
            'cardiology'        => 'JANT',
            'heart'             => 'JANT',
        
            // Terapi Alternatif
            'akupuntur'         => 'AKUP',
            'acupuncture'       => 'AKUP',
        
            'refleksi'          => 'REFK',
            'reflexology'       => 'REFK',
        
            'pijat'             => 'TRAD',
            'massage'           => 'TRAD',
            'tradisional'       => 'TRAD',
            'traditional'       => 'TRAD',
        
            'chiropractic'      => 'CHIR',
            'kiropraktik'       => 'CHIR',
        
            'tulang'            => 'TULANG',
            'bones'             => 'TULANG',
        
            // Subspesialis Mata
            'low vision'        => 'LV',
            'penglihatan rendah' => 'LV',
        
            'glaucoma'          => 'GLAU',
        
            'retina'            => 'RET',
            'vitreoretina'      => 'RET',
        
            'neuro'             => 'NO',
            'neuro-oftalmologi' => 'NO',
            'neuro-ophthalmology' => 'NO',
        
            'pediatrik'         => 'POS',
            'pediatric'         => 'POS',
            'child eye'         => 'POS',
            'strabismus'        => 'POS',
        
            'facial'            => 'OCUL',
            'oculoplastic'      => 'OCUL',
            'oculo plastic'     => 'OCUL',
        
            'uvea'              => 'UVEA',
            'infeksi'           => 'UVEA',
            'infection'         => 'UVEA',
            'imunologi'         => 'UVEA',
            'immunology'        => 'UVEA',
        ];

        $lastNumbers = [];
        $duplikat = [];

        // Cek dulu semua input apakah sudah ada
        foreach ($request->Nama_Layanan as $desc) {
            $desc = trim($desc);
            $descForMatch = strtolower($desc);
            $descFinal = ucwords($descForMatch);
    
            // Cek apakah sudah ada di database (case-insensitive)
            $exists = Layanan::whereRaw('LOWER(Nama_Layanan) = ?', [$descForMatch])->exists();
            if ($exists) {
                $duplikat[] = $descFinal;
                continue;
            }
    
            // Deteksi prefix
            $prefix = 'LYN';
            foreach ($prefixes as $keyword => $code) {
                if (str_contains($descForMatch, $keyword)) {
                    $prefix = $code;
                    break;
                }
            }
    
            // Ambil nomor terakhir untuk prefix
            if (!isset($lastNumbers[$prefix])) {
                $lastNumbers[$prefix] = Layanan::where('Id_Layanan', 'like', $prefix . '%')
                    ->selectRaw("MAX(CAST(SUBSTRING(Id_Layanan, LENGTH('$prefix') + 1) AS UNSIGNED)) as max_number")
                    ->value('max_number') ?? 0;
            }
    
            //$kode = $prefix . str_pad(++$lastNumbers[$prefix], 4, '0', STR_PAD_LEFT); // sesuai urutan 
            $kode = $prefix . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT); // nomor acak
    
            Layanan::create([
                'Id_Layanan' => $kode,
                'Nama_Layanan' => $descFinal,
                'Create_By' => Auth::user()->username,
                'Create_Date' => now(),
            ]);
    
            $this->addToLangFiles($descFinal);
        }

        return redirect()->route('indikasi.index')->with('success', 'Data berhasil ditambahkan.');
    }



    // Tambahkan layanan baru ke file bahasa jika belum ada
    private function addToLangFiles($text)
    {
        if (empty($text)) {
            Log::warning("Teks kosong saat menambahkan ke file bahasa.");
            return;
        }

        $timestamp = now()->translatedFormat('l, d M Y H:i:s');

        foreach (['id', 'en'] as $locale) {
            $file = resource_path("lang/{$locale}/messages.php");

            if (!file_exists($file)) {
                Log::warning("File bahasa tidak ditemukan: {$file}");
                continue;
            }

            // Baca file asli tanpa mengubah urutannya
            $content = file_get_contents($file);
            $lang = include $file;

            // Lewati jika key sudah ada
            if (array_key_exists($text, $lang)) {
                Log::info("Key '{$text}' sudah ada di {$locale}, tidak ditambahkan ulang.");
                continue;
            }

            // Translate jika bahasa Inggris
            $value = $text;
            if ($locale === 'en') {
                try {
                    $value = GoogleTranslate::trans($text, 'en');
                } catch (\Exception $e) {
                    Log::error("Gagal translate '{$text}' ke EN: {$e->getMessage()}");
                    $value = $text; // fallback
                }
            }

            // Siapkan string yang akan ditambahkan
            $newEntry = "\n    // New {$timestamp}\n    '" . addslashes($text) . "' => '" . addslashes($value) . "',\n";

            // Masukkan sebelum tanda penutup array
            $updatedContent = preg_replace('/\n\];\s*$/', "{$newEntry}];", $content);

            try {
                file_put_contents($file, $updatedContent);
                Log::info("Berhasil update file bahasa {$locale}: {$text} => {$value}");
            } catch (\Exception $e) {
                Log::error("Gagal menulis ke file {$file}: {$e->getMessage()}");
            }
        }
    }




    // Menampilkan form edit berdasarkan ID
    public function edit($id)
    {
        $data = Layanan::where('Id_Layanan', $id)->firstOrFail(); // Cari data berdasarkan ID
        return view('indikasi.edit', compact('data')); // Kirim data ke view edit
    }
    

    // Memperbarui data yang sudah ada
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'Nama_Layanan' => 'required|max:255',
        ]);

        $layanan = Layanan::findOrFail($id);

        $layanan->update([
            'Nama_Layanan' => $request->Nama_Layanan,
            'Create_By' => Auth::user()->username,
        ]);

        //preg_match('/\((.*?)\)/', $request->deskripsi, $matches);
        //$kodeg = isset($matches[1]) ? strtoupper($matches[1]) : 'UNK';


        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('indikasi.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus data berdasarkan Select
    public function SelectDelete(Request $request) {
        $ids = $request->input('selected_layanan');

        if(!$ids || !is_array($ids)) {
            return redirect()->route('indikasi.index')->with('error', 'Tidak ada data yang di pilih!');
        }

        Layanan::whereIn('Id_Layanan', $ids)->delete();

        return redirect()->route('indikasi.index')->with('success', 'Data berhasil di hapus');
    }

    // Menghapus data berdasarkan ID
    public function destroy($id)
    {
        $indikasi = Layanan::findOrFail($id);
        $indikasi->delete();

        return redirect()->route('indikasi.index')->with('success', 'Data berhasil dihapus.');
    }

    public function checkDeskripsi(Request $request)
    {
        $text = strtolower(trim($request->input('Nama_Layanan')));

        $exists = Layanan::whereRaw('LOWER(Nama_Layanan) = ?', [$text])->exists();

        return response()->json(['exists' => $exists]);
    }




    // ============= [ PDF Laporan Indikasi ]
    //public function laporan_indikasi(Request $request) {
    //    $tahun = $request ->input('tahun', now()->year);
    //    $data = DB::table('ms_pasien')
    //        ->select(
    //            DB::raw('MONTH(Tanggal_Registrasi) as bulan'),
    //            DB::raw('COUNT(*) as jumlah')
    //        )
    //        ->whereYear('Tanggal_Registrasi', $tahun)
    //        ->groupBy(DB::raw('MONTH(Tanggal_Registrasi)'))
    //        ->orderBy(DB::raw('MONTH(Tanggal_Registrasi)'))
    //        ->get();

    //    $laporan = collect(range(1, 12))->map(function ($bulan) use ($data) {
    //        $item = $data->firstWhere('bulan', $bulan);
    //        return [
    //            'bulan' => Carbon::create()->month($bulan)->translatedFormat('F'),
    //            'jumlah' => $item ? $item->jumlah : 0
    //        ];
    //    });

    //    return view('indikasi.index', compact('laporan', 'tahun'));
    //}
}
 