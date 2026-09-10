<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Support\SantriReportScope;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query();
        SantriReportScope::apply($query, $request);

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status_pembayaran', 'pending')->count(),
            'lunas' => (clone $query)->where('status_pembayaran', 'lunas')->count(),
            'transfer' => (clone $query)->where('metode_pembayaran', 'transfer')->count(),
            'bayar_ditempat' => (clone $query)->where('metode_pembayaran', 'bayar_ditempat')->count(),
        ];

        $pmbStats = [
            'mengikuti_tes' => (clone $query)->where('status_pendaftar', 'mengikuti_tes')->count(),
            'lulus' => (clone $query)->whereIn('status_pendaftar', ['lulus', 'sertifikat_diterbitkan'])->count(),
            'tidak_lulus' => (clone $query)->where('status_pendaftar', 'tidak_lulus')->count(),
            'sertifikat' => (clone $query)->where('status_pendaftar', 'sertifikat_diterbitkan')->count(),
        ];

        $genderQuery = clone $query;
        $genderStats = [
            'L' => (clone $genderQuery)->where('jenis_kelamin', 'L')->count(),
            'P' => (clone $genderQuery)->where('jenis_kelamin', 'P')->count(),
        ];
        $genderStats['total'] = $genderStats['L'] + $genderStats['P'];

        $lembagaStats = (clone $query)
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
            'pmbStats' => $pmbStats,
            'genderStats' => $genderStats,
            'lembagaStats' => $lembagaStats,
            ...SantriReportScope::filterOptions($request),
        ]);
    }
}
