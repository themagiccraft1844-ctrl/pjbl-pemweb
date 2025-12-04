<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote;
use App\Models\TreeMessage;
use App\Models\User;
use App\Models\UserAlert;
use App\Models\BannedEmail;

class AdminNoteController extends Controller
{
    // ==========================================
    // BAGIAN 1: KELOLA CATATAN (Index & Destroy)
    // ==========================================

    // Menampilkan daftar semua catatan
    public function index(Request $request)
    {
        $notes = WishNote::with('user')
            ->withCount('messages')
            ->latest();

        // Fitur Pencarian Judul
        if ($request->has('search')) {
            $notes->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi_singkat', 'like', '%' . $request->search . '%');
        }

        $notes = $notes->paginate(10);

        return view('admin.notes.index', compact('notes'));
    }

    // Menghapus Catatan (Wishnote) Secara Utuh
    public function destroy($id)
    {
        $note = WishNote::findOrFail($id);
        $note->delete();

        return back()->with('success', 'Catatan berhasil dihapus oleh Admin.');
    }

    // ==========================================
    // BAGIAN 2: MODERASI PESAN (Detail & Warn)
    // ==========================================

    // Menampilkan Detail Pesan dalam Tabel (Bukan tampilan pohon/mading)
    public function show(Request $request, $id)
    {
        $note = WishNote::with('user')->findOrFail($id);

        // Ambil pesan di dalam note ini
        $messages = $note->messages()->with('sender'); 

        // Fitur Cari Pesan Kasar
        if ($request->has('search')) {
            $messages->where('message', 'like', '%' . $request->search . '%');
        }

        $messages = $messages->paginate(20); 

        return view('admin.notes.show', compact('note', 'messages'));
    }

    // Hapus Pesan Spesifik (Sensor)
    public function deleteMessage($id)
    {
        $msg = TreeMessage::findOrFail($id);
        $msg->delete();
        return back()->with('success', 'Pesan berhasil dihapus (disensor).');
    }

    // Berikan Peringatan / Hukuman ke User
    public function warnUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $level = $request->level; // 1 (Ringan), 2 (Menengah), 3 (Berat)

        // Simpan Riwayat Alert
        UserAlert::create([
            'user_id' => $user->id,
            'level' => $level == 1 ? 'ringan' : ($level == 2 ? 'menengah' : 'berat'),
            'pesan' => $request->pesan_admin
        ]);

        if ($level == 1) {
            // RINGAN: Suspend 1 Hari
            $user->update([
                'suspended_until' => now()->addDay(),
                'suspension_type' => 'light'
            ]);
            return back()->with('success', 'User diberi teguran RINGAN (Mute 1 Hari).');

        } elseif ($level == 2) {
            // MENENGAH: Suspend 7 Hari
            $user->update([
                'suspended_until' => now()->addWeek(),
                'suspension_type' => 'medium'
            ]);
            return back()->with('success', 'User diberi teguran MENENGAH (Suspend Login 1 Minggu).');

        } elseif ($level == 3) {
            // BERAT: Hapus Akun & Banned Email
            
            // 1. Masukkan email ke blacklist
            BannedEmail::create([
                'email' => $user->email,
                'reason' => $request->pesan_admin,
                'admin_name' => auth()->user()->name
            ]);

            // 2. Hapus User
            $user->delete();

            return back()->with('success', 'User BERHASIL DIBANNED PERMANEN dan akun dihapus.');
        }
    }
}