@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="auth-layout">
    <section class="auth-intro">
        <span class="eyebrow">MULAI DARI LINGKUNGANMU</span>
        <h1>Satu laporan<br>bisa membawa <em>perubahan.</em></h1>
        <p>Buat akun warga untuk menyampaikan pengaduan dan mengikuti perkembangannya.</p>
        <div class="auth-points">
            <span><b>01</b> Isi data dirimu dengan benar</span>
            <span><b>02</b> Laporkan hal yang perlu diperbaiki</span>
            <span><b>03</b> Dapatkan kabar setiap tindak lanjut</span>
        </div>
        <div class="auth-decoration"><span></span><span></span><span></span></div>
    </section>
    <section class="auth-card">
        <span class="eyebrow">AKUN WARGA</span>
        <h2>Buat akun baru</h2>
        <p class="muted">Hanya butuh beberapa data untuk memulai.</p>
        <form method="POST" action="{{ route('register.store') }}" class="form-stack">
            @csrf
            <label class="field"><span>Nama lengkap</span><input type="text" name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" autocomplete="name" required>@error('name') <small class="field-error">{{ $message }}</small> @enderror</label>
            <label class="field"><span>Email</span><input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required>@error('email') <small class="field-error">{{ $message }}</small> @enderror</label>
            <label class="field"><span>Nomor WhatsApp</span><input type="tel" name="no_whatsapp" value="{{ old('no_whatsapp') }}" placeholder="08xxxxxxxxxx" autocomplete="tel" required>@error('no_whatsapp') <small class="field-error">{{ $message }}</small> @enderror</label>
            <label class="field"><span>Alamat</span><textarea name="alamat" rows="2" placeholder="Alamat tempat tinggal" autocomplete="street-address" required>{{ old('alamat') }}</textarea>@error('alamat') <small class="field-error">{{ $message }}</small> @enderror</label>
            <div class="field-pair">
                <label class="field"><span>Kata sandi</span><input type="password" name="password" placeholder="Minimal 10 karakter" autocomplete="new-password" required>@error('password') <small class="field-error">{{ $message }}</small> @enderror</label>
                <label class="field"><span>Ulangi kata sandi</span><input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" autocomplete="new-password" required></label>
            </div>
            <button class="button button-primary button-full" type="submit">Buat akun <span>→</span></button>
        </form>
        <p class="auth-switch">Sudah terdaftar? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </section>
</div>
@endsection
