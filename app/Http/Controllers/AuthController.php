<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            $role = Auth::guard('web')->user()->role;

            if ($role === 'admin') {
                return redirect('/admin');
            } elseif ($role === 'dokter') {
                return redirect('/dokter/dashboard');
            }
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            // Logging
            logger('User logged in: ' . $user->username . ' (Role: ' . $user->role . ')');

            // Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'dokter') {
                return redirect('/dokter');
            } else {
                Auth::guard('web')->logout();
                return back()->with('error', 'Akses tidak diizinkan.');
            }
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
