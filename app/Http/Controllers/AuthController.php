<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // menampilkan halaman login 
    public function login() {
        return view('login');
    }

    // ini menggunakan login Request 
    public function auth(LoginRequest $request) {
        if(Auth::attempt($request->validated())) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Selamat Datang,' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid',
        ]);
    }

    // bikin fungsi logout atau method 
    public function logout(Request $request) {
        // mengakhiri sesi pengguna
        Auth::logout();

        // menghapus session pengguna
        $request->session()->invalidate();
        // meregenerasi token csrf
        $request->session()->regenerateToken();

        // redirect ke halaman login setelah logout 
        return redirect()->route('login')->with('success', 'Anda Telah Keluar Aplikasi');
    }
}
