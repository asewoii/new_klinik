<?php
namespace App\Exports;

use App\Models\Ruangan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class RuanganExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Ruangan::query();

        if ($this->request->filled('ruangan')) {
            $query->where('Id_Ruangan', $this->request->ruangan);
        }

        if ($this->request->filled('jenis_ruangan')) {
            $query->where('Jenis_Ruangan', $this->request->jenis_ruangan);
        }

        if ($this->request->filled('lantai')) {
            $query->where('Lantai', $this->request->lantai);
        }

        if ($this->request->filled('status')) {
            $query->where('Status', $this->request->status);
        }

        $ruangans = $query->orderBy('Nama_Ruangan')->get();

        return view('laporan.export_excel_ruangan', compact('ruangans'));
    }
}
