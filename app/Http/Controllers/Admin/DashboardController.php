<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Santri::count(),
            'pending' => Santri::where('status_pembayaran', 'pending')->count(),
            'lunas' => Santri::where('status_pembayaran', 'lunas')->count(),
            'transfer' => Santri::where('metode_pembayaran', 'transfer')->count(),
            'bayar_ditempat' => Santri::where('metode_pembayaran', 'bayar_ditempat')->count(),
        ];

        $genderStats = [
            'L' => Santri::where('jenis_kelamin', 'L')->count(),
            'P' => Santri::where('jenis_kelamin', 'P')->count(),
        ];
        $genderStats['total'] = $genderStats['L'] + $genderStats['P'];

        $lembagaStats = Santri::query()
            ->selectRaw("COALESCE(lembaga, '') as lembaga, count(*) as total")
            ->groupBy('lembaga')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                $name = trim((string) $item->lembaga);

                return (object) [
                    'lembaga' => $name !== '' ? $name : 'Belum diisi',
                    'total' => (int) $item->total,
                ];
            })
            ->groupBy('lembaga')
            ->map(function ($rows, $name) {
                return (object) [
                    'lembaga' => $name,
                    'total' => $rows->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('admin.dashboard', [
            'stats' => $stats,
            'genderStats' => $genderStats,
            'lembagaStats' => $lembagaStats,
        ]);
    }
}
