@extends('layouts.app')
@section('title', 'Pantau kategori Lainnya')

@section('content')
<h1>Pantau kategori "Lainnya"</h1>

@if(session('success'))
    <div class="card">{{ session('success') }}</div>
@endif

@php
    $topRecent = $keywords->max('recent') ?? 0;
    $alert = $percent >= $alertPercent || $topRecent >= $alertKeyword;
@endphp

@if($alert)
    <div class="card" style="border-left:4px solid #c0392b">
        <strong>Perhatian:</strong>
        @if($percent >= $alertPercent)
            "Lainnya" mencapai {{ $percent }}% dari semua laporan {{ $days }} hari terakhir.
        @endif
        @if($topRecent >= $alertKeyword)
            Ada kata kunci yang muncul {{ $topRecent }}x dalam {{ $days }} hari terakhir.
        @endif
        Pertimbangkan membuat kategori resmi.
    </div>
@endif

<p class="muted">
    {{ $days }} hari terakhir: {{ $otherRecent }} dari {{ $allRecent }} laporan masuk ke "Lainnya" ({{ $percent }}%).
</p>

<div class="card">
    <table style="width:100%">
        <thead>
            <tr>
                <th align="left">Kata kunci</th>
                <th align="right">{{ $days }} hari terakhir</th>
                <th align="right">Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($keywords as $k)
            <tr>
                <td>{{ $k->other_category }}</td>
                <td align="right">
                    {{ $k->recent }}
                    @if($k->recent >= $alertKeyword) <strong style="color:#c0392b">&#9650;</strong> @endif
                </td>
                <td align="right">{{ $k->total }}</td>
                <td align="right">
                    <form method="POST" action="{{ route('admin.other-categories.promote') }}"
                          onsubmit="return confirm('Jadikan kategori resmi dan pindahkan {{ $k->total }} laporan?')">
                        @csrf
                        <input type="hidden" name="keyword" value="{{ $k->other_category }}">
                        <button class="btn" type="submit">Jadikan kategori</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada laporan dengan kategori "Lainnya".</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection