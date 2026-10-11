@extends('layouts.app')
@section('title', 'Semua laporan')

@section('content')
@php
    $current = request('status');
    $isAdmin = auth()->user()->isAdmin();
@endphp
<div class="page-head"><h1>Semua laporan</h1></div>

<div class="chips">
    <a class="chip {{ $current ? '' : 'active' }}" href="{{ route('admin.reports.index') }}">Semua ({{ $counts->sum() }})</a>
    @foreach(\App\Models\Report::STATUSES as $key => $label)
        <a class="chip {{ $current === $key ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => $key]) }}">{{ $label }} ({{ $counts[$key] ?? 0 }})</a>
    @endforeach
</div>

<form class="filters" method="GET" action="{{ route('admin.reports.index') }}">
    <div><label for="q">Cari</label><input id="q" name="q" value="{{ request('q') }}" maxlength="100" placeholder="Judul, tiket, atau lokasi"></div>
    <div>
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            @foreach(\App\Models\Report::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected($current === $key)>{{ $label }}</option>
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
            <thead><tr><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Pelapor</th><th>Status</th><th>Ubah status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($reports as $r)
                @php $locked = $r->isFinal() && ! $isAdmin; @endphp
                <tr>
                    <td class="ticket">{{ $r->ticket }}<br><span class="muted small" style="font-weight:400;letter-spacing:0">{{ $r->created_at->translatedFormat('d M Y') }}</span></td>
                    <td><a href="{{ route('admin.reports.show', $r) }}">{{ $r->title }}</a><br><span class="muted small">{{ $r->location }}</span></td>
                    <td>{{ $r->category->name }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td><span class="badge badge-{{ $r->status }}">{{ $r->status_label }}</span></td>
                    <td>
                        @if($locked)
                            <span class="lock">Terkunci</span>
                        @else
                            <form class="inline-form" method="POST" action="{{ route('admin.reports.update', $r) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="quick" value="1">
                                <select name="status" aria-label="Status untuk {{ $r->ticket }}">
                                    @foreach(\App\Models\Report::STATUSES as $key => $label)
                                        <option value="{{ $key }}" @selected($r->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button class="btn small" type="submit">Ubah</button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            <a class="btn ghost small" href="{{ route('admin.reports.show', $r) }}">Tanggapi</a>
                            @if($isAdmin)
                                <form method="POST" action="{{ route('admin.reports.destroy', $r) }}" data-confirm="Hapus laporan {{ $r->ticket }} beserta riwayatnya?">
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
    {{ $reports->links('pagination.simple') }}
@endif
@endsection
