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
            <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="081234567890">
            @error('phone') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="address">Alamat / RT RW (opsional)</label>
            <input id="address" name="address" value="{{ old('address') }}">
        </div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter, mengandung huruf dan angka.</div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button class="btn" type="submit">Buat akun</button>
    </form>
</div>
@endsection
