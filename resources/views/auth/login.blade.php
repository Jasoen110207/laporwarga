@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="card narrow">
    <h1 style="font-size:1.7rem">Masuk</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>
        <div class="field"><label style="font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label></div>
        <button class="btn" type="submit">Masuk</button>
    </form>
    <p class="muted small" style="margin-top:1rem">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai...</a></p>
</div>
@endsection
