<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Support\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KartuPesertaController extends Controller
{
    public function show(Request $request, Santri $santri): View
    {
        $this->authorizeLookup($request, $santri);

        $santri->load('gelombang');

        return view('kartu-peserta.show', [
            'santri' => $santri,
            'backgroundPath' => DocumentTemplate::isPrintableBackground('kartu_ujian')
                ? DocumentTemplate::absolutePath('kartu_ujian')
                : null,
        ]);
    }

    public function adminShow(Santri $santri): View
    {
        $santri->load('gelombang');

        return view('kartu-peserta.show', [
            'santri' => $santri,
            'backgroundPath' => DocumentTemplate::isPrintableBackground('kartu_ujian')
                ? DocumentTemplate::absolutePath('kartu_ujian')
                : null,
        ]);
    }

    private function authorizeLookup(Request $request, Santri $santri): void
    {
        $request->validate([
            'tanggal_lahir' => ['required', 'date'],
        ]);

        if (! $santri->tanggal_lahir->isSameDay($request->date('tanggal_lahir'))) {
            abort(403, 'Data tidak valid.');
        }
    }
}
