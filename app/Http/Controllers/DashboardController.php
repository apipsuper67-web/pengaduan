<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return redirect()->route(auth()->user()->dashboardRoute());
    }

    public function citizen(): View
    {
        $user = auth()->user();
        $stats = $user->laporans()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');
        $recent = $user->laporans()->latest()->limit(5)->get();

        return view('dashboard.citizen', compact('stats', 'recent'));
    }

    public function admin(): View
    {
        $counts = Laporan::query()
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');
        $stats = array_map(fn (string $status) => (int) ($counts[$status] ?? 0), array_keys(Laporan::STATUSES));
        $stats = array_combine(array_keys(Laporan::STATUSES), $stats);
        $total = array_sum($stats);
        $citizens = User::where('role', 'warga')->count();
        $recent = Laporan::with('pelapor')->latest()->limit(5)->get();

        return view('dashboard.admin', compact('stats', 'total', 'citizens', 'recent'));
    }
}
