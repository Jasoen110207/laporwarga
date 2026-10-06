@extends('layouts.app')
@section('title', $report->ticket)

@section('content')
<div class="page-head">
    <h1>{{ $report->title }}</h1>
    <a href="{{ route('admin.reports.index') }}">Kembali ke daftar</a>
</div>

<div class="detail">
    <div>
        <div class="card" style="margin-bottom:1rem">
            <p class="ticket">{{ $report->ticket }} <span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span></p>
            <p style="white-space:pre-line">{{ $report->description }}</p>
            @if($report->photo)
                <img class="photo" src="{{ asset('storage/' . $report->photo) }}" alt="Foto pendukung laporan">
            @endif
            <dl class="meta" style="margin-top:1rem">
                <dt>Kategori</dt><dd>{{ $report->category->name }}</dd>
                <dt>Lokasi</dt><dd>{{ $report->location }}</dd>
                <dt>Pelapor</dt><dd>{{ $report->user->name }}@if($report->user->phone), {{ $report->user->phone }}@endif</dd>
                <dt>Alamat</dt><dd>{{ $report->user->address ?: '-' }}</dd>
                <dt>Dilaporkan</dt><dd>{{ $report->created_at->translatedFormat('d F Y, H:i') }}</dd>
            </dl>
        </div>

        <form class="card" method="POST" action="{{ route('admin.reports.update', $report) }}">
            @csrf @method('PUT')
            <h2>Tanggapi laporan</h2>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    @foreach(\App\Models\Report::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $report->status) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="message">Tanggapan untuk warga</label>
                <textarea id="message" name="message" style="min-height:100px">{{ old('message') }}</textarea>
                @error('message') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn" type="submit">Simpan tanggapan</button>
        </form>
    </div>

    <div class="card">
        <h3>Perkembangan</h3>
        @include('reports._timeline', ['report' => $report])
    </div>
</div>
@endsection
