<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BannedEmail; // Pastikan model ini ada

class LoginController extends Controller
{
    public function index(Request $request) {
        return view('auth.login');
    }

    public function store(Request $request){
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // 2. Cek apakah email ada di tabel BannedEmail? (Global Ban)
        // Gunakan string class path lengkap atau use di atas
        $isBanned = BannedEmail::where('email', $request->email)->exists();
        
        if ($isBanned) {
            // Tidak perlu Auth::logout() karena belum login, tapi aman saja kalau ada
            return back()->withErrors(['email' => 'AKUN DIBANNED PERMANEN. Hubungi admin jika ini kesalahan.']);
        }

        // 3. Ambil data User dari Database berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // 4. LOGIKA: Jika Email TIDAK DITEMUKAN -> Redirect ke Register
        if (!$user) {
            return redirect()->route('register')
                ->with('warning', 'Silahkan daftar terlebih dahulu.')
                ->withInput(['email' => $request->email]); 
        }

        // 5. LOGIKA: Jika Email ADA, Cek Suspend SEBELUM Login
        // Perbaikan: Gunakan variabel $user, JANGAN auth()->user() karena belum login
        if ($user->suspension_type == 'medium' && $user->isSuspended()) {
            // Pastikan suspended_until tidak null sebelum format
            $date = $user->suspended_until ? $user->suspended_until->format('d M Y') : 'waktu yang ditentukan';
            
            return back()->withErrors(['email' => "Akun disuspend hingga $date karena pelanggaran aturan."]);
        }

        // 6. Coba Login (Mencocokkan Password)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek Role untuk Redirect
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard'); 
            }
            return redirect()->intended('dashboard');
        }
        
        // 7. Jika Password Salah
        return back()->with('salahtau', 'Email atau Password salah!');
        // Sesuai request: tidak pakai withInput agar user mengetik ulang
    }
}