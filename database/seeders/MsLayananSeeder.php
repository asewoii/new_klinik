<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MsLayananSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $createBy = 'Seeder';

        $layanan = [
            // Layanan Poli Umum
            'Poli Umum',
            'Poli Gigi',
            'Poli Anak',
            'Poli Mata',
            'Poli THT',
            'Poli Kulit dan Kelamin',
            'Poli Saraf',
            'Poli Bedah',
            'Poli Jantung',
            'Poli Paru',
            'Poli Kandungan',
            'Poli Psikiatri',
            'Poli Rehabilitasi Medis',

            // Layanan Terapi & Rehabilitasi
            'Terapi Fisioterapi',
            'Terapi Okupasi',
            'Terapi Wicara',
            'Terapi Pijat Bayi',
            'Terapi Refleksi',
            'Terapi Akupuntur',
            'Terapi Psikologi',
            'Terapi Ortopedi',

            // Layanan Tambahan Klinik
            'Cek Laboratorium',
            'Tes Darah',
            'Vaksinasi',
            'Tes Covid-19',
            'Konsultasi Gizi',
            'Pemeriksaan Kehamilan',
            'Konsultasi Dokter Umum',
        ];

        $data = collect($layanan)->map(function ($nama) use ($now, $createBy) {
            return [
                'Id_Layanan'   => Str::uuid(),
                'Nama_Layanan' => $nama,
                'Create_Date'  => $now,
                'Create_By'    => $createBy,
            ];
        })->toArray();

        DB::table('ms_layanan')->insert($data);
    }
}
