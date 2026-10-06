@extends('layouts.app')
@section('title', $report->ticket)

@section('content')
<div class="page-head">
    <h1>{{ $report->title }}</h1>
    <a href="{{ auth()->user()->isStaff() ? route('admin.reports.index') : route('reports.index') }}">Kembali ke daftar</a>
</div>

<div class="detail">
    <div class="card">
        <p class="ticket">{{ $report->ticket }} <span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span></p>
        <p style="white-space:pre-line">{{ $report->description }}</p>
        @if($report->photo)
            <img class="photo" src="{{ asset('storage/' . $report->photo) }}" alt="Foto pendukung laporan">
        @endif
    </div>
    <div>
        <div class="card" style="margin-bottom:1rem">
            <dl class="meta">
                <dt>Kategori</dt><dd>{{ $report->category->name }}</dd>
                <dt>Lokasi</dt><dd>{{ $report->location }}</dd>
                <dt>Pelapor</dt><dd>{{ $report->user->name }}</dd>
                <dt>Dilaporkan</dt><dd>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</dd>
            </dl>
        </div>
        <div class="card">
            <h3>Perkembangan</h3>
            @include('reports._timeline', ['report' => $report])
        </div>
    </div>
</div>
@endsection
