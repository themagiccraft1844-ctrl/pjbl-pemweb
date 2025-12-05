<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WishNote;
use Illuminate\Support\Facades\DB; // Diperlukan jika menghitung sesi aktif

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK UTAMA
        // Hitung total semua catatan
        $totalCatatan = WishNote::count();

        // Hitung pengguna aktif (Opsional: bisa pakai User::count() untuk total user terdaftar)
        // Jika ingin menghitung user yang sedang login (berdasarkan tabel sessions):
        $activeUsers = DB::table('sessions')
                        ->whereNotNull('user_id')
                        ->distinct('user_id')
                        ->count();
        // Atau jika hanya ingin total user terdaftar:
        // $activeUsers = User::count();

        // Hitung catatan dengan privasi 'Private'
        $catatanPrivate = WishNote::where('privasi', 'Private')->count();


        // 2. AKTIVITAS TERBARU (Recent Activity)
        // Mengambil 5 WishNote terbaru beserta data user pembuatnya dan jumlah pesan
        $recentActivities = WishNote::with('user')
            ->withCount('messages') // Pastikan relasi 'messages' ada di model WishNote
            ->latest() // Sama dengan orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalCatatan', 
            'activeUsers', 
            'catatanPrivate', 
            'recentActivities'
            
        ));
    }
}