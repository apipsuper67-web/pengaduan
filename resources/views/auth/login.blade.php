@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="auth-layout">
    <section class="auth-intro">
        <span class="eyebrow">RUANG PUBLIK, SUARA BERSAMA</span>
        <h1>Perubahan baik<br>dimulai dari <em>laporanmu.</em></h1>
        <p>Sampaikan masalah di sekitarmu. Pantau tindak lanjutnya dengan mudah, dari mana saja.</p>
        <div class="auth-points">
            <span><b>01</b> Ceritakan masalah dengan jelas</span>
            <span><b>02</b> Pantau proses secara transparan</span>
            <span><b>03</b> Bersama wujudkan lingkungan lebih baik</span>
        </div>
        <div class="auth-decoration"><span></span><span></span><span></span></div>
    </section>
    <section class="auth-card">
        <span class="eyebrow">SELAMAT DATANG KEMBALI</span>
        <h2>Masuk ke akunmu</h2>
        <p class="muted">Gunakan email yang terdaftar untuk melanjutkan.</p>
        <form method="POST" action="{{ route('login.store') }}" class="form-stack">
            @csrf
            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required autofocus>
                @error('email') <small class="field-error">{{ $message }}</small> @enderror
            </label>
            <label class="field">
                <span>Kata sandi</span>
                <input type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                @error('password') <small class="field-error">{{ $message }}</small> @enderror
            </label>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> <span>Ingat saya di perangkat ini</span></label>
            <button class="button button-primary button-full" type="submit">Masuk <span>→</span></button>
        </form>
        <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai warga</a></p>
    </section>
</div>
@endsection
