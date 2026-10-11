<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function edit()
    {
        return view('account.edit');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'phone' => ['nullable', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'address' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
        ]);

        $request->user()->update($data);
        AuditLog::record('profil_diubah');

        return back()->with('success', 'Profil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.different' => 'Kata sandi baru harus berbeda dari yang lama.',
        ]);

        // Di-hash otomatis oleh cast 'hashed' pada model User
        $request->user()->update(['password' => $request->password]);
        AuditLog::record('password_diubah');

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
