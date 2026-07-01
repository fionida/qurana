<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Santri extends Model
{
    protected $fillable = [
        'nomor_pendaftaran',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'provinsi_id',
        'provinsi',
        'kota_kab_id',
        'kota_kab',
        'kecamatan_id',
        'kecamatan',
        'desa_id',
        'desa',
        'lembaga',
        'jenis_kelamin',
        'no_wa',
        'email',
        'pas_foto',
        'metode_pembayaran',
        'bukti_transfer',
        'status_pembayaran',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getMetodePembayaranLabelAttribute(): string
    {
        return $this->metode_pembayaran === 'transfer' ? 'Transfer' : 'Bayar di Tempat';
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        return $this->status_pembayaran === 'lunas' ? 'Lunas' : 'Pending';
    }

    public function getTtlAttribute(): string
    {
        return $this->tempat_lahir.', '.$this->tanggal_lahir->translatedFormat('d F Y');
    }

    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function getWilayahLengkapAttribute(): ?string
    {
        $parts = array_filter([$this->desa, $this->kecamatan, $this->kota_kab, $this->provinsi]);

        return $parts ? implode(', ', $parts) : null;
    }

    public function getAlamatLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->desa,
            $this->kecamatan,
            $this->kota_kab,
            $this->provinsi,
        ]);

        return implode(', ', $parts);
    }

    public static function generateNomorPendaftaran(): string
    {
        $year = now()->format('Y');
        $prefix = "QRN-{$year}-";

        $lastNumber = static::query()
            ->where('nomor_pendaftaran', 'like', "{$prefix}%")
            ->orderByDesc('nomor_pendaftaran')
            ->value('nomor_pendaftaran');

        $sequence = $lastNumber
            ? ((int) substr($lastNumber, -4)) + 1
            : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function lembagaOptions(): array
    {
        return static::query()
            ->whereNotNull('lembaga')
            ->where('lembaga', '!=', '')
            ->orderBy('lembaga')
            ->distinct()
            ->pluck('lembaga')
            ->values()
            ->all();
    }
}
