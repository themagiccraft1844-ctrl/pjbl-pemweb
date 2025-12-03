<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishNote; 

class SkinController extends Controller
{
    public function showTree($id)
    {
        $tree = WishNote::findOrFail($id);

    // cek akses
    if ($tree->privacy === 'private' && auth()->id() !== $tree->users_id) {
        abort(403, 'This tree is private');
    }

    return view('pohon', compact('tree', 'id'));
    }

    public function showMading($id)
    {
        $mading = WishNote::findOrFail($id);

    if ($mading->privacy === 'private' && auth()->id() !== $mading->users_id) {
        abort(403, 'This mading is private');
    }

    return view('mading', compact('mading', 'id'));
    }

    public function showMailbox($id)
    {
    $mailbox = WishNote::findOrFail($id);

    if ($mailbox->privacy === 'private' && auth()->id() !== $mailbox->users_id) {
        abort(403, 'This mailbox is private');
    }

    return view('mailbox', compact('mailbox'));
    }

}
