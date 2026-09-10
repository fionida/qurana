<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\GelombangKomponenTes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GelombangKomponenTesController extends Controller
{
    public function edit(Gelombang $gelombang): View
    {
        $gelombang->ensureKomponenTesSeeded();

        return view('admin.gelombangs.komponen-tes.edit', [
            'gelombang' => $gelombang,
            'komponen' => $gelombang->komponenTes()->orderBy('urutan')->get(),
        ]);
    }

    public function store(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $validated = $request->validate([
            'nama_komponen' => ['required', 'string', 'max:255'],
            'bobot' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_maksimal' => ['required', 'numeric', 'min:1', 'max:1000'],
        ], [
            'nama_komponen.required' => 'Nama komponen wajib diisi.',
        ]);

        $nextUrutan = (int) ($gelombang->komponenTes()->max('urutan') ?? 0) + 1;

        $gelombang->komponenTes()->create([
            'urutan' => $nextUrutan,
            'nama_komponen' => $validated['nama_komponen'],
            'bobot' => $validated['bobot'] ?? null,
            'nilai_maksimal' => $validated['nilai_maksimal'],
        ]);

        return redirect()
            ->route('admin.gelombangs.komponen-tes.edit', $gelombang)
            ->with('success', 'Komponen tes berhasil ditambahkan.');
    }

    public function update(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $validated = $request->validate([
            'komponen' => ['required', 'array', 'min:1'],
            'komponen.*.id' => ['required', 'exists:gelombang_komponen_tes,id'],
            'komponen.*.nama_komponen' => ['required', 'string', 'max:255'],
            'komponen.*.bobot' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'komponen.*.nilai_maksimal' => ['required', 'numeric', 'min:1', 'max:1000'],
        ]);

        foreach ($validated['komponen'] as $index => $row) {
            $item = GelombangKomponenTes::query()
                ->where('gelombang_id', $gelombang->id)
                ->whereKey($row['id'])
                ->firstOrFail();

            $item->update([
                'urutan' => $index + 1,
                'nama_komponen' => $row['nama_komponen'],
                'bobot' => $row['bobot'] ?? null,
                'nilai_maksimal' => $row['nilai_maksimal'],
            ]);
        }

        return redirect()
            ->route('admin.gelombangs.komponen-tes.edit', $gelombang)
            ->with('success', 'Komponen tes berhasil disimpan.');
    }

    public function destroy(Gelombang $gelombang, GelombangKomponenTes $komponenTes): RedirectResponse
    {
        abort_unless($komponenTes->gelombang_id === $gelombang->id, 404);

        if ($gelombang->komponenTes()->count() <= 1) {
            return redirect()
                ->route('admin.gelombangs.komponen-tes.edit', $gelombang)
                ->with('error', 'Minimal satu komponen tes harus tetap ada.');
        }

        $komponenTes->delete();

        $this->renumberUrutan($gelombang);

        return redirect()
            ->route('admin.gelombangs.komponen-tes.edit', $gelombang)
            ->with('success', 'Komponen tes dihapus.');
    }

    private function renumberUrutan(Gelombang $gelombang): void
    {
        foreach ($gelombang->komponenTes()->orderBy('urutan')->orderBy('id')->get() as $index => $item) {
            $urutan = $index + 1;
            if ((int) $item->urutan !== $urutan) {
                $item->update(['urutan' => $urutan]);
            }
        }
    }
}
