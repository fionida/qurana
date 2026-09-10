<?php

namespace App\Support;

use App\Models\Gelombang;
use App\Models\PembayaranRiwayat;
use App\Models\Program;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class SantriReportScope
{
    /**
     * @param  Builder<Santri>  $query
     */
    public static function apply(Builder $query, Request $request): void
    {
        if ($request->filled('gelombang')) {
            $query->where('gelombang_id', $request->integer('gelombang'));

            return;
        }

        if ($request->filled('program')) {
            $programId = $request->integer('program');
            $query->whereHas('gelombang', fn (Builder $q) => $q->where('program_id', $programId));
        }
    }

    /**
     * @param  Builder<PembayaranRiwayat>  $query
     */
    public static function applyToPembayaranRiwayat(Builder $query, Request $request): void
    {
        if ($request->filled('gelombang')) {
            $query->whereHas('santri', fn (Builder $q) => $q->where('gelombang_id', $request->integer('gelombang')));

            return;
        }

        if ($request->filled('program')) {
            $programId = $request->integer('program');
            $query->whereHas('santri.gelombang', fn (Builder $q) => $q->where('program_id', $programId));
        }
    }

    /**
     * @return array{programOptions: Collection, gelombangOptions: Collection, selectedProgram: ?Program, selectedGelombang: ?Gelombang}
     */
    public static function filterOptions(Request $request): array
    {
        $programId = $request->filled('program') ? $request->integer('program') : null;

        $gelombangQuery = Gelombang::query()
            ->with('program')
            ->orderByDesc('id');

        if ($programId) {
            $gelombangQuery->where('program_id', $programId);
        }

        return [
            'programOptions' => Program::query()->orderBy('urutan')->orderBy('nama')->get(),
            'gelombangOptions' => $gelombangQuery->get(),
            'selectedProgram' => $programId ? Program::find($programId) : null,
            'selectedGelombang' => $request->filled('gelombang')
                ? Gelombang::with('program')->find($request->integer('gelombang'))
                : null,
        ];
    }
}
