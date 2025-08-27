<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::parse('2025-07-03 08:20:14');

        DB::table('settings')->insert([
            [
                'id' => 1,
                'key' => 'nama_klinik',
                'value' => 'Medical Caree',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'key' => 'batas_otp',
                'value' => '10',
                'created_at' => $now,
                'updated_at' => Carbon::parse('2025-07-08 04:16:53'),
            ],
            [
                'id' => 3,
                'key' => 'batas_pin',
                'value' => null,
                'created_at' => $now,
                'updated_at' => Carbon::parse('2025-07-03 08:47:28'),
            ],
            [
                'id' => 4,
                'key' => 'otp_expire_sec',
                'value' => '1',
                'created_at' => $now,
                'updated_at' => Carbon::parse('2025-07-08 04:16:53'),
            ],
            [
                'id' => 5,
                'key' => 'batas_percobaan_pin',
                'value' => '5',
                'created_at' => Carbon::parse('2025-07-03 08:50:25'),
                'updated_at' => Carbon::parse('2025-07-10 04:58:45'),
            ],
            [
                'id' => 6,
                'key' => 'limit_reset_percobaan',
                'value' => '10',
                'created_at' => Carbon::parse('2025-07-03 08:50:25'),
                'updated_at' => Carbon::parse('2025-07-08 04:16:53'),
            ],
            [
                'id' => 7,
                'key' => 'blokir_menggunakan_pin',
                'value' => '3',
                'created_at' => Carbon::parse('2025-07-04 08:15:52'),
                'updated_at' => Carbon::parse('2025-07-08 04:16:53'),
            ],
            [
                'id' => 8,
                'key' => 'fonnte_active',
                'value' => '1',
                'created_at' => Carbon::parse('2025-07-04 08:15:52'),
                'updated_at' => Carbon::parse('2025-07-08 04:03:26'),
            ],
        ]);
    }
}
