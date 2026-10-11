<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|max:255',
        ]);

        // Pembatasan percobaan: 5 kali per menit untuk kombinasi email + IP
        $key = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            AuditLog::record('login_diblokir', 'Terlalu banyak percobaan: ' . $credentials['email'], null);

            return back()->withErrors(['email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."])->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            AuditLog::record('login_gagal', 'Email: ' . $credentials['email'], null);

            return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.'])->onlyInput('email');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();
        AuditLog::record('login_berhasil');

        $default = $request->user()->isStaff() ? route('admin.reports.index') : route('reports.index');

        return redirect()->intended($default);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => ['nullable', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'address' => 'nullable|string|max:255',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'in:warga,admin'],
            'admin_code' => ['nullable', 'required_if:role,admin'],
        ], [
            'phone.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
        ]);

        // Peran admin hanya diberikan jika kode admin benar. Selain itu selalu "warga".
        $role = 'warga';

        if ($data['role'] === 'admin') {
            $code = config('laporwarga.admin_code');

            if (! $code || ! hash_equals((string) $code, (string) $request->admin_code)) {
                AuditLog::record('daftar_admin_ditolak', 'Kode admin salah: ' . $data['email'], null);

                return back()
                    ->withErrors(['admin_code' => 'Kode admin salah.'])
                    ->withInput($request->except('admin_code', 'password', 'password_confirmation'));
            }

            $role = 'admin';
        }

        unset($data['role'], $data['admin_code']);

        $user = User::create($data + ['role' => $role]);

        Auth::login($user);
        $request->session()->regenerate();
        AuditLog::record('daftar', "Akun {$role} baru: {$user->email}");

        return redirect()->route('reports.create')->with('success', 'Akun dibuat. Silakan tulis laporan pertama Anda.');
    }

    public function logout(Request $request)
    {
        AuditLog::record('logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}