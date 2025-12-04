<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Daftar Teman (Accepted)
        $friends = $user->friends;

        // 2. Logika Pencarian & Saran Teman
        // Kita cari user yang BUKAN diri sendiri, dan BELUM ada di tabel friendship (baik pending/accepted)
        $keyword = $request->input('search');
        
        $query = User::where('id', '!=', $user->id)
            ->whereDoesntHave('friendsOfMine', function($q) use ($user) {
                $q->where('receiver_id', $user->id);
            })
            ->whereDoesntHave('friendOf', function($q) use ($user) {
                $q->where('sender_id', $user->id);
            });

        // Jika ada search, filter berdasarkan nama
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // Ambil maksimal 5 saran
        $suggestions = $query->inRandomOrder()->take(5)->get();

        return view('friendlist', compact('friends', 'suggestions'));
    }

    public function addFriend($id)
    {
        // Cek dulu biar gak double
        $exists = Friendship::where(function($q) use ($id) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('sender_id', $id)->where('receiver_id', Auth::id());
        })->exists();

        if (!$exists) {
            Friendship::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $id,
                'status' => 'accepted' // AUTO ACCEPTED (Sesuai request simpel, kalau mau pending ganti 'pending')
            ]);
        }

        return back()->with('success', 'Berhasil menambahkan teman!');
    }

    public function removeFriend($id)
    {
        // Hapus hubungan pertemanan (bolak-balik)
        Friendship::where(function($q) use ($id) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('sender_id', $id)->where('receiver_id', Auth::id());
        })->delete();

        return back()->with('success', 'Teman dihapus.');
    }
}