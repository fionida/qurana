<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()
            ->where('status_pembayaran', 'lunas')
            ->latest('verified_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_pendaftaran', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lembaga')) {
            $query->where('lembaga', $request->lembaga);
        }

        return view('admin.certificates.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
        ]);
    }

    public function print(Santri $santri): Response
    {
        if (! $santri->isLunas()) {
            abort(403, 'Sertifikat hanya tersedia untuk santri yang sudah lunas.');
        }

        $santri->load('verifier');

        $fotoPath = public_path('storage/'.$santri->pas_foto);

        $pdf = Pdf::loadView('admin.sertifikat', [
            'santri' => $santri,
            'fotoPath' => file_exists($fotoPath) ? $fotoPath : null,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("sertifikat-{$santri->nomor_pendaftaran}.pdf");
    }
}
