<div id="reg-step-4" class="reg-step-panel" x-show="step === 4" x-cloak>
    <h2 class="reg-step-title">Dokumen Pendukung</h2>
    <p class="reg-step-desc mb-5">Unggah pas foto dan identitas jika ada.</p>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="reg-upload-card" :class="{ 'reg-upload-card-has-file': files.pas_foto }">
            <p class="text-sm font-semibold text-slate-800">Pas Foto *</p>
            <p class="mt-1 text-xs text-slate-500">JPG/PNG, maks. 2 MB · 4×6, background merah</p>
            <p class="mt-2 text-xs font-medium text-emerald-700" x-show="files.pas_foto" x-text="files.pas_foto"></p>
            <input type="file" x-ref="filePasFoto" id="pas_foto" name="pas_foto" accept="image/jpeg,image/jpg,image/png" required class="hidden"
                @change="onFileChange($event, 'pas_foto')">
            <button type="button" class="public-btn-primary mt-4 !text-xs" @click="triggerFile('filePasFoto')">Pilih file</button>
            @error('pas_foto')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="reg-upload-card" :class="{ 'reg-upload-card-has-file': files.dokumen_ktp }">
            <p class="text-sm font-semibold text-slate-800">KTP / Identitas</p>
            <p class="mt-1 text-xs text-slate-500">Opsional · JPG, PNG, PDF</p>
            <p class="mt-2 text-xs font-medium text-emerald-700" x-show="files.dokumen_ktp" x-text="files.dokumen_ktp"></p>
            <input type="file" x-ref="fileKtp" name="dokumen_ktp" accept="image/jpeg,image/jpg,image/png,application/pdf" class="hidden"
                @change="onFileChange($event, 'dokumen_ktp')">
            <button type="button" class="public-btn-secondary mt-4 !text-xs" @click="triggerFile('fileKtp')">Pilih file</button>
            @error('dokumen_ktp')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
