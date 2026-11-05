@extends('layouts.app')

@section('title', $editing ? 'Edit laporan' : 'Buat laporan')
@section('eyebrow', $editing ? 'PERBARUI PENGADUAN' : 'PENGADUAN BARU')

@section('content')
<div class="page-heading">
    <a class="back-link" href="{{ $editing ? route('laporan.show', $laporan) : route('laporan.index') }}">← Kembali</a>
    <span class="eyebrow">{{ $editing ? 'PERBARUI PENGADUAN' : 'SUARA WARGA' }}</span>
    <h1>{{ $editing ? 'Edit laporan' : 'Ceritakan masalahnya' }}</h1>
    <p>Jelaskan dengan lengkap agar laporanmu dapat segera dipahami dan ditindaklanjuti.</p>
</div>
<div class="form-layout">
    <form class="panel form-panel" method="POST" action="{{ $editing ? route('laporan.update', $laporan) : route('laporan.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="form-section-title"><span class="step-number">1</span><div><h2>Informasi laporan</h2><p>Berikan ringkasan dan detail kejadian.</p></div></div>
        <label class="field"><span>Judul laporan <i>*</i></span><input type="text" name="judul" value="{{ old('judul', $laporan->judul) }}" maxlength="160" placeholder="Contoh: Lampu jalan mati di Jalan Melati" required>@error('judul') <small class="field-error">{{ $message }}</small> @enderror</label>
        <label class="field"><span>Tanggal kejadian <i>*</i></span><input type="date" name="tanggal" value="{{ old('tanggal', $laporan->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>@error('tanggal') <small class="field-error">{{ $message }}</small> @enderror</label>
        <label class="field"><span>Detail kejadian <i>*</i></span><textarea name="detail" rows="6" maxlength="5000" placeholder="Ceritakan lokasi, kondisi, dan hal penting lainnya..." required>{{ old('detail', $laporan->detail) }}</textarea><small class="field-hint">Minimal 20 karakter. Hindari membagikan informasi pribadi orang lain.</small>@error('detail') <small class="field-error">{{ $message }}</small> @enderror</label>
        <div class="form-section-title section-spaced"><span class="step-number">2</span><div><h2>Foto pendukung <span class="optional">(opsional)</span></h2><p>Foto membantu petugas memahami kondisi di lokasi.</p></div></div>
        @if ($editing && $laporan->foto)
            <div class="current-photo"><img src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto laporan saat ini"><span>Foto saat ini</span></div>
        @endif
        <label class="upload-box">
            <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
            <span class="upload-icon">↑</span><strong>Pilih foto dari perangkat</strong><small>JPG, PNG, atau WebP · Maksimal 2 MB</small>
        </label>
        @error('foto') <small class="field-error">{{ $message }}</small> @enderror
        <div class="form-actions"><a class="button button-secondary" href="{{ $editing ? route('laporan.show', $laporan) : route('laporan.index') }}">Batal</a><button class="button button-primary" type="submit">{{ $editing ? 'Simpan perubahan' : 'Kirim laporan' }} <span>→</span></button></div>
    </form>
    <aside class="guide-card">
        <span class="guide-icon">✳</span><h3>Tips laporan yang baik</h3>
        <ul><li>Tulis judul singkat dan mudah dipahami.</li><li>Sebutkan lokasi kejadian dengan jelas.</li><li>Sertakan foto terbaru jika memungkinkan.</li><li>Gunakan bahasa yang sopan dan faktual.</li></ul>
        <div class="privacy-note"><strong>Privasi terjaga</strong><p>Data kontakmu hanya digunakan untuk keperluan tindak lanjut laporan.</p></div>
    </aside>
</div>
@endsection
