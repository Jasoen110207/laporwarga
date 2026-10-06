<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function edit()
    {
        return view('account.password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi lama tidak cocok.',
        ]);

        // Di-hash otomatis oleh cast 'hashed' di model User
        $request->user()->update(['password' => $request->password]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}