<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->Id_Kunjungan, 
                $item->Nik,
                $item->Nama_Pasien,
                $item->Kode_Indikasi,
                $item->Nama_Dokter,
                $item->Id_Ruangan,
                $item->Jadwal,
                $item->Tanggal_Registrasi,
                $item->Keluhan,
                $item->Status,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'NIK', 'Nama Pasien','Poli', 'Dokter', 'Ruangan','Jadwal', 'Tanggal Registrasi','Keluhan', 'Status'];
    }
}
