<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name', 'E-Report') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@auth
    @php($currentUser = auth()->user())
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand" href="{{ route($currentUser->dashboardRoute()) }}">
                <span class="brand-mark">e</span>
                <span><strong>e-report</strong><small>LAYANAN WARGA</small></span>
            </a>
            <div class="side-label">MENU UTAMA</div>
            <nav class="side-nav" aria-label="Navigasi utama">
                @if ($currentUser->role === 'admin')
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav-icon">⌂</span> Ringkasan</a>
                    <a class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}"><span class="nav-icon">▤</span> Semua laporan</a>
                @else
                    <a class="{{ request()->routeIs('warga.dashboard') ? 'active' : '' }}" href="{{ route('warga.dashboard') }}"><span class="nav-icon">⌂</span> Beranda</a>
                    <a class="{{ request()->routeIs('laporan.index', 'laporan.show', 'laporan.edit') ? 'active' : '' }}" href="{{ route('laporan.index') }}"><span class="nav-icon">▤</span> Laporan saya</a>
                    <a class="{{ request()->routeIs('laporan.create') ? 'active' : '' }}" href="{{ route('laporan.create') }}"><span class="nav-icon">＋</span> Buat laporan</a>
                @endif
                <a class="{{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}"><span class="nav-icon">◎</span> Profil saya</a>
            </nav>
            <div class="sidebar-note">
                <span class="note-mark">i</span>
                <strong>Suaramu berarti.</strong>
                <p>Setiap laporan membantu lingkungan kita menjadi lebih baik.</p>
            </div>
            <div class="sidebar-user">
                <span class="avatar">{{ mb_substr($currentUser->name, 0, 1) }}</span>
                <span class="user-summary"><strong>{{ $currentUser->name }}</strong><small>{{ $currentUser->role === 'admin' ? 'Administrator' : 'Warga' }}</small></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-icon" type="submit" aria-label="Keluar" title="Keluar">↗</button>
                </form>
            </div>
        </aside>

        <section class="workspace">
            <header class="topbar">
                <button class="menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" onclick="document.body.classList.toggle('nav-open'); this.setAttribute('aria-expanded', document.body.classList.contains('nav-open'))">☰</button>
                <div class="topbar-title">@yield('eyebrow', 'PORTAL PENGADUAN WARGA')</div>
                <div class="topbar-right">
                    <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="top-avatar">{{ mb_substr($currentUser->name, 0, 1) }}</span>
                </div>
            </header>
            <main class="page-content">
                @if (session('success'))
                    <div class="flash flash-success" role="status"><span>✓</span>{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="flash flash-error" role="alert"><span>!</span>{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
            <footer class="page-footer">E-Report <span>·</span> Pelayanan publik yang lebih dekat dan transparan</footer>
        </section>
    </div>
@else
    <div class="guest-shell">
        <a class="brand guest-brand" href="{{ route('login') }}">
            <span class="brand-mark">e</span>
            <span><strong>e-report</strong><small>LAYANAN WARGA</small></span>
        </a>
        <main class="guest-content">
            @if (session('success'))
                <div class="flash flash-success" role="status"><span>✓</span>{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash flash-error" role="alert"><span>!</span>{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
        <p class="guest-footer">E-Report · Suara warga, perubahan nyata.</p>
    </div>
@endauth
</body>
</html>
