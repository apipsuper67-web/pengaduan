@extends('layouts.app')

@section('title', 'Ringkasan admin')
@section('eyebrow', 'DASHBOARD ADMINISTRATOR')

@section('content')
<div class="page-heading heading-with-action">
    <div>
        <span class="eyebrow">PUSAT PEMANTAUAN</span>
        <h1>Dashboard admin</h1>
        <p>Pantau pengaduan dan tindak lanjuti kebutuhan warga.</p>
    </div>
    <a class="button button-primary" href="{{ route('admin.laporan.index') }}">Kelola laporan <span>→</span></a>
</div>

<section class="stat-grid admin-stat-grid" aria-label="Ringkasan laporan">
    <article class="stat-card stat-total"><span class="stat-icon">▤</span><span class="stat-label">Total laporan</span><strong>{{ number_format($total) }}</strong><small>Semua laporan tercatat</small></article>
    <article class="stat-card"><span class="stat-icon icon-amber">◷</span><span class="stat-label">Menunggu</span><strong>{{ number_format($stats['pending']) }}</strong><small>Perlu ditinjau</small></article>
    <article class="stat-card"><span class="stat-icon icon-blue">↗</span><span class="stat-label">Diproses</span><strong>{{ number_format($stats['proses']) }}</strong><small>Sedang ditindaklanjuti</small></article>
    <article class="stat-card"><span class="stat-icon icon-green">✓</span><span class="stat-label">Selesai</span><strong>{{ number_format($stats['selesai']) }}</strong><small>Berhasil ditangani</small></article>
    <article class="stat-card stat-citizens"><span class="stat-icon icon-violet">◎</span><span class="stat-label">Warga terdaftar</span><strong>{{ number_format($citizens) }}</strong><small>Pengguna portal</small></article>
</section>

<section class="panel">
    <div class="panel-heading">
        <div><h2>Laporan terbaru</h2><p>Pengaduan terbaru yang masuk dari warga</p></div>
        <a class="text-link" href="{{ route('admin.laporan.index') }}">Lihat semua <span>→</span></a>
    </div>
    @if ($recent->isEmpty())
        <div class="empty-state compact-empty"><span class="empty-icon">▤</span><h3>Belum ada laporan masuk</h3><p>Laporan warga akan tampil di sini setelah dikirim.</p></div>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul laporan</th><th>Pelapor</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($recent as $laporan)
                    <tr>
                        <td><a class="table-title" href="{{ route('admin.laporan.show', $laporan) }}">{{ $laporan->judul }}</a><small class="table-subtitle">#{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</small></td>
                        <td>{{ $laporan->pelapor->name }}</td>
                        <td>{{ $laporan->tanggal->translatedFormat('d M Y') }}</td>
                        <td><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></td>
                        <td><a class="row-arrow" href="{{ route('admin.laporan.show', $laporan) }}" aria-label="Lihat laporan">→</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
