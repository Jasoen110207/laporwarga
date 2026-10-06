@extends('layouts.app')

@section('content')
<section class="hero">
    <div>
        <h1>Ada masalah di lingkungan Anda? Laporkan, lalu pantau sampai selesai.</h1>
        <p>Jalan rusak, sampah menumpuk, air mati, atau warga yang butuh bantuan. Tulis laporannya, dapatkan nomor tiket, dan lihat tanggapan petugas langsung di sini.</p>
        <div class="actions">
            <a class="btn" href="{{ auth()->check() ? route('reports.create') : route('register') }}">Buat laporan</a>
            @guest <a class="btn ghost" href="{{ route('login') }}">Sudah punya akun? Masuk</a> @endguest
        </div>
    </div>

    <form class="stub" method="GET" action="{{ route('track') }}">
        <h2>Lacak laporan</h2>
        <p class="muted small">Masukkan nomor tiket yang Anda terima saat melapor.</p>
        <div class="field">
            <label for="ticket">Nomor tiket</label>
            <input id="ticket" name="ticket" placeholder="LW-ABC123" value="{{ old('ticket') }}" required maxlength="20">
            @error('ticket') <div class="error">{{ $message }}</div> @enderror
        </div>
        <button class="btn" type="submit">Cek status</button>
    </form>
</section>

<p class="summary-line">
    Sejauh ini <strong>{{ $total }} laporan</strong> masuk: <strong>{{ $diproses }}</strong> sedang diproses dan <strong>{{ $selesai }}</strong> sudah selesai.
</p>

<h2>Cara kerjanya</h2>
<ol class="steps">
    <li><h3>Tulis laporan</h3><p class="muted">Pilih kategori, jelaskan masalahnya, sebutkan lokasi, dan tambahkan foto jika ada.</p></li>
    <li><h3>Petugas menindaklanjuti</h3><p class="muted">Petugas memeriksa laporan, mengubah status, dan menulis tanggapan.</p></li>
    <li><h3>Pantau hasilnya</h3><p class="muted">Lihat perkembangan di akun Anda atau lewat nomor tiket tanpa perlu masuk.</p></li>
</ol>

<h2>Yang bisa dilaporkan</h2>
<ul class="cat-list">
    @foreach($categories as $c)
        <li><strong>{{ $c->name }}</strong><br><span class="muted small">{{ $c->description }}</span></li>
    @endforeach
</ul>
@endsection
