@extends('layouts.app')
@section('title', 'Daftar')

@section('content')
<div class="card narrow">
    <h1 style="font-size:1.7rem">Daftar sebagai warga</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field">
            <label for="name">Nama lengkap</label>
            <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="phone">Nomor HP (opsional)</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
            @error('phone') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="address">Alamat / RT RW (opsional)</label>
            <input id="address" name="address" value="{{ old('address') }}">
        </div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter.</div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">        
        </div>
        <div class="field">
    <label for="role">Daftar sebagai</label>
    <select id="role" name="role" required>
        <option value="warga" @selected(old('role', 'warga') === 'warga')>Warga</option>
        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
    </select>
    @error('role') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field" id="admin-code-wrapper" style="display:none">
    <label for="admin_code">Kode admin</label>
    <input id="admin_code" type="password" name="admin_code" autocomplete="off">
    @error('admin_code') <div class="error">{{ $message }}</div> @enderror
</div>

<script>
    const roleSelect = document.getElementById('role');
    const codeBox = document.getElementById('admin-code-wrapper');
    const codeInput = document.getElementById('admin_code');

    function toggleCode() {
        const isAdmin = roleSelect.value === 'admin';
        codeBox.style.display = isAdmin ? 'block' : 'none';
        codeInput.required = isAdmin;
        if (!isAdmin) codeInput.value = '';
    }

    roleSelect.addEventListener('change', toggleCode);
    toggleCode();
</script>
        <button class="btn" type="submit">Buat akun</button>
    </form>
</div>
@endsection
