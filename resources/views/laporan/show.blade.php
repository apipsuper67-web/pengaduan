@extends('layouts.app')

@section('title', 'Detail laporan')
@section('eyebrow', 'DETAIL LAPORAN')

@section('content')
<div class="page-heading">
    <a class="back-link" href="{{ route('laporan.index') }}">← Kembali ke laporan saya</a>
    <div class="detail-title-row"><div><span class="eyebrow">LAPORAN #{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</span><h1>{{ $laporan->judul }}</h1></div><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></div>
</div>
<div class="detail-layout">
    <article class="panel detail-panel">
        <div class="detail-meta"><span><small>TANGGAL KEJADIAN</small><strong>{{ $laporan->tanggal->translatedFormat('d F Y') }}</strong></span><span><small>TANGGAL DILAPORKAN</small><strong>{{ $laporan->created_at->translatedFormat('d F Y, H:i') }}</strong></span></div>
        <div class="detail-copy"><h2>Detail kejadian</h2><p>{{ $laporan->detail }}</p></div>
        @if ($laporan->foto)
            <div class="detail-photo-wrap"><h2>Foto pendukung</h2><a href="{{ asset('storage/'.$laporan->foto) }}" target="_blank" rel="noopener"><img class="detail-photo" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto pendukung laporan: {{ $laporan->judul }}"></a></div>
        @endif
        <div class="status-message"><span class="status-message-icon">i</span><div><strong>Status laporan: {{ \App\Models\Laporan::STATUSES[$laporan->status] }}</strong><p>{{ $laporan->statusDescription() }}</p></div></div>
        @if ($laporan->status === 'pending')
            <div class="form-actions detail-actions">
                <a class="button button-secondary" href="{{ route('laporan.edit', $laporan) }}">Edit laporan</a>
                <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">@csrf @method('DELETE')<button class="button button-danger" type="submit">Hapus laporan</button></form>
            </div>
        @endif
    </article>
    <aside class="guide-card status-guide">
        <span class="eyebrow">ALUR PENANGANAN</span><h3>Perjalanan laporanmu</h3>
        <ol class="timeline">
            <li class="timeline-done"><span>✓</span><div><strong>Laporan terkirim</strong><small>{{ $laporan->created_at->translatedFormat('d M Y, H:i') }}</small></div></li>
            <li class="{{ in_array($laporan->status, ['proses', 'selesai'], true) ? 'timeline-done' : 'timeline-current' }}"><span>{{ in_array($laporan->status, ['proses', 'selesai'], true) ? '✓' : '2' }}</span><div><strong>Dalam penanganan</strong><small>{{ $laporan->status === 'pending' ? 'Menunggu peninjauan admin' : 'Sedang ditindaklanjuti' }}</small></div></li>
            <li class="{{ $laporan->status === 'selesai' ? 'timeline-done' : '' }}"><span>{{ $laporan->status === 'selesai' ? '✓' : '3' }}</span><div><strong>Penanganan selesai</strong><small>{{ $laporan->status === 'selesai' ? 'Laporan telah selesai' : 'Menunggu tindak lanjut' }}</small></div></li>
        </ol>
        <div class="privacy-note"><strong>Butuh bantuan?</strong><p>Admin akan memperbarui status laporan ini setelah proses peninjauan.</p></div>
    </aside>
</div>
@endsection
