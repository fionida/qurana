<div id="reg-step-6" class="reg-step-panel" x-show="step === 6" x-cloak>
    <h2 class="reg-step-title">Review & Konfirmasi</h2>
    <p class="reg-step-desc mb-5">Periksa kembali data sebelum mengirim.</p>
    <div class="space-y-3">
        <template x-for="row in reviewRows()" :key="row.label">
            <div class="reg-review-row">
                <div>
                    <p class="font-semibold text-slate-800" x-text="row.label"></p>
                    <p class="mt-0.5 text-xs text-slate-500" x-text="row.detail"></p>
                </div>
                <span class="shrink-0 text-xs font-bold" :class="row.ok ? 'text-emerald-600' : 'text-amber-600'" x-text="row.ok ? 'Lengkap' : 'Perlu diperiksa'"></span>
            </div>
        </template>
    </div>
    <label class="mt-6 flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/80 p-4">
        <input type="checkbox" name="pernyataan_benar" value="1" class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" x-model="declaration">
        <span class="text-sm text-slate-700">Saya menyatakan bahwa data yang saya isi benar, dapat dipertanggungjawabkan, dan bersedia mengikuti ketentuan panitia.</span>
    </label>
    @error('pernyataan_benar')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    <p class="mt-3 text-sm text-red-600" x-show="stepError" x-text="stepError"></p>
    <p class="mt-2 text-xs text-slate-400">Data Anda aman dan hanya digunakan untuk keperluan pendaftaran program ini.</p>
</div>
