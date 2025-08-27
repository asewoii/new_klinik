<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use setasign\Fpdi\Fpdi; // ✅ Tambahkan ini

class PdfToolkitController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:pdf,jpg,jpeg,png|max:10000',
        ]);

        $uploaded = [];
        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads', $filename);
            $uploaded[] = $filename;
        }

        return response()->json(['files' => $uploaded]);
    }

    public function combine(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
        ]);

        $pdf = new Fpdi(); // ✅ Menggunakan class yang benar

        foreach ($request->input('files') as $filename) {
            $path = storage_path("app/uploads/$filename");

            if (!file_exists($path)) {
                continue; // Skip jika file tidak ditemukan
            }

            $pageCount = $pdf->setSourceFile($path);

            for ($page = 1; $page <= $pageCount; $page++) {
                $tplId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($tplId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);
            }
        }

        // Simpan hasil PDF
        $output = 'combined_' . Str::uuid() . '.pdf';
        $outputPath = storage_path("app/processed/{$output}");

        // Pastikan folder 'processed' ada
        if (!Storage::exists('processed')) {
            Storage::makeDirectory('processed');
        }

        $pdf->Output($outputPath, 'F'); // ✅ Tidak error lagi

        return response()->json(['file' => $output]);
    }

    // ---- DELETE ---- \\
    public function delete($filename)
    {
        $path = 'public/uploads/' . $filename;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }

        Storage::delete($path);
        return response()->json(['message' => 'File berhasil dihapus.']);
    }

    // ---- LIST ---- \\
    public function list()
    {
        $files = Storage::files('public/uploads');
        $filenames = array_map(function ($path) {
            return basename($path);
        }, $files);

        return response()->json(['files' => $filenames]);
    }

    // TODO: split(), pdfToJpg(), jpgToPdf(), rotate(), compress()
}
