@extends('layouts.app')
@section('title', 'Ubah laporan')

@section('content')
<h1>Ubah laporan {{ $report->ticket }}</h1>
<p class="muted">Laporan hanya bisa diubah selama statusnya masih "Baru masuk".</p>
@include('reports._form', ['action' => route('reports.update', $report), 'categories' => $categories, 'report' => $report])
@endsection
