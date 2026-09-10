<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $editProgram = null;
        if ($request->filled('edit')) {
            $editProgram = Program::find($request->edit);
        }

        return view('admin.programs.index', [
            'programs' => Program::query()->orderBy('urutan')->orderBy('nama')->paginate(15)->withQueryString(),
            'editProgram' => $editProgram,
            'openCreate' => $request->boolean('create') || old('_modal') === 'create',
            'openEdit' => $editProgram || old('_modal') === 'edit',
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.programs.index', ['create' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Program::create([
            ...$validated,
            'urutan' => (int) ($validated['urutan'] ?? 0),
            'slug' => Program::slugFromNama($validated['nama']),
            'is_active' => $request->boolean('is_active', true),
            'butuh_seleksi_tes' => $request->boolean('butuh_seleksi_tes', true),
            'butuh_kelulusan' => $request->boolean('butuh_kelulusan', true),
            'butuh_sertifikat_resmi' => $request->boolean('butuh_sertifikat_resmi', true),
        ]);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program kegiatan berhasil ditambahkan.');
    }

    public function edit(Program $program): RedirectResponse
    {
        return redirect()->route('admin.programs.index', ['edit' => $program->id]);
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $this->validated($request, $program);

        $program->update([
            ...$validated,
            'urutan' => (int) ($validated['urutan'] ?? $program->urutan),
            'is_active' => $request->boolean('is_active'),
            'butuh_seleksi_tes' => $request->boolean('butuh_seleksi_tes'),
            'butuh_kelulusan' => $request->boolean('butuh_kelulusan'),
            'butuh_sertifikat_resmi' => $request->boolean('butuh_sertifikat_resmi'),
        ]);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program kegiatan berhasil diperbarui.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        $gelombangCount = Gelombang::query()->where('program_id', $program->id)->count();

        if ($gelombangCount > 0) {
            return back()->with('error', "Program tidak dapat dihapus karena masih memiliki {$gelombangCount} gelombang.");
        }

        $program->delete();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program kegiatan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Program $program = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'judul_sertifikat' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'nama.required' => 'Nama program wajib diisi.',
        ]);
    }
}
