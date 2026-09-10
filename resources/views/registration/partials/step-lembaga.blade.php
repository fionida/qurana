<div id="reg-step-3" class="reg-step-panel" x-show="step === 3" x-cloak>
    <h2 class="reg-step-title">Informasi Lembaga Asal</h2>
    <p class="reg-step-desc mb-5">Lembaga tempat Anda berasal / aktif mengajar.</p>
    <div class="space-y-5">
        <div class="reg-field-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6v11.25A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75V9Z" /></svg>
            <label for="lembaga" class="public-label">Nama Lembaga *</label>
            <input type="text" id="lembaga" name="lembaga" value="{{ old('lembaga') }}" required class="public-input" placeholder="Contoh: TPQ Al Hidayah">
            @error('lembaga')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="reg-upload-card" :class="{ 'reg-upload-card-has-file': files.dokumen_surat_rekomendasi }">
            <p class="text-sm font-semibold text-slate-800">Surat Rekomendasi <span class="font-normal text-slate-500">(opsional)</span></p>
            <p class="mt-1 text-xs text-slate-500">JPG, PNG, atau PDF — maks. 5 MB</p>
            <p class="mt-2 text-xs font-medium text-emerald-700" x-show="files.dokumen_surat_rekomendasi" x-text="files.dokumen_surat_rekomendasi"></p>
            <input type="file" x-ref="fileSurat" name="dokumen_surat_rekomendasi" accept="image/jpeg,image/jpg,image/png,application/pdf" class="hidden"
                @change="onFileChange($event, 'dokumen_surat_rekomendasi')">
            <button type="button" class="public-btn-secondary mt-4 !text-xs" @click="triggerFile('fileSurat')">Pilih file</button>
            @error('dokumen_surat_rekomendasi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
