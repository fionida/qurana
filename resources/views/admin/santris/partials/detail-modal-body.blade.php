<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">No. Pendaftaran</dt><dd class="font-mono text-xs font-semibold text-emerald-600" x-text="detail.nomor_pendaftaran"></dd></div>
            <div><dt class="text-slate-500">Nama Lengkap</dt><dd class="font-semibold text-slate-900" x-text="detail.nama_lengkap"></dd></div>
            <div><dt class="text-slate-500">TTL</dt><dd class="font-medium text-slate-900" x-text="detail.ttl"></dd></div>
            <div><dt class="text-slate-500">Jenis Kelamin</dt><dd class="font-medium text-slate-900" x-text="detail.jenis_kelamin_label"></dd></div>
            <div><dt class="text-slate-500">Lembaga</dt><dd class="font-medium text-slate-900" x-text="detail.lembaga"></dd></div>
            <div><dt class="text-slate-500">No. WhatsApp</dt><dd class="font-medium text-slate-900" x-text="detail.no_wa || '-'"></dd></div>
            <div><dt class="text-slate-500">Email</dt><dd class="font-medium text-slate-900" x-text="detail.email || '-'"></dd></div>
            <div class="sm:col-span-2"><dt class="text-slate-500">Alamat Jalan</dt><dd class="text-slate-700" x-text="detail.alamat"></dd></div>
            <div class="sm:col-span-2"><dt class="text-slate-500">Alamat Lengkap</dt><dd class="text-slate-700" x-text="detail.alamat_lengkap || detail.alamat"></dd></div>
        </dl>
        <div class="border-t border-slate-100 pt-4">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
                <div><dt class="text-slate-500">Metode Bayar</dt><dd class="font-medium" x-text="detail.metode_pembayaran_label"></dd></div>
                <div><dt class="text-slate-500">Status</dt><dd class="font-medium" x-text="detail.status_pembayaran_label"></dd></div>
            </dl>
        </div>
        <div class="flex flex-wrap gap-2 pt-2">
            <template x-if="detail.is_lunas">
                <div class="flex flex-wrap gap-2">
                    <a :href="detail.kwitansi_url" target="_blank" class="admin-btn-secondary !py-2 !text-xs">Kwitansi</a>
                    <a :href="detail.sertifikat_url" target="_blank" class="admin-btn-secondary !py-2 !text-xs">Sertifikat</a>
                </div>
            </template>
            <template x-if="!detail.is_lunas">
                <a :href="detail.verify_url" class="admin-btn-primary !py-2 !text-xs">Verifikasi Bayar</a>
            </template>
        </div>
    </div>
    <div class="space-y-4">
        <div>
            <p class="admin-label !normal-case !tracking-normal mb-2">Pas Foto</p>
            <img :src="detail.foto_url" alt="Pas Foto" class="w-full rounded-xl border border-slate-200">
        </div>
        <template x-if="detail.bukti_url">
            <div>
                <p class="admin-label !normal-case !tracking-normal mb-2">Bukti Transfer</p>
                <a :href="detail.bukti_url" target="_blank"><img :src="detail.bukti_url" class="w-full rounded-xl border border-slate-200"></a>
            </div>
        </template>
    </div>
</div>
