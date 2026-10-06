<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lapor Warga')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="topbar">
    <div class="wrap bar">
        <a class="brand" href="{{ route('home') }}">Lapor Warga</a>
        <nav aria-label="Menu utama">
            @auth
                @if(auth()->user()->isStaff())
                    <a href="{{ route('admin.reports.index') }}" @class(['active' => request()->routeIs('admin.reports.*')])>Semua laporan</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Kategori</a>
                    @endif
                @else
                    <a href="{{ route('reports.index') }}" @class(['active' => request()->routeIs('reports.index')])>Laporan saya</a>
                    <a href="{{ route('reports.create') }}" @class(['active' => request()->routeIs('reports.create')])>Buat laporan</a>
                @endif
                <a href="{{ route('account.password') }}" @class(['active' => request()->routeIs('account.*')])>Ubah kata sandi</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button class="link" type="submit">Keluar ({{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }})</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a class="btn small" href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    <div class="wrap">
        @if(session('success'))
            <div class="alert success" role="status">{{ session('success') }}</div>
        @endif
        @if($errors->has('category'))
            <div class="alert error-box" role="alert">{{ $errors->first('category') }}</div>
        @endif
        @yield('content')
    </div>
</main>

<footer>
    <div class="wrap">Lapor Warga &mdash; kanal pengaduan dan permintaan bantuan masyarakat.</div>
</footer>
</body>
</html>
