<div>
    <label class="admin-label">Nama Lengkap</label>
    <input type="text" name="nama_lengkap" value="{{ old('_modal') === $prefix ? old('nama_lengkap') : '' }}" required class="admin-input">
    @if (old('_modal') === $prefix) @error('nama_lengkap')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
</div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="admin-label">NIP</label>
        <input type="text" name="nip" value="{{ old('_modal') === $prefix ? old('nip') : '' }}" class="admin-input font-mono">
        @if (old('_modal') === $prefix) @error('nip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
    </div>
    <div>
        <label class="admin-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="admin-select">
            <option value="">— Pilih —</option>
            <option value="L" @selected(old('_modal') === $prefix && old('jenis_kelamin') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('_modal') === $prefix && old('jenis_kelamin') === 'P')>Perempuan</option>
        </select>
    </div>
</div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="admin-label">No. WhatsApp</label>
        <input type="text" name="no_wa" value="{{ old('_modal') === $prefix ? old('no_wa') : '' }}" class="admin-input">
    </div>
    <div>
        <label class="admin-label">Email</label>
        <input type="email" name="email" value="{{ old('_modal') === $prefix ? old('email') : '' }}" class="admin-input">
        @if (old('_modal') === $prefix) @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
    </div>
</div>
<div>
    <label class="admin-label">Lembaga</label>
    <select name="lembaga" class="admin-select">
        <option value="">— Pilih Lembaga —</option>
        @foreach ($lembagaOptions as $lembaga)
            <option value="{{ $lembaga }}" @selected(old('_modal') === $prefix && old('lembaga') === $lembaga)>{{ $lembaga }}</option>
        @endforeach
    </select>
</div>
<label class="inline-flex cursor-pointer items-center gap-2.5">
    <input type="checkbox" name="is_active" value="1" @checked(old('_modal') !== $prefix || old('is_active', true)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
    <span class="text-sm text-slate-700">Aktif</span>
</label>
