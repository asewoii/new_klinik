<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporansController extends Controller
{
    public function index()
    {
        return view('laporans.index', ['data' => []]);
    }

    public function filter(Request $request)
    {
        $query = Kunjungan::with(['dokter', 'ruangan', 'layanan']);

        if ($request->tanggal) {
            $query->whereDate('Tanggal_Registrasi', $request->tanggal);
        }

        if ($request->status) {
            $query->where('Status', $request->status);
        }

        $data = $query->orderBy('Tanggal_Registrasi', 'desc')->get();

        return view('laporans.index', compact('data'));
    }

    public function download(Request $request)
    {
        $query = Kunjungan::with(['dokter', 'ruangan', 'layanan']);

        if ($request->tanggal) {
            $query->whereDate('Tanggal_Registrasi', $request->tanggal);
        }

        if ($request->status) {
            $query->where('Status', $request->status);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('laporans.export_pdf', compact('data'));
        return $pdf->download('laporans_kunjungan.pdf');
    }

    public function preview(Request $request)
    {
        $query = Kunjungan::with(['dokter', 'ruangan', 'layanan']);

        if ($request->tanggal) {
            $query->whereDate('Tanggal_Registrasi', $request->tanggal);
        }

        if ($request->status) {
            $query->where('Status', $request->status);
        }

        $data = $query->get();

        return view('laporans.print_preview', compact('data'));
    }

}
