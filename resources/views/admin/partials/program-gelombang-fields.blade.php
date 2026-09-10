<div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
    <div>
        <label class="admin-label !mb-1 !normal-case !tracking-normal">Program</label>
        <select name="program" class="admin-select w-full !py-2 text-sm">
            <option value="">Semua program</option>
            @foreach ($programOptions as $program)
                <option value="{{ $program->id }}" @selected(($selectedProgram?->id ?? null) === $program->id)>{{ $program->nama }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="admin-label !mb-1 !normal-case !tracking-normal">Gelombang</label>
        <select name="gelombang" class="admin-select w-full !py-2 text-sm">
            <option value="">{{ ($selectedProgram ?? null) ? 'Semua gelombang program ini' : 'Semua gelombang' }}</option>
            @foreach ($gelombangOptions as $g)
                <option value="{{ $g->id }}" @selected(($selectedGelombang?->id ?? null) === $g->id)>
                    {{ $g->nama }}@if (! $selectedProgram && $g->program) ({{ $g->program->nama }})@endif
                </option>
            @endforeach
        </select>
    </div>
</div>
