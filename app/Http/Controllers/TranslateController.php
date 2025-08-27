<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends Controller
{
    public function index(Request $request) {
        // Teks default (misal: dari halaman dashboard admin klinik)
        $text = $request->query('text', '');

        // auto pakai dari session/middleware
        $lang = app()->getLocale();

        if (!$text || strlen(trim($text)) === 0) {
            return response()->json(['translated' => null]);
        }

        try {
            $translated = GoogleTranslate::trans($text, $lang);
            return response()->json(['translated' => $translated]);
        } catch (\Exception $e) {
            return response()->json(['translated' => null]);
        }
    }
}
