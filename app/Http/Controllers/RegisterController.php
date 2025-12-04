<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request) {

        // Cek tabel banned
        if (\App\Models\BannedEmail::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email ini telah dibanned dari sistem kami.']);
        }
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        
        // Create the user
        $user = \App\Models\User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        
        
        // Log the user in
        auth()->login($user);
        
        // Redirect to dashboard or intended page
        return redirect()->intended('login');
    }
}
