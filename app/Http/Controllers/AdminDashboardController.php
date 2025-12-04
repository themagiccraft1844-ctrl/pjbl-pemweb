<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WishNote;
use App\Models\Message;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik User
        $totalUsers = User::count();
        // User aktif 30 hari terakhir
        $activeUsers = User::where('created_at', '>=', now()->subDays(30))->count();
        
        // 2. Statistik WishNote (Wadah)
        $totalCatatan = WishNote::count(); 
        
        // PERBAIKAN: Menambahkan variabel $catatanPrivate
        // Menghitung jumlah WishNote yang status privasinya 'private'
        $catatanPrivate = WishNote::where('privasi', 'private')->count();
        
        // Opsional: Mungkin Anda juga butuh catatanPublic? Saya tambahkan sekalian untuk jaga-jaga
        $catatanPublic = WishNote::where('privasi', 'public')->count();

        // 3. Statistik Pesan (Bola/Stiker)
        $totalMessages = Message::count();

        // 4. Aktivitas Terbaru (Misalnya 5 pesan terakhir yang dibuat)
        // Kita ambil 5 pesan terakhir beserta info usernya untuk ditampilkan di dashboard
        $recentActivities = Message::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Kirim semua variabel ke View
        return view('admin.dashboard', compact(
            'totalUsers', 
            'activeUsers', 
            'totalCatatan', 
            'catatanPrivate',
            'catatanPublic',
            'totalMessages',
            'recentActivities' // Variabel baru yang ditambahkan
        ));
    }
}