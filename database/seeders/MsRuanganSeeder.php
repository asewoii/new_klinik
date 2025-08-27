<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MsRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruanganList = [
            ['Nama_Ruangan' => 'Ruang Poli Umum', 'Jenis_Ruangan' => 'Poli', 'Lantai' => '1', 'Status' => true, 'Keterangan' => 'Digunakan untuk pemeriksaan umum'],
            ['Nama_Ruangan' => 'Ruang Poli Anak', 'Jenis_Ruangan' => 'Poli', 'Lantai' => '1', 'Status' => true, 'Keterangan' => 'Khusus pasien anak-anak'],
            ['Nama_Ruangan' => 'Ruang Poli Gigi', 'Jenis_Ruangan' => 'Poli', 'Lantai' => '2', 'Status' => true, 'Keterangan' => 'Pemeriksaan dan perawatan gigi'],
            ['Nama_Ruangan' => 'Ruang Terapi Fisik', 'Jenis_Ruangan' => 'Terapis', 'Lantai' => '2', 'Status' => true, 'Keterangan' => 'Fisioterapi dan terapi otot'],
            ['Nama_Ruangan' => 'Ruang Konsultasi Psikologi', 'Jenis_Ruangan' => 'Konsultasi', 'Lantai' => '3', 'Status' => true, 'Keterangan' => 'Konsultasi dengan psikolog'],
            ['Nama_Ruangan' => 'Ruang UGD', 'Jenis_Ruangan' => 'Darurat', 'Lantai' => '1', 'Status' => true, 'Keterangan' => 'Unit Gawat Darurat'],
            ['Nama_Ruangan' => 'Ruang Administrasi', 'Jenis_Ruangan' => 'Administrasi', 'Lantai' => '1', 'Status' => true, 'Keterangan' => 'Pendaftaran dan administrasi pasien'],
            ['Nama_Ruangan' => 'Ruang Isolasi', 'Jenis_Ruangan' => 'Isolasi', 'Lantai' => '3', 'Status' => false, 'Keterangan' => 'Sedang dalam perbaikan'],
            ['Nama_Ruangan' => 'Ruang Pemeriksaan Mata', 'Jenis_Ruangan' => 'Poli', 'Lantai' => '2', 'Status' => true, 'Keterangan' => 'Pemeriksaan mata dan optik'],
            ['Nama_Ruangan' => 'Ruang Terapi Akupuntur', 'Jenis_Ruangan' => 'Terapis', 'Lantai' => '2', 'Status' => true, 'Keterangan' => 'Layanan akupuntur']
        ];

        foreach ($ruanganList as $ruang) {
            DB::table('ms_ruangan')->insert([
                'Id_Ruangan' => Str::uuid(),
                'Nama_Ruangan' => $ruang['Nama_Ruangan'],
                'Jenis_Ruangan' => $ruang['Jenis_Ruangan'],
                'Lantai' => $ruang['Lantai'],
                'Status' => $ruang['Status'],
                'Keterangan' => $ruang['Keterangan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
