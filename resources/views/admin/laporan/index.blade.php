@extends('layouts.app')

@section('title', 'Semua laporan')
@section('eyebrow', 'MANAJEMEN PENGADUAN')

@section('content')
<div class="page-heading"><span class="eyebrow">PUSAT TINDAK LANJUT</span><h1>Semua laporan</h1><p>Tinjau laporan warga dan perbarui status penanganannya.</p></div>
<section class="panel">
    <form class="filter-bar" method="GET" action="{{ route('admin.laporan.index') }}">
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
        <div class="empty-state"><span class="empty-icon">⌕</span><h3>{{ request()->hasAny(['q', 'status']) ? 'Laporan tidak ditemukan' : 'Belum ada laporan masuk' }}</h3><p>{{ request()->hasAny(['q', 'status']) ? 'Coba ubah kata kunci atau filter status.' : 'Laporan warga yang masuk akan tampil di sini.' }}</p></div>
    @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul laporan</th><th>Pelapor</th><th>Tanggal kejadian</th><th>Dikirim</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach ($laporans as $laporan)
                    <tr>
                        <td><a class="table-title" href="{{ route('admin.laporan.show', $laporan) }}">{{ $laporan->judul }}</a><small class="table-subtitle">#{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</small></td>
                        <td>{{ $laporan->pelapor->name }}</td>
                        <td>{{ $laporan->tanggal->translatedFormat('d M Y') }}</td>
                        <td>{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                        <td><span class="status status-{{ $laporan->status }}">{{ \App\Models\Laporan::STATUSES[$laporan->status] }}</span></td>
                        <td><a class="row-arrow" href="{{ route('admin.laporan.show', $laporan) }}" aria-label="Tinjau laporan">→</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $laporans->links() }}</div>
    @endif
</section>
@endsection
