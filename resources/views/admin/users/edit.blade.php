@extends('layouts.app')
@section('title', 'Ubah pengguna')

@section('content')
<div class="page-head"><h1>Ubah pengguna</h1><a href="{{ route('admin.users.index') }}">Kembali ke daftar</a></div>

<div class="detail" style="grid-template-columns:1fr 1fr">
    <form class="card" method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <h2>Data akun</h2>
        <div class="field"><label for="name">Nama</label><input id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required>@error('name') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="150" required>@error('email') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field">
            <label for="role">Peran</label>
            <select id="role" name="role" required @disabled($user->id === auth()->id())>
                @foreach(['warga' => 'Warga', 'petugas' => 'Petugas', 'admin' => 'Admin'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @if($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role }}">
                <div class="hint">Peran akun Anda sendiri tidak bisa diubah.</div>
            @endif
            @error('role') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field"><label for="phone">Nomor HP</label><input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="081234567890">@error('phone') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="address">Alamat</label><input id="address" name="address" value="{{ old('address', $user->address) }}" maxlength="255">@error('address') <div class="error">{{ $message }}</div> @enderror</div>
        <button class="btn" type="submit">Simpan</button>
    </form>

    <form class="card" method="POST" action="{{ route('admin.users.password', $user) }}">
        @csrf @method('PUT')
        <h2>Atur ulang kata sandi</h2>
        <p class="muted small">Kata sandi disimpan dalam bentuk hash bcrypt, jadi yang lama tidak bisa dilihat, hanya bisa diganti.</p>
        <div class="field">
            <label for="password">Kata sandi baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter, mengandung huruf dan angka.</div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button class="btn" type="submit">Simpan kata sandi</button>
    </form>
</div>
@endsection
