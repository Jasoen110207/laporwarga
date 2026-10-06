@extends('layouts.app')
@section('title', 'Lacak laporan')

@section('content')
<div class="page-head"><h1>Lacak laporan</h1><a href="{{ route('home') }}">Kembali ke beranda</a></div>

@if(! $report)
    <div class="card empty">
        <h2>Tiket {{ $ticket }} tidak ditemukan</h2>
        <p style="margin-inline:auto">Periksa lagi penulisan nomor tiketnya. Formatnya seperti LW-ABC123.</p>
        <a class="btn" href="{{ route('home') }}">Coba lagi</a>
    </div>
@else
    <div class="card">
        <p class="ticket">{{ $report->ticket }} <span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span></p>
        <h2>{{ $report->title }}</h2>
        <dl class="meta">
            <dt>Kategori</dt><dd>{{ $report->category->name }}</dd>
            <dt>Lokasi</dt><dd>{{ $report->location }}</dd>
            <dt>Dilaporkan</dt><dd>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</dd>
        </dl>
        <h3 style="margin-top:1.5rem">Perkembangan</h3>
        @include('reports._timeline', ['report' => $report])
    </div>
@endif
@endsection
