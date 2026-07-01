@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Dashboard" description="Rekap data pendaftaran santri Qurana" />

    <div class="admin-page-body admin-page-body--scroll">
        <div class="grid shrink-0 grid-cols-2 gap-4 lg:grid-cols-5">
            <x-admin.stat-card label="Total Pendaftar" :value="$stats['total']" color="slate">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Belum Lunas" :value="$stats['pending']" color="amber">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Lunas" :value="$stats['lunas']" color="emerald">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Transfer" :value="$stats['transfer']" color="blue">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.375M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Bayar di Tempat" :value="$stats['bayar_ditempat']" color="violet">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
        </div>

        <div class="admin-card mt-4 shrink-0">
            <div class="admin-card-header">
                <h3 class="font-semibold text-slate-900">Rekap per Lembaga</h3>
            </div>
            <div class="admin-card-body pt-0">
                @if ($lembagaStats->isNotEmpty())
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($lembagaStats as $item)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3">
                                <span class="text-sm font-medium text-slate-700">{{ $item->lembaga }}</span>
                                <span class="rounded-lg bg-white px-2.5 py-1 text-sm font-bold text-emerald-600 shadow-sm">{{ $item->total }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="py-3 text-sm text-slate-400">Belum ada data lembaga.</p>
                @endif
            </div>
        </div>

        @php
            $genderTotal = max(1, $genderStats['total']);
            $maleDeg = ($genderStats['L'] / $genderTotal) * 360;

            $paymentTotal = max(1, $stats['transfer'] + $stats['bayar_ditempat']);
            $transferDeg = ($stats['transfer'] / $paymentTotal) * 360;
        @endphp

        <div class="mt-4 grid shrink-0 grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-semibold text-slate-900">Diagram Jenis Kelamin</h3>
                </div>
                <div class="admin-card-body flex flex-col items-center gap-5 pt-0 sm:flex-row sm:items-start sm:justify-between">
                    <div class="rounded-full border border-slate-100 shadow-inner"
                        style="width:11rem; height:11rem; min-width:11rem; min-height:11rem; background: conic-gradient(#0ea5e9 0deg {{ $maleDeg }}deg, #f472b6 {{ $maleDeg }}deg 360deg);"></div>
                    <div class="w-full space-y-2 sm:max-w-[220px]">
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span>Laki-laki</span>
                            <span class="font-semibold text-slate-900">{{ $genderStats['L'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-pink-400"></span>Perempuan</span>
                            <span class="font-semibold text-slate-900">{{ $genderStats['P'] }}</span>
                        </div>
                        <div class="pt-1 text-xs text-slate-500">Total: {{ $genderStats['total'] }} pendaftar</div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="font-semibold text-slate-900">Diagram Metode Pembayaran</h3>
                </div>
                <div class="admin-card-body flex flex-col items-center gap-5 pt-0 sm:flex-row sm:items-start sm:justify-between">
                    <div class="rounded-full border border-slate-100 shadow-inner"
                        style="width:11rem; height:11rem; min-width:11rem; min-height:11rem; background: conic-gradient(#2563eb 0deg {{ $transferDeg }}deg, #8b5cf6 {{ $transferDeg }}deg 360deg);"></div>
                    <div class="w-full space-y-2 sm:max-w-[220px]">
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Transfer</span>
                            <span class="font-semibold text-slate-900">{{ $stats['transfer'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2 text-sm">
                            <span class="inline-flex items-center gap-2 text-slate-700"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>Bayar di Tempat</span>
                            <span class="font-semibold text-slate-900">{{ $stats['bayar_ditempat'] }}</span>
                        </div>
                        <div class="pt-1 text-xs text-slate-500">Total: {{ $stats['transfer'] + $stats['bayar_ditempat'] }} pembayaran</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
