<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua WishNote + relasi user + jumlah pesan (messages_count)
        $wishnotes = WishNote::with('user')
            ->withCount('messages')     // <--- INI DITARO DI SINI
            ->orderBy('created_at', 'desc')
            ->get();
      // Jika ada user login → hanya ambil wishnote miliknya
    if (auth()->check()) {
        $wishnotes = WishNote::where('users_id', auth()->id())
            ->with('user')
            ->withCount('messages')
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        // Guest tidak boleh lihat apa-apa
        $wishnotes = collect();
    }

    return view('dashboard', compact('wishnotes'));
    }

    public function show(Request $request)
    {
        $wishnotesId = WishNote::find($request->id);
        return view('dashboard', compact('wishnotesId'));

    }
}
