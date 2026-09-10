<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatusCheckController extends Controller
{
    public function show(): View
    {
        return view('status-check.show');
    }

    public function lookup(Request $request): View
    {
        $validated = $request->validate([
            'nomor_pendaftaran' => ['required', 'string', 'max:32'],
            'tanggal_lahir' => ['required', 'date'],
        ], [
            'nomor_pendaftaran.required' => 'Nomor pendaftaran wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        ]);

        $santri = Santri::query()
            ->with('gelombang')
            ->where('nomor_pendaftaran', $validated['nomor_pendaftaran'])
            ->whereDate('tanggal_lahir', $validated['tanggal_lahir'])
            ->first();

        return view('status-check.show', [
            'santri' => $santri,
            'searched' => true,
            'input' => $validated,
        ]);
    }
}
