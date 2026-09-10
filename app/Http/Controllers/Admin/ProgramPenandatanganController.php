<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penandatangan;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramPenandatanganController extends Controller
{
    public function index(Program $program): View
    {
        return view('admin.programs.penandatangan.index', [
            'program' => $program,
            'penandatangans' => $program->penandatangans()->orderBy('urutan')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $this->validateRow($request);

        $urutan = (int) ($validated['urutan'] ?? ($program->penandatangans()->max('urutan') + 1));

        $program->penandatangans()->create([
            ...$validated,
            'urutan' => $urutan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Penandatangan berhasil ditambahkan.');
    }

    public function update(Request $request, Program $program, Penandatangan $penandatangan): RedirectResponse
    {
        $this->ensureBelongsToProgram($program, $penandatangan);

        $validated = $this->validateRow($request);

        $penandatangan->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Penandatangan berhasil diperbarui.');
    }

    public function destroy(Program $program, Penandatangan $penandatangan): RedirectResponse
    {
        $this->ensureBelongsToProgram($program, $penandatangan);

        $penandatangan->delete();

        return back()->with('success', 'Penandatangan dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRow(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:999'],
        ], [
            'nama.required' => 'Nama penandatangan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
        ]);
    }

    private function ensureBelongsToProgram(Program $program, Penandatangan $penandatangan): void
    {
        abort_unless($penandatangan->program_id === $program->id, 404);
    }
}
