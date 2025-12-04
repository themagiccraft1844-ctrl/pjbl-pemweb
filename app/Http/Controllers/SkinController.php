<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote; // Menggunakan satu model untuk semua
use Illuminate\Support\Facades\Auth;

class SkinController extends Controller
{
    /**
     * FUNGSI UTAMA (PRIVATE)
     * Menangani semua logika pengambilan data, like, dan keamanan.
     * Digunakan ulang oleh showTree, showMading, dan showMailbox.
     */
    private function getNoteAndCheckAccess($id, $typeLabel = 'Konten')
    {
        // 1. Ambil Data (Pakai WishNote sesuai request)
        // Gunakan with('messages') agar pesan/bola-bola ikut terambil
        $note = WishNote::with('messages')->findOrFail($id);

        // 2. Logika Cek Like (Session Based)
        $isLiked = false;
        $likedTrees = session()->get('liked_trees', []);
        
        if (in_array($note->id, $likedTrees)) {
            $isLiked = true;
        }

        // 3. Logika Cek Privasi
        // Perhatian: Pastikan nama kolom di database Anda 'privasi' atau 'privacy'.
        // Saya menggunakan 'privasi' sesuai standar file Tree.php Anda sebelumnya.
        // Jika di database namanya 'privacy', ubah kode di bawah ini.
        $statusPrivasi = $note->privasi ?? $note->privacy ?? 'public'; 
        
        // Perhatian: Pastikan nama kolom foreign key adalah 'user_id' atau 'users_id'.
        // Saya menggunakan 'user_id' (standar Laravel). 
        $pemilikId = $note->user_id ?? $note->users_id;

        if ($statusPrivasi === 'private') {
            if (Auth::id() !== $pemilikId) {
                abort(403, "$typeLabel ini bersifat privat dan hanya bisa dilihat pemiliknya.");
            }
        }

        return [
            'data' => $note,
            'isLiked' => $isLiked
        ];
    }

    /**
     * Menampilkan Pohon Natal
     */
    public function showTree($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Pohon Natal');
        
        // Kirim ke view 'pohon'
        // Variabel 'tree' di view akan berisi data WishNote
        return view('pohon', [
            'tree' => $result['data'],
            'isLiked' => $result['isLiked'],
            'id' => $id
        ]);
    }

    /**
     * Menampilkan Mading
     */
    public function showMading($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Mading');

        return view('mading', [
            'mading' => $result['data'],
            'id' => $id
        ]);
    }

    /**
     * Menampilkan Mailbox
     */
    public function showMailbox($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Kotak Surat');

        return view('mailbox', [
            'mailbox' => $result['data']
        ]);
    }
}