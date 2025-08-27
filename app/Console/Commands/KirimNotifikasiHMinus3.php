<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Settings;

class KirimNotifikasiHMinus3 extends Command
{
    protected $signature = 'kunjungan:reminder-h3';
    protected $description = 'Kirim pengingat kunjungan ke pasien H-3';

    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->dailyAt('08:00'); // setiap hari jam 08:00
    }

    public function handle()
    {
        $tanggalTarget = Carbon::today()->addDays(3)->toDateString();

        $kunjunganList = Kunjungan::where('Jadwal_Kedatangan', $tanggalTarget)
            ->whereIn('Status', ['terdaftar', 'menunggu'])
            ->get();

        foreach ($kunjunganList as $kunjungan) {
            $pasien = Pasien::find($kunjungan->Id_Pasien);
            $dokter = Dokter::find($kunjungan->Id_Dokter);
            if (!$pasien || !$dokter) continue;

            $cacheKey = 'notif-h3-' . $kunjungan->Id_Kunjungan;
            if (Cache::has($cacheKey)) continue;

            $pesan = "🔔 *Pengingat Kunjungan*\n\n" .
                    "Halo *{$pasien->Nama_Pasien}*,\n" .
                    "Anda memiliki jadwal kunjungan di Klinik:\n\n" .
                    "📅 Tanggal: *{$kunjungan->Jadwal_Kedatangan}*\n" .
                    "🕘 Jam: *{$kunjungan->Jadwal}*\n" .
                    "👨‍⚕️ Dokter: *{$dokter->Nama_Dokter}* ({$dokter->Spesialis})\n\n" .
                    "Mohon hadir tepat waktu. Terima kasih 🙏";

            $this->sendFonnteMessage($pasien->No_Tlp, $pesan);
            Cache::put($cacheKey, true, now()->addDay());
            Log::info("🔔 Notifikasi H-3 terkirim ke {$pasien->Nama_Pasien} ({$pasien->No_Tlp})");
        }
    }

    protected function sendFonnteMessage($nomor, $message)
    {
        $fonnte = Settings::get('fonnte_api_key', 'isi-default-api-key');

        $response = Http::withHeaders([
            'Authorization' => $fonnte,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $message,
            'delay' => 2,
            'schedule' => 0,
        ]);

        if (!$response->successful()) {
            Log::error('Fonnte gagal: ' . $response->body());
        }
    }
}
