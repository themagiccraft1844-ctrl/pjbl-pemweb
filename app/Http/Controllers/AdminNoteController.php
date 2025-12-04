<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishNote; // Model Wadah (Pohon/Mading)
use App\Models\Message;  // Model Pesan (Bola/Stiker)
use App\Models\User;

class AdminNoteController extends Controller
{
    /**
     * Menampilkan daftar semua WishNote
     */
    public function index()
    {
        $notes = WishNote::with('user')
            ->withCount('messages')
            ->latest()
            ->paginate(10);

        return view('admin.notes.index', compact('notes'));
    }

    /**
     * Menampilkan Detail WishNote beserta isinya (Dengan Filter & Pagination)
     */
    public function show(Request $request, $id)
    {
        // 1. Ambil Note Utama
        $note = WishNote::with('user')->findOrFail($id);

        // 2. Ambil Pesan (Messages) dengan Filter Pencarian & Pagination
        // Ini penting agar variabel $messages tersedia di view
        $query = Message::where('wish_note_id', $id) // Pastikan foreign key sesuai (wish_note_id / wishnote_id)
            ->with('user'); // Eager load user pengirim pesan

        // Logika Pencarian dari View (name="search")
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('body', 'like', "%{$search}%") // Asumsi kolom isi pesan di DB adalah 'body'
                  ->orWhere('sender', 'like', "%{$search}%"); // Asumsi kolom nama pengirim manual
            });
        }

        $messages = $query->latest()->paginate(10); // Pagination 10 per halaman

        return view('admin.notes.show', compact('note', 'messages'));
    }

    /**
     * Menghapus WishNote (Wadah)
     */
    public function destroy($id)
    {
        $note = WishNote::findOrFail($id);
        $note->delete(); // Cascade delete akan menghapus pesan di dalamnya

        return redirect()->back()->with('success', 'WishNote berhasil dihapus.');
    }

    /**
     * Menghapus Pesan Spesifik (Bola/Stiker)
     */
    public function deleteMessage($id)
    {
        $msg = Message::findOrFail($id);
        $msg->delete();

        return redirect()->back()->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Memberikan Peringatan (Opsional)
     */
    public function warnUser(Request $request)
    {
        // Logika warning user bisa ditambahkan di sini
        return redirect()->back()->with('success', 'Peringatan dikirim.');
    }
}