@extends('layouts.public')

@section('title', 'Form Pendaftaran')

@push('scripts')
    @vite(['resources/js/registration.js'])
@endpush

@section('content')
@php
    $loggedInUsername = auth()->user()?->username ?? auth()->user()?->name ?? auth()->user()?->email;
    $displayLogo = $siteLogo ?? \App\Models\Setting::logoUrl();
@endphp
<div class="public-card">
    <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-700 to-emerald-600 px-6 py-8 text-center text-white">
        <div class="mb-4 flex justify-center">
            @if ($displayLogo)
                <img src="{{ $displayLogo }}" alt="Logo Qurana" class="h-20 w-auto max-w-[200px] object-contain drop-shadow-md">
            @else
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 text-4xl font-extrabold backdrop-blur-sm">Q</div>
            @endif
        </div>
        <h2 class="text-xl font-bold">Pendaftaran Guru Pendidik Al Quran</h2>
        <p class="mt-1 text-sm text-emerald-100">Lengkapi data berikut dengan benar</p>
        @if ($loggedInUsername)
            <p class="mt-3 inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-emerald-50">
                Username login: <span class="ml-1 font-semibold text-white">{{ $loggedInUsername }}</span>
            </p>
        @endif
    </div>

    <form id="registration-form" action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 p-6 sm:p-8">
        @csrf

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-slate-400">Data Diri</h3>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="nama_lengkap" class="public-label">Nama Lengkap *</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        autocomplete="name" style="text-transform: uppercase"
                        placeholder="NAMA LENGKAP SESUAI AKTA"
                        class="public-input uppercase tracking-wide">
                    <p class="mt-1 text-xs text-slate-400">Otomatis huruf kapital</p>
                    @error('nama_lengkap')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="public-label">Tempat Lahir *</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="public-input" placeholder="Kota kelahiran">
                    @error('tempat_lahir')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="public-label">Tanggal Lahir *</label>
                    <div id="tanggal_lahir_wrap" class="datepicker-field relative flatpickr-wrap">
                        <input type="text" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            placeholder="Pilih tanggal" required readonly data-input class="public-input w-full !pr-10">
                        <svg class="pointer-events-none absolute right-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    @error('tanggal_lahir')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="public-label">Alamat Jalan / RT-RW *</label>
                    <textarea id="alamat" name="alamat" rows="2" required class="public-input" placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('alamat') }}</textarea>
                    @error('alamat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="provinsi_id" class="public-label">Provinsi *</label>
                    <select id="provinsi_id" name="provinsi_id" required class="public-select" data-wilayah="provinsi">
                        <option value="">— Pilih Provinsi —</option>
                    </select>
                    <input type="hidden" id="provinsi" name="provinsi" value="{{ old('provinsi') }}">
                    @error('provinsi_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('provinsi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kota_kab_id" class="public-label">Kota / Kabupaten *</label>
                    <select id="kota_kab_id" name="kota_kab_id" required class="public-select" data-wilayah="regency" disabled>
                        <option value="">— Pilih Kota/Kab —</option>
                    </select>
                    <input type="hidden" id="kota_kab" name="kota_kab" value="{{ old('kota_kab') }}">
                    @error('kota_kab_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('kota_kab')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kecamatan_id" class="public-label">Kecamatan *</label>
                    <select id="kecamatan_id" name="kecamatan_id" required class="public-select" data-wilayah="district" disabled>
                        <option value="">— Pilih Kecamatan —</option>
                    </select>
                    <input type="hidden" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}">
                    @error('kecamatan_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('kecamatan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="desa_id" class="public-label">Desa / Kelurahan *</label>
                    <select id="desa_id" name="desa_id" required class="public-select" data-wilayah="village" disabled>
                        <option value="">— Pilih Desa/Kel —</option>
                    </select>
                    <input type="hidden" id="desa" name="desa" value="{{ old('desa') }}">
                    @error('desa_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('desa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @php
                    $wilayahOld = [
                        'provinsi_id' => old('provinsi_id'),
                        'kota_kab_id' => old('kota_kab_id'),
                        'kecamatan_id' => old('kecamatan_id'),
                        'desa_id' => old('desa_id'),
                        'provinsi' => old('provinsi'),
                        'kota_kab' => old('kota_kab'),
                        'kecamatan' => old('kecamatan'),
                        'desa' => old('desa'),
                    ];
                @endphp
                <script type="application/json" id="wilayah-old">@json($wilayahOld)</script>

                <div>
                    <label for="lembaga" class="public-label">Lembaga Asal*</label>
                    <input type="text" id="lembaga" name="lembaga" value="{{ old('lembaga') }}" required class="public-input" placeholder="Contoh: TPQ Al Hidayah">
                    @error('lembaga')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="public-label">Jenis Kelamin *</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="public-select">
                        <option value="">— Pilih —</option>
                        <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="no_wa" class="public-label">No. WhatsApp</label>
                    <input type="text" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" placeholder="08xxxxxxxxxx" class="public-input">
                    @error('no_wa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="public-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="public-input">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="pas_foto" class="public-label">Pas Foto *</label>
                    <input type="file" id="pas_foto" name="pas_foto" accept="image/jpeg,image/jpg,image/png" required
                        class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="mt-1 text-xs text-slate-400">JPG/PNG, maks. 2 MB | Foto ukuran 4x3 Background Merah.</p>
                    @error('pas_foto')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8">
            <h3 class="mb-1 text-sm font-bold uppercase tracking-wide text-slate-400">Metode Pembayaran</h3>
            <p class="mb-5 text-sm text-slate-600">Biaya pendaftaran: <strong class="text-emerald-700">Rp {{ number_format($biaya, 0, ',', '.') }}</strong></p>

            <div class="space-y-3">
                <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500/20">
                    <input type="radio" name="metode_pembayaran" value="transfer" @checked(old('metode_pembayaran', 'transfer') === 'transfer')
                        class="mt-1 text-emerald-600 focus:ring-emerald-500" onchange="togglePayment()">
                    <div>
                        <span class="font-semibold text-slate-900">Transfer Bank</span>
                        <p class="mt-1 text-sm text-slate-500">{{ $rekening['bank'] }} — <span class="font-mono">{{ $rekening['nomor'] }}</span></p>
                        <p class="text-sm text-slate-500">a/n {{ $rekening['atas_nama'] }}</p>
                    </div>
                </label>

                <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500/20">
                    <input type="radio" name="metode_pembayaran" value="bayar_ditempat" @checked(old('metode_pembayaran') === 'bayar_ditempat')
                        class="mt-1 text-emerald-600 focus:ring-emerald-500" onchange="togglePayment()">
                    <div>
                        <span class="font-semibold text-slate-900">Bayar di Tempat</span>
                        <p class="mt-1 text-sm text-slate-500">Pembayaran langsung di kantor pendaftaran</p>
                    </div>
                </label>
            </div>
            @error('metode_pembayaran')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror

            <div id="bukti-transfer-field" class="mt-5">
                <label class="public-label">Bukti Transfer <span class="font-normal normal-case text-slate-400">(wajib melampirkan bukti)</span></label>
                <input type="file" name="bukti_transfer" accept="image/jpeg,image/jpg,image/png"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700">
                @error('bukti_transfer')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
            <button type="submit" class="public-btn-primary w-full !py-3 sm:w-auto sm:px-10">
                Kirim Pendaftaran
            </button>
        </div>
    </form>
</div>
@endsection
