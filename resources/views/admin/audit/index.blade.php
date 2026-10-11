@extends('layouts.app')
@section('title', 'Log aktivitas')

@section('content')
<div class="page-head"><h1>Log aktivitas</h1></div>
<p class="muted">Catatan kejadian penting seperti login, perubahan status, dan pengelolaan akun. Data ini tidak bisa diubah dari aplikasi.</p>

<form class="filters" method="GET" action="{{ route('admin.audit.index') }}" style="grid-template-columns:1fr auto">
    <div>
        <label for="action">Jenis aktivitas</label>
        <select id="action" name="action">
            <option value="">Semua</option>
            @foreach($actions as $a)
                <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn" type="submit">Terapkan</button>
</form>

@if($logs->isEmpty())
    <div class="card empty"><h2>Belum ada aktivitas tercatat</h2></div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td class="muted small">{{ $log->created_at?->translatedFormat('d M Y H:i:s') }}</td>
                    <td>{{ $log->user?->name ?? 'Tamu / tidak diketahui' }}</td>
                    <td class="mono">{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td class="mono">{{ $log->ip_address }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $logs->links('pagination.simple') }}
@endif
@endsection
