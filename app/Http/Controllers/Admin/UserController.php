<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ReportResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    private const ROLES = ['warga', 'petugas', 'admin'];

    public function index()
    {
        return view('admin.users.index', [
            'users' => User::withCount('reports')->orderBy('role')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'role' => ['required', Rule::in(self::ROLES)],
            'phone' => ['nullable', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'address' => 'nullable|string|max:255',
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ], [
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
        ]);

        $user = User::create($data);
        AuditLog::record('pengguna_dibuat', "{$user->email} ({$user->role})");

        return back()->with('success', "Akun {$user->name} dibuat.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(self::ROLES)],
            'phone' => ['nullable', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'address' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
        ]);

        // Admin tidak boleh mengubah peran dirinya sendiri (mencegah terkunci dari sistem)
        if ($user->id === $request->user()->id && $data['role'] !== $user->role) {
            return back()->with('error', 'Anda tidak bisa mengubah peran akun sendiri.');
        }

        $oldRole = $user->role;
        $user->update($data);
        AuditLog::record('pengguna_diubah', "{$user->email} (peran {$oldRole} -> {$user->role})");

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ]);

        // Di-hash otomatis oleh cast 'hashed' pada model User
        $user->update(['password' => $data['password']]);
        AuditLog::record('password_direset', $user->email);

        return back()->with('success', "Kata sandi {$user->name} berhasil diubah.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($user->reports()->exists() || ReportResponse::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Akun ini punya laporan atau riwayat tanggapan, jadi tidak bisa dihapus agar data tetap utuh.');
        }

        $email = $user->email;
        $user->delete();
        AuditLog::record('pengguna_dihapus', $email);

        return back()->with('success', 'Akun dihapus.');
    }
}
