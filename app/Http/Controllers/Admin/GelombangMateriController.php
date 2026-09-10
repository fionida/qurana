<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\GelombangMateri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GelombangMateriController extends Controller
{
    public function edit(Gelombang $gelombang): View
    {
        $gelombang->load('program');
        $gelombang->ensureMaterisSeeded();

        return view('admin.gelombangs.materi.edit', [
            'gelombang' => $gelombang,
            'materis' => $gelombang->materis()->orderBy('urutan')->get(),
        ]);
    }

    public function update(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $validated = $request->validate([
            'materi' => ['required', 'array', 'min:1'],
            'materi.*.id' => ['required', 'exists:gelombang_materis,id'],
            'materi.*.nama_materi' => ['required', 'string', 'max:255'],
            'materi.*.durasi' => ['nullable', 'string', 'max:20'],
            'materi.*.jpl' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [
            'materi.required' => 'Data materi wajib diisi.',
            'materi.*.nama_materi.required' => 'Nama materi wajib diisi.',
        ]);

        foreach ($validated['materi'] as $row) {
            $materi = GelombangMateri::query()
                ->where('gelombang_id', $gelombang->id)
                ->whereKey($row['id'])
                ->firstOrFail();

            $materi->update([
                'nama_materi' => $row['nama_materi'],
                'durasi' => isset($row['durasi']) ? trim($row['durasi']) : null,
                'jpl' => $row['jpl'] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.gelombangs.materi.edit', $gelombang)
            ->with('success', 'Materi sertifikasi berhasil disimpan.');
    }
}
