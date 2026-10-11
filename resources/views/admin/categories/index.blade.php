@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="page-head"><h1>Kategori laporan</h1></div>

<div class="detail" style="grid-template-columns:1.4fr 1fr">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nama</th><th>Laporan</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($categories as $c)
                <tr>
                    <td><strong>{{ $c->name }}</strong><br><span class="muted small">{{ $c->description }}</span></td>
                    <td>{{ $c->reports_count }}</td>
                    <td>
                        <div class="row-actions">
                            <a class="btn ghost small" href="{{ route('admin.categories.edit', $c) }}">Ubah</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" data-confirm="Hapus kategori {{ $c->name }}?">
                                @csrf @method('DELETE')
                                <button class="btn danger small" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <form class="card" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <h2>Tambah kategori</h2>
        <div class="field">
            <label for="name">Nama</label>
            <input id="name" name="name" value="{{ old('name') }}" maxlength="80" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <label for="description">Keterangan</label>
            <input id="description" name="description" value="{{ old('description') }}" maxlength="255">
            @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button class="btn" type="submit">Tambah kategori</button>
    </form>
</div>
@endsection
