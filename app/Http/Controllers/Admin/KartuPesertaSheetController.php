<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Support\DocumentTemplate;
use App\Support\SantriReportScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KartuPesertaSheetController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->baseQuery($request);

        return view('admin.kartu-peserta.index', [
            'santris' => $query->paginate(20)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
            'filters' => $request->only(['search', 'program', 'gelombang', 'lembaga', 'status_pembayaran']),
            ...SantriReportScope::filterOptions($request),
        ]);
    }

    public function print(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'program' => ['nullable', 'integer'],
            'gelombang' => ['nullable', 'integer'],
            'lembaga' => ['nullable', 'string', 'max:255'],
            'status_pembayaran' => ['nullable', 'in:pending,lunas'],
            'per_halaman' => ['nullable', 'integer', 'in:8,12,16'],
            'jumlah' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = $this->baseQuery($request);

        if (! empty($validated['jumlah'])) {
            $query->limit((int) $validated['jumlah']);
        }

        $santris = $query->get();
        abort_if($santris->isEmpty(), 404, 'Data peserta tidak ditemukan.');

        $perHalaman = (int) ($validated['per_halaman'] ?? 12);

        return view('admin.kartu-peserta.print', [
            'santris' => $santris,
            'perHalaman' => $perHalaman,
            'gridCols' => match ($perHalaman) {
                8 => 2,
                16 => 4,
                default => 3,
            },
            'backgroundUrl' => DocumentTemplate::isPrintableBackground('kartu_ujian')
                ? DocumentTemplate::publicUrl('kartu_ujian')
                : null,
        ]);
    }

    /**
     * @return Builder<Santri>
     */
    protected function baseQuery(Request $request): Builder
    {
        $query = Santri::query()
            ->with('gelombang.program')
            ->orderBy('nomor_pendaftaran');

        SantriReportScope::apply($query, $request);

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

        return $query;
    }
}
