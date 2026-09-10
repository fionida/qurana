@php
    $isCreate = $mode === 'create';
    $title = $isCreate ? 'Tambah Gelombang' : 'Edit Gelombang';
@endphp

<x-admin.modal :show="$show" :title="$title">
    <form
        @if ($isCreate) action="{{ route('admin.gelombangs.store') }}" @else :action="`/admin/gelombangs/${editForm.id}`" @endif
        method="POST"
        enctype="multipart/form-data"
        class="max-h-[70vh] space-y-4 overflow-y-auto pr-1"
    >
        @csrf
        @unless ($isCreate) @method('PUT') @endunless
        <input type="hidden" name="_modal" value="{{ $mode }}">
        @unless ($isCreate) <input type="hidden" name="_gelombang_id" :value="editForm.id"> @endunless

        <div>
            <label class="admin-label">Program kegiatan</label>
            @if ($isCreate)
                <select name="program_id" required class="admin-input">
                    @foreach ($programOptions ?? [] as $prog)
                        <option value="{{ $prog->id }}" @selected(old('_modal') === 'create' && (int) old('program_id') === $prog->id)>{{ $prog->nama }}</option>
                    @endforeach
                </select>
            @else
                <select name="program_id" x-model="editForm.program_id" required class="admin-input">
                    @foreach ($programOptions ?? [] as $prog)
                        <option value="{{ $prog->id }}">{{ $prog->nama }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div>
            <label class="admin-label">Nama gelombang</label>
            @if ($isCreate)
                <input type="text" name="nama" value="{{ old('_modal') === 'create' ? old('nama') : '' }}" required class="admin-input">
            @else
                <input type="text" name="nama" x-model="editForm.nama" required class="admin-input">
            @endif
        </div>

        <div class="rounded-xl border border-sky-200 bg-sky-50/80 p-4">
            <p class="text-sm font-semibold text-sky-900">PMB — pendaftaran & seleksi</p>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Biaya pendaftaran (Rp)</label>
                    @if ($isCreate)
                        <input type="number" name="biaya_pendaftaran" min="0" class="admin-input" placeholder="Kosong = pakai setting global">
                    @else
                        <input type="number" name="biaya_pendaftaran" x-model="editForm.biaya_pendaftaran" min="0" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Kuota</label>
                    @if ($isCreate)
                        <input type="number" name="kuota" min="1" class="admin-input" placeholder="Opsional">
                    @else
                        <input type="number" name="kuota" x-model="editForm.kuota" min="1" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Pendaftaran buka</label>
                    @if ($isCreate)
                        <input type="date" name="pendaftaran_buka" class="admin-input">
                    @else
                        <input type="date" name="pendaftaran_buka" x-model="editForm.pendaftaran_buka" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Pendaftaran tutup</label>
                    @if ($isCreate)
                        <input type="date" name="pendaftaran_tutup" class="admin-input">
                    @else
                        <input type="date" name="pendaftaran_tutup" x-model="editForm.pendaftaran_tutup" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Jadwal tes (mulai)</label>
                    @if ($isCreate)
                        <input type="date" name="jadwal_tes_mulai" class="admin-input">
                    @else
                        <input type="date" name="jadwal_tes_mulai" x-model="editForm.jadwal_tes_mulai" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Jadwal tes (selesai)</label>
                    @if ($isCreate)
                        <input type="date" name="jadwal_tes_selesai" class="admin-input">
                    @else
                        <input type="date" name="jadwal_tes_selesai" x-model="editForm.jadwal_tes_selesai" class="admin-input">
                    @endif
                </div>
                <div class="sm:col-span-2">
                    <label class="admin-label">Lokasi tes</label>
                    @if ($isCreate)
                        <input type="text" name="lokasi_tes" class="admin-input">
                    @else
                        <input type="text" name="lokasi_tes" x-model="editForm.lokasi_tes" class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Nilai lulus minimal (0–100)</label>
                    @if ($isCreate)
                        <input type="number" step="0.01" name="nilai_lulus_minimal" min="0" max="100" class="admin-input">
                    @else
                        <input type="number" step="0.01" name="nilai_lulus_minimal" x-model="editForm.nilai_lulus_minimal" min="0" max="100" class="admin-input">
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="admin-label">Tanggal sertifikasi (mulai)</label>
                @if ($isCreate)
                    <input type="date" name="tanggal_sertifikasi_mulai" required class="admin-input">
                @else
                    <input type="date" name="tanggal_sertifikasi_mulai" x-model="editForm.tanggal_sertifikasi_mulai" required class="admin-input">
                @endif
            </div>
            <div>
                <label class="admin-label">Tanggal sertifikasi (selesai)</label>
                @if ($isCreate)
                    <input type="date" name="tanggal_sertifikasi_selesai" required class="admin-input">
                @else
                    <input type="date" name="tanggal_sertifikasi_selesai" x-model="editForm.tanggal_sertifikasi_selesai" required class="admin-input">
                @endif
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="admin-label">Tanggal terbit (Masehi)</label>
                @if ($isCreate)
                    <input type="date" id="create_tanggal_terbit_masehi" name="tanggal_terbit_masehi" required class="admin-input" @change="suggestHijri('create')">
                @else
                    <input type="date" id="edit_tanggal_terbit_masehi" name="tanggal_terbit_masehi" x-model="editForm.tanggal_terbit_masehi" required class="admin-input" @change="suggestHijri('edit')">
                @endif
            </div>
            <div>
                <label class="admin-label">Tanggal terbit (Hijriyah)</label>
                @if ($isCreate)
                    <input type="text" id="create_tanggal_terbit_hijriyah" name="tanggal_terbit_hijriyah" required class="admin-input" placeholder="Contoh: 02 Muharram 1448 H">
                @else
                    <input type="text" id="edit_tanggal_terbit_hijriyah" name="tanggal_terbit_hijriyah" x-model="editForm.tanggal_terbit_hijriyah" required class="admin-input">
                @endif
                <p class="mt-1 text-xs text-slate-500">Wajib diisi; disarankan dari tanggal resmi (bukan hanya konversi otomatis).</p>
            </div>
        </div>

        <div>
            <label class="admin-label">Kota terbit</label>
            @if ($isCreate)
                <input type="text" name="kota_terbit" value="Malang" required class="admin-input">
            @else
                <input type="text" name="kota_terbit" x-model="editForm.kota_terbit" required class="admin-input">
            @endif
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4">
            <p class="text-sm font-semibold text-amber-900">Pengaturan nomor sertifikat</p>
            <p class="mt-1 text-xs text-amber-800">Sesuaikan <strong>nomor urut berikutnya</strong> sebelum mencetak. Setiap cetak pertama kali akan memakai nomor ini lalu bertambah otomatis.</p>
            <div class="mt-3 grid gap-3 sm:grid-cols-3">
                <div>
                    <label class="admin-label">Kode batch</label>
                    @if ($isCreate)
                        <input type="text" name="kode_batch" value="35.73" required class="admin-input" placeholder="35.73">
                    @else
                        <input type="text" name="kode_batch" x-model="editForm.kode_batch" required class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Jenis nomor</label>
                    @if ($isCreate)
                        <input type="text" name="jenis_nomor" value="S.S" required class="admin-input">
                    @else
                        <input type="text" name="jenis_nomor" x-model="editForm.jenis_nomor" required class="admin-input">
                    @endif
                </div>
                <div>
                    <label class="admin-label">Nomor urut berikutnya</label>
                    @if ($isCreate)
                        <input type="number" name="nomor_urut_berikutnya" value="1" min="1" required class="admin-input">
                    @else
                        <input type="number" name="nomor_urut_berikutnya" x-model.number="editForm.nomor_urut_berikutnya" min="1" required class="admin-input">
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="admin-label">Template halaman 1 (depan)</label>
                <input type="file" name="template_halaman_1" accept="image/jpeg,image/png,application/pdf" @if ($isCreate) required @endif class="admin-input !py-2">
                @unless ($isCreate)
                    <p class="mt-1 text-xs text-slate-500" x-show="editForm.has_template_1">Template saat ini tersimpan. Kosongkan file jika tidak ingin mengganti.</p>
                @endunless
                <p class="mt-1 text-xs text-slate-500">PNG/JPG/PDF atau salin master dari menu <a href="{{ route('admin.templates.index') }}" class="font-medium text-violet-600 hover:text-violet-700">Template Cetak</a>, lalu assign per gelombang di sini. A4 landscape (297×210 mm).</p>
            </div>
            <div>
                <label class="admin-label">Template halaman 2 (opsional, tidak dipakai)</label>
                <input type="file" name="template_halaman_2" accept="image/jpeg,image/png" class="admin-input !py-2">
                @unless ($isCreate)
                    <p class="mt-1 text-xs text-slate-500" x-show="editForm.has_template_2">File lama masih tersimpan; halaman belakang sertifikat sekarang di-generate sistem.</p>
                @endunless
                <p class="mt-1 text-xs text-slate-500">Halaman belakang otomatis: judul program, tabel komponen tes, dan penandatangan dari database.</p>
            </div>
        </div>

        <label class="inline-flex cursor-pointer items-start gap-2.5 rounded-lg border border-slate-200 bg-slate-50 p-3">
            @if ($isCreate)
                <input type="checkbox" name="template_siap_cetak" value="1" checked class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @else
                <input type="checkbox" name="template_siap_cetak" value="1" x-model.boolean="editForm.template_siap_cetak" class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @endif
            <span class="text-sm text-slate-700">
                <strong>Template sudah berisi teks statis</strong> (label &ldquo;Nama Lengkap&rdquo;, doa, direktur, dll.). Sistem hanya menimpa nama, N.I.Q, tanggal sertifikasi, nomor, dan tanggal terbit — seperti PDF <em>S.S TPQ AL MUSTAQIM</em> yang pernah terbit.
            </span>
        </label>

        <label class="inline-flex cursor-pointer items-center gap-2.5">
            @if ($isCreate)
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @else
                <input type="checkbox" name="is_active" value="1" x-model.boolean="editForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @endif
            <span class="text-sm text-slate-700">Gelombang aktif</span>
        </label>

        <label class="inline-flex cursor-pointer items-center gap-2.5">
            @if ($isCreate)
                <input type="checkbox" name="is_registration_open" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @else
                <input type="checkbox" name="is_registration_open" value="1" x-model.boolean="editForm.is_registration_open" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @endif
            <span class="text-sm text-slate-700">Buka di form pendaftaran publik (hanya satu gelombang)</span>
        </label>

        <div class="flex gap-3 pt-2">
            <button type="button" @click="{{ $show }} = false" class="admin-btn-secondary flex-1">Batal</button>
            <button type="submit" class="admin-btn-primary flex-1">{{ $isCreate ? 'Simpan' : 'Perbarui' }}</button>
        </div>
    </form>
</x-admin.modal>
