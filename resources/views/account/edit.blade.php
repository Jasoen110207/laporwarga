@extends('layouts.app')
@section('title', 'Akun saya')

@section('content')
<h1>Akun saya</h1>
<div class="detail" style="grid-template-columns:1fr 1fr">
    <form class="card" method="POST" action="{{ route('account.profile') }}">
        @csrf @method('PUT')
        <h2>Profil</h2>
        <div class="field"><label for="name">Nama lengkap</label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="100" required>@error('name') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="150" required>@error('email') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="phone">Nomor HP</label><input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="081234567890">@error('phone') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="address">Alamat / RT RW</label><input id="address" name="address" value="{{ old('address', auth()->user()->address) }}" maxlength="255">@error('address') <div class="error">{{ $message }}</div> @enderror</div>
        <button class="btn" type="submit">Simpan profil</button>
    </form>

    <form class="card" method="POST" action="{{ route('account.password.update') }}">
        @csrf @method('PUT')
        <h2>Ubah kata sandi</h2>
        <div class="field">
            <label for="current_password">Kata sandi saat ini</label>
            <input id="current_password" type="password" name="current_password" required autocomplete="current-password">
            @error('current_password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="new_password">Kata sandi baru</label>
            <input id="new_password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter, mengandung huruf dan angka.</div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button class="btn" type="submit">Simpan kata sandi</button>
    </form>
</div>
@endsection
