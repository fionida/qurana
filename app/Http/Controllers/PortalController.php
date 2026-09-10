<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        $programs = Program::query()
            ->activeOrdered()
            ->get();

        $hasOpenRegistration = $programs->contains(
            fn (Program $program) => $program->openGelombangForRegistration() !== null
        );

        return view('portal.index', [
            'programs' => $programs,
            'hasOpenRegistration' => $hasOpenRegistration,
        ]);
    }

    public function show(Program $program): View
    {
        if (! $program->is_active) {
            abort(404);
        }

        $gelombang = $program->openGelombangForRegistration();

        return view('portal.program', [
            'program' => $program,
            'gelombang' => $gelombang,
        ]);
    }
}
