@extends('layouts.app')
@section('title', 'Ubah kategori')

@section('content')
<h1>Ubah kategori</h1>
<form class="card form-card" method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf @method('PUT')
    <div class="field">
        <label for="name">Nama</label>
        <input id="name" name="name" value="{{ old('name', $category->name) }}" maxlength="80" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="description">Keterangan</label>
        <input id="description" name="description" value="{{ old('description', $category->description) }}" maxlength="255">
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="row-actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn ghost" href="{{ route('admin.categories.index') }}">Batal</a>
    </div>
</form>
@endsection
