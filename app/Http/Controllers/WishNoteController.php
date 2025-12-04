<?php

namespace App\Http\Controllers;

use App\Models\WishNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishNoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. AMBIL USER TERLEBIH DAHULU (PENTING!)
        // Agar variabel $user tersedia untuk pengecekan suspend di bawah.
        $user = Auth::user();

        // 2. Cek Suspend Ringan (Mute)
        // Sekarang variabel $user sudah ada isinya, jadi aman dicek.
        if ($user && $user->suspension_type == 'light' && $user->isSuspended()) {
            return back()->with('error', 'Anda sedang dalam masa hukuman (Mute). Tidak bisa memposting hingga besok.');
        }

        // 3. Validasi Input
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string|max:255',
            'tipe_wadah' => 'required', // pastikan form mengirim 'tipe_wadah' (pohon, mading, mailbox)
            'privasi' => 'required',    // public / private
        ]);

        // 4. Simpan ke Database
        WishNote::create([
            'judul' => $request->judul,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'tipe_wadah' => $request->tipe_wadah,
            'privasi' => $request->privasi,
            
            // Gunakan 'users_id' sesuai dengan Model WishNote Anda
            // PENTING: Gunakan $user->id jika user login, jika tidak null
            'users_id' => $user ? $user->id : null,
            
            'like_count' => 0 // Default 0
        ]);
        
        // Redirect
        return redirect()->intended('dashboard')->with('success', 'Wishnote berhasil dibuat!');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        $wishnote = WishNote::findOrFail($id);
        
        // Opsional: Tambahkan cek kepemilikan agar user lain tidak bisa hapus via URL
        if (Auth::id() != $wishnote->users_id && Auth::user()->role !== 'admin') {
             abort(403, 'Unauthorized action.');
        }

        $wishnote->delete();

        return redirect()->back()->with('success', 'Wishnote berhasil dihapus');
    }

    // Method lain biarkan kosong jika tidak dipakai
    public function index() {}
    public function create() {}
    public function show(WishNote $wishNote) {}
    public function edit(WishNote $wishNote) {}
    public function update(Request $request, WishNote $wishNote) {}
}