<div id="reg-step-2" class="reg-step-panel" x-show="step === 2" x-cloak>
    <h2 class="reg-step-title">Alamat / RT-RW</h2>
    <p class="reg-step-desc mb-5">Alamat domisili dan wilayah administratif.</p>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="reg-field-icon md:col-span-2 reg-field-icon-textarea">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
            <label for="alamat" class="public-label">Alamat Lengkap (Jalan / RT-RW) *</label>
            <textarea id="alamat" name="alamat" rows="3" required class="public-input" placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('alamat') }}</textarea>
            @error('alamat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="provinsi_id" class="public-label">Provinsi *</label>
            <select id="provinsi_id" name="provinsi_id" required class="public-select">
                <option value="">— Pilih Provinsi —</option>
            </select>
            <input type="hidden" id="provinsi" name="provinsi" value="{{ old('provinsi') }}">
            @error('provinsi_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="kota_kab_id" class="public-label">Kota / Kabupaten *</label>
            <select id="kota_kab_id" name="kota_kab_id" required class="public-select" disabled>
                <option value="">— Pilih Kota/Kab —</option>
            </select>
            <input type="hidden" id="kota_kab" name="kota_kab" value="{{ old('kota_kab') }}">
            @error('kota_kab_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="kecamatan_id" class="public-label">Kecamatan *</label>
            <select id="kecamatan_id" name="kecamatan_id" required class="public-select" disabled>
                <option value="">— Pilih Kecamatan —</option>
            </select>
            <input type="hidden" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}">
            @error('kecamatan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="desa_id" class="public-label">Desa / Kelurahan *</label>
            <select id="desa_id" name="desa_id" required class="public-select" disabled>
                <option value="">— Pilih Desa/Kel —</option>
            </select>
            <input type="hidden" id="desa" name="desa" value="{{ old('desa') }}">
            @error('desa_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <script type="application/json" id="wilayah-old">@json($wilayahOld)</script>
</div>
