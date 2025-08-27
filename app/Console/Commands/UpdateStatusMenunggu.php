<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AdminController;

class UpdateStatusMenunggu extends Command
{
    protected $signature = 'kunjungan:menunggu-otomatis';
    protected $description = 'Update status pasien jadi menunggu jika sesi sesuai';

    public function handle()
    {
        // Panggil fungsi dari AdminController
        $controller = new AdminController();
        $controller->updateStatusMenungguOtomatis();

        $this->info('Status pasien berhasil diupdate menjadi menunggu.');
        return 0;
    }
}
