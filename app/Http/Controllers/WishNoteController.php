<?php

namespace App\Http\Controllers;

use App\Models\WishNote;
use Illuminate\Http\Request;

class WishNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string|max:255',
            'tipe_wadah' => 'required',
            'privasi' => 'required',
        ]);
        // Create the user
        if (auth()->check()) {
            $userId = auth()->user()->id;
        } else {
            $userId = null; // atau apapun yang kamu perlukan
        }
        $user = \App\Models\WishNote::create([
            'judul' => $request['judul'],
            'deskripsi_singkat' => $request['deskripsi_singkat'],
            'tipe_wadah' => ($request['tipe_wadah']),
            'privasi' => ($request['privasi']),
            'users_id' => auth()->id(),
        ]);
        
        // Redirect to dashboard or intended page
        return redirect()->intended('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(WishNote $wishNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WishNote $wishNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WishNote $wishNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
    $wishnote = WishNote::findOrFail($id);
    $wishnote->delete();

    return redirect()->back()->with('success', 'Wishnote berhasil dihapus');
    }


}