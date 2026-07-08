<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessLevel
{
    /**
     * Menangani permintaan masuk (incoming request).
     *
     * Metode ini memeriksa apakah tingkat akses (access_level) dari pengguna 
     * yang terautentikasi sesuai dengan salah satu tingkat akses yang diizinkan untuk rute ini.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|int  ...$levels  Daftar level akses yang diizinkan (misalnya: 1 untuk Admin, 2 untuk Officer)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        // 1. Memeriksa apakah pengguna sudah melakukan login/terautentikasi.
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            abort(401, 'Unauthenticated.');
        }

        // 2. Mendapatkan access_level pengguna dan mengonversinya ke integer untuk konsistensi.
        $userAccessLevel = (int) $request->user()->access_level;

        // 3. Mengonversi semua parameter $levels ke integer untuk pencocokan yang ketat.
        $allowedLevels = array_map('intval', $levels);

        // 4. Memeriksa apakah access_level pengguna berada dalam daftar level yang diperbolehkan.
        if (!in_array($userAccessLevel, $allowedLevels, true)) {
            // Jika request mengharapkan respon JSON (misal API), kembalikan status 403.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Access level not authorized.'], 403);
            }
            // Jika rute web biasa, gunakan fungsi abort(403) bawaan Laravel.
            abort(403, 'Forbidden. Access level not authorized.');
        }

        // 5. Melanjutkan request ke proses selanjutnya jika lolos validasi akses.
        return $next($request);
    }
}

