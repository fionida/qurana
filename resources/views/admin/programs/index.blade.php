@extends('layouts.admin')

@section('title', 'Program kegiatan')

@section('content')
@php
    $editData = [
        'id' => old('_program_id', $editProgram?->id),
        'nama' => old('nama', $editProgram?->nama ?? ''),
        'judul_sertifikat' => old('judul_sertifikat', $editProgram?->judul_sertifikat ?? ''),
        'tagline' => old('tagline', $editProgram?->tagline ?? ''),
        'deskripsi' => old('deskripsi', $editProgram?->deskripsi ?? ''),
        'urutan' => old('urutan', $editProgram?->urutan ?? 0),
        'is_active' => old('is_active', $editProgram?->is_active ?? true),
        'butuh_seleksi_tes' => old('butuh_seleksi_tes', $editProgram?->butuh_seleksi_tes ?? true),
        'butuh_kelulusan' => old('butuh_kelulusan', $editProgram?->butuh_kelulusan ?? true),
        'butuh_sertifikat_resmi' => old('butuh_sertifikat_resmi', $editProgram?->butuh_sertifikat_resmi ?? true),
    ];
@endphp

<div class="admin-page" x-data="{
    createOpen: {{ ($openCreate ?? false) ? 'true' : 'false' }},
    editOpen: {{ ($openEdit ?? false) ? 'true' : 'false' }},
    editForm: @js($editData),
    openEdit(p) { this.editForm = p; this.editOpen = true; }
}">
    <x-admin.page-header title="Program kegiatan" description="Portal multi-kegiatan: sertifikasi, pelatihan, dan program lain — masing-masing punya gelombang & alur sendiri">
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah program
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Alur</th>
                            <th>Portal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                            <tr>
                                <td>
                                    <p class="font-medium text-slate-900">{{ $program->nama }}</p>
                                    <p class="text-xs text-slate-500">{{ $program->slug }}</p>
                                </td>
                                <td class="max-w-xs text-xs text-slate-600">{{ $program->alurRingkasLabel() }}</td>
                                <td>
                                    <a href="{{ route('portal.program.show', $program) }}" target="_blank" class="text-sm font-medium text-sky-600 hover:text-sky-700">Lihat</a>
                                </td>
                                <td><x-admin.badge :status="$program->is_active ? 'active' : 'inactive'" /></td>
                                <td>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('admin.programs.penandatangan.index', $program) }}" class="text-sm font-medium text-violet-600 hover:text-violet-700">Penandatangan</a>
                                        <button type="button"
                                            @click="openEdit({
                                                id: {{ $program->id }},
                                                nama: @js($program->nama),
                                                judul_sertifikat: @js($program->judul_sertifikat),
                                                tagline: @js($program->tagline),
                                                deskripsi: @js($program->deskripsi),
                                                urutan: {{ $program->urutan }},
                                                is_active: {{ $program->is_active ? 'true' : 'false' }},
                                                butuh_seleksi_tes: {{ $program->butuh_seleksi_tes ? 'true' : 'false' }},
                                                butuh_kelulusan: {{ $program->butuh_kelulusan ? 'true' : 'false' }},
                                                butuh_sertifikat_resmi: {{ $program->butuh_sertifikat_resmi ? 'true' : 'false' }},
                                            })"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Edit</button>
                                        <form x-ref="deleteForm{{ $program->id }}" action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus program '.$program->nama.'?'), $refs.deleteForm{{ $program->id }}, { title: 'Hapus Program', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-16 text-center text-slate-400">Belum ada program</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($programs->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $programs->links() }}</div>
            @endif
        </div>
    </div>

    @include('admin.programs.partials.form-modal', ['mode' => 'create', 'show' => 'createOpen'])
    @include('admin.programs.partials.form-modal', ['mode' => 'edit', 'show' => 'editOpen'])
</div>
@endsection
