<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemeriksaanExport implements FromCollection, WithHeadings
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
                $item->kunjungan->Nama_Pasien ?? '-',
                $item->dokter->Nama_Dokter ?? '-',
                $item->Diagnosa,
                $item->Tindakan,
                $item->Resep,
                $item->Catatan,
                $item->Jam_Pemeriksaan,
                $item->Tanggal_Pemeriksaan,
            ];
        });
    }

    public function headings(): array
    {
        return ['Pasien', 'Dokter', 'Diagnosa', 'Tindakan', 'Catatan','Jadwal','Tanggal'];
    }
}
