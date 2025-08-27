<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SesiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $createdBy = 'Seeder';

        $sesiList = [
            ['Nama_Sesi' => 'Sesi Pagi', 'Mulai' => '07:00:00', 'Selesai' => '10:00:00'],
            ['Nama_Sesi' => 'Sesi Siang', 'Mulai' => '10:30:00', 'Selesai' => '13:00:00'],
            ['Nama_Sesi' => 'Sesi Sore', 'Mulai' => '13:30:00', 'Selesai' => '16:00:00'],
            ['Nama_Sesi' => 'Sesi Malam', 'Mulai' => '17:00:00', 'Selesai' => '20:00:00'],
        ];

        foreach ($sesiList as $sesi) {
            DB::table('ms_sesi')->insert([
                'Id_Sesi'         => Str::uuid(),
                'Nama_Sesi'       => $sesi['Nama_Sesi'],
                'Mulai_Sesi'      => $sesi['Mulai'],
                'Selesai_Sesi'    => $sesi['Selesai'],
                'Status'          => true,
                'Create_Date'     => $now,
                'Create_By'       => $createdBy,
                'Last_Update'     => $now,
                'Last_Update_By'  => $createdBy,
            ]);
        }
    }
}
