<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

use App\Http\Middleware\Pasien;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\FormPasienController;
use App\Http\Controllers\ScanQRController;
use App\Http\Controllers\FormKunjunganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporansController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ResetPinController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\PdfToolkitController;



// ============== [ DEFAULT & INFO ] ==============
Route::get('/', fn() => redirect('/verify'));
Route::get('info', fn() => redirect('/info.php'));

// ============== [ GUEST (belum login) ] ==============
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/login-pasien', [OtpController::class, 'index'])->name('verify');
    Route::post('/verify_pin', [OtpController::class, 'loginpin'])->name('login_pin');
    Route::post('/send_otp', [OtpController::class, 'sendOtp'])->name('send_otp');
    Route::post('/kode_otp', [OtpController::class, 'verifyOtp'])->name('verify_otp');
    Route::post('/cek_status_pin', [OtpController::class, 'cekStatusPin']);


    Route::get('/reset-pin', [OtpController::class, 'reset_pin'])->name('reset.pin');
    Route::post('/reset-pin/request-otp', [OtpController::class, 'requestOtpResetPin'])->name('reset.pin.requestOtp');
    Route::post('/reset-pin/verifikasi-otp', [OtpController::class, 'verifyOtpResetPin'])->name('reset.pin.verifikasiOtp');
    Route::post('/reset-pin/simpan', [OtpController::class, 'simpanResetPin'])->name('reset.pin.simpan');



    Route::get('/daftar-pasien', [FormPasienController::class, 'showRegistrationForm'])->name('daftar.pasien');
    Route::post('/daftar-pasien', [FormPasienController::class, 'formstore'])->name('daftar.pasien.store');
    Route::post('/cek-notlp', [FormPasienController::class, 'checkNoTlp'])->name('cek.notlp');
    Route::post('/cek-nik', [FormPasienController::class, 'checkNik'])->name('cek.nik');
});

// ====================== [ ADMIN AREA ] ==============================
Route::middleware(['auth:web', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/Dashboard', [AdminController::class, 'index'])->name('admin');
    Route::post('/admin/kunjungan/{id}/ubah-status', [AdminController::class, 'ubahStatus'])->name('admin.kunjungan.ubahStatus');
    Route::put('/kunjungan/{id}/terproses', [AdminController::class, 'tandaiTerproses'])->name('admin.tandaiTerproses');
    Route::get('/dokter-terbanyak-hari-ini', [AdminController::class, 'dokterTerbanyakHariIni']);
    Route::get('/grafik-mingguan', [AdminController::class, 'grafikMingguan'])->name('grafik.mingguan');
    Route::get('/grafik-bulanan', [AdminController::class, 'grafikBulanan'])->name('grafik.bulanan');
    Route::post('/kunjungan/{id}/belum-hadir', [AdminController::class, 'tandaiBelumHadir'])->name('admin.kunjungan.belumHadir');
    Route::get('/admin/update-status-menunggu', [AdminController::class, 'updateStatusMenungguOtomatis'])
        ->name('admin.updateStatusMenunggu');


    // ===> [ Layanan ]
    Route::get('/layanan', [LayananController::class, 'index'])->name('indikasi.index');
    Route::get('/indikasi/create', [LayananController::class, 'create'])->name('indikasi.create');
    Route::post('/indikasi/store', [LayananController::class, 'store'])->name('indikasi.store');
    Route::get('/indikasi/{id}/edit', [LayananController::class, 'edit'])->name('indikasi.edit');
    Route::put('/indikasi/{id}', [LayananController::class, 'update'])->name('indikasi.update');
    Route::delete('/indikasi/select-delete', [LayananController::class, 'SelectDelete'])->name('indikasi.select_delete');
    Route::get('/indikasi/clear-notification', [LayananController::class, 'clearNotification'])->name('indikasi.clear_notification');
    Route::get('/indikasi/create/check-Nama-Layanan', [LayananController::class, 'checkDeskripsi'])->name('indikasi.checkLayanan');

    Route::resource('indikasi', LayananController::class);

    // ===> [ Sesi ]
    Route::get('/sesi', [SesiController::class, 'index'])->name('sesi.index');
    Route::get('/sesi/create', [SesiController::class, 'create'])->name('sesi.create');
    Route::post('/sesi/store', [SesiController::class, 'store'])->name('sesi.store');
    Route::get('/sesi/{id}/edit', [SesiController::class, 'edit'])->name('sesi.edit');
    Route::put('/sesi/{id}', [SesiController::class, 'update'])->name('sesi.update');
    Route::delete('/sesi/select-delete', [SesiController::class, 'SelectDelete'])->name('sesi.select_delete');
    Route::get('/sesi/clear-notification', [SesiController::class, 'clearNotification'])->name('sesi.clear_notification');
    Route::post('/sesi/store-multiple', [SesiController::class, 'storeMultiple'])->name('sesi.storeMultiple');

    Route::get('/sesi/check', [SesiController::class, 'checkSesi'])->name('sesi.check');
    Route::get('/sesi/check-store', [SesiController::class, 'checkStore'])->name('sesi.checkStore');
    Route::get('/sesi/check-store-jam-bentrok', [SesiController::class, 'checkStoreJamBentrok'])->name('sesi.checkStoreJamBentrok');

    Route::resource('sesi', SesiController::class);

    // ===> [ Dokter ]
    Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/dokter/create', [DokterController::class, 'create'])->name('dokter.create');
    Route::post('/dokter/store', [DokterController::class, 'store'])->name('dokter.store');
    Route::get('/dokter/{id}/edit', [DokterController::class, 'edit'])->name('dokter.edit');
    Route::put('/dokter/{id}', [DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/dokter/select-delete', [DokterController::class, 'SelectDelete'])->name('dokter.select_delete');
    Route::get('/dokter/clear-notification', [DokterController::class, 'clearNotification'])->name('dokter.clear_notification');
    Route::get('/get/available-ruangan', [DokterController::class, 'getAvailableRuangan'])->name('ruangan.tersedia');
    Route::get('/dokter/jadwal-harian', [DokterController::class, 'jadwalHarian'])->name('dokter.jadwal_harian');
    Route::resource('dokter', DokterController::class);

    // ===> [ Pasien ]
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/pasien/store', [PasienController::class, 'store'])->name('pasien.store');
    Route::delete('/pasien/select-delete', [PasienController::class, 'SelectDelete'])->name('pasien.select_delete');
    Route::get('/pasien/clear-notification', [PasienController::class, 'clearNotification'])->name('pasien.clear_notification');
    Route::get('/pasien/datadiri-pasien/{id}', [PasienController::class, 'show'])->name('pasien.datadiri.admin');

    Route::resource('pasien', PasienController::class);

    //RUANGAN
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::delete('/ruangan/select-delete', [RuanganController::class, 'SelectDelete'])->name('ruangan.select_delete');
    Route::get('/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::post('/ruangan/store', [RuanganController::class, 'store'])->name('ruangan.store');

    Route::get('/ruangan/check-store', [RuanganController::class, 'checkStore'])->name('ruangan.checkStore');
    Route::get('/ruangan/check-edit', [RuanganController::class, 'checkEdit'])->name('ruangan.checkEdit');
    Route::resource('ruangan', RuanganController::class);

    // ===> [ Form Kunjungan ]
    Route::get('formkunjungan', [FormKunjunganController::class, 'create'])->name('admin.kunjungan.create');
    Route::post('/kunjungan/store', [FormKunjunganController::class, 'store'])->name('admin.kunjungan.store');
    Route::get('nourut/{id}', [FormKunjunganController::class, 'nourut'])->name('admin.form.nourut');
    Route::get('/live-antrian-dokter', [FormKunjunganController::class, 'getLiveAntrian'])->name('admin.getLiveAntrian');

    // ===> [ Form Kunjungan ] Endpoint AJAX
    Route::get('/get-sesi-dan-jadwal', [FormKunjunganController::class, 'getSesiDanJadwal'])->name('admin.kunjungan.getSesiDanJadwal');
    Route::get('/get-dokter-by-tanggal', [FormKunjunganController::class, 'getDokterByTanggal'])->name('admin.kunjungan.getDokterByTanggal');
    Route::get('/get-dokter-by-layanan', [FormKunjunganController::class, 'getDokterByLayanan'])->name('admin.kunjungan.getDokterByLayanan');
    Route::get('/get-jam-by-dokter', [FormKunjunganController::class, 'getJamByDokter'])->name('admin.kunjungan.getJamByDokter');
    Route::get('/kunjungan/update-menunggu', [FormKunjunganController::class, 'updateStatusMenunggu'])->name('admin.kunjungan.updateMenunggu');
    Route::get('/kunjungan/update-menunggu', [FormKunjunganController::class, 'updateStatusMenunggu'])->name('kunjungan.getDokterByTanggal');


    //PEMERIKSAAN
    Route::prefix('pemeriksaan')->group(function () {
        Route::get('/', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
        Route::get('/periksa', [PemeriksaanController::class, 'phariIni'])->name('admin.pemeriksaan.periksa');
        Route::get('/laporan', [PemeriksaanController::class, 'laporan'])->name('pemeriksaan.laporan');
        Route::get('/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
        Route::post('/store', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
        Route::get('/{id}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');
        Route::get('/{id}/edit', [PemeriksaanController::class, 'edit'])->name('pemeriksaan.edit');
        Route::put('/{id}', [PemeriksaanController::class, 'update'])->name('pemeriksaan.update');
        Route::delete('/{id}', [PemeriksaanController::class, 'destroy'])->name('pemeriksaan.destroy');
    });

    Route::get('/kunjungan/history/{id}', [KunjunganController::class, 'history'])->name('admin.kunjungan.history');
    Route::get('/kunjungans', [KunjunganController::class, 'index'])->name('admin.kunjungan.index');
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('/kunjungan/{id}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
    Route::put('/kunjungan/{id}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::get('/kunjungan/hariini', [KunjunganController::class, 'hariIni'])->name('admin.kunjungan.hari_ini');
    Route::put('/admin/kunjungan/{id}/status', [KunjunganController::class, 'updateStatus'])->name('admin.updateStatus');

    Route::resource('kunjungan', KunjunganController::class);
    //Laporan kunjungan
    Route::get('/admin/kunjungan/laporan', [KunjunganController::class, 'laporan'])->name('kunjungan.laporan');
    Route::get('/admin/kunjungan/export/pdf', [KunjunganController::class, 'exportPdf'])->name('kunjungan.export.pdf');
    Route::get('/admin/kunjungan/export/excel', [KunjunganController::class, 'exportExcel'])->name('kunjungan.export.excel');


    // TES LAPORANS
    Route::delete('/laporan/select-delete', [LaporanController::class, 'selectDelete'])->name('laporan.select_delete');
    Route::get('/laporans', [LaporansController::class, 'index'])->name('laporans.index');
    Route::get('/laporans/filter', [LaporansController::class, 'filter'])->name('laporans.filter');
    Route::get('/laporans/download', [LaporansController::class, 'download'])->name('laporans.download');
    Route::get('/laporans/preview', [LaporansController::class, 'preview'])->name('laporans.preview');


    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::post('/upload', [PdfToolkitController::class, 'upload'])->name('upload');
        Route::post('/combine', [PdfToolkitController::class, 'combine'])->name('combine');
        Route::post('/split', [PdfToolkitController::class, 'split'])->name('split');
        Route::post('/pdf-to-jpg', [PdfToolkitController::class, 'pdfToJpg'])->name('pdf-to-jpg');
        Route::post('/jpg-to-pdf', [PdfToolkitController::class, 'jpgToPdf'])->name('jpg-to-pdf');
        Route::post('/rotate', [PdfToolkitController::class, 'rotate'])->name('rotate');
        Route::post('/compress', [PdfToolkitController::class, 'compress'])->name('compress');
    });

    Route::get('/pdf/toolkit', function () {
        return view('pdf.toolkit');
    });
    Route::delete('/pdf/delete/{filename}', [PdfToolkitController::class, 'delete'])->name('pdf.delete');
    Route::get('/pdf/list', [PdfToolkitController::class, 'list'])->name('pdf.list');

    Route::get('/adminnew', function () {
        return view('');
    });

    //ALL LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/pemeriksaan/export/pdf', [LaporanController::class, 'exportPdfPemeriksaan'])->name('laporan.pemeriksaan.export.pdf');
    Route::get('/laporan/pemeriksaan/export/excel', [LaporanController::class, 'exportExcelPemeriksaan'])->name('laporan.pemeriksaan.export.excel');
    Route::get('/laporan/pasien/export/pdf', [LaporanController::class, 'exportPasienPdf'])->name('laporan.pasien.export.pdf');
    Route::get('/laporan/pasien/export/excel', [LaporanController::class, 'exportPasienExcel'])->name('laporan.pasien.export.excel');
    Route::get('/laporan/dokter/export/pdf', [LaporanController::class, 'exportDokterPdf'])->name('laporan.dokter.pdf');
    Route::get('/laporan/dokter/export/excel', [LaporanController::class, 'exportDokterExcel'])->name('laporan.dokter.excel');
    Route::get('/laporan/ruangan/pdf', [LaporanController::class, 'exportRuanganPdf'])->name('laporan.ruangan.pdf');
    Route::get('/laporan/ruangan/excel', [LaporanController::class, 'exportRuanganExcel'])->name('laporan.ruangan.excel');




    // ===> [ Settings ]
    Route::get('/settings', [SettingsController::class, 'index'])->name('setting.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('setting.update');
    Route::post('/users', [AdminController::class, 'createAdmin'])->name('admin.users.store');

    /*
    // ===> [ Settings ]
    Route::get('semua-laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');*/

    Route::get('/log-view', function () {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return 'Log file not found.';
        }

        $lines = array_slice(file($logFile), -100); // ambil 100 baris terakhir
        return response('<pre>' . htmlentities(implode("", $lines)) . '</pre>');
    })->name('log.view');
});

// ====================== [ DOKTER AREA ] ==============================
Route::middleware(['auth:web', 'role:dokter'])->prefix('dokter')->group(function () {});

// ====================== [ PASIEN AREA ] ==============================
Route::middleware(Pasien::class)->group(function () {
    Route::get('/', fn() => redirect('/verify'));

    Route::get('/user/datadiri/{id}', [PasienController::class, 'show'])->name('pasien.datadiri');
    Route::get('/pasien/download-qr/{id}', [PasienController::class, 'downloadQR'])->name('pasien.download_qr');
    Route::post('/cek_no', [OtpController::class, 'cekNoTlp'])->name('cek_no');
    Route::get('/kunjungans', [KunjunganController::class, 'index'])->name('pasien.kunjungan.index');
    Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::get('/pasien/periksa/{id}', [PasienController::class, 'getDetailPemeriksaan']);

    //Route::post('/reset-pin/request-otp', [OtpController::class, 'requestOtpResetPin']);
    //Route::post('/reset-pin/verify-otp', [OtpController::class, 'verifyOtpResetPin']);
    //Route::post('/reset-pin/simpan', [OtpController::class, 'simpanResetPin']);

    // ===> [ Form Kunjungan ]
    Route::get('/formkunjungans', [FormKunjunganController::class, 'create'])->name('pasien.kunjungan.create');
    Route::post('/formkunjungan', [FormKunjunganController::class, 'store'])->name('pasien.kunjungan.store');
    Route::get('/nourut/{id}', [FormKunjunganController::class, 'nourut'])->name('pasien.form.nourut');

    // ===> [ Download QR ]
    Route::get('/pasien-kunjungan/download-qr/{id}', [FormKunjunganController::class, 'downloadQRkunjungan'])->name('pasien.kunjungan.download_qr');
});

// ============== [ Scan QR dan Data Publik ] ==============
Route::get('/qr-scanner', fn() => view('scanQR'));
Route::post('/api/process-qr', [ScanQRController::class, 'process']);
Route::get('/get_sesi/{tanggal}', [KunjunganController::class, 'get_tanggal_sesi']);
Route::get('/get-dokter-by-sesi/{id}', [FormKunjunganController::class, 'getDokterBySesi']);
Route::get('/get-jadwal-by-dokter/{idDokter}', [FormKunjunganController::class, 'getJadwalByDokter']);

// ============== [ Logout ] ==============
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout_admin');
Route::post('/pasien/logout', [PasienController::class, 'logout'])->name('logout_pasien');

// ==================== FORM KUNJUNGAN - AJAX FLEKSIBEL ====================
Route::post('/get-layanan-by-jam', [FormKunjunganController::class, 'getLayananByJam']);
Route::get('/get-jam-by-layanan', [FormKunjunganController::class, 'getJamByLayanan']);
Route::post('/get-dokter-by-layanan', [FormKunjunganController::class, 'getDokterByLayanan']);
Route::post('/get-jam-layanan-by-dokter', [FormKunjunganController::class, 'getJamLayananByDokter']);
Route::post('/get-dokter-by-jam-layanan', [FormKunjunganController::class, 'getDokterByJamAndLayanan']);
Route::post('/get-dokter-by-tanggal', [FormKunjunganController::class, 'getByTanggal']);
Route::post('/get-jam-by-dokter', [FormKunjunganController::class, 'getJamByDokter']);
Route::get('/get-tanggal-by-indikasi', [FormKunjunganController::class, 'getTanggalByIndikasi']);

Route::post('/get-dokter-by-tanggal', [FormKunjunganController::class, 'getDokterByTanggal']);
Route::post('/get-jam-by-tanggal', [FormKunjunganController::class, 'getJamByTanggal']);
Route::get('/get-tanggal-bisa-daftar', [FormKunjunganController::class, 'getTanggalBisaDaftar']);
Route::get('/get-layanan-by-indikasi', [FormKunjunganController::class, 'getLayananByIndikasi']);
Route::get('/get-tanggal-by-layanan', [FormKunjunganController::class, 'getTanggalByLayanan']);
Route::post('/get-dokter-by-jam', [FormKunjunganController::class, 'getDokterByJam']);


Route::get('/get-tanggal-by-indikasi', [FormKunjunganController::class, 'getTanggalByIndikasi']);
Route::get('/get-jam-by-indikasi', [FormKunjunganController::class, 'getJamByIndikasi']);
Route::get('/get-semua-indikasi', [FormKunjunganController::class, 'getSemuaIndikasi']);


Route::get('/get-dokter-available', [FormKunjunganController::class, 'getDokterAvailable'])->name('kunjungan.getDokterAvailable');

// Tambahan jika masih dipakai
Route::get('/get-dokter-by-sesi/{id}', [FormKunjunganController::class, 'getDokterBySesi']);
Route::get('/get-jadwal-by-dokter/{idDokter}', [FormKunjunganController::class, 'getJadwalByDokter']);

// Transalte - locations
Route::get('/translate', [TranslateController::class, 'index'])->name('translate.ajax');
Route::get('/lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return back(); // akan diabaikan kalau pakai AJAX
})->name('lang.switch');




// Message H-3 Sebelum Melakukan Kunjungan
Route::get('/tes-notif-h3', [FormKunjunganController::class, 'kirimNotifikasiHMinus3']);

Route::get('/live-antrian-dokter', [FormKunjunganController::class, 'getLiveAntrian'])->name('admin.getLiveAntrian');
/* Live Antrian
Route::get('/live-antrian', function (Request $request) {
    $dokter = $request->dokter;
    $tanggal = $request->tanggal;

    $sedang = DB::table('kunjungan')
        ->where('Id_Dokter', $dokter)
        ->whereDate('Jadwal_Kedatangan', $tanggal)
        ->where('Status', 'sedang')
        ->orderBy('Nomor_Urut')
        ->value('Nomor_Urut');

    return response()->json(['antrian' => $sedang ?? '-']);
});*/

// QR Scan sesuai role
Route::get('/view/datadiri/{id}', function ($id) {
    if (session()->has('pasien')) {
        return redirect()->route('pasien.datadiri', ['id' => $id]);
    } elseif (auth('web')->check() && auth('web')->user()->role === 'admin') {
        return redirect()->route('pasien.datadiri.admin', ['id' => $id]);
    } else {
        abort(403, 'Tidak diizinkan');
    }
});

// Form Kunjungan Sesuai role
Route::get('/formkunjungan-redirect/{id}', function ($id) {
    if (session()->has('pasien')) {
        return redirect('/formkunjungans?id=' . $id);
    } elseif (auth('web')->check() && auth('web')->user()->role === 'admin') {
        return redirect('/admin/formkunjungan?id=' . $id);
    } else {
        abort(403, 'Tidak diizinkan');
    }
})->name('redirect.formkunjungan');










Route::get('/get-dokter-available', [FormKunjunganController::class, 'getDokterAvailable'])->name('kunjungan.getDokterAvailable');

//Route::get('/kunjungan/create/{id}', [KunjunganController::class, 'create'])->name('kunjungan.create');
// Endpoint AJAX: Ambil sesi & dokter sesuai tanggal
//Route::get('/get-sesi-dan-jadwal', [FormKunjunganController::class, 'getSesiDanJadwal'])->name('kunjungan.getSesiDanJadwal');
