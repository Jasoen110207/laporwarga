@extends('layouts.app')
@section('title', 'Laporan saya')

@section('content')
<div class="page-head">
    <h1>Laporan saya</h1>
    <a class="btn" href="{{ route('reports.create') }}">Buat laporan</a>
</div>

@if($reports->isEmpty())
    <div class="card empty">
        <h2>Belum ada laporan</h2>
        <p style="margin-inline:auto">Laporan yang Anda kirim akan muncul di sini beserta statusnya.</p>
        <a class="btn" href="{{ route('reports.create') }}">Tulis laporan pertama</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Tiket</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @foreach($reports as $r)
                <tr>
                    <td class="ticket">{{ $r->ticket }}</td>
                    <td><a href="{{ route('reports.show', $r) }}">{{ $r->title }}</a></td>
                    <td>{{ $r->category->name }}</td>
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
