<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote;
use Illuminate\Support\Facades\Auth;

class SkinController extends Controller
{
    private function getNoteAndCheckAccess($id, $typeLabel = 'Konten')
    {
        // 1. Ambil Note TANPA eager loading messages dulu
        $note = WishNote::findOrFail($id);

        // 2. Cek Privasi WADAH (WishNote)
        $statusPrivasi = $note->privasi ?? $note->privacy ?? 'public'; 
        $pemilikId = $note->users_id ?? $note->user_id;
        $currentUserId = Auth::id();

        if ($statusPrivasi === 'private') {
            if (Auth::id() !== $note->user_id && Auth::user()->role !== 'admin') {
                abort(403, 'Anda tidak memiliki akses ke catatan private ini.');
            }
        }

        // 3. Ambil & Filter PESAN (Messages)
        // Aturan: 
        // - Pesan Public: Semua orang bisa lihat
        // - Pesan Private: Hanya bisa dilihat oleh Pemilik WishNote & Penulis Pesan itu sendiri
        
        $messages = $note->messages()
            ->where(function($query) use ($currentUserId, $pemilikId) {
                // Tampilkan jika Public
                $query->where('visibility', 'public')
                // ATAU jika Private TAPI user sekarang adalah Pemilik WishNote
                      ->orWhere(function($q) use ($currentUserId, $pemilikId) {
                          $q->where('visibility', 'private')
                            ->whereRaw("? = ?", [$currentUserId, $pemilikId]); 
                      })
                // ATAU jika Private TAPI user sekarang adalah Penulis Pesan itu
                      ->orWhere(function($q) use ($currentUserId) {
                          $q->where('visibility', 'private')
                            ->where('user_id', $currentUserId);
                      });
            })
            ->latest()
            ->get();

        // Masukkan messages yang sudah difilter kembali ke objek note (secara manual)
        $note->setRelation('messages', $messages);

        // 4. Cek Like
        $isLiked = false;
        $likedNotes = session()->get('liked_notes', []);
        if (in_array($note->id, $likedNotes)) {
            $isLiked = true;
        }

        return [
            'data' => $note,
            'isLiked' => $isLiked
        ];
    }

    public function showTree($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Pohon Natal');
        return view('pohon', ['tree' => $result['data'], 'isLiked' => $result['isLiked'], 'id' => $id]);
    }

    public function showMading($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Mading');
        return view('mading', ['mading' => $result['data'], 'isLiked' => $result['isLiked'], 'id' => $id]);
    }

    public function showMailbox($id)
    {
        $result = $this->getNoteAndCheckAccess($id, 'Kotak Surat');
        return view('mailbox', ['mailbox' => $result['data'], 'isLiked' => $result['isLiked'], 'id' => $id]);
    }
}