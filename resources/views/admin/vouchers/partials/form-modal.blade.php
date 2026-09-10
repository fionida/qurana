@php $isCreate = $mode === 'create'; @endphp
<x-admin.modal :show="$show" :title="$isCreate ? 'Tambah voucher' : 'Edit voucher'">
    <form @if($isCreate) action="{{ route('admin.vouchers.store') }}" @else :action="`/admin/vouchers/${editForm.id}`" @endif method="POST" class="space-y-4">
        @csrf @unless($isCreate) @method('PUT') @endunless
        <div>
            <label class="admin-label">Kode</label>
            @if($isCreate)<input name="kode" required class="admin-input uppercase">@else<input name="kode" x-model="editForm.kode" required class="admin-input uppercase">@endif
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="admin-label">Tipe</label>
                @if($isCreate)
                    <select name="tipe" class="admin-select"><option value="nominal">Nominal (Rp)</option><option value="persen">Persen</option></select>
                @else
                    <select name="tipe" x-model="editForm.tipe" class="admin-select"><option value="nominal">Nominal</option><option value="persen">Persen</option></select>
                @endif
            </div>
            <div>
                <label class="admin-label">Nilai</label>
                @if($isCreate)<input type="number" name="nilai" min="1" required class="admin-input">@else<input type="number" name="nilai" x-model="editForm.nilai" min="1" required class="admin-input">@endif
            </div>
        </div>
        <div>
            <label class="admin-label">Gelombang (kosong = semua)</label>
            <select name="gelombang_id" @unless($isCreate) x-model="editForm.gelombang_id" @endunless class="admin-select">
                <option value="">Semua gelombang</option>
                @foreach($gelombangOptions as $g)<option value="{{ $g->id }}">{{ $g->nama }}</option>@endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div><label class="admin-label">Maks. pakai</label><input type="number" name="maks_pakai" @unless($isCreate) x-model="editForm.maks_pakai" @endunless class="admin-input"></div>
            <div><label class="admin-label">Berlaku sampai</label><input type="date" name="berlaku_sampai" @unless($isCreate) x-model="editForm.berlaku_sampai" @endunless class="admin-input"></div>
        </div>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @if($isCreate) checked @else x-model.boolean="editForm.is_active" @endif class="rounded border-slate-300 text-emerald-600">
            Aktif
        </label>
        <div class="flex gap-3 pt-2">
            <button type="button" @click="{{ $show }} = false" class="admin-btn-secondary flex-1">Batal</button>
            <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
        </div>
    </form>
</x-admin.modal>
