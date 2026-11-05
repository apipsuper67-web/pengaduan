<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $laporans = $request->user()->laporans()
            ->when($request->filled('q'), fn ($query) => $query->where('judul', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status') && array_key_exists($request->string('status')->toString(), Laporan::STATUSES),
                fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('laporan.index', compact('laporans'));
    }

    public function create(): View
    {
        return view('laporan.form', ['laporan' => new Laporan, 'editing' => false]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedReport($request);
        $validated['foto'] = $request->file('foto')?->store('laporan', 'public');
        $laporan = $request->user()->laporans()->create($validated);

        return redirect()->route('laporan.show', $laporan)->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Laporan $laporan): View
    {
        $laporan = $this->ownedReport($laporan);

        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan): View|RedirectResponse
    {
        $laporan = $this->ownedReport($laporan);

        if ($laporan->status !== 'pending') {
            return redirect()->route('laporan.show', $laporan)
                ->with('error', 'Laporan yang sudah ditindaklanjuti tidak dapat diedit.');
        }

        return view('laporan.form', ['laporan' => $laporan, 'editing' => true]);
    }

    public function update(Request $request, Laporan $laporan): RedirectResponse
    {
        $laporan = $this->ownedReport($laporan);

        abort_if($laporan->status !== 'pending', 403, 'Laporan yang sudah ditindaklanjuti tidak dapat diedit.');

        $validated = $this->validatedReport($request);
        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }
        $laporan->update($validated);

        return redirect()->route('laporan.show', $laporan)->with('success', 'Perubahan laporan berhasil disimpan.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $laporan = $this->ownedReport($laporan);
        abort_if($laporan->status !== 'pending', 403, 'Laporan yang sudah ditindaklanjuti tidak dapat dihapus.');

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }
        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function adminIndex(Request $request): View
    {
        $laporans = Laporan::with('pelapor')
            ->when($request->filled('q'), fn ($query) => $query->where('judul', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status') && array_key_exists($request->string('status')->toString(), Laporan::STATUSES),
                fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.laporan.index', compact('laporans'));
    }

    public function adminShow(Laporan $laporan): View
    {
        $laporan->load('pelapor');

        return view('admin.laporan.show', compact('laporan'));
    }

    public function updateStatus(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['proses', 'selesai'])],
        ]);
        $nextStatus = ['pending' => 'proses', 'proses' => 'selesai'][$laporan->status] ?? null;

        if ($validated['status'] !== $nextStatus) {
            return back()->withErrors(['status' => 'Status harus diperbarui berurutan: Menunggu, Diproses, lalu Selesai.']);
        }

        $laporan->status = $validated['status'];
        $laporan->save();

        return redirect()->route('admin.laporan.show', $laporan)->with('success', 'Status laporan berhasil diperbarui.');
    }

    private function validatedReport(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'min:5', 'max:160'],
            'detail' => ['required', 'string', 'min:20', 'max:5000'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function ownedReport(Laporan $laporan): Laporan
    {
        return auth()->user()->laporans()->findOrFail($laporan->id);
    }
}
