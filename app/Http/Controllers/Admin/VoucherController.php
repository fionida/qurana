<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoucherController extends Controller
{
    public function index(Request $request): View
    {
        $editVoucher = $request->filled('edit') ? Voucher::find($request->edit) : null;

        return view('admin.vouchers.index', [
            'vouchers' => Voucher::query()->with('gelombang')->orderByDesc('id')->paginate(15)->withQueryString(),
            'gelombangOptions' => Gelombang::query()->orderByDesc('id')->get(),
            'editVoucher' => $editVoucher,
            'openCreate' => $request->boolean('create') || old('_modal') === 'create',
            'openEdit' => $editVoucher || old('_modal') === 'edit',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['kode'] = strtoupper($validated['kode']);

        Voucher::create($this->mergeActive($request, $validated));

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function update(Request $request, Voucher $voucher): RedirectResponse
    {
        $validated = $this->validated($request, $voucher);
        $validated['kode'] = strtoupper($validated['kode']);

        $voucher->update($this->mergeActive($request, $validated));

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        if ($voucher->santris()->exists()) {
            return back()->with('error', 'Voucher sudah dipakai pendaftar dan tidak dapat dihapus.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Voucher $voucher = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:32', Rule::unique('vouchers', 'kode')->ignore($voucher?->id)],
            'tipe' => ['required', Rule::in(['persen', 'nominal'])],
            'nilai' => ['required', 'integer', 'min:1'],
            'gelombang_id' => ['nullable', 'exists:gelombangs,id'],
            'maks_pakai' => ['nullable', 'integer', 'min:1'],
            'berlaku_sampai' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function mergeActive(Request $request, array $validated): array
    {
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['gelombang_id'] = $validated['gelombang_id'] ?? null;

        return $validated;
    }
}
