<?php

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/firebase-login', function (Request $request) {
    $idToken = $request->input('idToken');

    $auth = (new Factory)->withServiceAccount(base_path('firebase.json'))->createAuth();

    try {
        $verifiedIdToken = $auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub');

        return response()->json(['uid' => $uid], 200);

    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 401);
    }
});
