<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('wishnotes') // Hitung jumlah wishnote otomatis
            ->where('id', '!=', auth()->id()) // Exclude diri sendiri
            ->latest();

        // Fitur Pencarian
        if ($request->has('search')) {
            $users->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $users->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Opsional: Cek jika user yang mau dihapus adalah admin lain
        if ($user->role === 'admin') {
            return back()->with('error', 'Sesama Admin dilarang saling menghapus!');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // UBAH JADI ADMIN
    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'admin';
        $user->save();

        return back()->with('success', $user->name . ' berhasil dijadikan Admin!');
    }

    // UBAH JADI USER BIASA (CABUT ADMIN)
    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);

        // Validasi: Jangan biarkan admin mencabut akses dirinya sendiri saat login
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa mencabut akses Admin diri sendiri!');
        }

        $user->role = 'user';
        $user->save();

        return back()->with('success', 'Akses Admin untuk ' . $user->name . ' telah dicabut.');
    }
}