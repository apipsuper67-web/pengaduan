@extends('layouts.app')

@section('title', 'Beranda')
@section('eyebrow', 'BERANDA WARGA')

@section('content')
<div class="page-heading heading-with-action">
    <div>
        <span class="eyebrow">RINGKASAN AKTIVITAS</span>
        <h1>Halo, {{ auth()->user()->name }} <span class="wave">👋</span></h1>
        <p>Terima kasih sudah ikut menjaga lingkungan kita tetap nyaman.</p>
    </div>
    <a class="button button-primary" href="{{ route('laporan.create') }}"><span class="button-plus">＋</span> Buat laporan</a>
</div>

<section class="stat-grid" aria-label="Ringkasan laporan">
    <article class="stat-card stat-total"><span class="stat-icon">▤</span><span class="stat-label">Total laporan</span><strong>{{ array_sum(array_map('intval', $stats->all())) }}</strong><small>Semua laporan yang kamu kirim</small></article>
    <article class="stat-card"><span class="stat-icon icon-amber">◷</span><span class="stat-label">Menunggu</span><strong>{{ $stats['pending'] ?? 0 }}</strong><small>Menanti peninjauan admin</small></article>
    <article class="stat-card"><span class="stat-icon icon-blue">↗</span><span class="stat-label">Diproses</span><strong>{{ $stats['proses'] ?? 0 }}</strong><small>Sedang ditindaklanjuti</small></article>
    <article class="stat-card"><span class="stat-icon icon-green">✓</span><span class="stat-label">Selesai</span><strong>{{ $stats['selesai'] ?? 0 }}</strong><small>Sudah ditangani</small></article>
</section>

<section class="panel">
    <div class="panel-heading">
        <div><h2>Laporan terbaru</h2><p>Perkembangan laporan yang kamu kirim</p></div>
        <a class="text-link" href="{{ route('laporan.index') }}">Lihat semua <span>→</span></a>
    </div>
    @if ($recent->isEmpty())
        <div class="empty-state">
            <span class="empty-icon">▤</span>
            <h3>Belum ada laporan</h3>
            <p>Temukan masalah di sekitar? Ceritakan kepada kami agar bisa segera ditindaklanjuti.</p>
            <a class="button button-primary" href="{{ route('laporan.create') }}">Kirim laporan pertama</a>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul laporan</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($recent as $laporan)
                    <tr>
                        <td><a class="table-title" href="{{ route('laporan.show', $laporan) }}">{{ $laporan->judul }}</a><small class="table-subtitle">#{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</small></td>
                        <td>{{ $laporan->tanggal->translatedFormat('d M Y') }}</td>
                        <td><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></td>
                        <td><a class="row-arrow" href="{{ route('laporan.show', $laporan) }}" aria-label="Lihat laporan">→</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
