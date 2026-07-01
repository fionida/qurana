<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekeningController extends Controller
{
    public function edit(): View
    {
        return view('admin.rekening.edit', [
            'rekening' => Setting::rekening(),
            'biaya' => Setting::biayaPendaftaran(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank' => ['required', 'string', 'max:255'],
            'nomor' => ['required', 'string', 'max:50'],
            'atas_nama' => ['required', 'string', 'max:255'],
            'biaya_pendaftaran' => ['required', 'integer', 'min:0'],
        ], [
            'bank.required' => 'Nama bank wajib diisi.',
            'nomor.required' => 'Nomor rekening wajib diisi.',
            'atas_nama.required' => 'Atas nama wajib diisi.',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi.',
            'biaya_pendaftaran.integer' => 'Biaya pendaftaran harus berupa angka.',
            'biaya_pendaftaran.min' => 'Biaya pendaftaran tidak boleh kurang dari 0.',
        ]);

        Setting::set('rekening_bank', $validated['bank']);
        Setting::set('rekening_nomor', $validated['nomor']);
        Setting::set('rekening_atas_nama', $validated['atas_nama']);
        Setting::set('biaya_pendaftaran', (string) $validated['biaya_pendaftaran']);

        return back()->with('success', 'Pengaturan rekening dan biaya pendaftaran berhasil diperbarui.');
    }
}
