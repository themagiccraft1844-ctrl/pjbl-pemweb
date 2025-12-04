<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use App\Models\TreeMessage;
use App\Models\WishNote; 

use Illuminate\Http\Request;

class TreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function addLike(Request $request)
    {
        $wish = WishNote::find($request->tree_id);
        $wish->like_count += 1;
        $wish->save();

        return response()->json(['status' => 'success', 'message'=> 'Success']);
    }
    public function rmLike(Request $request)
    {
        $wish = WishNote::find($request->tree_id);
        $wish->like_count -= 1;
        $wish->save();

        return response()->json(['status' => 'success', 'message'=> 'Success']);
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
        // Cek Suspend Ringan (Mute)
        if ($user && $user->suspension_type == 'light' && $user->isSuspended()) {
            return back()->with('error', 'Anda sedang dalam masa hukuman (Mute). Tidak bisa memposting hingga besok.');
        }  
        TreeMessage::create([
            'tree_id' => $request->tree_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'message' => $request->message,
            'color' => $request->color,
            'x' => $request->x,
            'y' => $request->y
        ]);

        return response()->json(['status' => 200,'message'=> 'Data terkirim']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tree $tree)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tree $tree)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tree $tree)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        TreeMessage::destroy($request->tree_id);
    }
}
