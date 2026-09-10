@extends('layouts.admin')

@section('title', 'Voucher')

@section('content')
@php
    $editData = [
        'id' => old('_voucher_id', $editVoucher?->id),
        'kode' => old('kode', $editVoucher?->kode ?? ''),
        'tipe' => old('tipe', $editVoucher?->tipe ?? 'nominal'),
        'nilai' => old('nilai', $editVoucher?->nilai ?? ''),
        'gelombang_id' => old('gelombang_id', $editVoucher?->gelombang_id ?? ''),
        'maks_pakai' => old('maks_pakai', $editVoucher?->maks_pakai ?? ''),
        'berlaku_sampai' => old('berlaku_sampai', $editVoucher?->berlaku_sampai?->format('Y-m-d') ?? ''),
        'is_active' => old('is_active', $editVoucher?->is_active ?? true),
    ];
@endphp

<div class="admin-page" x-data="{
    createOpen: {{ ($openCreate ?? false) ? 'true' : 'false' }},
    editOpen: {{ ($openEdit ?? false) ? 'true' : 'false' }},
    editForm: @js($editData),
    openEdit(v) { this.editForm = v; this.editOpen = true; }
}">
    <x-admin.page-header title="Voucher & diskon" description="Kode diskon untuk form pendaftaran (opsional)">
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">Tambah voucher</button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Kode</th><th>Diskon</th><th>Gelombang</th><th>Pakai</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($vouchers as $v)
                        <tr>
                            <td class="font-mono font-semibold">{{ $v->kode }}</td>
                            <td>{{ $v->labelDiskon() }}</td>
                            <td class="text-sm">{{ $v->gelombang?->nama ?? 'Semua' }}</td>
                            <td class="text-sm">{{ $v->terpakai }}@if($v->maks_pakai)/{{ $v->maks_pakai }}@endif</td>
                            <td>@if($v->is_active)<x-admin.badge status="active" />@else<x-admin.badge status="inactive" />@endif</td>
                            <td class="text-right">
                                <button type="button" @click="openEdit({
                                    id: {{ $v->id }},
                                    kode: @js($v->kode),
                                    tipe: @js($v->tipe),
                                    nilai: {{ $v->nilai }},
                                    gelombang_id: @js($v->gelombang_id),
                                    maks_pakai: @js($v->maks_pakai),
                                    berlaku_sampai: @js($v->berlaku_sampai?->format('Y-m-d')),
                                    is_active: {{ $v->is_active ? 'true' : 'false' }},
                                })" class="text-sm text-emerald-600">Edit</button>
                                <form x-ref="del{{ $v->id }}" action="{{ route('admin.vouchers.destroy', $v) }}" method="POST" class="inline ml-2">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="askConfirm('Hapus voucher?', $refs.del{{ $v->id }})" class="text-sm text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-slate-400">Belum ada voucher</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($vouchers->hasPages())<div class="border-t px-5 py-4">{{ $vouchers->links() }}</div>@endif
    </div>

    @include('admin.vouchers.partials.form-modal', ['mode' => 'create', 'show' => 'createOpen'])
    @include('admin.vouchers.partials.form-modal', ['mode' => 'edit', 'show' => 'editOpen'])
</div>
@endsection
