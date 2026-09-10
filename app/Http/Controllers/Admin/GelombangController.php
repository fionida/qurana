<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Program;
use App\Models\Santri;
use App\Support\HijriDate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GelombangController extends Controller
{
    public function index(Request $request): View
    {
        $editGelombang = null;
        if ($request->filled('edit')) {
            $editGelombang = Gelombang::find($request->edit);
        }

        return view('admin.gelombangs.index', [
            'gelombangs' => Gelombang::query()->with('program')->orderByDesc('id')->paginate(10)->withQueryString(),
            'programOptions' => Program::query()->orderBy('urutan')->orderBy('nama')->get(),
            'editGelombang' => $editGelombang,
            'editForm' => $this->editFormDefaults($editGelombang),
            'openCreate' => $request->boolean('create') || old('_modal') === 'create',
            'openEdit' => $editGelombang || old('_modal') === 'edit',
            'suggestHijriUrl' => route('admin.gelombangs.suggest-hijri'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function editFormDefaults(?Gelombang $editGelombang): array
    {
        $programOptions = Program::query()->orderBy('urutan')->orderBy('nama')->get();

        return [
            'id' => old('_gelombang_id', $editGelombang?->id),
            'program_id' => old('program_id', $editGelombang?->program_id ?? $programOptions->first()?->id),
            'nama' => old('nama', $editGelombang?->nama ?? ''),
            'is_active' => old('is_active', $editGelombang?->is_active ?? true),
            'is_registration_open' => old('is_registration_open', $editGelombang?->is_registration_open ?? false),
            'tanggal_sertifikasi_mulai' => old('tanggal_sertifikasi_mulai', $editGelombang?->tanggal_sertifikasi_mulai?->format('Y-m-d') ?? ''),
            'tanggal_sertifikasi_selesai' => old('tanggal_sertifikasi_selesai', $editGelombang?->tanggal_sertifikasi_selesai?->format('Y-m-d') ?? ''),
            'tanggal_terbit_masehi' => old('tanggal_terbit_masehi', $editGelombang?->tanggal_terbit_masehi?->format('Y-m-d') ?? ''),
            'tanggal_terbit_hijriyah' => old('tanggal_terbit_hijriyah', $editGelombang?->tanggal_terbit_hijriyah ?? ''),
            'kota_terbit' => old('kota_terbit', $editGelombang?->kota_terbit ?? 'Malang'),
            'kode_batch' => old('kode_batch', $editGelombang?->kode_batch ?? '35.73'),
            'jenis_nomor' => old('jenis_nomor', $editGelombang?->jenis_nomor ?? 'S.S'),
            'nomor_urut_berikutnya' => old('nomor_urut_berikutnya', $editGelombang?->nomor_urut_berikutnya ?? 1),
            'has_template_1' => (bool) $editGelombang?->template_halaman_1,
            'has_template_2' => (bool) $editGelombang?->template_halaman_2,
            'template_siap_cetak' => old('template_siap_cetak', $editGelombang?->template_siap_cetak ?? true),
            'biaya_pendaftaran' => old('biaya_pendaftaran', $editGelombang?->biaya_pendaftaran ?? ''),
            'kuota' => old('kuota', $editGelombang?->kuota ?? ''),
            'pendaftaran_buka' => old('pendaftaran_buka', $editGelombang?->pendaftaran_buka?->format('Y-m-d') ?? ''),
            'pendaftaran_tutup' => old('pendaftaran_tutup', $editGelombang?->pendaftaran_tutup?->format('Y-m-d') ?? ''),
            'jadwal_tes_mulai' => old('jadwal_tes_mulai', $editGelombang?->jadwal_tes_mulai?->format('Y-m-d') ?? ''),
            'jadwal_tes_selesai' => old('jadwal_tes_selesai', $editGelombang?->jadwal_tes_selesai?->format('Y-m-d') ?? ''),
            'lokasi_tes' => old('lokasi_tes', $editGelombang?->lokasi_tes ?? ''),
            'nilai_lulus_minimal' => old('nilai_lulus_minimal', $editGelombang?->nilai_lulus_minimal ?? ''),
        ];
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.gelombangs.index', ['create' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $data = $this->mergeTemplates($request, $validated);

        if ($request->boolean('is_registration_open')) {
            $programId = (int) ($validated['program_id'] ?? 0);
            Gelombang::query()
                ->when($programId > 0, fn ($q) => $q->where('program_id', $programId))
                ->update(['is_registration_open' => false]);
        }

        $gelombang = Gelombang::create($data);
        $gelombang->ensureMaterisSeeded();
        $gelombang->ensureKomponenTesSeeded();

        return redirect()
            ->route('admin.gelombangs.index')
            ->with('success', 'Gelombang pendaftaran berhasil ditambahkan.');
    }

    public function edit(Gelombang $gelombang): RedirectResponse
    {
        return redirect()->route('admin.gelombangs.index', ['edit' => $gelombang->id]);
    }

    public function update(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $validated = $this->validated($request, $gelombang);

        $data = $this->mergeTemplates($request, $validated, $gelombang);

        if ($request->boolean('is_registration_open')) {
            $programId = (int) ($validated['program_id'] ?? $gelombang->program_id);
            Gelombang::query()
                ->where('id', '!=', $gelombang->id)
                ->where('program_id', $programId)
                ->update(['is_registration_open' => false]);
        }

        $gelombang->update($data);

        return redirect()
            ->route('admin.gelombangs.index')
            ->with('success', 'Gelombang pendaftaran berhasil diperbarui.');
    }

    public function destroy(Gelombang $gelombang): RedirectResponse
    {
        $usedCount = Santri::query()->where('gelombang_id', $gelombang->id)->count();

        if ($usedCount > 0) {
            return back()->with('error', "Gelombang tidak dapat dihapus karena masih dipakai {$usedCount} pendidik.");
        }

        $this->deleteTemplate($gelombang->template_halaman_1);
        $this->deleteTemplate($gelombang->template_halaman_2);
        $gelombang->delete();

        return redirect()
            ->route('admin.gelombangs.index')
            ->with('success', 'Gelombang berhasil dihapus.');
    }

    public function suggestHijri(Request $request): JsonResponse
    {
        $request->validate(['date' => ['required', 'date']]);

        try {
            $label = HijriDate::formatFromGregorian(
                Carbon::parse($request->date('date'))
            );
        } catch (\Throwable) {
            return response()->json(['hijriyah' => '']);
        }

        return response()->json(['hijriyah' => $label]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Gelombang $gelombang = null): array
    {
        return $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'nama' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'is_registration_open' => ['nullable', 'boolean'],
            'biaya_pendaftaran' => ['nullable', 'integer', 'min:0'],
            'kuota' => ['nullable', 'integer', 'min:1'],
            'pendaftaran_buka' => ['nullable', 'date'],
            'pendaftaran_tutup' => ['nullable', 'date', 'after_or_equal:pendaftaran_buka'],
            'jadwal_tes_mulai' => ['nullable', 'date'],
            'jadwal_tes_selesai' => ['nullable', 'date', 'after_or_equal:jadwal_tes_mulai'],
            'lokasi_tes' => ['nullable', 'string', 'max:255'],
            'nilai_lulus_minimal' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tanggal_sertifikasi_mulai' => ['required', 'date'],
            'tanggal_sertifikasi_selesai' => ['required', 'date', 'after_or_equal:tanggal_sertifikasi_mulai'],
            'tanggal_terbit_masehi' => ['required', 'date'],
            'tanggal_terbit_hijriyah' => ['required', 'string', 'max:255'],
            'kota_terbit' => ['required', 'string', 'max:255'],
            'kode_batch' => ['required', 'string', 'max:20'],
            'jenis_nomor' => ['required', 'string', 'max:20'],
            'nomor_urut_berikutnya' => ['required', 'integer', 'min:1'],
            'template_halaman_1' => [$gelombang ? 'nullable' : 'required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:10240'],
            'template_halaman_2' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'template_siap_cetak' => ['nullable', 'boolean'],
        ], [
            'nama.required' => 'Nama gelombang wajib diisi.',
            'program_id.required' => 'Program kegiatan wajib dipilih.',
            'tanggal_sertifikasi_mulai.required' => 'Tanggal mulai sertifikasi wajib diisi.',
            'tanggal_sertifikasi_selesai.required' => 'Tanggal selesai sertifikasi wajib diisi.',
            'tanggal_terbit_masehi.required' => 'Tanggal terbit (Masehi) wajib diisi.',
            'tanggal_terbit_hijriyah.required' => 'Tanggal terbit (Hijriyah) wajib diisi.',
            'kode_batch.required' => 'Kode batch nomor sertifikat wajib diisi.',
            'nomor_urut_berikutnya.required' => 'Nomor urut berikutnya wajib diisi.',
            'template_halaman_1.required' => 'Template halaman depan sertifikat wajib diunggah.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function mergeTemplates(Request $request, array $validated, ?Gelombang $gelombang = null): array
    {
        $data = [
            'program_id' => (int) $validated['program_id'],
            'nama' => $validated['nama'],
            'is_active' => $request->boolean('is_active', true),
            'is_registration_open' => $request->boolean('is_registration_open'),
            'biaya_pendaftaran' => isset($validated['biaya_pendaftaran']) ? (int) $validated['biaya_pendaftaran'] : null,
            'kuota' => isset($validated['kuota']) ? (int) $validated['kuota'] : null,
            'pendaftaran_buka' => $validated['pendaftaran_buka'] ?? null,
            'pendaftaran_tutup' => $validated['pendaftaran_tutup'] ?? null,
            'jadwal_tes_mulai' => $validated['jadwal_tes_mulai'] ?? null,
            'jadwal_tes_selesai' => $validated['jadwal_tes_selesai'] ?? null,
            'lokasi_tes' => $validated['lokasi_tes'] ?? null,
            'nilai_lulus_minimal' => $validated['nilai_lulus_minimal'] ?? null,
            'tanggal_sertifikasi_mulai' => $validated['tanggal_sertifikasi_mulai'],
            'tanggal_sertifikasi_selesai' => $validated['tanggal_sertifikasi_selesai'],
            'tanggal_terbit_masehi' => $validated['tanggal_terbit_masehi'],
            'tanggal_terbit_hijriyah' => trim($validated['tanggal_terbit_hijriyah']),
            'kota_terbit' => $validated['kota_terbit'],
            'kode_batch' => $validated['kode_batch'],
            'jenis_nomor' => $validated['jenis_nomor'],
            'nomor_urut_berikutnya' => (int) $validated['nomor_urut_berikutnya'],
            'template_siap_cetak' => $request->boolean('template_siap_cetak', true),
        ];

        if ($request->hasFile('template_halaman_1')) {
            if ($gelombang?->template_halaman_1) {
                $this->deleteTemplate($gelombang->template_halaman_1);
            }
            $data['template_halaman_1'] = $request->file('template_halaman_1')->store('sertifikat-template', 'public');
        } elseif ($gelombang) {
            $data['template_halaman_1'] = $gelombang->template_halaman_1;
        }

        if ($request->hasFile('template_halaman_2')) {
            if ($gelombang?->template_halaman_2) {
                $this->deleteTemplate($gelombang->template_halaman_2);
            }
            $data['template_halaman_2'] = $request->file('template_halaman_2')->store('sertifikat-template', 'public');
        } elseif ($gelombang) {
            $data['template_halaman_2'] = $gelombang->template_halaman_2;
        }

        return $data;
    }

    private function deleteTemplate(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
