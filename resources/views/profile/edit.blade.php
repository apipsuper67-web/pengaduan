@extends('layouts.app')

@section('title', 'Profil saya')
@section('eyebrow', 'PENGATURAN AKUN')

@section('content')
<div class="page-heading"><span class="eyebrow">INFORMASI PRIBADI</span><h1>Profil saya</h1><p>Pastikan data kontakmu tetap aktif agar tindak lanjut lebih mudah.</p></div>
<div class="profile-layout">
    <form class="panel form-panel" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="profile-header">
            @if (auth()->user()->foto)
                <img class="profile-avatar" src="{{ asset('storage/'.auth()->user()->foto) }}" alt="Foto profil">
            @else
                <span class="profile-avatar profile-avatar-fallback">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
            @endif
            <div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Warga terdaftar' }}</small></div>
        </div>
        <label class="upload-inline"><span>Perbarui foto profil</span><input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, atau WebP · Maksimal 2 MB</small></label>
        @error('foto') <small class="field-error">{{ $message }}</small> @enderror
        <div class="profile-divider"></div>
        <label class="field"><span>Nama lengkap</span><input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>@error('name') <small class="field-error">{{ $message }}</small> @enderror</label>
        <label class="field"><span>Email</span><input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required>@error('email') <small class="field-error">{{ $message }}</small> @enderror</label>
        <label class="field"><span>Nomor WhatsApp</span><input type="tel" name="no_whatsapp" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}" required>@error('no_whatsapp') <small class="field-error">{{ $message }}</small> @enderror</label>
        <label class="field"><span>Alamat</span><textarea name="alamat" rows="3" required>{{ old('alamat', auth()->user()->alamat) }}</textarea>@error('alamat') <small class="field-error">{{ $message }}</small> @enderror</label>
        <div class="form-actions"><button class="button button-primary" type="submit">Simpan perubahan <span>→</span></button></div>
    </form>
    <aside class="guide-card">
        <span class="guide-icon">◎</span><h3>Data akunmu</h3>
        <p class="muted">Informasi ini membantu admin menghubungimu jika ada detail laporan yang perlu dikonfirmasi.</p>
        <div class="privacy-note"><strong>Keamanan akun</strong><p>Kata sandi disimpan secara aman dan tidak pernah ditampilkan di halaman profil.</p></div>
    </aside>
</div>
@endsection
