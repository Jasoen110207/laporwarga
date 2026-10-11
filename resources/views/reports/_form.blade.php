@php $r = $report ?? null; @endphp
<form class="card form-card" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($r) @method('PUT') @endif

    <div class="field">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $r?->category_id) == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="title">Judul singkat</label>
        <input id="title" name="title" value="{{ old('title', $r?->title) }}" maxlength="120" required>
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="location">Lokasi kejadian</label>
        <input id="location" name="location" value="{{ old('location', $r?->location) }}" maxlength="200" placeholder="Nama jalan, RT/RW, patokan terdekat" required>
        @error('location') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="description">Ceritakan masalahnya</label>
        <textarea id="description" name="description" required>{{ old('description', $r?->description) }}</textarea>
        <div class="hint">Minimal 20 karakter. Sebutkan sejak kapan dan siapa yang terdampak.</div>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label for="photo">Foto pendukung (opsional)</label>
        @if($r?->photo)
            <img class="photo" src="{{ asset('storage/' . $r->photo) }}" alt="Foto saat ini" style="max-height:160px">
            <label class="checkline" style="margin-top:.5rem"><input type="checkbox" name="remove_photo" value="1"> Hapus foto ini</label>
        @endif
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
        <div class="hint">Format JPG, PNG, atau WEBP, maksimal 2 MB.</div>
        @error('photo') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="row-actions">
        <button class="btn" type="submit">{{ $r ? 'Simpan perubahan' : 'Kirim laporan' }}</button>
        @if($r) <a class="btn ghost" href="{{ route('reports.show', $r) }}">Batal</a> @endif
    </div>
</form>
