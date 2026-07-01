<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lembaga;
use App\Models\Pengajar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajarController extends Controller
{
    public function index(Request $request): View
    {
        $editPengajar = null;
        if ($request->filled('edit')) {
            $editPengajar = Pengajar::find($request->edit);
        }

        $query = Pengajar::query()->orderBy('nama_lengkap');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_wa', 'like', "%{$search}%");
            });
        }

        return view('admin.pengajars.index', [
            'pengajars' => $query->paginate(15)->withQueryString(),
            'lembagaOptions' => Lembaga::allNames(),
            'editPengajar' => $editPengajar,
            'openCreate' => $request->boolean('create') || old('_modal') === 'create',
            'openEdit' => $editPengajar || old('_modal') === 'edit',
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.pengajars.index', ['create' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePengajar($request);

        Pengajar::create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.pengajars.index')
            ->with('success', 'Data pengajar berhasil ditambahkan.');
    }

    public function edit(Pengajar $pengajar): RedirectResponse
    {
        return redirect()->route('admin.pengajars.index', ['edit' => $pengajar->id]);
    }

    public function update(Request $request, Pengajar $pengajar): RedirectResponse
    {
        $validated = $this->validatePengajar($request, $pengajar->id);

        $pengajar->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.pengajars.index')
            ->with('success', 'Data pengajar berhasil diperbarui.');
    }

    public function destroy(Pengajar $pengajar): RedirectResponse
    {
        $pengajar->delete();

        return redirect()
            ->route('admin.pengajars.index')
            ->with('success', 'Data pengajar berhasil dihapus.');
    }

    private function validatePengajar(Request $request, ?int $ignoreId = null): array
    {
        $nipRule = ['nullable', 'string', 'max:50'];
        if ($ignoreId) {
            $nipRule[] = 'unique:pengajars,nip,'.$ignoreId;
        } else {
            $nipRule[] = 'unique:pengajars,nip';
        }

        return $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => $nipRule,
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'no_wa' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'lembaga' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
        ]);
    }
}
