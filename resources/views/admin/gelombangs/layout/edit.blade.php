@extends('layouts.admin')

@section('title', 'Atur Posisi Sertifikat')

@section('content')
<style>
    .layout-tool-board {
        touch-action: none;
        isolation: isolate;
    }
    .layout-tool-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        object-fit: fill;
        display: block;
    }
    .layout-tool-layer {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }
    .layout-tool-layer .layout-tool-box {
        pointer-events: auto;
    }
    .layout-tool-box {
        position: absolute;
        min-height: 1.25rem;
        padding: 2px 4px;
        line-height: 1.25;
        font-weight: bold;
        color: #0f172a;
        border: 1px dashed rgb(59 130 246 / 0.55);
        background: rgb(96 165 250 / 0.12);
        cursor: move;
        user-select: none;
    }
    .layout-tool-box.is-selected {
        border: 2px solid #059669;
        background: rgb(16 185 129 / 0.15);
        box-shadow: 0 0 0 1px rgb(255 255 255 / 0.8);
    }
    .layout-tool-box:not(.is-selected):hover {
        border-color: rgb(37 99 235 / 0.85);
        background: rgb(96 165 250 / 0.2);
    }
    .layout-tool-label {
        position: absolute;
        left: 0;
        top: -1.35rem;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 10px;
        font-weight: 600;
        font-family: ui-sans-serif, system-ui, sans-serif;
        color: #fff;
        background: #059669;
        padding: 1px 6px;
        border-radius: 4px;
        pointer-events: none;
    }
    .layout-tool-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        margin: -5px 0 0 -5px;
        background: #fff;
        border: 2px solid #059669;
        border-radius: 2px;
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.15);
        z-index: 2;
    }
    .layout-tool-handle-n { top: 0; left: 50%; cursor: ns-resize; }
    .layout-tool-handle-s { top: 100%; left: 50%; cursor: ns-resize; }
    .layout-tool-handle-e { top: 50%; left: 100%; cursor: ew-resize; }
    .layout-tool-handle-w { top: 50%; left: 0; cursor: ew-resize; }
    .layout-tool-handle-ne { top: 0; left: 100%; cursor: nesw-resize; }
    .layout-tool-handle-nw { top: 0; left: 0; cursor: nwse-resize; }
    .layout-tool-handle-se { top: 100%; left: 100%; cursor: nwse-resize; }
    .layout-tool-handle-sw { top: 100%; left: 0; cursor: nesw-resize; }
    .layout-tool-field-row {
        cursor: pointer;
        display: block;
        width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.65rem;
        text-align: left;
        font-size: 12px;
        font-weight: 500;
        color: #475569;
        background: #fff;
        transition: border-color 0.15s, background 0.15s, color 0.15s;
    }
    .layout-tool-field-row.is-active {
        border-color: #059669;
        background: #ecfdf5;
        color: #047857;
    }
    .layout-tool-workspace {
        display: flex;
        min-height: 0;
        flex: 1 1 auto;
        flex-direction: column;
        gap: 1rem;
    }
    @media (min-width: 1024px) {
        .layout-tool-workspace {
            flex-direction: row;
            align-items: stretch;
        }
    }
</style>

<div class="admin-page" x-data="layoutEditor(@js($layoutH1), @js($fieldLabels), @js($samplesH1), @js(session('success')))">
    {{-- Toast sukses (dekat tombol Simpan) --}}
    <div x-show="saveNotice" x-cloak x-transition
        class="fixed bottom-6 right-6 z-[70] max-w-sm rounded-xl border border-emerald-200 bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20"
        role="status">
        <span x-text="saveNotice"></span>
    </div>

    <x-admin.page-header :title="'Posisi overlay — '.$gelombang->nama" description="Pratinjau di kiri, properti field di kanan — geser kotak atau ubah angka %.">
        <x-slot:actions>
            <a href="{{ route('admin.gelombangs.index') }}" class="admin-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if ($errors->any())
        <x-admin.alert type="error" class="shrink-0">
            {{ $errors->first() }}
        </x-admin.alert>
    @endif

    <div class="admin-page-body min-h-0 flex-1">
        <div class="layout-tool-workspace h-full min-h-[420px]">
            {{-- Pratinjau (utama) --}}
            <div class="admin-card flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden p-4 lg:min-h-[480px]">
                <div class="mb-2 flex shrink-0 flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-slate-700">Pratinjau sertifikat</p>
                    <p class="text-xs text-slate-500" x-show="active" x-cloak>
                        Field: <span class="font-semibold text-emerald-700" x-text="labels[active]"></span>
                    </p>
                </div>
                @unless ($templateReady)
                    <x-admin.alert type="error" class="mb-3 shrink-0">
                        Berkas template tidak ada di server. Edit gelombang dan unggah ulang <strong>Template halaman 1</strong>.
                    </x-admin.alert>
                @endunless
                @if ($templateIsPdf ?? false)
                    <x-admin.alert type="info" class="mb-3 shrink-0">
                        Template halaman depan berformat <strong>PDF vektor</strong>. Pratinjau gambar tidak ditampilkan di sini — gunakan <strong>Cetak sertifikat</strong> (FPDI) untuk melihat posisi overlay pada PDF asli. Atur kotak % seperti biasa; koordinat dicetak dalam mm (297×210).
                    </x-admin.alert>
                @endif
                <div class="flex min-h-0 flex-1 items-center justify-center overflow-auto rounded-lg border border-slate-200 bg-slate-100/80 p-3">
                    <div class="layout-tool-board relative w-full max-w-5xl select-none shadow-lg"
                        style="aspect-ratio: 297 / 210; background-color: #fff;"
                        x-ref="board"
                        :class="dragging ? 'cursor-grabbing' : ''"
                        @mousedown.self="active = null"
                        @mousemove.window="onDrag($event)"
                        @mouseup.window="endDrag()"
                        @mouseleave.window="endDrag()">
                        @if ($templatePreviewSrc)
                            <img src="{{ $templatePreviewSrc }}" alt="" class="layout-tool-bg" draggable="false" width="297" height="210">
                        @endif
                        <div class="layout-tool-layer">
                            <template x-for="(box, key) in h1" :key="'pv_'+key">
                                <div class="layout-tool-box overflow-hidden text-[10px] leading-tight"
                                    :class="active === key ? 'is-selected' : ''"
                                    :style="boxStyle(box, key)"
                                    @mousedown.stop.prevent="selectField(key); startInteraction(key, $event, 'move')">
                                    <span class="layout-tool-label" x-show="active === key" x-text="labels[key]"></span>
                                    <span x-text="sampleText(key)"></span>
                                    <template x-if="active === key">
                                        <div>
                                            <span class="layout-tool-handle layout-tool-handle-n" @mousedown.stop.prevent="startInteraction(key, $event, 'n')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-s" @mousedown.stop.prevent="startInteraction(key, $event, 's')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-e" @mousedown.stop.prevent="startInteraction(key, $event, 'e')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-w" @mousedown.stop.prevent="startInteraction(key, $event, 'w')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-ne" @mousedown.stop.prevent="startInteraction(key, $event, 'ne')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-nw" @mousedown.stop.prevent="startInteraction(key, $event, 'nw')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-se" @mousedown.stop.prevent="startInteraction(key, $event, 'se')"></span>
                                            <span class="layout-tool-handle layout-tool-handle-sw" @mousedown.stop.prevent="startInteraction(key, $event, 'sw')"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <p class="mt-2 shrink-0 text-xs text-slate-500">Geser kotak di template · tarik handle sudut/sisi · klik area kosong untuk lepas seleksi.</p>
            </div>

            {{-- Panel properti (samping kanan) --}}
            <div class="admin-card flex w-full shrink-0 flex-col overflow-hidden lg:w-80 xl:w-[22rem]">
                <div class="border-b border-slate-100 px-4 py-3">
                    <p class="text-sm font-semibold text-slate-800">Properti field</p>
                    <p class="text-xs text-slate-500">Pilih field, lalu edit angka atau geser di pratinjau.</p>
                </div>
                <form action="{{ route('admin.gelombangs.layout.update', $gelombang) }}" method="POST" class="flex min-h-0 flex-1 flex-col" @submit="onSaveSubmit">
                    @csrf
                    @method('PUT')
                    <div class="max-h-48 shrink-0 space-y-1 overflow-y-auto border-b border-slate-100 p-3 lg:max-h-none lg:flex-1">
                        <template x-for="(box, key) in h1" :key="'list_'+key">
                            <button type="button" class="layout-tool-field-row"
                                :class="active === key ? 'is-active' : ''"
                                @click="selectField(key)"
                                x-text="labels[key]"></button>
                        </template>
                    </div>
                    <div class="shrink-0 border-b border-slate-100 p-4">
                        <template x-for="(box, key) in h1" :key="'props_'+key">
                            <div x-show="active === key" x-cloak>
                                <p class="mb-3 text-xs font-semibold text-slate-800" x-text="labels[key]"></p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-[10px] text-slate-500">Top %</label>
                                        <input type="number" step="0.1" x-model.number="box.top_pct" class="admin-input !py-1.5 !text-xs">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-slate-500">Left %</label>
                                        <input type="number" step="0.1" x-model.number="box.left_pct" class="admin-input !py-1.5 !text-xs">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-slate-500">Lebar %</label>
                                        <input type="number" step="0.1" x-model.number="box.width_pct" class="admin-input !py-1.5 !text-xs">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-slate-500">Font pt</label>
                                        <input type="number" step="0.5" x-model.number="box.size" class="admin-input !py-1.5 !text-xs">
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-4 text-xs">
                                    <label class="flex items-center gap-1.5">
                                        <input type="radio" :name="'align_ui_'+key" value="left" @change="box.align='left'" :checked="box.align === 'left'"> Rata kiri
                                    </label>
                                    <label class="flex items-center gap-1.5">
                                        <input type="radio" :name="'align_ui_'+key" value="center" @change="box.align='center'" :checked="box.align === 'center'"> Tengah
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                    <template x-for="(box, key) in h1" :key="'submit_'+key">
                        <div class="hidden" aria-hidden="true">
                            <input type="hidden" :name="'halaman_1['+key+'][align]'" :value="box.align">
                            <input type="hidden" :name="'halaman_1['+key+'][top_pct]'" :value="box.top_pct">
                            <input type="hidden" :name="'halaman_1['+key+'][left_pct]'" :value="box.left_pct">
                            <input type="hidden" :name="'halaman_1['+key+'][width_pct]'" :value="box.width_pct">
                            <input type="hidden" :name="'halaman_1['+key+'][size]'" :value="box.size">
                        </div>
                    </template>
                    <div class="mt-auto shrink-0 space-y-2 border-t border-slate-100 p-4">
                        <button type="submit" class="admin-btn-primary w-full" :disabled="saving">
                            <span x-show="!saving">Simpan posisi</span>
                            <span x-show="saving" x-cloak>Menyimpan…</span>
                        </button>
                        <button type="button" class="admin-btn-secondary w-full !text-xs"
                            onclick="if (confirm('Reset ke posisi default?')) document.getElementById('layout-reset-form').submit()">
                            Reset default
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="layout-reset-form" action="{{ route('admin.gelombangs.layout.reset', $gelombang) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection
