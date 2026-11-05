@extends('layouts.app')

@section('title', 'Tinjau laporan')
@section('eyebrow', 'TINJAUAN ADMIN')

@section('content')
<div class="page-heading">
    <a class="back-link" href="{{ route('admin.laporan.index') }}">← Kembali ke semua laporan</a>
    <div class="detail-title-row"><div><span class="eyebrow">LAPORAN #{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</span><h1>{{ $laporan->judul }}</h1></div><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></div>
</div>
<div class="detail-layout">
    <article class="panel detail-panel">
        <div class="reporter-card"><span class="avatar">{{ mb_substr($laporan->pelapor->name, 0, 1) }}</span><div><small>DILAPORKAN OLEH</small><strong>{{ $laporan->pelapor->name }}</strong><span>{{ $laporan->pelapor->email }} · {{ $laporan->pelapor->no_whatsapp }}</span></div></div>
        <div class="detail-meta"><span><small>TANGGAL KEJADIAN</small><strong>{{ $laporan->tanggal->translatedFormat('d F Y') }}</strong></span><span><small>TANGGAL DILAPORKAN</small><strong>{{ $laporan->created_at->translatedFormat('d F Y, H:i') }}</strong></span></div>
        <div class="detail-copy"><h2>Detail kejadian</h2><p>{{ $laporan->detail }}</p></div>
        @if ($laporan->foto)
            <div class="detail-photo-wrap"><h2>Foto pendukung</h2><a href="{{ asset('storage/'.$laporan->foto) }}" target="_blank" rel="noopener"><img class="detail-photo" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto pendukung laporan: {{ $laporan->judul }}"></a></div>
        @endif
    </article>
    <aside class="guide-card status-guide">
        <span class="eyebrow">TINDAK LANJUT</span><h3>Perbarui status</h3>
        <p class="muted">Status diperbarui berurutan dan akan langsung terlihat oleh pelapor.</p>
        <div class="admin-status-list">
            <span class="{{ $laporan->status === 'pending' ? 'current' : 'complete' }}">01 <b>Menunggu</b></span>
            <span class="{{ in_array($laporan->status, ['proses', 'selesai'], true) ? ($laporan->status === 'proses' ? 'current' : 'complete') : '' }}">02 <b>Sedang diproses</b></span>
            <span class="{{ $laporan->status === 'selesai' ? 'current complete' : '' }}">03 <b>Selesai</b></span>
        </div>
        @if ($laporan->status !== 'selesai')
            <form method="POST" action="{{ route('admin.laporan.status', $laporan) }}" class="status-update-form">
                @csrf @method('PATCH')
                @if ($laporan->status === 'pending')
                    <input type="hidden" name="status" value="proses">
                    <button class="button button-primary button-full" type="submit">Mulai proses <span>→</span></button>
                @else
                    <input type="hidden" name="status" value="selesai">
                    <button class="button button-primary button-full" type="submit">Tandai selesai <span>✓</span></button>
                @endif
                @error('status') <small class="field-error">{{ $message }}</small> @enderror
            </form>
        @else
            <div class="success-note">✓ Laporan ini telah selesai ditangani.</div>
        @endif
        <div class="privacy-note"><strong>Informasi pelapor</strong><p>{{ $laporan->pelapor->alamat }}</p></div>
    </aside>
</div>
@endsection
