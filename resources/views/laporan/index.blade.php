@extends('layouts.app')

@section('title', 'Laporan saya')
@section('eyebrow', 'LAPORAN SAYA')

@section('content')
<div class="page-heading heading-with-action">
    <div><span class="eyebrow">RIWAYAT PENGADUAN</span><h1>Laporan saya</h1><p>Pantau semua laporan dan perkembangan penanganannya.</p></div>
    <a class="button button-primary" href="{{ route('laporan.create') }}"><span class="button-plus">＋</span> Buat laporan</a>
</div>
<section class="panel">
    <form class="filter-bar" method="GET" action="{{ route('laporan.index') }}">
        <label class="search-field"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul laporan"></label>
        <select name="status" aria-label="Filter berdasarkan status">
            <option value="">Semua status</option>
            @foreach (\App\Models\Laporan::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="button button-secondary" type="submit">Terapkan filter</button>
    </form>
    @if ($laporans->isEmpty())
        <div class="empty-state"><span class="empty-icon">⌕</span><h3>{{ request()->hasAny(['q', 'status']) ? 'Laporan tidak ditemukan' : 'Belum ada laporan' }}</h3><p>{{ request()->hasAny(['q', 'status']) ? 'Coba ubah kata kunci atau filter status.' : 'Laporan yang kamu kirim akan tercatat dan bisa dipantau di sini.' }}</p>
        @unless (request()->hasAny(['q', 'status']))<a class="button button-primary" href="{{ route('laporan.create') }}">Kirim laporan pertama</a>@endunless</div>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul laporan</th><th>Tanggal kejadian</th><th>Dikirim</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($laporans as $laporan)
                    <tr>
                        <td><a class="table-title" href="{{ route('laporan.show', $laporan) }}">{{ $laporan->judul }}</a><small class="table-subtitle">#{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</small></td>
                        <td>{{ $laporan->tanggal->translatedFormat('d M Y') }}</td>
                        <td>{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                        <td><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></td>
                        <td><a class="row-arrow" href="{{ route('laporan.show', $laporan) }}" aria-label="Lihat laporan">→</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $laporans->links() }}</div>
    @endif
</section>
@endsection
