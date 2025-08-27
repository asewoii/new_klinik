<?php

namespace App\Exports;

use App\Models\Pasien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class PasienExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pasien::query();

        if ($this->request->filled('from')) {
            $query->whereDate('Tanggal_Registrasi', '>=', $this->request->from);
        }

        if ($this->request->filled('to')) {
            $query->whereDate('Tanggal_Registrasi', '<=', $this->request->to);
        }

        if ($this->request->filled('jk')) {
            $query->where('Jk', $this->request->jk);
        }

        if ($this->request->filled('umur_min')) {
            $query->where('Umur', '>=', $this->request->umur_min);
        }

        if ($this->request->filled('umur_max')) {
            $query->where('Umur', '<=', $this->request->umur_max);
        }

        if ($this->request->filled('keyword')) {
            $keyword = $this->request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('Nama_Pasien', 'like', '%' . $keyword . '%')
                  ->orWhere('Nik', 'like', '%' . $keyword . '%');
            });
        }

        return $query->select('Nik', 'Nama_Pasien', 'Jk', 'Umur', 'No_Tlp', 'Alamat', 'Tanggal_Registrasi')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Pasien',
            'Jenis Kelamin',
            'Umur',
            'No. Telepon',
            'Alamat',
            'Tanggal Registrasi',
        ];
    }
}
