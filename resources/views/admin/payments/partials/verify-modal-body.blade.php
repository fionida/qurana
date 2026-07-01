<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="space-y-4">
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between border-b border-slate-50 pb-2"><dt class="text-slate-500">Nama</dt><dd class="font-medium text-slate-900" x-text="verify.nama_lengkap"></dd></div>
            <div class="flex justify-between border-b border-slate-50 pb-2"><dt class="text-slate-500">Lembaga</dt><dd class="font-medium text-slate-900" x-text="verify.lembaga"></dd></div>
            <div class="flex justify-between border-b border-slate-50 pb-2"><dt class="text-slate-500">Metode</dt><dd class="font-medium text-slate-900" x-text="verify.metode_pembayaran_label"></dd></div>
            <div class="flex justify-between border-b border-slate-50 pb-2"><dt class="text-slate-500">Status</dt><dd class="font-medium text-slate-900" x-text="verify.status_pembayaran_label"></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Biaya</dt><dd class="text-lg font-bold text-emerald-600" x-text="verify.biaya_formatted"></dd></div>
        </dl>
        <template x-if="!verify.is_lunas">
            <form x-ref="verifyForm" :action="verify.verify_url" method="POST">
                @csrf
                <button type="button"
                    @click="askConfirm('Verifikasi pembayaran sebagai lunas?', $refs.verifyForm, { title: 'Verifikasi Pembayaran', confirmText: 'Ya, verifikasi lunas' })"
                    class="admin-btn-primary w-full !py-3">
                    Verifikasi Lunas
                </button>
            </form>
        </template>
        <template x-if="verify.is_lunas">
            <div class="space-y-3">
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" x-text="verify.verified_info"></div>
                <div class="flex gap-3">
                    <a :href="verify.kwitansi_url" target="_blank" class="admin-btn-secondary flex-1 text-center">Kwitansi</a>
                    <a :href="verify.sertifikat_url" target="_blank" class="admin-btn-primary flex-1 text-center">Sertifikat</a>
                </div>
            </div>
        </template>
    </div>
    <div class="space-y-4">
        <template x-if="verify.metode_pembayaran === 'transfer'">
            <div>
                <p class="admin-label !normal-case !tracking-normal mb-2">Preview Bukti Transfer</p>
                <template x-if="verify.bukti_url">
                    <a :href="verify.bukti_url" target="_blank"><img :src="verify.bukti_url" class="w-full rounded-xl border border-slate-200"></a>
                </template>
                <template x-if="!verify.bukti_url">
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-6 text-center text-sm text-amber-800">Belum upload bukti transfer</div>
                </template>
            </div>
        </template>
        <template x-if="verify.metode_pembayaran === 'bayar_ditempat'">
            <div class="rounded-xl border border-violet-200 bg-violet-50 px-4 py-4 text-sm text-slate-700">Bayar di tempat — verifikasi setelah menerima pembayaran tunai.</div>
        </template>
        <div>
            <p class="admin-label !normal-case !tracking-normal mb-2">Pas Foto</p>
            <img :src="verify.foto_url" class="h-32 w-32 rounded-xl border border-slate-200 object-cover">
        </div>
    </div>
</div>
