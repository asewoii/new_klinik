<?php

namespace App\Exports;

use App\Models\Dokter;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class DokterExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Dokter::query();

        if ($this->request->filled('keyword')) {
            $query->where(function ($q) {
                $q->where('Nama_Dokter', 'like', '%' . $this->request->keyword . '%')
                  ->orWhere('Spesialis', 'like', '%' . $this->request->keyword . '%');
            });
        }

        $sesiFilter = $this->request->has('sesi') ? (array) $this->request->input('sesi') : [];
        $hariFilter = $this->request->has('hari') ? (array) $this->request->input('hari') : [];
        $ruanganFilter = $this->request->has('ruangan') ? (array) $this->request->input('ruangan') : [];

        // Filter manual berbasis jadwal
        $filtered = $query->get()->filter(function ($dokter) use ($sesiFilter, $hariFilter, $ruanganFilter) {
            if (!$dokter->Jadwal_Dokter) return false;

            $jadwal = json_decode($dokter->Jadwal_Dokter, true);
            if (!is_array($jadwal)) return false;

            foreach ($jadwal as $hari => $slots) {
                if (!empty($hariFilter) && !in_array($hari, $hariFilter)) continue;

                foreach ($slots as $slot) {
                    $matchSesi = empty($sesiFilter) || array_intersect($sesiFilter, explode(',', $slot['sesi']));
                    $matchRuangan = empty($ruanganFilter) || in_array($slot['ruang'], $ruanganFilter);

                    if ($matchSesi && $matchRuangan) {
                        return true;
                    }
                }
            }
            return false;
        });

        return view('laporan.export_excel_dokter', [
            'dokters' => $filtered
        ]);
    }
}
