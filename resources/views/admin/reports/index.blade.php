@extends('layouts.app')
@section('title', 'Semua laporan')

@section('content')
<div class="page-head"><h1>Semua laporan</h1></div>

<div class="tabs">
    <a class="btn ghost small" href="{{ route('admin.reports.index') }}">Semua</a>
    @foreach(\App\Models\Report::STATUSES as $key => $label)
        <a class="btn ghost small" href="{{ route('admin.reports.index', ['status' => $key]) }}">{{ $label }} ({{ $counts[$key] ?? 0 }})</a>
    @endforeach
</div>

<form class="filters" method="GET">
    <div><label for="q">Cari</label><input id="q" name="q" value="{{ request('q') }}" placeholder="Judul, tiket, atau lokasi"></div>
    <div>
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            @foreach(\App\Models\Report::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="category">Kategori</label>
        <select id="category" name="category">
            <option value="">Semua</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn" type="submit">Terapkan</button>
</form>

@if($reports->isEmpty())
    <div class="card empty"><h2>Tidak ada laporan yang cocok</h2><p style="margin-inline:auto">Ubah kata kunci atau filter untuk melihat laporan lain.</p></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Pelapor</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @foreach($reports as $r)
                <tr>
                    <td class="ticket">{{ $r->ticket }}</td>
                    <td><a href="{{ route('admin.reports.show', $r) }}">{{ $r->title }}</a><br><span class="muted small">{{ $r->location }}</span></td>
                    <td>{{ $r->category->name }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td><span class="badge badge-{{ $r->status }}">{{ $r->status_label }}</span></td>
                    <td class="muted">{{ $r->created_at->translatedFormat('d M Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $reports->links('pagination.simple') }}
@endif
@endsection
