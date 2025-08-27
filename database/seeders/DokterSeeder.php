<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokter;
use Carbon\Carbon;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $dokters = [
            [
                'Nama_Dokter' => 'dr. Andi Sutrisno',
                'Spesialis' => 'Poli Umum',
                'No_Telp' => '081234567891',
                'Email' => 'andi@example.com',
                'Alamat' => 'Jl. Melati No. 10',
                'Jadwal_Dokter' => [
                    ['hari' => 'Senin', 'start' => '08:00', 'end' => '12:00'],
                    ['hari' => 'Rabu', 'start' => '09:00', 'end' => '13:00'],
                ],
                'Kuota_Max' => 10,
                'Create_Date' => $now,
                'Create_By' => 'Seeder',
                'Last_Update_By' => 'Seeder',
            ],
            [
                'Nama_Dokter' => 'dr. Budi Santoso',
                'Spesialis' => 'Poli Anak',
                'No_Telp' => '081234567892',
                'Email' => 'budi@example.com',
                'Alamat' => 'Jl. Kenanga No. 23',
                'Jadwal_Dokter' => [
                    ['hari' => 'Selasa', 'start' => '10:00', 'end' => '14:00'],
                    ['hari' => 'Kamis', 'start' => '08:30', 'end' => '12:30'],
                ],
                'Kuota_Max' => 12,
                'Create_Date' => $now,
                'Create_By' => 'Seeder',
                'Last_Update_By' => 'Seeder',
            ],
            [
                'Nama_Dokter' => 'dr. Clara Wijaya',
                'Spesialis' => 'Poli Gigi',
                'No_Telp' => '081234567893',
                'Email' => 'clara@example.com',
                'Alamat' => 'Jl. Cempaka No. 5',
                'Jadwal_Dokter' => [
                    ['hari' => 'Senin', 'start' => '13:00', 'end' => '17:00'],
                    ['hari' => 'Jumat', 'start' => '08:00', 'end' => '11:00'],
                ],
                'Kuota_Max' => 8,
                'Create_Date' => $now,
                'Create_By' => 'Seeder',
                'Last_Update_By' => 'Seeder',
            ],
        ];

        foreach ($dokters as $dokter) {
            Dokter::create($dokter);
        }
    }
}
