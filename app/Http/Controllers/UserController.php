<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;


class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = User::find($request->user_id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['status' => 'success']);
    }
    public function updatePassword(Request $request)
    {
        $user = User::find($request->user_id);

        $user->password = Hash::make($request->new_password_confirmation);
        $user->save();

        return response()->json(['status' => 'success']);
    }
}
