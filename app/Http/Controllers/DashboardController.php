<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote;
use App\Models\Friendship; 
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. POPULER (Semua Public - Urut Like)
        $popularWishnotes = WishNote::where('privasi', 'public')
            ->with('user')
            ->withCount('messages')
            ->orderBy('like_count', 'desc')
            ->take(9)
            ->get();

        // 2. TERBARU / TELUSURI (Semua Public - Urut Waktu)
        $recentWishnotes = WishNote::where('privasi', 'public')
            ->with('user')
            ->withCount('messages')
            ->latest()
            ->take(12)
            ->get();

        // Data Khusus User Login
        $myWishnotes = collect();
        $friendsWishnotes = collect();

        if (Auth::check()) {
            $userId = Auth::id();

            // 3. MILIK SAYA
            $myWishnotes = WishNote::where('users_id', $userId)
                ->with('user')
                ->withCount('messages')
                ->latest()
                ->get();

            // 4. MILIK TEMAN (Jika ada model Friendship)
            // Logika sederhana: Ambil wishnote dari user yang ada di tabel friendship dengan status 'accepted'
            $friendships = Friendship::where(function($q) use ($userId) {
                    $q->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
                })
                ->where('status', 'accepted')
                ->get();

            $friendIds = $friendships->map(function ($f) use ($userId) {
                return $f->sender_id == $userId ? $f->receiver_id : $f->sender_id;
            });

            if ($friendIds->isNotEmpty()) {
                $friendsWishnotes = WishNote::whereIn('users_id', $friendIds)
                    // Teman bisa lihat Public & Private (asumsi teman dekat boleh lihat private)
                    // Atau sesuaikan jika teman hanya boleh lihat public
                    ->with('user')
                    ->withCount('messages')
                    ->latest()
                    ->get();
            }
        }

        return view('dashboard', compact(
            'popularWishnotes', 
            'recentWishnotes', 
            'myWishnotes', 
            'friendsWishnotes'
        ));
    }

    public function show(Request $request)
    {
        return redirect()->route('dashboard');
    }
}