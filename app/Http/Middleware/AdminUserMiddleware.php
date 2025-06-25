<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {


        // Periksa apakah pengguna sudah terautentikasi
        if (!Auth::check()) {
            // Jika belum login, redirect atau abort dengan kode 401
            abort(401, 'Unauthorized - Please log in');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Cek apakah pengguna aktif
        if ($user->is_active != 1) {
            // Logout dan arahkan kembali jika pengguna tidak aktif
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif.');
        }

        // Cek apakah peran pengguna sesuai dengan peran yang dibolehkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Forbidden - Access denied');
        }

        // Jika pengguna aktif dan memiliki peran yang sesuai, lanjutkan ke request berikutnya
        return $next($request);
    }
}
