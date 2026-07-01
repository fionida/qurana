<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Santri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LembagaController extends Controller
{
    public function index(Request $request): View
    {
        $editLembaga = null;
        if ($request->filled('edit')) {
            $editLembaga = Lembaga::find($request->edit);
        }

        return view('admin.lembagas.index', [
            'lembagas' => Lembaga::query()->orderBy('nama')->paginate(15)->withQueryString(),
            'editLembaga' => $editLembaga,
            'openCreate' => $request->boolean('create') || (old('_modal') === 'create'),
            'openEdit' => $editLembaga || old('_modal') === 'edit',
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.lembagas.index', ['create' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:lembagas,nama'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama.required' => 'Nama lembaga wajib diisi.',
            'nama.unique' => 'Nama lembaga sudah ada.',
        ]);

        Lembaga::create([
            'nama' => $validated['nama'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.lembagas.index')
            ->with('success', 'Lembaga berhasil ditambahkan.');
    }

    public function edit(Lembaga $lembaga): RedirectResponse
    {
        return redirect()->route('admin.lembagas.index', ['edit' => $lembaga->id]);
    }

    public function update(Request $request, Lembaga $lembaga): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:lembagas,nama,'.$lembaga->id],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'nama.required' => 'Nama lembaga wajib diisi.',
            'nama.unique' => 'Nama lembaga sudah ada.',
        ]);

        $oldName = $lembaga->nama;
        $newName = $validated['nama'];

        $lembaga->update([
            'nama' => $newName,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($oldName !== $newName) {
            Santri::query()->where('lembaga', $oldName)->update(['lembaga' => $newName]);
        }

        return redirect()
            ->route('admin.lembagas.index')
            ->with('success', 'Lembaga berhasil diperbarui.');
    }

    public function destroy(Lembaga $lembaga): RedirectResponse
    {
        $usedCount = Santri::query()->where('lembaga', $lembaga->nama)->count();

        if ($usedCount > 0) {
            return back()->with('error', "Lembaga tidak dapat dihapus karena masih digunakan oleh {$usedCount} santri.");
        }

        $lembaga->delete();

        return redirect()
            ->route('admin.lembagas.index')
            ->with('success', 'Lembaga berhasil dihapus.');
    }
}
