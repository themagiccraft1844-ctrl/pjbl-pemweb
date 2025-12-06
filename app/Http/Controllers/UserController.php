<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        return view('profil'); // Pastikan nama file blade kamu 'profil.blade.php'
    }

    // Update Data Diri (Nama, Email, Bio)
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // 'bio' => 'nullable|string' // Jika ada kolom bio di database
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        // $user->bio = $request->bio; 
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('password_update', [ // Pakai named bag error agar tab bisa dideteksi
            'current_password' => 'required|current_password',
            'new_password'     => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();
        
        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    // Update Avatar
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada (opsional, sesuaikan path)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan yang baru
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Simpan path ke database (pastikan ada kolom avatar di tabel users)
            // Jika tidak ada kolom, kode ini harus disesuaikan
            $user->avatar = $path; 
            $user->save();
        }

        return back()->with('success', 'Foto profil berhasil diganti!');
    }
    public function updateTheme(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:light,dark,system',
            'color' => 'required|in:blue,yellow,purple,green,red', // Pilihan warna yang kita tentukan
        ]);

        $user = Auth::user();
        
        // Gabungkan menjadi format string tunggal: "mode-color"
        // Contoh: "dark-yellow", "system-blue"
        $themeString = $request->mode . '-' . $request->color;

        /** @var \App\Models\User $user */
        $user->theme_setting = $themeString;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tema berhasil disimpan',
            'theme' => $themeString
        ]);
    }
}