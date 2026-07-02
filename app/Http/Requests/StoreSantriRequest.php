<?php

namespace App\Http\Requests;

use App\Models\Santri;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSantriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['required', 'string'],
            'provinsi_id' => ['required', 'string', 'max:10'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kota_kab_id' => ['required', 'string', 'max:10'],
            'kota_kab' => ['required', 'string', 'max:255'],
            'kecamatan_id' => ['required', 'string', 'max:10'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'desa_id' => ['required', 'string', 'max:15'],
            'desa' => ['required', 'string', 'max:255'],
            'lembaga' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'no_wa' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'pas_foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'metode_pembayaran' => ['required', Rule::in(['transfer', 'bayar_ditempat'])],
            'bukti_transfer' => ['required_if:metode_pembayaran,transfer', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'alamat.required' => 'Alamat wajib diisi.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'kota_kab_id.required' => 'Kota/Kabupaten wajib dipilih.',
            'kota_kab.required' => 'Kota/Kabupaten wajib dipilih.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'kecamatan.required' => 'Kecamatan wajib dipilih.',
            'desa_id.required' => 'Desa/Kelurahan wajib dipilih.',
            'desa.required' => 'Desa/Kelurahan wajib dipilih.',
            'lembaga.required' => 'Lembaga asal wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'pas_foto.required' => 'Pas foto wajib diunggah.',
            'pas_foto.image' => 'Pas foto harus berupa gambar.',
            'pas_foto.max' => 'Pas foto maksimal 2 MB.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti_transfer.required_if' => 'Bukti transfer wajib diunggah untuk metode pembayaran transfer.',
            'bukti_transfer.image' => 'Bukti transfer harus berupa gambar.',
            'bukti_transfer.max' => 'Bukti transfer maksimal 5 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('nama_lengkap')) {
            $merge['nama_lengkap'] = mb_strtoupper(trim($this->nama_lengkap));
        }

        foreach (['provinsi', 'kota_kab', 'kecamatan', 'desa', 'alamat', 'tempat_lahir', 'lembaga'] as $field) {
            if ($this->has($field) && is_string($this->$field)) {
                $merge[$field] = trim($this->$field);
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $exists = Santri::query()
                ->where('nama_lengkap', $this->input('nama_lengkap'))
                ->where('tempat_lahir', $this->input('tempat_lahir'))
                ->whereDate('tanggal_lahir', $this->input('tanggal_lahir'))
                ->exists();

            if ($exists) {
                $validator->errors()->add('nama_lengkap', 'Data pendaftar dengan identitas yang sama sudah terdaftar. Pendaftaran hanya dapat dilakukan satu kali.');
            }
        });
    }
}
