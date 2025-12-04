<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote;
use App\Models\Friendship; // Pastikan Model Friendship di-import
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. JELAJAHI (Semua Public)
        $exploreWishnotes = WishNote::where('privasi', 'public')
            ->with('user')
            ->withCount('messages') // Pastikan relasi 'messages' di Model WishNote menggunakan foreign key 'wishnote_id'
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Variable Default
        $wishnotes = collect();        // Milik Saya
        $friendsWishnotes = collect(); // Milik Teman (Accepted)

        if (auth()->check()) {
            $userId = auth()->id();

            // A. Ambil Wishnote Milik Sendiri
            $wishnotes = WishNote::where('users_id', $userId) // Pastikan kolom di DB adalah 'users_id' atau 'user_id'
                ->with('user')
                ->withCount('messages')
                ->orderBy('created_at', 'desc')
                ->get();

            // B. Ambil Wishnote Milik Teman (Status Accepted)
            // Cari hubungan dimana user jadi sender ATAU receiver, dan statusnya accepted
            $friendships = Friendship::where(function($q) use ($userId) {
                    $q->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
                })
                ->where('status', 'accepted') // Validasi status accepted
                ->get();

            // Kumpulkan ID teman
            $friendIds = $friendships->map(function ($f) use ($userId) {
                return $f->sender_id == $userId ? $f->receiver_id : $f->sender_id;
            });

            // Ambil Wishnote berdasarkan ID teman
            // Opsional: Tambahkan where('privasi', 'public') jika teman cuma boleh lihat yang public
            $friendsWishnotes = WishNote::whereIn('users_id', $friendIds)
                ->with('user')
                ->withCount('messages')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $myTrees = $wishnotes->where('tipe_wadah', 'pohon');
        $myMadings = $wishnotes->where('tipe_wadah', 'mading');
        $myMailboxes = $wishnotes->where('tipe_wadah', 'mailbox');
        $user = Auth::user();

        // Mengirim semua variabel yang mungkin dibutuhkan oleh view
        return view('dashboard', compact(
            'wishnotes', 
            'exploreWishnotes', 
            'friendsWishnotes',
            'myTrees',
            'myMadings',
            'myMailboxes',
            'user'
        ));
    }

    public function show(Request $request)
    {
        $wishnotesId = WishNote::find($request->id);
        // Tetap kirim variabel lain agar view tidak error
        $wishnotes = collect();
        $exploreWishnotes = collect();
        $friendsWishnotes = collect();
        
        return view('dashboard', compact('wishnotesId', 'wishnotes', 'exploreWishnotes', 'friendsWishnotes'));
    }
}