<div id="reg-step-5" class="reg-step-panel" x-show="step === 5" x-cloak>
    <h2 class="reg-step-title">Metode Pembayaran</h2>
    <p class="mb-4 text-sm text-slate-600">Biaya pendaftaran: <strong class="text-emerald-700">Rp {{ number_format($biaya, 0, ',', '.') }}</strong></p>
    <div class="mb-5 max-w-md">
        <label class="public-label">Kode voucher (opsional)</label>
        <input type="text" name="kode_voucher" value="{{ old('kode_voucher') }}" class="public-input uppercase" placeholder="Contoh: QFI2026">
        @error('kode_voucher')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="space-y-3">
        <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500/20">
            <input type="radio" name="metode_pembayaran" value="transfer" @checked(old('metode_pembayaran', 'transfer') === 'transfer')
                class="mt-1 text-emerald-600 focus:ring-emerald-500" onchange="togglePayment()">
            <div>
                <span class="font-semibold text-slate-900">Transfer Bank</span>
                <p class="mt-1 text-sm text-slate-500">{{ $rekening['bank'] }} — <span class="font-mono">{{ $rekening['nomor'] }}</span></p>
                <p class="text-sm text-slate-500">a/n {{ $rekening['atas_nama'] }}</p>
            </div>
        </label>
        <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500/20">
            <input type="radio" name="metode_pembayaran" value="bayar_ditempat" @checked(old('metode_pembayaran') === 'bayar_ditempat')
                class="mt-1 text-emerald-600 focus:ring-emerald-500" onchange="togglePayment()">
            <div>
                <span class="font-semibold text-slate-900">Bayar di Tempat</span>
                <p class="mt-1 text-sm text-slate-500">Pembayaran langsung di kantor pendaftaran</p>
            </div>
        </label>
    </div>
    @error('metode_pembayaran')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    <div id="bukti-transfer-field" class="reg-upload-card mt-5" :class="{ 'reg-upload-card-has-file': files.bukti_transfer }">
        <p class="text-sm font-semibold text-slate-800">Bukti Transfer</p>
        <p class="mt-1 text-xs text-slate-500">Wajib jika memilih transfer · JPG/PNG maks. 5 MB</p>
        <p class="mt-2 text-xs font-medium text-emerald-700" x-show="files.bukti_transfer" x-text="files.bukti_transfer"></p>
        <input type="file" x-ref="fileBukti" name="bukti_transfer" accept="image/jpeg,image/jpg,image/png" class="hidden"
            @change="onFileChange($event, 'bukti_transfer')">
        <button type="button" class="public-btn-secondary mt-4 !text-xs" @click="triggerFile('fileBukti')">Pilih file</button>
        @error('bukti_transfer')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
