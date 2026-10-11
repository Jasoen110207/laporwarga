@extends('layouts.app')
@section('title', 'Buat laporan')

@section('content')
<h1>Buat laporan</h1>
<form class="card form-card" method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="field">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" data-slug="{{ $c->slug }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field" id="other-wrapper" style="display:none">
        <label for="other_category">Kategori lainnya</label>
        <input id="other_category" name="other_category" value="{{ old('other_category') }}" maxlength="80" placeholder="Ketik kategori laporan">
        @error('other_category') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="title">Judul singkat</label>
        <input id="title" name="title" value="{{ old('title') }}" maxlength="120" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="location">Lokasi kejadian</label>
        <input id="location" name="location" value="{{ old('location') }}" maxlength="200" placeholder="Nama jalan, RT/RW, patokan terdekat" required>
        @error('location') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="description">Ceritakan masalahnya</label>
        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        <div class="hint">Minimal 20 karakter. Sebutkan sejak kapan dan siapa yang terdampak.</div>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="photo">Foto pendukung (opsional)</label>
        <input id="photo" type="file" name="photo" accept="image/*">
        <div class="hint">Format gambar, maksimal 2 MB.</div>
        @error('photo') <div class="error">{{ $message }}</div> @enderror
    </div>
    <button class="btn" type="submit">Kirim laporan</button>
</form>

<script>
    const select = document.getElementById('category_id');
    const wrapper = document.getElementById('other-wrapper');
    const other = document.getElementById('other_category');

    function toggleOther() {
        const isOther = select.selectedOptions[0]?.dataset.slug === 'lainnya';
        wrapper.style.display = isOther ? 'block' : 'none';
        other.required = isOther;
        if (!isOther) other.value = '';
    }

    select.addEventListener('change', toggleOther);
    toggleOther();
</script>
@endsection