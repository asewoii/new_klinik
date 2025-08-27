<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Kunjungan;
use App\Models\Dokter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $data = Pemeriksaan::with('kunjungan', 'dokter')->latest('Tanggal_Pemeriksaan')->get();
        $listDokter = Dokter::select('Nama_Dokter')->get();

        return view('pemeriksaan.index', compact('data','listDokter'));
    }

    public function create(Request $request)
{
    $idPasien = $request->query('pasien'); // Ambil dari query string ?pasien=...

    $kunjungan = Kunjungan::where('Status', 'Diperiksa')
        ->when($idPasien, function ($query) use ($idPasien) {
            $query->where('Id_Pasien', $idPasien);
        })
        ->get();

    $dokter = Dokter::all();

    return view('pemeriksaan.create', compact('kunjungan', 'dokter'));
}

    public function store(Request $request)
    {
        $request->validate([
            'Id_Kunjungan' => 'required',
            'Id_Dokter' => 'required',
            'Diagnosa' => 'nullable|string',
            'Tindakan' => 'nullable|string',
            'Resep' => 'nullable|string',
            'Catatan' => 'nullable|string',
            'Tanggal_Pemeriksaan' => 'required|date',
        ]);

        Pemeriksaan::create([
            'Id_Kunjungan' => $request->Id_Kunjungan,
            'Id_Dokter' => $request->Id_Dokter,
            'Diagnosa' => $request->Diagnosa,
            'Tindakan' => $request->Tindakan,
            'Resep' => $request->Resep,
            'Catatan' => $request->Catatan,
            'Tanggal_Pemeriksaan' => $request->Tanggal_Pemeriksaan,
            'Jam_Pemeriksaan' => Carbon::now(),
            'Create_Date' => Carbon::now(),
            'Create_By' => Auth::user()->name ?? 'System'
        ]);

        $kunjungan = Kunjungan::find($request->Id_Kunjungan);
        if ($kunjungan) {
            $kunjungan->Status = 'Selesai';
            $kunjungan->save();
        }
        return redirect()->route('pemeriksaan.index')->with('success', 'Pemeriksaan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $kunjungan = Kunjungan::all();
        $dokter = Dokter::all();
        return view('pemeriksaan.edit', compact('pemeriksaan', 'kunjungan', 'dokter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_Kunjungan' => 'required',
            'Id_Dokter' => 'required',
            'Diagnosa' => 'nullable|string',
            'Tindakan' => 'nullable|string',
            'Resep' => 'nullable|string',
            'Catatan' => 'nullable|string',
            'Tanggal_Pemeriksaan' => 'required|date',
            'Jam_Pemeriksaan' => 'required',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update($request->all());

        return redirect()->route('pemeriksaan.index')->with('success', 'Pemeriksaan berhasil diperbarui.');
    }

    public function show($id) {
        $data = Pemeriksaan::with('kunjungan', 'dokter')->findOrFail($id); 
        return view('pemeriksaan.show', compact('data')); 
    }

    public function destroy($id)
    {
        Pemeriksaan::destroy($id);
        return redirect()->route('pemeriksaan.index')->with('success', 'Pemeriksaan berhasil dihapus.');
    }

    //Kunjungan Hari Ini
public function phariIni()
{
    $today = now()->toDateString();
    
    $listDokter = Dokter::select('Nama_Dokter')->get();

    $data = Kunjungan::with(['dokter', 'layanan'])
        ->whereDate('Jadwal_Kedatangan', $today)
        ->where('Status', 'Diperiksa') // HANYA status 'Diperiksa'
        ->orderBy('Nomor_Urut')
        ->paginate(15);

    return view('pemeriksaan.periksa', compact('listDokter', 'data'));
}

public function updateStatus(Request $request, $id)
{
    $kunjungan = Kunjungan::findOrFail($id);
    $status = $request->input('status');

    if (!in_array($status, ['diperiksa', 'tidak hadir'])) {
        return redirect()->back()->with('error', 'Status tidak valid.');
    }

    $kunjungan->Status = $status;
    $kunjungan->save();

    return redirect()->back()->with('success', 'Status berhasil diubah.');
}

public function laporan(Request $request)
{
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $dokterId = $request->input('dokter_id');

    $query = Pemeriksaan::with(['kunjungan.pasien', 'dokter']);

    if ($tanggalAwal && $tanggalAkhir) {
        $query->whereBetween('Tanggal_Pemeriksaan', [$tanggalAwal, $tanggalAkhir]);
    }

    if ($dokterId) {
        $query->where('Id_Dokter', $dokterId);
    }

    $data = $query->orderBy('Tanggal_Pemeriksaan', 'desc')->get();
    $listDokter = Dokter::all();

    return view('pemeriksaan.laporan', compact('data', 'listDokter', 'tanggalAwal', 'tanggalAkhir', 'dokterId'));
}

}
