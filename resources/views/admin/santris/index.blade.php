@extends('layouts.admin')

@section('title', 'Database Pendidik')

@section('content')
<div class="admin-page" x-data="{
    detailOpen: {{ $detailPayload ? 'true' : 'false' }},
    detail: @js($detailPayload),
    photoEditOpen: false,
    photoEditAction: '',
    photoEditName: '',
    photoEditPreview: '',
    openDetail(data) { this.detail = data; this.detailOpen = true; },
    openPhotoEdit(action, name, currentUrl) {
        this.photoEditAction = action;
        this.photoEditName = name;
        this.photoEditPreview = currentUrl;
        this.photoEditOpen = true;
        this.$nextTick(() => {
            if (this.$refs.photoEditInput) this.$refs.photoEditInput.value = '';
        });
    },
    onPhotoPicked(event) {
        const file = event.target.files?.[0];
        if (!file) return;
        this.photoEditPreview = URL.createObjectURL(file);
    }
}">
    <x-admin.page-header title="Database Pendidik" description="Daftar lengkap pendidik yang sudah mendaftar">
        <x-slot:actions>
            <a href="{{ route('admin.payments.index') }}" class="admin-btn-secondary">Verifikasi Bayar</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor, WA, email..." class="admin-input flex-1">
                    <select name="lembaga" class="admin-select sm:w-48">
                        <option value="">Semua Lembaga</option>
                        @foreach ($lembagaOptions as $lembaga)
                            <option value="{{ $lembaga }}" @selected(request('lembaga') === $lembaga)>{{ $lembaga }}</option>
                        @endforeach
                    </select>
                    <select name="status_pembayaran" class="admin-select sm:w-40">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status_pembayaran') === 'pending')>Pending</option>
                        <option value="lunas" @selected(request('status_pembayaran') === 'lunas')>Lunas</option>
                    </select>
                    <button type="submit" class="admin-btn-primary sm:w-auto">Cari</button>
                </form>
                @error('pas_foto')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                            <th>Nama Lengkap</th>
                            <th>Lembaga</th>
                            <th>JK</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            @php $fotoUrl = asset('storage/'.$santri->pas_foto); @endphp
                            <tr>
                                <td>
                                    <button type="button" onclick="openPhotoModal('{{ $fotoUrl }}', '{{ addslashes($santri->nama_lengkap) }}')"
                                        class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-slate-200 transition hover:ring-emerald-400">
                                        <img src="{{ $fotoUrl }}" alt="" class="h-full w-full object-cover">
                                    </button>
                                </td>
                                <td><span class="font-mono text-xs text-slate-500">{{ $santri->nomor_pendaftaran }}</span></td>
                                <td class="font-semibold text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->lembaga }}</td>
                                <td>{{ $santri->jenis_kelamin_label }}</td>
                                <td class="text-xs">
                                    @if ($santri->no_wa)<div class="text-slate-700">{{ $santri->no_wa }}</div>@endif
                                    @if ($santri->email)<div class="text-slate-400">{{ $santri->email }}</div>@endif
                                </td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button"
                                            @click="openDetail(@js(\App\Http\Controllers\Admin\SantriController::modalPayload($santri)))"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Detail</button>
                                        @if ($santri->isLunas())
                                            <a href="{{ route('admin.payments.kwitansi', $santri) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700">Kwitansi</a>
                                            <a href="{{ route('admin.certificates.print', $santri) }}" target="_blank" class="text-sm font-medium text-violet-600 hover:text-violet-700">Sertifikat</a>
                                        @endif
                                        <button type="button"
                                            @click="openPhotoEdit(@js(route('admin.santris.update-photo', $santri)), @js($santri->nama_lengkap), @js($fotoUrl))"
                                            class="text-sm font-medium text-amber-600 hover:text-amber-700">
                                            Ganti Foto
                                        </button>
                                        <form x-ref="deleteForm{{ $santri->id }}" action="{{ route('admin.santris.destroy', $santri) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus data '.$santri->nama_lengkap.'?'), $refs.deleteForm{{ $santri->id }}, { title: 'Hapus Santri', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-16 text-center"><p class="text-slate-400">Belum ada data santri</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>

    <x-admin.modal show="detailOpen" title="Detail Santri" size="2xl">
        <template x-if="detail">
            <div>@include('admin.santris.partials.detail-modal-body')</div>
        </template>
    </x-admin.modal>

    <x-admin.modal show="photoEditOpen" title="Ganti Pas Foto">
        <form x-bind:action="photoEditAction" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <p class="text-sm text-slate-600">
                Pendaftar:
                <span class="font-semibold text-slate-900" x-text="photoEditName"></span>
            </p>

            <template x-if="photoEditPreview">
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                    <img :src="photoEditPreview" alt="Preview pas foto" class="mx-auto h-44 w-36 rounded-lg object-cover ring-2 ring-slate-200">
                </div>
            </template>

            <div>
                <label class="admin-label">Pilih Foto Baru</label>
                <input x-ref="photoEditInput" type="file" name="pas_foto" accept="image/jpeg,image/jpg,image/png" required
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
                    @change="onPhotoPicked($event)">
                <p class="mt-1 text-xs text-slate-400">JPG/PNG, maksimal 2 MB.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="photoEditOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan Foto</button>
            </div>
        </form>
    </x-admin.modal>
</div>

<x-photo-preview-modal />
@endsection
