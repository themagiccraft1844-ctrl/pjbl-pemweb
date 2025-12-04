<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Apakah user sudah login?
        // Cek 2: Apakah role user adalah 'admin'?
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // Lanjut, silakan masuk
        }

        // Jika tidak memenuhi syarat, tolak akses (403 Forbidden) atau redirect
        abort(403, 'Akses Ditolak. Halaman ini khusus Admin.');
        // atau return redirect('/dashboard'); jika ingin dilempar balik ke dashboard biasa
    }
}