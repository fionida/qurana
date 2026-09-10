@extends('layouts.admin')

@section('title', 'Gelombang Pendaftaran')

@section('content')
<div class="admin-page" x-data="gelombangIndexPage(@js([
    'createOpen' => (bool) ($openCreate ?? false),
    'editOpen' => (bool) ($openEdit ?? false),
    'editForm' => $editForm,
    'suggestHijriUrl' => $suggestHijriUrl,
]))">
    <x-admin.page-header title="Gelombang Pendaftaran" description="Atur periode sertifikasi, nomor urut sertifikat, dan template cetak (TTD & stempel pada gambar template)">
        @if (auth()->user()->isAdmin())
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Gelombang
            </button>
        </x-slot:actions>
        @endif
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Program / Gelombang</th>
                            <th>Sertifikasi</th>
                            <th>Terbit</th>
                            <th>Nomor berikutnya</th>
                            <th>Pendaftaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gelombangs as $gelombang)
                            <tr>
                                <td>
                                    <p class="text-xs font-medium uppercase tracking-wide text-emerald-700">{{ $gelombang->program?->nama ?? '—' }}</p>
                                    <p class="font-medium text-slate-900">{{ $gelombang->nama }}</p>
                                    <p class="text-xs text-slate-500">Kode {{ $gelombang->kode_batch }} · {{ $gelombang->jenis_nomor }}</p>
                                </td>
                                <td class="text-sm text-slate-600">{{ $gelombang->tanggalSertifikasiLabel() }}</td>
                                <td class="text-sm text-slate-600">
                                    {{ $gelombang->tanggalTerbitMasehiLabel() }}<br>
                                    <span class="text-xs">{{ $gelombang->tanggal_terbit_hijriyah }}</span>
                                </td>
                                <td><span class="font-mono text-sm font-semibold text-emerald-700">{{ $gelombang->nomor_urut_berikutnya }}</span></td>
                                <td>
                                    @if ($gelombang->is_registration_open)
                                        <x-admin.badge status="active" />
                                        <span class="ml-1 text-xs text-slate-500">Form terbuka</span>
                                    @else
                                        <x-admin.badge status="inactive" />
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('admin.gelombangs.layout.edit', $gelombang) }}" class="text-sm font-medium text-sky-600 hover:text-sky-700" title="Geser posisi teks dinamis sertifikat">Atur posisi cetak</a>
                                        <a href="{{ route('admin.gelombangs.materi.edit', $gelombang) }}" class="text-sm font-medium text-violet-600 hover:text-violet-700">Materi</a>
                                        @if (auth()->user()->canAccessAdminModule('tes'))
                                        <a href="{{ route('admin.gelombangs.komponen-tes.edit', $gelombang) }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">Komponen tes</a>
                                        @endif
                                        @if (auth()->user()->isAdmin())
                                        <button type="button"
                                            @click="openEdit({
                                                id: {{ $gelombang->id }},
                                                program_id: {{ $gelombang->program_id ?? 'null' }},
                                                nama: @js($gelombang->nama),
                                                is_active: {{ $gelombang->is_active ? 'true' : 'false' }},
                                                is_registration_open: {{ $gelombang->is_registration_open ? 'true' : 'false' }},
                                                tanggal_sertifikasi_mulai: @js($gelombang->tanggal_sertifikasi_mulai->format('Y-m-d')),
                                                tanggal_sertifikasi_selesai: @js($gelombang->tanggal_sertifikasi_selesai->format('Y-m-d')),
                                                tanggal_terbit_masehi: @js($gelombang->tanggal_terbit_masehi->format('Y-m-d')),
                                                tanggal_terbit_hijriyah: @js($gelombang->tanggal_terbit_hijriyah),
                                                kota_terbit: @js($gelombang->kota_terbit),
                                                kode_batch: @js($gelombang->kode_batch),
                                                jenis_nomor: @js($gelombang->jenis_nomor),
                                                nomor_urut_berikutnya: {{ $gelombang->nomor_urut_berikutnya }},
                                                has_template_1: {{ $gelombang->template_halaman_1 ? 'true' : 'false' }},
                                                has_template_2: {{ $gelombang->template_halaman_2 ? 'true' : 'false' }},
                                                template_siap_cetak: {{ $gelombang->template_siap_cetak ? 'true' : 'false' }},
                                                biaya_pendaftaran: @js($gelombang->biaya_pendaftaran),
                                                kuota: @js($gelombang->kuota),
                                                pendaftaran_buka: @js($gelombang->pendaftaran_buka?->format('Y-m-d')),
                                                pendaftaran_tutup: @js($gelombang->pendaftaran_tutup?->format('Y-m-d')),
                                                jadwal_tes_mulai: @js($gelombang->jadwal_tes_mulai?->format('Y-m-d')),
                                                jadwal_tes_selesai: @js($gelombang->jadwal_tes_selesai?->format('Y-m-d')),
                                                lokasi_tes: @js($gelombang->lokasi_tes),
                                                nilai_lulus_minimal: @js($gelombang->nilai_lulus_minimal),
                                            })"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Edit</button>
                                        <form x-ref="deleteForm{{ $gelombang->id }}" action="{{ route('admin.gelombangs.destroy', $gelombang) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus gelombang '.$gelombang->nama.'?'), $refs.deleteForm{{ $gelombang->id }}, { title: 'Hapus Gelombang', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-16 text-center text-slate-400">Belum ada gelombang pendaftaran</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($gelombangs->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $gelombangs->links() }}</div>
            @endif
        </div>
    </div>

    @if (auth()->user()->isAdmin())
    @include('admin.gelombangs.partials.form-modal', ['mode' => 'create', 'show' => 'createOpen', 'formPrefix' => 'create'])
    @include('admin.gelombangs.partials.form-modal', ['mode' => 'edit', 'show' => 'editOpen', 'formPrefix' => 'edit'])
    @endif
</div>
@endsection
