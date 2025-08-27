<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateStatusMenunggu;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command yang bisa dipanggil
     */
    protected $commands = [
        UpdateStatusMenunggu::class,
    ];

    /**
     * Jadwal cron job
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('kunjungan:menunggu-otomatis')->everyMinute();
        
    }

    /**
     * Command artisan lokal
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
