<?php

namespace App\Http\Controllers;

use App\Models\Message;   // Gunakan Model Message yang baru diupdate
use App\Models\WishNote;  // Gunakan WishNote sebagai parent
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreeController extends Controller
{
    /**
     * Simpan Pesan (Bola Natal / Stiker Mading)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Cek Suspend (Opsional)
        if ($user && $user->suspension_type == 'light' && $user->isSuspended()) {
            return back()->with('error', 'Anda sedang dalam masa hukuman (Mute).');
        }

        // 2. Validasi
        $request->validate([
            'message' => 'required|string|max:255',
            'name' => 'nullable|string|max:50',
            'tree_id' => 'required|exists:wish_notes,id' // Pastikan ID ada di tabel wish_notes
        ]);

        // 3. Simpan ke Tabel Messages
        Message::create([
            'wish_note_id' => $request->tree_id, // Sambungkan ke WishNote
            'user_id' => $user ? $user->id : null,
            'name' => $request->name ?? 'Anonim',
            'message' => $request->message,
            
            // Koordinat & Warna
            'color' => $request->color,
            'x' => $request->x,
            'y' => $request->y,
            'visibility' => $request->visibility ?? 'public'
        ]);

        return redirect()->back()->with('success', 'Berhasil ditambahkan!');
    }

    /**
     * Hapus Pesan
     */
    public function destroy(Request $request)
    {
        $msg = Message::find($request->tree_id); // tree_id disini adalah ID pesan yg mau dihapus

        if ($msg) {
            // Cek akses (Pemilik pesan atau Admin atau Pemilik WishNote)
            $wishNoteOwner = $msg->wishNote->user_id ?? null;
            
            if (Auth::id() == $msg->user_id || Auth::user()->role == 'admin' || Auth::id() == $wishNoteOwner) {
                $msg->delete();
                return redirect()->back()->with('success', 'Berhasil dihapus.');
            }
            return redirect()->back()->with('error', 'Tidak ada akses.');
        }

        return redirect()->back()->with('error', 'Pesan tidak ditemukan.');
    }

    /**
     * Toggle Like
     */
    public function toggleLike(Request $request)
    {
        // Like WishNote (Bukan Tree)
        $note = WishNote::find($request->tree_id);
        
        if (!$note) {
            return back()->with('error', 'Tidak ditemukan');
        }

        $likedNotes = session()->get('liked_notes', []);

        if (in_array($note->id, $likedNotes)) {
            if ($note->like_count > 0) $note->decrement('like_count');
            $likedNotes = array_diff($likedNotes, [$note->id]);
        } else {
            $note->increment('like_count');
            $likedNotes[] = $note->id;
        }

        session()->put('liked_notes', $likedNotes);
        return redirect()->back();
    }
}