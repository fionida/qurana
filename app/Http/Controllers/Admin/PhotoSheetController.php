<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotoSheetController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()
            ->whereNotNull('pas_foto')
            ->where('pas_foto', '!=', '')
            ->latest();

        $this->applyFilters($query, $request);

        return view('admin.photo-sheets.index', [
            'santris' => $query->paginate(20)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
            'filters' => $request->only(['search', 'lembaga', 'status_pembayaran']),
        ]);
    }

    public function print(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'lembaga' => ['nullable', 'string', 'max:255'],
            'status_pembayaran' => ['nullable', 'in:pending,lunas'],
            'per_halaman' => ['nullable', 'integer', 'min:1', 'max:20'],
            'jumlah' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = Santri::query()
            ->whereNotNull('pas_foto')
            ->where('pas_foto', '!=', '')
            ->latest();

        $this->applyFilters($query, $request);

        if (! empty($validated['jumlah'])) {
            $query->limit((int) $validated['jumlah']);
        }

        $santris = $query->get();
        abort_if($santris->isEmpty(), 404, 'Data foto peserta tidak ditemukan.');

        return view('admin.photo-sheets.print', [
            'santris' => $santris,
            'perHalaman' => (int) ($validated['per_halaman'] ?? 12),
        ]);
    }

    protected function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('lembaga', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lembaga')) {
            $query->where('lembaga', $request->lembaga);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
    }
}
