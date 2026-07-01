@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="admin-page" x-data="{
    verifyOpen: {{ $verifyPayload ? 'true' : 'false' }},
    verify: @js($verifyPayload),
    openVerify(data) { this.verify = data; this.verifyOpen = true; }
}">
    <x-admin.page-header title="Verifikasi Pembayaran" description="Kelola dan verifikasi pembayaran santri" />

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <select name="status_pembayaran" class="admin-select sm:w-44">
                        <option value="pending" @selected(request('status_pembayaran', 'pending') === 'pending')>Pending</option>
                        <option value="lunas" @selected(request('status_pembayaran') === 'lunas')>Lunas</option>
                        <option value="" @selected(request('status_pembayaran') === '')>Semua</option>
                    </select>
                    <select name="metode_pembayaran" class="admin-select sm:w-48">
                        <option value="">Semua Metode</option>
                        <option value="transfer" @selected(request('metode_pembayaran') === 'transfer')>Transfer</option>
                        <option value="bayar_ditempat" @selected(request('metode_pembayaran') === 'bayar_ditempat')>Bayar di Tempat</option>
                    </select>
                    <button type="submit" class="admin-btn-primary">Filter</button>
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
                            <th>No. Daftar</th>
                            <th>Nama</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            <tr>
                                <td><span class="font-mono text-xs text-slate-500">{{ $santri->nomor_pendaftaran }}</span></td>
                                <td class="font-medium text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->metode_pembayaran_label }}</td>
                                <td>
                                    @if ($santri->bukti_transfer)
                                        <span class="admin-badge-success">Ada</span>
                                    @elseif ($santri->metode_pembayaran === 'transfer')
                                        <span class="admin-badge-warning">Belum upload</span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                                <td>
                                    <button type="button"
                                        @click="openVerify(@js(\App\Http\Controllers\Admin\PaymentController::modalPayload($santri)))"
                                        class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Verifikasi →</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-16 text-center text-slate-400">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>

    <x-admin.modal show="verifyOpen" title="Verifikasi Pembayaran" size="2xl">
        <template x-if="verify">
            <div>@include('admin.payments.partials.verify-modal-body')</div>
        </template>
    </x-admin.modal>
</div>
@endsection
