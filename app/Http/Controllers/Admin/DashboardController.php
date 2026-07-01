<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query();

        $this->applyFilters($query, $request);

        $santris = (clone $query)->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Santri::count(),
            'pending' => Santri::where('status_pembayaran', 'pending')->count(),
            'lunas' => Santri::where('status_pembayaran', 'lunas')->count(),
            'transfer' => Santri::where('metode_pembayaran', 'transfer')->count(),
            'bayar_ditempat' => Santri::where('metode_pembayaran', 'bayar_ditempat')->count(),
        ];

        $lembagaStats = Santri::query()
            ->selectRaw('lembaga, count(*) as total')
            ->groupBy('lembaga')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', [
            'santris' => $santris,
            'stats' => $stats,
            'lembagaStats' => $lembagaStats,
            'filters' => $request->only([
                'nama_lengkap',
                'lembaga',
                'jenis_kelamin',
                'metode_pembayaran',
                'status_pembayaran',
                'tanggal_dari',
                'tanggal_sampai',
            ]),
            'lembagaOptions' => Santri::lembagaOptions(),
        ]);
    }

    protected function applyFilters($query, Request $request): void
    {
        if ($request->filled('nama_lengkap')) {
            $query->where('nama_lengkap', 'like', '%'.$request->nama_lengkap.'%');
        }

        if ($request->filled('lembaga')) {
            $query->where('lembaga', $request->lembaga);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
    }
}
