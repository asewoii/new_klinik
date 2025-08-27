<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class MsIndikasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ms_indikasi')->insert([
            [
                'Kode_Indikasi' => 'IND001',
                'deskripsi'     => 'Poli Umum',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND002',
                'deskripsi'     => 'Poli Anak',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND003',
                'deskripsi'     => 'Poli Kandungan',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND004',
                'deskripsi'     => 'Poli Penyakit Dalam',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND005',
                'deskripsi'     => 'Poli Saraf',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND006',
                'deskripsi'     => 'Poli Paru',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND007',
                'deskripsi'     => 'Poli Gigi',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND008',
                'deskripsi'     => 'Poli Jantung',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND009',
                'deskripsi'     => 'Poli THT',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
            [
                'Kode_Indikasi' => 'IND010',
                'deskripsi'     => 'Poli Jiwa',
                'Create_Date'   => Carbon::now(),
                'Create_By'     => 'Admin'
            ],
        ]);
    }
}