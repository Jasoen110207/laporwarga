@extends('layouts.app')
@section('title', 'Buat laporan')

@section('content')
<h1>Buat laporan</h1>
@include('reports._form', ['action' => route('reports.store'), 'categories' => $categories])
@endsection