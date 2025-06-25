<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //

    public function login()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_active == 1) {
                // Pastikan session di-regenerate untuk keamanan
                session()->regenerate();
                return redirect()->route('halamanRekap');
            } else {
                // Jika pengguna tidak aktif, logout dan berikan pesan
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator login.');
            }
        }

        // Tampilkan halaman login jika belum login
        return view('pages.auth.login');
    }
    // auth_login

    public function auth_login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Jika email tidak ditemukan
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        // Cek apakah akun aktif
        if ($user->is_active == 0) {
            // Jika user ditemukan tapi tidak aktif
            return redirect()->back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Autentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->route('halamanRekap');
        } else {
            // Password salah
            return redirect()->back()->with('error', 'Password yang Anda masukkan salah.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
