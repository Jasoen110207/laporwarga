@extends('layouts.app')
@section('title', 'Ubah kata sandi')

@section('content')
<div class="card narrow">
    <h1 style="font-size:1.7rem">Ubah kata sandi</h1>
    <form method="POST" action="{{ route('account.password.update') }}">
        @csrf @method('PUT')
        <div class="field">
            <label for="current_password">Kata sandi saat ini</label>
            <input id="current_password" type="password" name="current_password" required autocomplete="current-password">
            @error('current_password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="password">Kata sandi baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter.</div>
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