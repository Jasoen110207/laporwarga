@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="page-head"><h1>Pengguna</h1></div>

<div class="detail" style="grid-template-columns:1.6fr 1fr">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nama</th><th>Peran</th><th>Laporan</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong>@if($u->id === auth()->id()) <span class="muted small">(Anda)</span>@endif<br><span class="muted small">{{ $u->email }}</span></td>
                    <td><span class="badge role-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                    <td>{{ $u->reports_count }}</td>
                    <td>
                        <div class="row-actions">
                            <a class="btn ghost small" href="{{ route('admin.users.edit', $u) }}">Ubah</a>
                            @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Hapus akun {{ $u->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <form class="card" method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <h2>Tambah pengguna</h2>
        <div class="field"><label for="name">Nama</label><input id="name" name="name" value="{{ old('name') }}" maxlength="100" required>@error('name') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="150" required>@error('email') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field">
            <label for="role">Peran</label>
            <select id="role" name="role" required>
                @foreach(['petugas' => 'Petugas', 'warga' => 'Warga', 'admin' => 'Admin'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', 'petugas') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('role') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field"><label for="phone">Nomor HP (opsional)</label><input id="phone" name="phone" value="{{ old('phone') }}" placeholder="081234567890">@error('phone') <div class="error">{{ $message }}</div> @enderror</div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            <div class="hint">Minimal 8 karakter, mengandung huruf dan angka.</div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button class="btn" type="submit">Buat akun</button>
    </form>
</div>
@endsection
