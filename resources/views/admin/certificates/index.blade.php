@extends('layouts.admin')

@section('title', 'Cetak Sertifikat')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Cetak Sertifikat" description="Pendidik lunas dapat dicetak sertifikat pendaftarannya" />

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor pendaftaran..." class="admin-input flex-1">
                    <select name="lembaga" class="admin-select sm:w-52">
                        <option value="">Semua Lembaga</option>
                        @foreach ($lembagaOptions as $lembaga)
                            <option value="{{ $lembaga }}" @selected(request('lembaga') === $lembaga)>{{ $lembaga }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="admin-btn-primary">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>No. Daftar</th>
                            <th>Nama</th>
                            <th>Lembaga</th>
                            <th>Diverifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            @php $fotoUrl = asset('storage/'.$santri->pas_foto); @endphp
                            <tr>
                                <td>
                                    <button type="button" onclick="openPhotoModal('{{ $fotoUrl }}', '{{ addslashes($santri->nama_lengkap) }}')"
                                        class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-slate-200 hover:ring-emerald-400">
                                        <img src="{{ $fotoUrl }}" alt="" class="h-full w-full object-cover">
                                    </button>
                                </td>
                                <td><span class="font-mono text-xs text-slate-500">{{ $santri->nomor_pendaftaran }}</span></td>
                                <td class="font-medium text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->lembaga }}</td>
                                <td class="text-slate-500">{{ $santri->verified_at?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.certificates.print', $santri) }}" target="_blank" class="admin-btn-primary !py-1.5 !text-xs">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V5.25A2.25 2.25 0 0 1 5.25 3h13.5A2.25 2.25 0 0 1 21 5.25v10.5A2.25 2.25 0 0 1 18.75 18h-1.09M6.34 18h11.31" /></svg>
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-16 text-center text-slate-400">Belum ada santri lunas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>
</div>

<x-photo-preview-modal />
@endsection
