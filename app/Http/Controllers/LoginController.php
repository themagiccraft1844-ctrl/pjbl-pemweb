<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // 1. Cek apakah email ada di tabel BannedEmail?
        $isBanned = \App\Models\BannedEmail::where('email', $request->email)->exists();
        if ($isBanned) {
            Auth::logout();
            return back()->withErrors(['email' => 'AKUN DIBANNED PERMANEN. Hubungi admin jika ini kesalahan.']);
        }

        // 2. Cek apakah user kena Suspend Menengah (Tidak boleh login)?
        if (auth()->user()->suspension_type == 'medium' && auth()->user()->isSuspended()) {
            $date = auth()->user()->suspended_until->format('d M Y');
            Auth::logout(); // Tendang keluar
            return back()->withErrors(['email' => "Akun disuspend hingga $date karena pelanggaran aturan."]);
        }
        
        // 2. Cek apakah Email ada di database?
        $user = User::where('email', $request->email)->first();

        // 3. LOGIKA: Jika Email TIDAK DITEMUKAN -> Redirect ke Register
        if (!$user) {
            return redirect()->route('register')
                ->with('warning', 'Silahkan daftar terlebih dahulu.')
                ->withInput(['email' => $request->email]); // Kirim balik emailnya biar gak ngetik ulang
        }

        // 4. LOGIKA: Jika Email ADA, coba cocokkan password (Login)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard'); 
            }
            return redirect()->intended('dashboard');
        }
        
        if (!Auth::attempt($credentials)) {
            return back()->with('salahtau', 'Email atau Password salah!');
                // -> withInput(['email' => $request->email]); gausah lah ya, suruh ketik ulang email dan password aja
        }

        // 5. Erorr yang lain
        return back()->withErrors(['GALAT',])->withInput();
    }

}